<?php

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Reservas");
        $this->processoAp = '609';
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

    public $ref_cod_biblioteca;
    public $login_;
    public $senha_;
    public $ref_cod_cliente;

    public function Inicializar()
    {
        $retorno = 'Novo';

        Session::forget([
            'reservas.cod_cliente',
            'reservas.ref_cod_biblioteca',
        ]);

        return $retorno;
    }

    public function Gerar()
    {
        unset($this->login_);
        unset($this->senha_);
        unset($this->ref_cod_biblioteca);

        $get_escola     = 1;
        $escola_obrigatorio = false;
        $get_biblioteca = 1;
        $instituicao_obrigatorio = true;
        $biblioteca_obrigatorio = true;
        include('Source/pmieducar/educar_campo_lista.php');

        // text
        $this->campoNumero('login_', 'Login', $this->login_, 9, 9);
        $this->campoSenha('senha_', 'Senha', $this->senha_);

        $this->campoTexto('nm_cliente1', 'Cliente', $this->nm_cliente, 30, 255, false, false, false, '', "<img border=\"0\" onclick=\"pesquisa_cliente();\" id=\"ref_cod_cliente_lupa\" name=\"ref_cod_cliente_lupa\" src=\"imagens/lupa.png\"\/>", '', '', true);
        $this->campoOculto('ref_cod_cliente', $this->ref_cod_cliente);
        $this->botao_enviar = false;
        $this->array_botao = ['Continuar', 'Cancelar'];
        $this->array_botao_url_script = ['acao();','go(\'educar_reservas_lst.php\');'];
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(610, $this->pessoa_logada, 11, 'educar_reservas_lst.php');

        if ($this->ref_cod_cliente) {
            Session::put([
                'reservas.cod_cliente' => $this->ref_cod_cliente,
                'reservas.ref_cod_biblioteca' => $this->ref_cod_biblioteca,
            ]);

            throw new HttpResponseException(
                new RedirectResponse(
                    URL::to('Intranet/educar_reservas_cad.php')
                )
            );
        } elseif ($this->login_ && $this->senha_) {
            $this->senha_ = md5($this->senha_.'asnk@#*&(23');

            $obj_cliente = new Cliente();
            $lst_cliente = $obj_cliente->lista(null, null, null, null, $this->login_, $this->senha_, null, null, null, null, 1);

            if (is_array($lst_cliente) && count($lst_cliente)) {
                $cliente = array_shift($lst_cliente);
                $cod_cliente = $cliente['cod_cliente'];

                $obj_cliente_tipo_cliente = new ClienteTipoCliente();
                $lst_cliente_tipo_cliente = $obj_cliente_tipo_cliente->lista(null, $cod_cliente);

                if (is_array($lst_cliente_tipo_cliente) && count($lst_cliente_tipo_cliente)) {
                    foreach ($lst_cliente_tipo_cliente as $cliente_tipo) {
                        // tipo do cliente
                        $cod_cliente_tipo = $cliente_tipo['ref_cod_cliente_tipo'];

                        $obj_cliente_tipo = new ClienteTipo($cod_cliente_tipo);
                        $det_cliente_tipo = $obj_cliente_tipo->detalhe();
                        $biblioteca_cliente = $det_cliente_tipo['ref_cod_biblioteca'];

                        if ($this->ref_cod_biblioteca == $biblioteca_cliente) {
                            Session::put([
                                'reservas.cod_cliente' => $cod_cliente,
                                'reservas.ref_cod_biblioteca' => $this->ref_cod_biblioteca,
                            ]);

                            throw new HttpResponseException(
                                new RedirectResponse(
                                    URL::to('Intranet/educar_reservas_cad.php')
                                )
                            );
                        }
                    }
                    echo '<script> alert(\'Cliente não pertence a biblioteca escolhida!\'); </script>';

                    return true;
                }
            } else {
                $this->mensagem = 'Login e/ou Senha incorreto(s).<br>';

                return false;
            }
        } else {
            $this->mensagem = 'Preencha o(s) campo(s) corretamente.<br>';

            return false;
        }
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

document.getElementById('login_').setAttribute('autocomplete','off');
document.getElementById('login_').onkeypress = function(e)
{
    //IE bug
    if(!e)
        e = window.event;
    if(e.keyCode == 13)
        document.getElementById('senha_').focus();
}

document.getElementById('senha_').onkeypress = function(e)
{
    //IE bug
    if(!e)
        e = window.event;
    if(e.keyCode == 13)
        document.getElementById('btn_enviar').focus();
    else if(e.keyCode == 8)
        document.getElementById('senha_').value = null;
}

function pesquisa_cliente()
{
    var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;
    pesquisa_valores_popless('educar_pesquisa_cliente_lst.php?campo1=ref_cod_cliente&campo2=nm_cliente1&ref_cod_biblioteca='+campoBiblioteca)
}

setVisibility('tr_login_', false);
setVisibility('tr_senha_', false);
setVisibility('tr_nm_cliente', false);

function requisitaSenha(xml_tipo_regime)
{
    var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;

    var DOM_array = xml_tipo_regime.getElementsByTagName( "biblioteca" );

    if (campoBiblioteca == '')
    {
        setVisibility('tr_login_', false);
        setVisibility('tr_senha_', false);
        setVisibility('tr_nm_cliente', false);
    }
    else
    {
        for( var i = 0; i < DOM_array.length; i++ )
        {
            if (DOM_array[i].getAttribute("requisita_senha") == 0)
            {
                setVisibility('tr_login_', false);
                setVisibility('tr_senha_', false);
                setVisibility('tr_nm_cliente', true);
            }
            else if (DOM_array[i].getAttribute("requisita_senha") == 1)
            {
                setVisibility('tr_nm_cliente', false);
                setVisibility('tr_login_', true);
                setVisibility('tr_senha_', true);
            }
        }
    }
}

document.getElementById('ref_cod_biblioteca').onchange = function()
{
//  requisitaSenha();
    var campoBiblioteca = document.getElementById('ref_cod_biblioteca').value;

    var xml_biblioteca = new ajax( requisitaSenha );
    xml_biblioteca.envia( "educar_biblioteca_xml.php?bib="+campoBiblioteca );
}

</script>
