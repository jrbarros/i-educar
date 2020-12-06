<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Tipo Ensino");
        $this->processoAp = '558';
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

    public $cod_tipo_ensino;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Tipo Ensino - Detalhe';

        $this->cod_tipo_ensino=$_GET['cod_tipo_ensino'];

        $tmp_obj = new TipoEnsino($this->cod_tipo_ensino, null, null, null, null, null, 1);
        if (!$registro = $tmp_obj->detalhe()) {
            $this->simpleRedirect('educar_tipo_ensino_lst.php');
        }

        if (!$registro['ativo']) {
            $this->simpleRedirect('educar_tipo_ensino_lst.php');
        }

        if ($registro['cod_tipo_ensino']) {
            $this->addDetalhe([ 'Tipo Ensino', "{$registro['cod_tipo_ensino']}"]);
        }

        if ($registro['ref_cod_instituicao']) {
            $obj_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
            $obj_cod_instituicao_det = $obj_cod_instituicao->detalhe();
            $registro['ref_cod_instituicao'] = $obj_cod_instituicao_det['nm_instituicao'];

            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
        }
        if ($registro['nm_tipo']) {
            $this->addDetalhe([ 'Nome Tipo', "{$registro['nm_tipo']}"]);
        }

        //** Verificacao de permissao para cadastro ou edicao
        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(558, $this->pessoa_logada, 7)) {
            $this->url_novo = 'educar_tipo_ensino_cad.php';
            $this->url_editar = "educar_tipo_ensino_cad.php?cod_tipo_ensino={$registro['cod_tipo_ensino']}";
        }
        //**

        $this->url_cancelar = 'educar_tipo_ensino_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do tipo de ensino', [
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
