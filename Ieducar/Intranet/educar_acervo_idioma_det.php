<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Idioma");
        $this->processoAp = '590';
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

    public $cod_acervo_idioma;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_idioma;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Idioma - Detalhe';

        $this->cod_acervo_idioma=$_GET['cod_acervo_idioma'];

        $tmp_obj = new AcervoIdioma($this->cod_acervo_idioma);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_acervo_idioma_lst.php');
        }

        if ($registro['cod_acervo_idioma']) {
            $this->addDetalhe([ 'C&oacute;digo Idioma', "{$registro['cod_acervo_idioma']}"]);
        }
        if ($registro['nm_idioma']) {
            $this->addDetalhe([ 'Idioma', "{$registro['nm_idioma']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(590, $this->pessoa_logada, 11)) {
            $this->url_novo = 'educar_acervo_idioma_cad.php';
            $this->url_editar = "educar_acervo_idioma_cad.php?cod_acervo_idioma={$registro['cod_acervo_idioma']}";
        }

        $this->url_cancelar = 'educar_acervo_idioma_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do idioma', [
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
