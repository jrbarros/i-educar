<?php
//error_reporting(E_ERROR);
//ini_set("display_errors", 1);

require_once('include/Base.php');
require_once('include/clsCadastro.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Acervo Assunto");
        $this->processoAp = '592';
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

    public $cod_acervo_assunto;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_assunto;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    #var $ref_cod_biblioteca;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_acervo_assunto=$_GET['cod_acervo_assunto'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(592, $this->pessoa_logada, 11, 'educar_acervo_assunto_lst.php');

        if (is_numeric($this->cod_acervo_assunto)) {
            $obj = new AcervoAssunto($this->cod_acervo_assunto);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }

                $obj_permissoes = new Permissoes();
                if ($obj_permissoes->permissao_excluir(592, $this->pessoa_logada, 11)) {
                    $this->fexcluir = true;
                }

                $retorno = 'Editar';
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_acervo_assunto_det.php?cod_acervo_assunto={$registro['cod_acervo_assunto']}" : 'educar_acervo_assunto_lst.php';
        $this->nome_url_cancelar = 'Cancelar';
        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' assunto', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
        ]);

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_acervo_assunto', $this->cod_acervo_assunto);

        //foreign keys
        #$this->inputsHelper()->dynamic(array('instituicao', 'escola', 'biblioteca'));

        // text
        $this->campoTexto('nm_assunto', 'Assunto', $this->nm_assunto, 30, 255, true);
        $this->campoMemo('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 60, 5, false);
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(592, $this->pessoa_logada, 11, 'educar_acervo_assunto_lst.php');

        $obj = new AcervoAssunto(null, null, $this->pessoa_logada, $this->nm_assunto, $this->descricao, null, null, 1);#, $this->ref_cod_biblioteca );
        $this->cod_acervo_assunto = $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $obj->cod_acervo_assunto = $this->cod_acervo_assunto;
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';

            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(592, $this->pessoa_logada, 11, 'educar_acervo_assunto_lst.php');

        $obj = new AcervoAssunto($this->cod_acervo_assunto, $this->pessoa_logada, null, $this->nm_assunto, $this->descricao, null, null, 1);#, $this->ref_cod_biblioteca);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';

            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(592, $this->pessoa_logada, 11, 'educar_acervo_assunto_lst.php');

        $obj = new AcervoAssunto($this->cod_acervo_assunto, $this->pessoa_logada, null, null, null, null, null, 0);
        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';

            $this->simpleRedirect('educar_abandono_tipo_lst.php');
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
