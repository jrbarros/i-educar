<?php

/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versão 2 da Licença, como (a seu critério)
 * qualquer versão posterior.
 *
 * Este programa é distribuí­do na expectativa de que seja útil, porém, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implí­cita de COMERCIABILIDADE OU
 * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral
 * do GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto
 * com este programa; se não, escreva para a Free Software Foundation, Inc., no
 * endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Arquivo disponível desde a versão 1.0.0
 *
 * @version   $Id$
 */

require_once 'include/Base.php';
require_once 'include/clsListagem.inc.php';
require_once 'include/Banco.inc.php';
require_once 'include/pmieducar/geral.inc.php';
require_once 'CoreExt/View/Helper/UrlHelper.php';

/**
 * clsIndexBase class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponível desde a versão 1.0.0
 *
 * @version   @@package_version@@
 */
class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' Servidores - Falta Atraso');
        $this->processoAp = 635;
    }
}

/**
 * indice class.
 *
 * @author    Prefeitura Municipal de Itajaí <ctima@itajai.sc.gov.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   iEd_Pmieducar
 *
 * @since     Classe disponível desde a versão 1.0.0
 *
 * @version   @@package_version@@
 */
class indice extends clsListagem
{
    public $pessoa_logada;
    public $titulo;
    public $limite;
    public $offset;

    public $cod_falta_atraso        = null;
    public $ref_cod_escola          = null;
    public $ref_ref_cod_instituicao = null;
    public $ref_usuario_exc         = null;
    public $ref_usuario_cad         = null;
    public $ref_cod_servidor        = null;
    public $tipo                    = null;
    public $data_falta_atraso       = null;
    public $qtd_horas               = null;
    public $qtd_min                 = null;
    public $justificada             = null;
    public $data_cadastro           = null;
    public $data_exclusao           = null;
    public $ativo                   = null;

    public function Gerar()
    {
        $this->titulo = 'Faltas e atrasos - Listagem';

        foreach ($_GET as $var => $val) {
            $this->$var = ($val === '') ? null : $val;
        }

        $tmp_obj = new Servidor($this->ref_cod_servidor, null, null, null, null, null, null, $this->ref_cod_instituicao);
        $registro = $tmp_obj->detalhe();

        $this->addCabecalhos([
      'Escola',
      'Instituição',
      'Tipo',
      'Dia',
      'Horas',
      'Minutos'
    ]);

        $fisica = new PessoaFisica($this->ref_cod_servidor);
        $fisica = $fisica->detalhe();

        $this->campoOculto('ref_cod_servidor', $this->ref_cod_servidor);
        $this->campoRotulo('nm_servidor', 'Servidor', $fisica['nome']);

        $this->inputsHelper()->dynamic('instituicao', ['required' => false, 'show-select' => true, 'value' => $this->ref_cod_instituicao]);
        $this->inputsHelper()->dynamic('escola', ['required' => false, 'show-select' => true, 'value' => $this->ref_cod_escola]);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET['pagina_' . $this->nome]) ?
      $_GET['pagina_' . $this->nome] * $this->limite-$this->limite : 0;

        $obj_falta_atraso = new FaltaAtraso(
        null,
        $this->ref_cod_escola,
        $this->ref_ref_cod_instituicao,
        null,
        null,
        $this->ref_cod_servidor
    );

        $obj_falta_atraso->setOrderby('tipo ASC');
        $obj_falta_atraso->setLimite($this->limite, $this->offset);

        // Recupera a lista de faltas/atrasos
        $lista = $obj_falta_atraso->lista(null, $this->ref_cod_escola, $this->ref_ref_cod_instituicao, null, null, $this->ref_cod_servidor);

        $total = $obj_falta_atraso->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {

        // Recupera o nome da escola
                $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
                $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                $registro['nm_escola'] = $det_ref_cod_escola['nome'];

                $obj_ins = new Instituicao($registro['ref_ref_cod_instituicao']);
                $det_ins = $obj_ins->detalhe();

                $obj_comp = new FaltaAtrasoCompensado();
                $horas    = $obj_comp->ServidorHorasCompensadas(
            $this->ref_cod_servidor,
            $registro['ref_cod_escola'],
            $registro['ref_ref_cod_instituicao']
        );

                if ($horas) {
                    $horas_aux   = $horas['hora'];
                    $minutos_aux = $horas['min'];
                }

                $horas_aux   = $horas_aux - $registro['qtd_horas'];
                $minutos_aux = $minutos_aux - $registro['qtd_min'];

                if ($horas_aux > 0 && $minutos_aux < 0) {
                    $horas_aux--;
                    $minutos_aux += 60;
                }

                if ($horas_aux < 0 && $minutos_aux > 0) {
                    $horas_aux--;
                    $minutos_aux -= 60;
                }

                if ($horas_aux < 0) {
                    $horas_aux = '('.($horas_aux * -1).')';
                }

                if ($minutos_aux < 0) {
                    $minutos_aux = '('.($minutos_aux * -1).')';
                }

                $tipo = $registro['tipo'] == 1 ?
          'Atraso' : 'Falta';

                $urlHelper = CoreExt_View_Helper_UrlHelper::getInstance();
                $url       = 'educar_falta_atraso_det.php';
                $options   = ['query' => [
          'cod_falta_atraso'    => $registro['cod_falta_atraso'],
          'ref_cod_servidor'    => $registro['ref_cod_servidor'],
          'ref_cod_escola'      => $registro['ref_cod_escola'],
          'ref_cod_instituicao' => $registro['ref_ref_cod_instituicao'],
        ]];

                $dt = new DateTime($registro['data_falta_atraso']);
                $data = $dt->format('d/m/Y');
                $this->addLinhas([
          $urlHelper->l($registro['nm_escola'], $url, $options),
          $urlHelper->l($det_ins['nm_instituicao'], $url, $options),
          $urlHelper->l($tipo, $url, $options),
          $urlHelper->l($data, $url, $options),
          $urlHelper->l($horas_aux, $url, $options),
          $urlHelper->l($minutos_aux, $url, $options)
        ]);
            }
        }

        $this->addPaginador2(
        'educar_falta_atraso_lst.php',
        $total,
        $_GET,
        $this->nome,
        $this->limite
    );
        $obj_permissoes = new Permissoes();

        if ($obj_permissoes->permissao_cadastra(635, $this->pessoa_logada, 7)) {
            $this->array_botao[] = 'Novo';

            $this->array_botao_url[] = sprintf(
          'educar_falta_atraso_cad.php?ref_cod_servidor=%d&ref_cod_instituicao=%d',
          $this->ref_cod_servidor,
          $this->ref_ref_cod_instituicao
      );
        }

        $this->array_botao[] = 'Voltar';

        $this->array_botao_url[] = sprintf(
        'educar_servidor_det.php?cod_servidor=%d&ref_cod_instituicao=%d',
        $this->ref_cod_servidor,
        $this->ref_cod_instituicao
    );

        $this->largura = '100%';

        $this->breadcrumb('Registro das faltas e atrasos do servidor', [
        url('Intranet/educar_servidores_index.php') => 'Servidores',
    ]);
    }
}

// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à  página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();;
