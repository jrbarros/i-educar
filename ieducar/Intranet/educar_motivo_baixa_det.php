<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Motivo Baixa");
        $this->processoAp = '600';
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

    public $cod_motivo_baixa;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_motivo_baixa;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Motivo Baixa - Detalhe';

        $this->cod_motivo_baixa=$_GET['cod_motivo_baixa'];

        $tmp_obj = new MotivoBaixa($this->cod_motivo_baixa);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_motivo_baixa_lst.php');
        }

        if ($registro['nm_motivo_baixa']) {
            $this->addDetalhe([ 'Motivo Baixa', "{$registro['nm_motivo_baixa']}"]);
        }
        if ($registro['descricao']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o', "{$registro['descricao']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(600, $this->pessoa_logada, 11)) {
            $this->url_novo = 'educar_motivo_baixa_cad.php';
            $this->url_editar = "educar_motivo_baixa_cad.php?cod_motivo_baixa={$registro['cod_motivo_baixa']}";
        }

        $this->url_cancelar = 'educar_motivo_baixa_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do motivo de baixa', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
        ]);
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
