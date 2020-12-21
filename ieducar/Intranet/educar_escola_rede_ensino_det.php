<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Escola Rede Ensino");
        $this->processoAp = '647';
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

    public $cod_escola_rede_ensino;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_rede;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Escola Rede Ensino - Detalhe';

        $this->cod_escola_rede_ensino=$_GET['cod_escola_rede_ensino'];

        $tmp_obj = new EscolaRedeEnsino($this->cod_escola_rede_ensino);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_escola_rede_ensino_lst.php');
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
        if ($registro['nm_rede']) {
            $this->addDetalhe([ 'Rede Ensino', "{$registro['nm_rede']}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(647, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_escola_rede_ensino_cad.php';
            $this->url_editar = "educar_escola_rede_ensino_cad.php?cod_escola_rede_ensino={$registro['cod_escola_rede_ensino']}";
        }

        $this->url_cancelar = 'educar_escola_rede_ensino_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da rede de ensino', [
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
