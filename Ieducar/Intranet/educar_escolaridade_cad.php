<?php

require_once 'Source/Base.php';
require_once 'Source/Cadastro.inc.php';
require_once 'Source/Banco.php';
require_once '';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'lib/Portabilis/String/Utils.php';

use iEducar\Support\View\SelectOptions;

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' Servidores - Escolaridade');
        $this->processoAp = '632';
    }
}

class indice extends clsCadastro
{
    /**
     * Referência a usuário da sessão
     *
     * @var int
     */
    public $pessoa_logada = null;
    public $idesco;
    public $descricao;
    public $escolaridade;
    public $findUsage;

    protected function loadAssets()
    {
        $jsFile = '/Modules/Cadastro/Assets/Javascripts/ModalExclusaoEscolaridade.js';
        Portabilis_View_Helper_Application::loadJavascript($this, $jsFile);
    }

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->idesco = $_GET['idesco'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(632, $this->pessoa_logada, 3, 'educar_escolaridade_lst.php');

        if (is_numeric($this->idesco)) {
            $obj = new CadastroEscolaridade($this->idesco);
            $registro = $obj->detalhe();
            $this->findUsage = $obj->findUsages();

            if ($this->findUsage) {
                $this->script_excluir = 'modalOpen();';
            }

            if ($registro) {
                // Passa todos os valores obtidos no registro para atributos do objeto
                foreach ($registro as $campo => $val) {
                    $this->$campo = $val;
                }

                if ($obj_permissoes->permissao_excluir(632, $this->pessoa_logada, 3)) {
                    $this->fexcluir = true;
                }

                $retorno = 'Editar';
            }
        }

        $this->url_cancelar = ($retorno == 'Editar') ?
            'educar_escolaridade_det.php?idesco=' . $registro['idesco'] :
            'educar_escolaridade_lst.php';

        $this->nome_url_cancelar = 'Cancelar';

        $nomeMenu = $retorno == 'Editar' ? $retorno : 'Cadastrar';

        $this->breadcrumb($nomeMenu . ' escolaridade', [
            url('Intranet/educar_servidores_index.php') => 'Servidores',
        ]);

        $this->loadAssets();

        return $retorno;
    }

    public function Gerar()
    {
        // Primary keys
        $this->campoOculto('idesco', $this->idesco);

        // Outros campos
        $this->campoTexto('descricao', 'Descri&ccedil;&atilde;o', $this->descricao, 30, 255, true);

        $options = ['label' => 'Escolaridade educacenso', 'resources' => SelectOptions::escolaridades(), 'value' => $this->escolaridade];
        $this->inputsHelper()->select('escolaridade', $options);
    }

    public function Novo()
    {
        $tamanhoDesc = strlen($this->descricao);
        if ($tamanhoDesc > 60) {
            $this->mensagem = 'A descrição deve conter no máximo 60 caracteres.<br>';

            return false;
        }

        $obj = new CadastroEscolaridade(null, $this->descricao, $this->escolaridade);
        $cadastrou = $obj->cadastra();

        if ($cadastrou) {
            $this->mensagem .= 'Cadastro efetuado com sucesso.<br>';
            $this->simpleRedirect('educar_escolaridade_lst.php');
        }

        $this->mensagem = 'Cadastro n&atilde;o realizado.<br>';

        return false;
    }

    public function Editar()
    {
        $obj = new CadastroEscolaridade($this->idesco, $this->descricao, $this->escolaridade);
        $editou = $obj->edita();
        if ($editou) {
            $this->mensagem .= 'Edição efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_escolaridade_lst.php');
        }

        $this->mensagem = 'Edição não realizada.<br>';

        return false;
    }

    public function Excluir()
    {
        $obj = new CadastroEscolaridade($this->idesco, $this->descricao);

        if ($obj->findUsages()) {
            $this->mensagem = 'Exclusão não realizada - Ainda existe vínculos.<br>';

            return false;
        }

        $excluiu = $obj->excluir();
        if ($excluiu) {
            $this->mensagem .= 'Exclusão efetuada com sucesso.<br>';
            $this->simpleRedirect('educar_escolaridade_lst.php');
        }

        $this->mensagem = 'Exclusão não realizada.<br>';

        return false;
    }
}

// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();
