<?php

require_once('include/Base.php');
require_once('include/clsCadastro.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Fonte");
        $this->processoAp = '608';
    }
}

class indice extends clsCadastro
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

    public $cod_fonte;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_fonte;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_biblioteca;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_fonte=$_GET['cod_fonte'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(608, $this->pessoa_logada, 11, 'educar_fonte_lst.php');

        if (is_numeric($this->cod_fonte)) {
            $obj = new Fonte($this->cod_fonte);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }

                $obj_permissoes = new Permissoes();
                if ($obj_permissoes->permissao_excluir(608, $this->pessoa_logada, 11)) {
                    $this->fexcluir = true;
                }

                $retorno = 'Editar';
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_fonte_det.php?cod_fonte={$registro['cod_fonte']}" : 'educar_fonte_lst.php';
        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' fonte', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
        ]);

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_fonte', $this->cod_fonte);

        // foreign keys
        $this->inputsHelper()->dynamic(['instituicao', 'escola', 'biblioteca']);

        // text
        $this->campoTexto('nm_fonte', 'Fonte', $this->nm_fonte, 30, 255, true);
        $this->campoMemo('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 60, 5, false);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(608, $this->pessoa_logada, 11, 'educar_fonte_lst.php');

        $obj = new Fonte($this->cod_fonte, $this->pessoa_logada, $this->pessoa_logada, $this->nm_fonte, $this->descricao, $this->data_cadastro, $this->data_exclusao, $this->ativo, $this->ref_cod_biblioteca);
        $this->cod_fonte = $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $obj->cod_fonte = $this->cod_fonte;
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
            $this->simpleRedirect('educar_fonte_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(608, $this->pessoa_logada, 11, 'educar_fonte_lst.php');

        $obj = new Fonte($this->cod_fonte, $this->pessoa_logada, $this->pessoa_logada, $this->nm_fonte, $this->descricao, $this->data_cadastro, $this->data_exclusao, $this->ativo, $this->ref_cod_biblioteca);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_fonte_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(608, $this->pessoa_logada, 11, 'educar_fonte_lst.php');

        $obj = new Fonte($this->cod_fonte, $this->pessoa_logada, $this->pessoa_logada, $this->nm_fonte, $this->descricao, $this->data_cadastro, $this->data_exclusao, 0);
        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_fonte_lst.php');
        }

        $this->mensagem = 'Exclus&atilde;o n&atilde;o realizada.<br>';

        return false;
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
