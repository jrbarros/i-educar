<?php

/**
 * @author Adriano Erik Weiguert Nagasava
 */
require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Cliente");
        $this->processoAp = '623';
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

    public $cod_cliente;
    public $nm_cliente;
    public $nm_biblioteca;
    public $ref_cod_instituicao;
    public $ref_cod_escola;
    public $ref_cod_biblioteca;
    public $ref_cod_cliente_tipo;
    public $ref_cod_cliente_tipo_original;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_idpes;
    public $login;
    public $senha;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $del_cod_cliente;
    public $del_cod_cliente_tipo;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_cliente                   = $_GET['cod_cliente'];
        $this->ref_cod_cliente_tipo          = $_GET['cod_cliente_tipo'];
        $this->ref_cod_cliente_tipo_original = $_GET['cod_cliente_tipo'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(623, $this->pessoa_logada, 11, 'educar_definir_cliente_tipo_lst.php');

        if (is_numeric($this->cod_cliente) && is_numeric($this->ref_cod_cliente_tipo)) {
            $obj_cliente = new Cliente();
            $lst_cliente = $obj_cliente->listaCompleta(
                $this->cod_cliente,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                1,
                null,
                null,
                $this->ref_cod_cliente_tipo
            );
            if ($lst_cliente) {
                foreach ($lst_cliente as $cliente) {
                    $this->ref_idpes           = $cliente['ref_idpes'];
                    $this->nm_cliente          = $cliente['nome'];
                    $this->nm_biblioteca       = $cliente['nm_biblioteca'];
                    $this->ref_cod_instituicao = $cliente['cod_instituicao'];
                    $this->ref_cod_escola      = $cliente['cod_escola'];
                    $this->ref_cod_biblioteca  = $cliente['cod_biblioteca'];
                    $obj_permissoes      = new Permissoes();
                    if ($obj_permissoes->permissao_excluir(623, $this->pessoa_logada, 11)) {
                        $this->fexcluir = true;
                    }

                    $retorno = 'Editar';
                }
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_definir_cliente_tipo_det.php?cod_cliente={$this->cod_cliente}&cod_cliente_tipo={$this->ref_cod_cliente_tipo}" : 'educar_definir_cliente_tipo_lst.php';
        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('ref_cod_cliente_tipo_original', $this->ref_cod_cliente_tipo_original);

        $instituicao_obrigatorio  = true;
        $escola_obrigatorio       = false;
        $biblioteca_obrigatorio   = true;
        $cliente_tipo_obrigatorio = true;
        $get_instituicao          = true;
        $get_escola               = true;
        $get_biblioteca           = true;
        $get_cliente_tipo         = true;

        if ($this->cod_cliente) {
            $instituicao_desabilitado = true;
            $escola_desabilitado = true;
            $biblioteca_desabilitado = true;
        }
        include('Source/pmieducar/educar_campo_lista.php');
        if (!$this->cod_cliente) {
            $opcoes_cliente = [ '' => 'Pesquise a Pessoa clicando na lupa ao lado' ];

            $this->campoListaPesq('cod_cliente', 'Cliente', $opcoes_cliente, $this->cod_cliente, 'educar_pesquisa_cliente_lst.php?campo1=cod_cliente', '', false, '', '', null, null, '', true);
        } else {
            $this->campoOculto('cod_cliente', $this->cod_cliente);
            $this->campoRotulo('nm_cliente', 'Cliente', $this->nm_cliente);
        }
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(623, $this->pessoa_logada, 11, 'educar_definir_cliente_tipo_lst.php');

        $obj_cliente = new Cliente($this->cod_cliente);
        $det_cliente = $obj_cliente->detalhe();

        if ($det_cliente) {
            $obj_cliente_tipo = new ClienteTipoCliente($this->ref_cod_cliente_tipo, $det_cliente['cod_cliente'], null, null, null, null);
            if ($obj_cliente_tipo->existeCliente()) {
                $obj_cliente_tipo = new ClienteTipoCliente($this->ref_cod_cliente_tipo, $det_cliente['cod_cliente'], null, null, null, $this->pessoa_logada, 1);
                if ($obj_cliente_tipo->trocaTipo()) {
                    $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
                    $this->simpleRedirect('educar_definir_cliente_tipo_lst.php');
                }
            } else {
                $obj_cliente_tipo = new ClienteTipoCliente($this->ref_cod_cliente_tipo, $det_cliente['cod_cliente'], null, null, $this->pessoa_logada, null, 1);
                if ($obj_cliente_tipo->cadastra()) {
                    $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
                    $this->simpleRedirect('educar_definir_cliente_tipo_lst.php');
                }
            }
            $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

            return false;
        }
        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(623, $this->pessoa_logada, 11, 'educar_definir_cliente_tipo_lst.php');

        $obj_cliente_tipo = new ClienteTipoCliente($this->ref_cod_cliente_tipo, $this->cod_cliente, null, null, null, $this->pessoa_logada);
        if ($obj_cliente_tipo->existeCliente()) {
            //$obj_cliente_tipo = new ClienteTipoCliente( $this->ref_cod_cliente_tipo, $this->cod_cliente, null, null, null, $this->pessoa_logada, 1 );
            //if( $obj_cliente_tipo->edita() )
            //{
            //$obj_cliente_tipo = new ClienteTipoCliente( $this->ref_cod_cliente_tipo_original, $this->cod_cliente, null, null, null, $this->pessoa_logada, 0 );
            if ($obj_cliente_tipo->trocaTipo()) {
                $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';
                $this->simpleRedirect('educar_definir_cliente_tipo_lst.php');
            }
            //  }
            $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

            return false;
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(623, $this->pessoa_logada, 11, 'educar_definir_cliente_tipo_lst.php');

        $obj_cliente_tipo = new ClienteTipoCliente($this->ref_cod_cliente_tipo, $this->cod_cliente, null, null, null, $this->pessoa_logada, 1);
        if ($obj_cliente_tipo->existe()) {
            if ($obj_cliente_tipo->excluir()) {
                $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';
                $this->simpleRedirect('educar_definir_cliente_tipo_lst.php');
            }
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
?>
<script>
if ( document.getElementById( 'ref_cod_instituicao' ) ) {
    var ref_cod_instituicao = document.getElementById( 'ref_cod_instituicao' );
    ref_cod_instituicao.onchange = function() { getEscola(); getBiblioteca(1); getClienteTipo(); }
}
if ( document.getElementById( 'ref_cod_escola' ) ) {
    var ref_cod_escola = document.getElementById( 'ref_cod_escola' );
    ref_cod_escola.onchange = function() { if ( document.getElementById('ref_cod_escola').value != '' ) { getBiblioteca(2); } else { getBiblioteca(1); } getClienteTipo(); }
}
if ( document.getElementById( 'ref_cod_biblioteca' ) ) {
    var ref_cod_biblioteca = document.getElementById( 'ref_cod_biblioteca' );
    ref_cod_biblioteca.onchange = function() { getClienteTipo(); }
}
</script>
