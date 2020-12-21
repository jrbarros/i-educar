<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Calend&aacute;rio Dia Motivo");
        $this->processoAp = '576';
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

    public $cod_calendario_dia_motivo;
    public $ref_cod_escola;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $sigla;
    public $descricao;
    public $tipo;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Calend&aacute;rio Dia Motivo - Detalhe';

        $this->cod_calendario_dia_motivo=$_GET['cod_calendario_dia_motivo'];

        $tmp_obj = new CalendarioDiaMotivo($this->cod_calendario_dia_motivo);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_calendario_dia_motivo_lst.php');
        }

        $obj_cod_escola = new Escola($registro['ref_cod_escola']);
        $obj_cod_escola_det = $obj_cod_escola->detalhe();
        $registro['ref_cod_escola'] = $obj_cod_escola_det['nome'];

        $cod_instituicao = $obj_cod_escola_det['ref_cod_instituicao'];
        $obj_instituicao = new Instituicao($cod_instituicao);
        $obj_instituicao_det = $obj_instituicao->detalhe();
        $nm_instituicao = $obj_instituicao_det['nm_instituicao'];

        if ($nm_instituicao) {
            $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$nm_instituicao}" ]);
        }
        if ($registro['ref_cod_escola']) {
            $this->addDetalhe([ 'Escola', "{$registro['ref_cod_escola']}"]);
        }
        if ($registro['nm_motivo']) {
            $this->addDetalhe([ 'Motivo', "{$registro['nm_motivo']}"]);
        }
        if ($registro['sigla']) {
            $this->addDetalhe([ 'Sigla', "{$registro['sigla']}"]);
        }
        if ($registro['descricao']) {
            $this->addDetalhe([ 'Descric&atilde;o', "{$registro['descricao']}"]);
        }
        if ($registro['tipo']) {
            if ($registro['tipo'] == 'e') {
                $registro['tipo'] = 'extra';
            } elseif ($registro['tipo'] == 'n') {
                $registro['tipo'] = 'n&atilde;o-letivo';
            }
            $this->addDetalhe([ 'Tipo', "{$registro['tipo']}"]);
        }

        $obj_permissao = new Permissoes();
        if ($obj_permissao->permissao_cadastra(576, $this->pessoa_logada, 7)) {
            $this->url_novo = 'educar_calendario_dia_motivo_cad.php';
            $this->url_editar = "educar_calendario_dia_motivo_cad.php?cod_calendario_dia_motivo={$registro['cod_calendario_dia_motivo']}";
        }
        $this->url_cancelar = 'educar_calendario_dia_motivo_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do motivo de dias do calendário', [
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
