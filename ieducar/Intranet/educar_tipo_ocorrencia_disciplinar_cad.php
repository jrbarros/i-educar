<?php
 // error_reporting(E_ERROR);
 // ini_set("display_errors", 1);

require_once('include/Base.php');
require_once('include/clsCadastro.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Tipo Ocorr&ecirc;ncia Disciplinar");
        $this->processoAp = '580';
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

    public $cod_tipo_ocorrencia_disciplinar;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $descricao;
    public $max_ocorrencias;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_tipo_ocorrencia_disciplinar=$_GET['cod_tipo_ocorrencia_disciplinar'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(580, $this->pessoa_logada, 3, 'educar_tipo_ocorrencia_disciplinar_lst.php');

        if (is_numeric($this->cod_tipo_ocorrencia_disciplinar)) {
            $obj = new TipoOcorrenciaDisciplinar($this->cod_tipo_ocorrencia_disciplinar);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }

                $this->fexcluir = $obj_permissoes->permissao_excluir(580, $this->pessoa_logada, 3);
                $retorno = 'Editar';
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_tipo_ocorrencia_disciplinar_det.php?cod_tipo_ocorrencia_disciplinar={$registro['cod_tipo_ocorrencia_disciplinar']}" : 'educar_tipo_ocorrencia_disciplinar_lst.php';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' tipo de ocorrência disciplinar', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_tipo_ocorrencia_disciplinar', $this->cod_tipo_ocorrencia_disciplinar);

        // foreign keys
        $obrigatorio = true;
        include('include/pmieducar/educar_campo_lista.php');

        // text
        $this->campoTexto('nm_tipo', 'Tipo Ocorr&ecirc;ncia Disciplinar', $this->nm_tipo, 30, 255, true);
        $this->campoMemo('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 60, 5, false);
        $this->campoNumero('max_ocorrencias', 'M&aacute;ximo Ocorr&ecirc;ncias', $this->max_ocorrencias, 4, 4, false);
    }

    public function Novo()
    {
        $obj = new TipoOcorrenciaDisciplinar(null, null, $this->pessoa_logada, $this->nm_tipo, $this->descricao, $this->max_ocorrencias, null, null, 1, $this->ref_cod_instituicao);
        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
            $this->simpleRedirect('educar_tipo_ocorrencia_disciplinar_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj = new TipoOcorrenciaDisciplinar($this->cod_tipo_ocorrencia_disciplinar, $this->pessoa_logada, null, $this->nm_tipo, $this->descricao, $this->max_ocorrencias, null, null, 1, $this->ref_cod_instituicao);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_tipo_ocorrencia_disciplinar_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj = new TipoOcorrenciaDisciplinar($this->cod_tipo_ocorrencia_disciplinar, $this->pessoa_logada, null, null, null, null, null, null, 0);
        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_tipo_ocorrencia_disciplinar_lst.php');
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
