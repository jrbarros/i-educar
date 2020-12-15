<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;

require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Habilita&ccedil;&atilde;o");
        $this->processoAp = '573';
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

    public $cod_habilitacao;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public $ref_cod_instituicao;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_habilitacao=$_GET['cod_habilitacao'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(573, $this->pessoa_logada, 3, 'educar_habilitacao_lst.php');

        if (is_numeric($this->cod_habilitacao)) {
            $obj = new Habilitacao($this->cod_habilitacao);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }
                $this->data_cadastro = dataFromPgToBr($this->data_cadastro);
                $this->data_exclusao = dataFromPgToBr($this->data_exclusao);

                $this->fexcluir = $obj_permissoes->permissao_excluir(573, $this->pessoa_logada, 3);
                $retorno = 'Editar';
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_habilitacao_det.php?cod_habilitacao={$registro['cod_habilitacao']}" : 'educar_habilitacao_lst.php';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' habilitação', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_habilitacao', $this->cod_habilitacao);
        // foreign keys

        $get_escola = false;
        $obrigatorio = true;
        include('Source/pmieducar/educar_campo_lista.php');
        // text
        $this->campoTexto('nm_tipo', 'Habilita&ccedil;&atilde;o', $this->nm_tipo, 30, 255, true);
        $this->campoMemo('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 60, 5, false);
    }

    public function Novo()
    {
        $obj = new Habilitacao(null, null, $this->pessoa_logada, $this->nm_tipo, $this->descricao, null, null, 1, $this->ref_cod_instituicao);
        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';

            throw new HttpResponseException(
                new RedirectResponse('educar_habilitacao_lst.php')
            );
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj = new Habilitacao($this->cod_habilitacao, $this->pessoa_logada, null, $this->nm_tipo, $this->descricao, null, null, 1, $this->ref_cod_instituicao);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';

            throw new HttpResponseException(
                new RedirectResponse('educar_habilitacao_lst.php')
            );
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj = new Habilitacao($this->cod_habilitacao, $this->pessoa_logada, null, null, null, null, null, 0, $this->ref_cod_instituicao);
        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';

            throw new HttpResponseException(
                new RedirectResponse('educar_habilitacao_lst.php')
            );
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
