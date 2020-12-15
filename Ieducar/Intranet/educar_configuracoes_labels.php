<?php

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Portabilis/Utils/CustomLabel.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Customiza&ccedil;&atilde;o de labels');
        $this->processoAp = 9998869;
    }
}

class indice extends clsCadastro
{
    public $pessoa_logada;
    public $ref_cod_instituicao;
    public $custom_labels;

    public function Inicializar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(9998869, $this->pessoa_logada, 7, 'educar_index.php');
        $this->ref_cod_instituicao = $obj_permissoes->getInstituicao($this->pessoa_logada);

        $this->breadcrumb('Customização de labels', [
        url('Intranet/educar_configuracoes_index.php') => 'Configurações',
    ]);

        return 'Editar';
    }

    public function Gerar()
    {
        $obj_permissoes = new Permissoes();
        $ref_cod_instituicao = $obj_permissoes->getInstituicao($this->pessoa_logada);

        $configuracoes = new ConfiguracoesGerais($ref_cod_instituicao);
        $configuracoes = $configuracoes->detalhe();

        $this->custom_labels = $configuracoes['custom_labels'];

        $customLabel = new CustomLabel();
        $defaults = $customLabel->getDefaults();
        ksort($defaults);
        $rotulo = null;
        foreach ($defaults as $k => $v) {
            $rotulo2 = explode('.', $k)[0];

            if ($rotulo2 != $rotulo) {
                $rotulo2 = ucfirst($rotulo2);
                $this->campoRotulo($rotulo2, '<strong>' . $rotulo2 . '</strong>');
            }
            $this->inputsHelper()->text('custom_labels[' . $k . ']', [
            'label' => $k,
            'size' => 100,
            'required' => false,
            'placeholder' => $v,
            'value' => (!empty($this->custom_labels[$k])) ? $this->custom_labels[$k] : ''
        ]);
        }
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $ref_cod_instituicao = $obj_permissoes->getInstituicao($this->pessoa_logada);

        $configuracoes = new ConfiguracoesGerais($ref_cod_instituicao, [
        'custom_labels' => $this->custom_labels
    ]);

        $editou = $configuracoes->edita();

        if ($editou) {
            $this->mensagem .= 'Edi&ccedil;&atilde;o efetuada com sucesso.<br>';
            $this->simpleRedirect('index.php');
        }

        $this->mensagem = 'Edi&ccedil;&atilde;o n&atilde;o realizada.<br>';

        return false;
    }
}
// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à  página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();
