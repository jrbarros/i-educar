<?php

require_once('include/Base.php');
require_once('include/clsCadastro.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Turma Tipo");
        $this->processoAp = '570';
        $this->renderBanner = false;
        $this->renderMenu = false;
        $this->renderMenuSuspenso = false;
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

    public $cod_turma_tipo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $sgl_tipo;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;
    public $ref_cod_escola;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_turma_tipo=$_GET['cod_turma_tipo'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(570, $this->pessoa_logada, 7, 'educar_turma_tipo_lst.php');

        if (is_numeric($this->cod_turma_tipo)) {
            $obj = new TurmaTipo($this->cod_turma_tipo);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }

                //$obj_ref_cod_escola = new Escola( $this->ref_cod_escola );
                //$det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                //$this->ref_cod_instituicao = $det_ref_cod_escola["ref_cod_instituicao"];

                $this->fexcluir = $obj_permissoes->permissao_excluir(570, $this->pessoa_logada, 7);
                $retorno = 'Editar';
            }
        }
//      $this->url_cancelar = ($retorno == "Editar") ? "educar_turma_tipo_det.php?cod_turma_tipo={$registro["cod_turma_tipo"]}" : "educar_turma_tipo_lst.php";
        $this->nome_url_cancelar = 'Cancelar';
        $this->script_cancelar = 'window.parent.fechaExpansivel("div_dinamico_"+(parent.DOM_divs.length-1));';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_turma_tipo', $this->cod_turma_tipo);

        if ($_GET['precisa_lista']) {
            $obrigatorio = true;
            // foreign keys
            $get_escola = false;
            include('include/pmieducar/educar_campo_lista.php');
        } else {
            $this->campoOculto('ref_cod_instituicao', $this->ref_cod_instituicao);
        }
        // text
        $this->campoTexto('nm_tipo', 'Turma Tipo', $this->nm_tipo, 30, 255, true);
        $this->campoTexto('sgl_tipo', 'Sigla', $this->sgl_tipo, 15, 15, true);
    }

    public function Novo()
    {
        $obj = new TurmaTipo(null, null, $this->pessoa_logada, $this->nm_tipo, $this->sgl_tipo, null, null, 1, $this->ref_cod_instituicao);
        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            echo "<script>
                        if (parent.document.getElementById('ref_cod_turma_tipo').disabled)
                            parent.document.getElementById('ref_cod_turma_tipo').options[0] = new Option('Selectione um tipo de turma', '', false, false);
                        parent.document.getElementById('ref_cod_turma_tipo').options[parent.document.getElementById('ref_cod_turma_tipo').options.length] = new Option('$this->nm_tipo', '$cadastrou', false, false);
                        parent.document.getElementById('ref_cod_turma_tipo').value = '$cadastrou';
                        parent.document.getElementById('ref_cod_turma_tipo').disabled = false;
                        window.parent.fechaExpansivel('div_dinamico_'+(parent.DOM_divs.length-1));
                    </script>";
            die();

            return true;
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
    }

    public function Excluir()
    {
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

<?php
if (!$_GET['precisa_lista']) {
    ?>
    Event.observe(window, 'load', Init);
    function Init()
    {
        $('ref_cod_instituicao').value = parent.document.getElementById('ref_cod_instituicao').value;
    }
<?php
}
?>

</script>
