<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Situa&ccedil;&atilde;o");
        $this->processoAp = '602';
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

    public $cod_situacao;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_situacao;
    public $permite_emprestimo;
    public $descricao;
    public $situacao_padrao;
    public $situacao_emprestada;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_biblioteca;

    public $ref_cod_instituicao;
    public $ref_cod_escola;

    public function Gerar()
    {
        $this->titulo = 'Situa&ccedil;&atilde;o - Detalhe';

        $this->cod_situacao=$_GET['cod_situacao'];

        $tmp_obj = new Situacao($this->cod_situacao);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_situacao_lst.php');
        }

        $obj_ref_cod_biblioteca = new Biblioteca($registro['ref_cod_biblioteca']);
        $det_ref_cod_biblioteca = $obj_ref_cod_biblioteca->detalhe();
        $registro['ref_cod_biblioteca'] = $det_ref_cod_biblioteca['nm_biblioteca'];
        $registro['ref_cod_instituicao'] = $det_ref_cod_biblioteca['ref_cod_instituicao'];
        $registro['ref_cod_escola'] = $det_ref_cod_biblioteca['ref_cod_escola'];
        if ($registro['ref_cod_instituicao']) {
            $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
            $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
            $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];
        }
        if ($registro['ref_cod_escola']) {
            $obj_ref_cod_escola = new Escola();
            $det_ref_cod_escola = array_shift($obj_ref_cod_escola->lista($registro['ref_cod_escola']));
            $registro['ref_cod_escola'] = $det_ref_cod_escola['nome'];
        }

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);

        if ($registro['ref_cod_instituicao'] && $nivel_usuario == 1) {
            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
        }
        if ($registro['ref_cod_escola'] && ($nivel_usuario == 1 || $nivel_usuario == 2)) {
            $this->addDetalhe([ 'Escola', "{$registro['ref_cod_escola']}"]);
        }
        if ($registro['ref_cod_biblioteca']) {
            $this->addDetalhe([ 'Biblioteca', "{$registro['ref_cod_biblioteca']}"]);
        }
        if ($registro['nm_situacao']) {
            $this->addDetalhe([ 'Situa&ccedil;&atilde;o', "{$registro['nm_situacao']}"]);
        }
        if ($registro['permite_emprestimo']) {
            if ($registro['permite_emprestimo'] == 1) {
                $registro['permite_emprestimo'] = 'n&atilde;o';
            } elseif ($registro['permite_emprestimo'] == 2) {
                $registro['permite_emprestimo'] = 'sim';
            }
            $this->addDetalhe([ 'Permite Empr&eacute;stimo', "{$registro['permite_emprestimo']}"]);
        }
        if ($registro['descricao']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o', "{$registro['descricao']}"]);
        }
        if ($registro['situacao_padrao']) {
            if ($registro['situacao_padrao'] == 0) {
                $registro['situacao_padrao'] = 'n&atilde;o';
            } elseif ($registro['situacao_padrao'] == 1) {
                $registro['situacao_padrao'] = 'sim';
            }
            $this->addDetalhe([ 'Situa&ccedil;&atilde;o Padr&atilde;o', "{$registro['situacao_padrao']}"]);
        }
        if ($registro['situacao_emprestada']) {
            if ($registro['situacao_emprestada'] == 0) {
                $registro['situacao_emprestada'] = 'n&atilde;o';
            } elseif ($registro['situacao_emprestada'] == 1) {
                $registro['situacao_emprestada'] = 'sim';
            }
            $this->addDetalhe([ 'Situa&ccedil;&atilde;o Emprestada', "{$registro['situacao_emprestada']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(602, $this->pessoa_logada, 11)) {
            $this->url_novo = 'educar_situacao_cad.php';
            $this->url_editar = "educar_situacao_cad.php?cod_situacao={$registro['cod_situacao']}";
        }

        $this->url_cancelar = 'educar_situacao_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da situação', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
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
