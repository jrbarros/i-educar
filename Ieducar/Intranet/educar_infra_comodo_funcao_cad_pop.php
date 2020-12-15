<?php

require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Tipo de ambiente ");
        $this->processoAp = '572';
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

    public $cod_infra_comodo_funcao;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_funcao;
    public $desc_funcao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_escola;
    public $ref_cod_instituicao;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_infra_comodo_funcao=$_GET['cod_infra_comodo_funcao'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(572, $this->pessoa_logada, 7, 'educar_infra_comodo_funcao_lst.php');

        /*if( is_numeric( $this->cod_infra_comodo_funcao ) )
        {

            $obj = new InfraComodoFuncao();
            $lst  = $obj->lista( $this->cod_infra_comodo_funcao );
            if (is_array($lst))
            {
                $registro = array_shift($lst);
                if( $registro )
                {
                    foreach( $registro AS $campo => $val )  // passa todos os valores obtidos no registro para atributos do objeto
                        $this->$campo = $val;

                    //** verificao de permissao para exclusao
                    $this->fexcluir = $obj_permissoes->permissao_excluir(572,$this->pessoa_logada,7);
                    //**

                    $retorno = "Editar";
                }else{
                    header( "Location: educar_infra_comodo_funcao_lst.php" );
                    die();
                }
            }
        }*/
//      $this->url_cancelar = ($retorno == "Editar") ? "educar_infra_comodo_funcao_det.php?cod_infra_comodo_funcao={$registro["cod_infra_comodo_funcao"]}" : "educar_infra_comodo_funcao_lst.php";
        $this->nome_url_cancelar = 'Cancelar';
        $this->script_cancelar = 'window.parent.fechaExpansivel("div_dinamico_"+(parent.DOM_divs.length-1));';

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_infra_comodo_funcao', $this->cod_infra_comodo_funcao);
        if ($_GET['precisa_lista']) {
            $obrigatorio = true;
            $get_escola = true;
            include('Source/pmieducar/educar_campo_lista.php');
        } else {
            $this->campoOculto('ref_cod_instituicao', $this->ref_cod_instituicao);
            $this->campoOculto('ref_cod_escola', $this->ref_cod_escola);
        }
        // text
        $this->campoTexto('nm_funcao', 'Tipo', $this->nm_funcao, 30, 255, true);
        $this->campoMemo('desc_funcao', 'Descrição do tipo', $this->desc_funcao, 60, 5, false);

        // data
    }

    public function Novo()
    {
        $obj = new InfraComodoFuncao(null, null, $this->pessoa_logada, $this->nm_funcao, $this->desc_funcao, null, null, 1, $this->ref_cod_escola);
        $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            echo "<script>
                        if (parent.document.getElementById('ref_cod_infra_comodo_funcao').disabled)
                            parent.document.getElementById('ref_cod_infra_comodo_funcao').options[0] = new Option('Selecione uma função cômodo', '', false, false);
                        parent.document.getElementById('ref_cod_infra_comodo_funcao').options[parent.document.getElementById('ref_cod_infra_comodo_funcao').options.length] = new Option('$this->nm_funcao', '$cadastrou', false, false);
                        parent.document.getElementById('ref_cod_infra_comodo_funcao').value = '$cadastrou';
                        parent.document.getElementById('ref_cod_infra_comodo_funcao').disabled = false;
                        window.parent.fechaExpansivel('div_dinamico_'+(parent.DOM_divs.length-1));
                    </script>";
            die();
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
    Event.observe(window, 'load', Init, false);

    function Init()
    {
        $('ref_cod_instituicao').value = parent.document.getElementById('ref_cod_instituicao').value;
        $('ref_cod_escola').value = parent.document.getElementById('ref_cod_escola').value;

//      alert($F('ref_cod_instituicao')+'   '+$F('ref_cod_escola'));

    }

<?php
}
?>

</script>
