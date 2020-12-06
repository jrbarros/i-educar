<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Ambiente");
        $this->processoAp = '574';
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

    public $cod_infra_predio_comodo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_infra_comodo_funcao;
    public $ref_cod_infra_predio;
    public $nm_comodo;
    public $desc_comodo;
    public $area;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Ambiente - Detalhe';

        $this->cod_infra_predio_comodo=$_GET['cod_infra_predio_comodo'];

        $tmp_obj = new InfraPredioComodo($this->cod_infra_predio_comodo);
        $lst = $tmp_obj->lista($this->cod_infra_predio_comodo);
        if (is_array($lst)) {
            $registro = array_shift($lst);
        }

        if (! $registro) {
            $this->simpleRedirect('educar_infra_predio_comodo_lst.php');
        }

        $obj_ref_cod_infra_comodo_funcao = new InfraComodoFuncao($registro['ref_cod_infra_comodo_funcao']);
        $det_ref_cod_infra_comodo_funcao = $obj_ref_cod_infra_comodo_funcao->detalhe();
        $registro['ref_cod_infra_comodo_funcao'] = $det_ref_cod_infra_comodo_funcao['nm_funcao'];

        $obj_ref_cod_infra_predio = new InfraPredio($registro['ref_cod_infra_predio']);
        $det_ref_cod_infra_predio = $obj_ref_cod_infra_predio->detalhe();
        $registro['ref_cod_infra_predio'] = $det_ref_cod_infra_predio['nm_predio'];

        $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];

        $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
        $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
        $nm_escola = $det_ref_cod_escola['nome'];

        $obj_permissao = new Permissoes();
        $nivel_usuario = $obj_permissao->nivel_acesso($this->pessoa_logada);
        if ($registro['ref_cod_instituicao']) {
            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
        }
        if ($nm_escola) {
            $this->addDetalhe([ 'Escola', "{$nm_escola}"]);
        }
        if ($registro['ref_cod_infra_predio']) {
            $this->addDetalhe([ 'Pr&eacute;dio', "{$registro['ref_cod_infra_predio']}"]);
        }
        if ($registro['nm_comodo']) {
            $this->addDetalhe([ 'Ambiente', "{$registro['nm_comodo']}"]);
        }
        if ($registro['ref_cod_infra_comodo_funcao']) {
            $this->addDetalhe([ 'Tipo de ambiente', "{$registro['ref_cod_infra_comodo_funcao']}"]);
        }
        if ($registro['area']) {
            $this->addDetalhe([ '&Aacute;rea m²', "{$registro['area']}"]);
        }
        if ($registro['desc_comodo']) {
            $this->addDetalhe([ 'Descri&ccedil;&atilde;o do ambiente', "{$registro['desc_comodo']}"]);
        }

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(574, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = 'educar_infra_predio_comodo_cad.php';
            $this->url_editar = "educar_infra_predio_comodo_cad.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}";
        }

        $this->url_cancelar = 'educar_infra_predio_comodo_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do ambiente', [
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
