<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Pre Requisito");
        $this->processoAp = '601';
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

    public $cod_pre_requisito;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $schema_;
    public $tabela;
    public $nome;
    public $sql;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Pre Requisito - Detalhe';

        $this->cod_pre_requisito=$_GET['cod_pre_requisito'];

        $tmp_obj = new PreRequisito($this->cod_pre_requisito);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_pre_requisito_lst.php');
        }

        $obj_ref_usuario_exc = new Usuario($registro['ref_usuario_exc']);
        $det_ref_usuario_exc = $obj_ref_usuario_exc->detalhe();
        $registro['ref_usuario_exc'] = $det_ref_usuario_exc['data_cadastro'];

        $obj_ref_usuario_cad = new Usuario($registro['ref_usuario_cad']);
        $det_ref_usuario_cad = $obj_ref_usuario_cad->detalhe();
        $registro['ref_usuario_cad'] = $det_ref_usuario_cad['data_cadastro'];

        if ($registro['cod_pre_requisito']) {
            $this->addDetalhe([ 'Pre Requisito', "{$registro['cod_pre_requisito']}"]);
        }
        if ($registro['schema_']) {
            $this->addDetalhe([ 'Schema ', "{$registro['schema_']}"]);
        }
        if ($registro['tabela']) {
            $this->addDetalhe([ 'Tabela', "{$registro['tabela']}"]);
        }
        if ($registro['nome']) {
            $this->addDetalhe([ 'Nome', "{$registro['nome']}"]);
        }
        if ($registro['sql']) {
            $this->addDetalhe([ 'Sql', "{$registro['sql']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(601, $this->pessoa_logada, 3, null, true)) {
            $this->url_novo = 'educar_pre_requisito_cad.php';
            $this->url_editar = "educar_pre_requisito_cad.php?cod_pre_requisito={$registro['cod_pre_requisito']}";
        }

        $this->url_cancelar = 'educar_pre_requisito_lst.php';
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
