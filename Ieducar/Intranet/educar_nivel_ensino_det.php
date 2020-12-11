<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Nivel Ensino");
        $this->processoAp = '571';
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

    public $cod_nivel_ensino;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_nivel;
    public $descricao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'N&iacute;vel Ensino - Detalhe';

        $this->cod_nivel_ensino=$_GET['cod_nivel_ensino'];

        $tmp_obj = new NivelEnsino($this->cod_nivel_ensino);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_nivel_ensino_lst.php');
        }

        $obj_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $obj_instituicao_det = $obj_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $obj_instituicao_det['nm_instituicao'];

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            if ($registro['ref_cod_instituicao']) {
                $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
            }
        }
        if ($registro['nm_nivel']) {
            $this->addDetalhe([ 'N&iacute;vel Ensino', "{$registro['nm_nivel']}"]);
        }
        if ($registro['descricao']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o', "{$registro['descricao']}"]);
        }

        if ($obj_permissoes->permissao_cadastra(571, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_nivel_ensino_cad.php';
            $this->url_editar = "educar_nivel_ensino_cad.php?cod_nivel_ensino={$registro['cod_nivel_ensino']}";
        }
        $this->url_cancelar = 'educar_nivel_ensino_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do nível de ensino', [
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
