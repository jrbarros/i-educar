<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Coffebreak Tipo");
        $this->processoAp = '564';
    }
}

class indice extends clsDetalhe
{
    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

    public $cod_coffebreak_tipo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $desc_tipo;
    public $custo_unitario;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Coffebreak Tipo - Detalhe';

        $this->cod_coffebreak_tipo=$_GET['cod_coffebreak_tipo'];

        $tmp_obj = new CoffeeBreakTipo($this->cod_coffebreak_tipo);
        $registro = $tmp_obj->detalhe();

        if (! $registro || !$registro['ativo']) {
            $this->simpleRedirect('educar_coffebreak_tipo_lst.php');
        }

        if ($registro['cod_coffebreak_tipo']) {
            $this->addDetalhe([ 'Coffebreak Tipo', "{$registro['cod_coffebreak_tipo']}"]);
        }
        if ($registro['nm_tipo']) {
            $this->addDetalhe([ 'Nome Tipo', "{$registro['nm_tipo']}"]);
        }
        if ($registro['desc_tipo']) {
            $this->addDetalhe([ 'Desc Tipo', "{$registro['desc_tipo']}"]);
        }
        if ($registro['custo_unitario']) {
            $this->addDetalhe([ 'Custo Unitario', str_replace('.', ',', $registro['custo_unitario'])]);
        }

        //** Verificacao de permissao para cadastro
        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(554, $this->pessoa_logada, 7)) {
            $this->url_novo = 'educar_coffebreak_tipo_cad.php';
            $this->url_editar = "educar_coffebreak_tipo_cad.php?cod_coffebreak_tipo={$registro['cod_coffebreak_tipo']}";
        }
        //**

        $this->url_cancelar = 'educar_coffebreak_tipo_lst.php';
        $this->largura = '100%';
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
