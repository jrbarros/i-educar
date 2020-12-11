<?php

require_once('Source/Base.php');
require_once('Source/Cadastro.inc.php');
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

class indice extends clsCadastro
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

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
    public $ref_cod_biblioteca_;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_situacao=$_GET['cod_situacao'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(602, $this->pessoa_logada, 11, 'educar_situacao_lst.php');

        $this->ref_cod_biblioteca = $this->ref_cod_biblioteca_ = $obj_permissoes->getBiblioteca($this->pessoa_logada);
        if (is_numeric($this->cod_situacao)) {
            $obj = new Situacao($this->cod_situacao);
            $registro  = $obj->detalhe();
            if ($registro) {
                foreach ($registro as $campo => $val) {  // passa todos os valores obtidos no registro para atributos do objeto
                    $this->$campo = $val;
                }

                if ($this->cod_situacao) {
                    $obj_biblioteca = new Biblioteca($this->ref_cod_biblioteca);
                    $det_biblioteca = $obj_biblioteca->detalhe();
                    $this->ref_cod_instituicao = $det_biblioteca['ref_cod_instituicao'];
                    $this->ref_cod_escola = $det_biblioteca['ref_cod_escola'];
                    $this->ref_cod_biblioteca = $this->ref_cod_biblioteca_ = $this->ref_cod_biblioteca;
                }

                if ($obj_permissoes->permissao_excluir(602, $this->pessoa_logada, 11)) {
                    $this->fexcluir = true;
                }
                $retorno = 'Editar';
            }
        }
        $this->url_cancelar = ($retorno == 'Editar') ? "educar_situacao_det.php?cod_situacao={$registro['cod_situacao']}" : 'educar_situacao_lst.php';
        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' situação', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
        ]);

        return $retorno;
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_situacao', $this->cod_situacao);

        // foreign keys
        $get_escola     = 1;
        $escola_obrigatorio = false;
        $get_biblioteca = 1;
        $instituicao_obrigatorio = true;
        $biblioteca_obrigatorio = true;
        include('Source/pmieducar/educar_campo_lista.php');

        //-------------- JS para os Check --------------//
        //if (!$this->cod_situacao)
        //  {
        /*$todas_situacoes = "situacao = new Array();\n";
        $obj_biblioteca = new Situacao();
        $lista = $obj_biblioteca->lista(null,null,null,null,null,null,null,null,null,null,null,null,1);
        if ( is_array( $lista ) && count( $lista ) )
        {
            foreach ( $lista as $registro )
            {
                $todas_situacoes .= "situacao[situacao.length] = new Array( {$registro["cod_situacao"]}, {$registro['situacao_padrao']}, {$registro['situacao_emprestada']}, {$registro['ref_cod_biblioteca']});\n";
            }
        }
        echo "<script>{$todas_situacoes}</script>";*/
        //  }

        // text
        $this->campoTexto('nm_situacao', 'Situa&ccedil;&atilde;o', $this->nm_situacao, 30, 255, true);

        $opcoes = ['' => 'Selecione', 1 => 'n&atilde;o', 2 => 'sim' ];
        $this->campoLista('permite_emprestimo', 'Permite Empr&eacute;stimo', $opcoes, $this->permite_emprestimo);
        $this->campoMemo('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 60, 5, false);

        $obj_situacao = new Situacao();
        if ($this->ref_cod_biblioteca_) {
            $lst_situacao = $obj_situacao->lista(null, null, null, null, null, null, 1, null, null, null, null, null, 1, $this->ref_cod_biblioteca_, null, null);
        }

        if ($lst_situacao) {   //echo "<pre>";

            $achou = false;
            //print_r($lst_situacao);die;
            foreach ($lst_situacao as $situacao) {
                if ($situacao['cod_situacao'] == $this->cod_situacao) {
                    $achou = true;
                }
            }
            if (!$achou) {
                $script .="setVisibility('tr_situacao_padrao',false);\n";
            }
            //$lista = array_shift($lst_situacao);
            //$situacao = $lista["cod_situacao"];
            //$biblioteca = $lista["ref_cod_biblioteca"];
        }
        $this->campoCheck('situacao_padrao', 'Situa&ccedil;&atilde;o Padr&atilde;o', $this->situacao_padrao);
        //if (!isset($lst_situacao) || $this->cod_situacao == $situacao || $this->ref_cod_biblioteca != $biblioteca)
        //if (!$this->cod_situacao)
        //$this->campoCheck( "situacao_padrao", "Situa&ccedil;&atilde;o Padr&atilde;o", $this->situacao_padrao );

        //$lst_situacao = $obj_situacao->lista(null,null,null,null,null,null,null,1,null,null,null,null,1,$this->ref_cod_biblioteca,$this->ref_cod_instituicao,$this->ref_cod_escola);
        /*  if ($lst_situacao)
            {
                $lista = array_shift($lst_situacao);
                $situacao = $lista["cod_situacao"];
                $biblioteca = $lista["ref_cod_biblioteca"];
            }*/
        //if (!isset($lst_situacao) || $this->cod_situacao == $situacao || $this->ref_cod_biblioteca != $biblioteca)

        $obj_situacao = new Situacao();
        if ($this->ref_cod_biblioteca_) {
            $lst_situacao = $obj_situacao->lista(null, null, null, null, null, null, null, 1, null, null, null, null, 1, $this->ref_cod_biblioteca_, null, null);
        }

        if ($lst_situacao) {
            $achou = false;
            foreach ($lst_situacao as $situacao) {
                if ($situacao['cod_situacao'] == $this->cod_situacao) {
                    $achou = true;
                }
            }
            //$lista = array_shift($lst_situacao);
            //$situacao = $lista["cod_situacao"];
            //$biblioteca = $lista["ref_cod_biblioteca"];
            if (!$achou) {
                $script .="setVisibility('tr_situacao_emprestada',false);\n";
            }
        }

        if ($script) {
            echo "<script>window.onload=function(){{$script}}</script>";
        }
        $this->campoCheck('situacao_emprestada', 'Situa&ccedil;&atilde;o Emprestada', $this->situacao_emprestada);
        //if ($this->situacao_emprestada)
        //$this->campoCheck( "situacao_emprestada", "Situa&ccedil;&atilde;o Emprestada", $this->situacao_emprestada );

        $this->acao_enviar = 'valida()';
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(602, $this->pessoa_logada, 11, 'educar_situacao_lst.php');

        $this->situacao_padrao = is_null($this->situacao_padrao) ? 0 : 1;
        $this->situacao_emprestada = is_null($this->situacao_emprestada) ? 0 : 1;

        $obj = new Situacao(null, null, $this->pessoa_logada, $this->nm_situacao, $this->permite_emprestimo, $this->descricao, $this->situacao_padrao, $this->situacao_emprestada, null, null, 1, $this->ref_cod_biblioteca);
        $this->cod_situacao = $cadastrou = $obj->cadastra();
        if ($cadastrou) {
            $obj->cod_situacao = $this->cod_situacao;
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
            $this->simpleRedirect('educar_situacao_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(602, $this->pessoa_logada, 11, 'educar_situacao_lst.php');

        $this->situacao_padrao = is_null($this->situacao_padrao) ? 0 : 1;
        $this->situacao_emprestada = is_null($this->situacao_emprestada) ? 0 : 1;

        $obj = new Situacao($this->cod_situacao, $this->pessoa_logada, null, $this->nm_situacao, $this->permite_emprestimo, $this->descricao, $this->situacao_padrao, $this->situacao_emprestada, null, null, 1, $this->ref_cod_biblioteca);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_situacao_lst.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_excluir(602, $this->pessoa_logada, 11, 'educar_situacao_lst.php');

        $obj = new Situacao($this->cod_situacao, $this->pessoa_logada, null, null, null, null, null, null, null, null, 0);
        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclus&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_situacao_lst.php');
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

document.getElementById('ref_cod_biblioteca').onchange = function()
{
//  getSituacao();
    var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;

    var xml_situacao = new ajax( getSituacao );
    xml_situacao.envia( "educar_situacao_xml.php?bib="+campoBiblioteca );
}

function getSituacao(xml_situacao)
{
    /*
    var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;

    setVisibility('tr_situacao_padrao',true);
    setVisibility('tr_situacao_emprestada',true);

    for (var j = 0; j < situacao.length; j++)
    {
        if (situacao[j][3] == campoBiblioteca)
        {
            if (situacao[j][1] == 1) //jah existe uma situacao padrao
                setVisibility('tr_situacao_padrao',false);

            if (situacao[j][2] == 1) //jah existe uma situacao emprestada
                setVisibility('tr_situacao_emprestada',false);
        }
    }
    */
    setVisibility('tr_situacao_padrao',true);
    setVisibility('tr_situacao_emprestada',true);

    var DOM_array = xml_situacao.getElementsByTagName( "situacao" );

    if(DOM_array.length)
    {
        for( var i = 0; i < DOM_array.length; i++ )
        {
            if (DOM_array[i].getAttribute("situacao_padrao") == 1) //jah existe uma situacao padrao
                setVisibility('tr_situacao_padrao',false);

            if (DOM_array[i].getAttribute("situacao_emprestada") == 1) //jah existe uma situacao emprestada
                setVisibility('tr_situacao_emprestada',false);
        }
    }
}

function valida()
{
    var campoPadrao = document.getElementById('situacao_padrao').checked;
    var campoEmprestada = document.getElementById('situacao_emprestada').checked;

    if( campoPadrao == true && campoEmprestada == true)
    {
        alert("Não é permitido setar ao mesmo tempo os campos \n 'Situação Padrão' e 'Situação Emprestada'!");
        return false;
    }

    if(!acao())
        return;
    document.forms[0].submit();
}

</script>
