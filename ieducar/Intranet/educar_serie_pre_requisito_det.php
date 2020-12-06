<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Serie Pre Requisito");
        $this->processoAp = '599';
    }
}

class indice extends clsDetalhe
{
    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

    public $ref_cod_pre_requisito;
    public $ref_cod_operador;
    public $ref_cod_serie;
    public $valor;

    public function Gerar()
    {
        $this->titulo = 'Serie Pre Requisito - Detalhe';

        $this->ref_cod_serie         = $_GET['ref_cod_serie'];
        $this->ref_cod_operador      = $_GET['ref_cod_operador'];
        $this->ref_cod_pre_requisito = $_GET['ref_cod_pre_requisito'];

        $tmp_obj = new SeriePreRequisito($this->ref_cod_pre_requisito, $this->ref_cod_operador, $this->ref_cod_serie);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_serie_pre_requisito_lst.php');
        }

        $obj_ref_cod_serie = new Serie($registro['ref_cod_serie']);
        $det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
        $registro['ref_cod_serie'] = $det_ref_cod_serie['nm_serie'];

        $obj_ref_cod_operador = new Operador($registro['ref_cod_operador']);
        $det_ref_cod_operador = $obj_ref_cod_operador->detalhe();
        $registro['ref_cod_operador'] = $det_ref_cod_operador['nome'];

        $obj_ref_cod_pre_requisito = new PreRequisito($registro['ref_cod_pre_requisito']);
        $det_ref_cod_pre_requisito = $obj_ref_cod_pre_requisito->detalhe();
        $registro['ref_cod_pre_requisito'] = $det_ref_cod_pre_requisito['nome'];

        if ($registro['ref_cod_pre_requisito']) {
            $this->addDetalhe([ 'Pre Requisito', "{$registro['ref_cod_pre_requisito']}"]);
        }
        if ($registro['ref_cod_operador']) {
            $this->addDetalhe([ 'Operador', "{$registro['ref_cod_operador']}"]);
        }
        if ($registro['ref_cod_serie']) {
            $this->addDetalhe([ 'Serie', "{$registro['ref_cod_serie']}"]);
        }
        if ($registro['valor']) {
            $this->addDetalhe([ 'Valor', "{$registro['valor']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(599, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_serie_pre_requisito_cad.php';
            $this->url_editar = "educar_serie_pre_requisito_cad.php?ref_cod_pre_requisito={$this->ref_cod_pre_requisito}&ref_cod_operador={$this->ref_cod_operador}&ref_cod_serie={$this->ref_cod_serie}";
        }

        $this->url_cancelar = 'educar_serie_pre_requisito_lst.php';
        $this->largura = '100%';
    }
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na Base
$pagina->addForm($miolo);
// gera o html
$pagina->MakeAll();
