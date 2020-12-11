<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Acervo Assunto");
        $this->processoAp = '592';
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

    public $cod_acervo_assunto;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_assunto;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Acervo Assunto - Detalhe';

        $this->cod_acervo_assunto=$_GET['cod_acervo_assunto'];

        $tmp_obj = new AcervoAssunto($this->cod_acervo_assunto);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_acervo_assunto_lst.php');
        }

        if ($registro['nm_assunto']) {
            $this->addDetalhe([ 'Assunto', "{$registro['nm_assunto']}"]);
        }
        if ($registro['descricao']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o', "{$registro['descricao']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(592, $this->pessoa_logada, 11)) {
            $this->url_novo = 'educar_acervo_assunto_cad.php';
            $this->url_editar = "educar_acervo_assunto_cad.php?cod_acervo_assunto={$registro['cod_acervo_assunto']}";
        }

        $this->url_cancelar = 'educar_acervo_assunto_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Listagem de assuntos', [
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
