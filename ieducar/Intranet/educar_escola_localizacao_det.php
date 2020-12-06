<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Escola Localiza&ccedil;&atilde;o");
        $this->processoAp = '562';
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

    public $cod_escola_localizacao;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_localizacao;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Escola Localiza&ccedil;&atilde;o - Detalhe';

        $this->cod_escola_localizacao=$_GET['cod_escola_localizacao'];

        $tmp_obj = new EscolaLocalizacao($this->cod_escola_localizacao);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_escola_localizacao_lst.php');
        }

        $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            if ($registro['ref_cod_instituicao']) {
                $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
            }
        }
        if ($registro['nm_localizacao']) {
            $this->addDetalhe([ 'Localiza&ccedil;&atilde;o', "{$registro['nm_localizacao']}"]);
        }

        if ($obj_permissoes->permissao_cadastra(562, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_escola_localizacao_cad.php';
            $this->url_editar = "educar_escola_localizacao_cad.php?cod_escola_localizacao={$registro['cod_escola_localizacao']}";
        }
        $this->url_cancelar = 'educar_escola_localizacao_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da localização', [
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
