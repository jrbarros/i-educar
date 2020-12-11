<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Bloqueio do ano letivo");
        $this->processoAp = '21251';
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

    public $ref_cod_instituicao;
    public $ref_ano;
    public $data_inicio;
    public $data_fim;

    public function Gerar()
    {
        $this->titulo = 'Bloqueio do ano letivo - Detalhe';

        $this->ref_cod_instituicao=$_GET['ref_cod_instituicao'];
        $this->ref_ano=$_GET['ref_ano'];

        $tmp_obj = new BloqueioAnoLetivo($this->ref_cod_instituicao, $this->ref_ano);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_bloqueio_ano_letivo_lst.php');
        }

        if ($registro['instituicao']) {
            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['instituicao']}"]);
        }
        if ($registro['ref_ano']) {
            $this->addDetalhe([ 'Ano', "{$registro['ref_ano']}"]);
        }
        if ($registro['data_inicio']) {
            $this->addDetalhe([ 'Data inicial permitida', dataToBrasil($registro['data_inicio'])]);
        }
        if ($registro['data_fim']) {
            $this->addDetalhe([ 'Data final permitida', dataToBrasil($registro['data_fim'])]);
        }

        //** Verificacao de permissao para cadastro
        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(21251, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_bloqueio_ano_letivo_cad.php';
            $this->url_editar = "educar_bloqueio_ano_letivo_cad.php?ref_cod_instituicao={$registro['ref_cod_instituicao']}&ref_ano={$registro['ref_ano']}";
        }
        //**
        $this->url_cancelar = 'educar_bloqueio_ano_letivo_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do bloqueio do ano letivo', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
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
