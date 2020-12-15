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
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

/**
 * clsIndexBase class.
 *
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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
        $this->SetTitulo($this->_instituicao . ' i-Educar - Vagas por sÃ©rie');
        $this->processoAp = 21253;
    }
}

/**
 * indice class.
 *
 * @author    Lucas Schmoeller da Silva <lucas@portabilis.com.br>
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

    public $cod_serie_vaga;
    public $ano;
    public $ref_cod_instituicao;
    public $ref_cod_escola;
    public $ref_cod_curso;
    public $ref_cod_serie;
    public $vagas;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_serie_vaga = $_GET['cod_serie_vaga'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
        21253,
        $this->pessoa_logada,
        7,
        'educar_serie_vaga_lst.php'
    );

        if (is_numeric($this->cod_serie_vaga)) {
            $obj = new SerieVaga($this->cod_serie_vaga);

            $registro  = $obj->detalhe();

            if ($registro) {
                // passa todos os valores obtidos no registro para atributos do objeto
                foreach ($registro as $campo => $val) {
                    $this->$campo = $val;
                }

                $obj_permissoes = new Permissoes();

                if ($obj_permissoes->permissao_excluir(21253, $this->pessoa_logada, 7)) {
                    $this->fexcluir = true;
                }

                $retorno = 'Editar';
            }
        }

        $this->url_cancelar = $retorno == 'Editar' ?
      sprintf('educar_serie_vaga_det.php?cod_serie_vaga=%d', $this->cod_serie_vaga) : 'educar_serie_vaga_lst.php';

        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' vagas por série', [
        url('Intranet/educar_index.php') => 'Escola',
    ]);

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_serie_vaga', $this->cod_serie_vaga);

        $this->inputsHelper()->dynamic(['ano', 'instituicao', 'escola', 'curso', 'serie'], ['disabled' => is_numeric($this->cod_serie_vaga)]);

        $options = [
      'value'     => $this->turno,
      'resources' => [
        0 => 'Selecione',
        1 => 'Matutino',
        2 => 'Vespertino',
        3 => 'Noturno',
        4 => 'Integral'
    ],
      'disabled' => is_numeric($this->cod_serie_vaga)
    ];
        $this->inputsHelper()->select('turno', $options);

        $this->campoNumero('vagas', 'Vagas', $this->vagas, 3, 3, true);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(21253, $this->pessoa_logada, 7, 'educar_serie_vaga_lst.php');

        $sql = 'SELECT MAX(cod_serie_vaga) + 1 FROM pmieducar.serie_vaga';
        $db  = new Banco();
        $max_cod_serie = $db->CampoUnico($sql);
        $max_cod_serie = $max_cod_serie > 0 ? $max_cod_serie : 1;

        $obj = new SerieVaga(
        $max_cod_serie,
        $this->ano,
        $this->ref_cod_instituicao,
        $this->ref_cod_escola,
        $this->ref_cod_curso,
        $this->ref_cod_serie,
        $this->turno,
        $this->vagas
    );

        $lista = $obj->lista($this->ano, $this->ref_cod_escola, $this->ref_cod_curso, $this->ref_cod_serie, $this->turno);
        if (count($lista[0])) {
            $this->mensagem = 'J&aacute; existe cadastro para est&aacute; s&eacute;rie/ano!<br />';

            return false;
        }

        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br />';
            $this->simpleRedirect('educar_serie_vaga_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado. Verifique se j&aacute; n&atilde;o existe cadastro para est&aacute; s&eacute;rie/ano!<br />';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(21253, $this->pessoa_logada, 7, 'educar_serie_vaga_lst.php');

        $obj = new SerieVaga($this->cod_serie_vaga);
        $obj->vagas = $this->vagas;

        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br />';
            $this->simpleRedirect('educar_serie_vaga_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o nÃ£o realizada.<br />';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(21253, $this->pessoa_logada, 7, 'educar_serie_vaga_lst.php');

        $obj = new SerieVaga($this->cod_serie_vaga);

        $excluiu = $obj->excluir();

        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br />';
            $this->simpleRedirect('educar_serie_vaga_lst.php');
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
