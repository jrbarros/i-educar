<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Benef&iacute;cio Aluno");
        $this->processoAp = '581';
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

    public $cod_aluno_beneficio;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_beneficio;
    public $desc_beneficio;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Aluno Beneficio - Detalhe';

        $this->cod_aluno_beneficio=$_GET['cod_aluno_beneficio'];

        $tmp_obj = new AlunoBeneficio($this->cod_aluno_beneficio);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_aluno_beneficio_lst.php');
        }

        if ($registro['cod_aluno_beneficio']) {
            $this->addDetalhe([ 'C&oacute;digo Benef&iacute;cio', "{$registro['cod_aluno_beneficio']}"]);
        }
        if ($registro['nm_beneficio']) {
            $this->addDetalhe([ 'Benef&iacute;cio', "{$registro['nm_beneficio']}"]);
        }
        if ($registro['desc_beneficio']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o', nl2br("{$registro['desc_beneficio']}")]);
        }

        //** Verificacao de permissao para cadastro
        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(581, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_aluno_beneficio_cad.php';
            $this->url_editar = "educar_aluno_beneficio_cad.php?cod_aluno_beneficio={$registro['cod_aluno_beneficio']}";
        }
        //**
        $this->url_cancelar = 'educar_aluno_beneficio_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do benefício de alunos', [
            url('Intranet/educar_index.php') => 'Escola',
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
