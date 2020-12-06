<?php

/**
 * i-Educar - Sistema de gestÃ£o escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de ItajaÃ­
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa Ã© software livre; vocÃª pode redistribuÃ­-lo e/ou modificÃ¡-lo
 * sob os termos da LicenÃ§a PÃºblica Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versÃ£o 2 da LicenÃ§a, como (a seu critÃ©rio)
 * qualquer versÃ£o posterior.
 *
 * Este programa Ã© distribuÃ­Â­do na expectativa de que seja Ãºtil, porÃ©m, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implÃ­Â­cita de COMERCIABILIDADE OU
 * ADEQUAÃÃO A UMA FINALIDADE ESPECÃFICA. Consulte a LicenÃ§a PÃºblica Geral
 * do GNU para mais detalhes.
 *
 * VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral do GNU junto
 * com este programa; se nÃ£o, escreva para a Free Software Foundation, Inc., no
 * endereÃ§o 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Caroline Salib <caroline@portabillis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Arquivo disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   $Id$
 */

require_once 'include/Base.php';
require_once 'include/clsCadastro.inc.php';
require_once 'include/Banco.inc.php';
require_once 'include/pmieducar/geral.inc.php';
require_once 'lib/Portabilis/Date/Utils.php';

/**
 * clsIndexBase class.
 *
 * @author    Caroline Salib <caroline@portabillis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   @@package_version@@
 */
class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Bloqueio de lanÃ§amento de notas e faltas por etapa');
        $this->processoAp = 999848;
    }
}

/**
 * indice class.
 *
 * @author    Caroline Salib <caroline@portabillis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponÃ­vel desde a versÃ£o 1.0.0
 *
 * @version   @@package_version@@
 */
class indice extends clsCadastro
{
    public $pessoa_logada;

    public $cod_bloqueio;
    public $ano;
    public $ref_cod_escola;
    public $etapa;
    public $data_inicio;
    public $data_fim;
    public $modoEdicao;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_bloqueio = $_GET['cod_bloqueio'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        999848,
        $this->pessoa_logada,
        7,
        'educar_bloqueio_lancamento_faltas_notas_lst.php'
    );

        if (is_numeric($this->cod_bloqueio)) {
            $obj = new BloqueioLancamentoFaltasNotas($this->cod_bloqueio);

            $registro  = $obj->detalhe();

            if ($registro) {
                // passa todos os valores obtidos no registro para atributos do objeto
                foreach ($registro as $campo => $val) {
                    $this->$campo = $val;
                }

                $obj_permissoes = new Permissoes();

                if ($obj_permissoes->permissao_excluir(999848, $this->pessoa_logada, 7)) {
                    $this->fexcluir = true;
                }

                $retorno = 'Editar';
            }
        }

        $this->url_cancelar = $retorno == 'Editar' ?
      sprintf('educar_bloqueio_lancamento_faltas_notas_det.php?cod_bloqueio=%d', $this->cod_bloqueio) :
                'educar_bloqueio_lancamento_faltas_notas_lst.php';

        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' bloqueio de lançamento de notas e faltas por etapa', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        $this->modoEdicao = ($retorno == 'Editar');

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_bloqueio', $this->cod_bloqueio);

        $this->inputsHelper()->dynamic(['ano', 'instituicao']);

        if ($this->modoEdicao) {
            $objEscola = new Escola($this->ref_cod_escola);
            $objEscola = $objEscola->detalhe();

            $options = [
        'required'    => false,
        'label'       => 'Escola',
        'placeholder' => '',
        'value'       => $objEscola['nome'],
        'size'        => 35,
        'disabled'    => true
      ];

            $this->inputsHelper()->text('escola', $options);
        } else {
            $this->inputsHelper()->multipleSearchEscola(null, ['label' => 'Escola(s)']);
        }

        $selectOptions = [
      1 => '1ª Etapa',
      2 => '2ª Etapa',
      3 => '3ª Etapa',
      4 => '4ª Etapa'
    ];

        $options = ['label' => 'Etapa', 'resources' => $selectOptions, 'value' => $this->etapa];

        $this->inputsHelper()->select('etapa', $options);

        $this->inputsHelper()->date('data_inicio', ['label' => 'Data inicial', 'value' => dataToBrasil($this->data_inicio), 'placeholder' => '']);
        $this->inputsHelper()->date('data_fim', ['label' => 'Data final', 'value' => dataToBrasil($this->data_fim), 'placeholder' => '']);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(999848, $this->pessoa_logada, 7, 'educar_bloqueio_lancamento_faltas_notas_lst.php');

        $array_escolas = array_filter($this->escola);

        foreach ($array_escolas as $escolaId) {
            $obj = new BloqueioLancamentoFaltasNotas(
          null,
          $this->ano,
          $escolaId,
          $this->etapa,
          Portabilis_Date_Utils::brToPgSQL($this->data_inicio),
          Portabilis_Date_Utils::brToPgSQL($this->data_fim)
      );

            $obj->cadastra();
        }

        $this->mensagem .= 'Cadastro efetuado com sucesso.<br />';
        $this->simpleRedirect('educar_bloqueio_lancamento_faltas_notas_lst.php');
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(999848, $this->pessoa_logada, 7, 'educar_bloqueio_lancamento_faltas_notas_lst.php');

        $obj = new BloqueioLancamentoFaltasNotas(
        $this->cod_bloqueio,
        $this->ano,
        $this->ref_cod_escola,
        $this->etapa,
        Portabilis_Date_Utils::brToPgSQL($this->data_inicio),
        Portabilis_Date_Utils::brToPgSQL($this->data_fim)
    );

        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br />';
            $this->simpleRedirect('educar_bloqueio_lancamento_faltas_notas_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o nÃ£o realizada.<br />';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(999848, $this->pessoa_logada, 7, 'educar_bloqueio_lancamento_faltas_notas_lst.php');

        $obj = new BloqueioLancamentoFaltasNotas($this->cod_bloqueio);

        $excluiu = $obj->excluir();

        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br />';
            $this->simpleRedirect('educar_bloqueio_lancamento_faltas_notas_lst.php');
        }

        $this->mensagem = 'Exclus&atilde;o nÃ£o realizada.<br />';

        return false;
    }
}

// Instancia objeto de pÃ¡gina
$pagina = new clsIndexBase();

// Instancia objeto de conteÃºdo
$miolo = new indice();

// Atribui o conteÃºdo Ã   pÃ¡gina
$pagina->addForm($miolo);

// Gera o cÃ³digo HTML
$pagina->MakeAll();
