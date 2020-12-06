<?php

require_once('include/Base.php');
require_once('include/clsDetalhe.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Sequ&ecirc;ncia Enturma&ccedil;&atilde;o");
        $this->processoAp = '587';
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

    public $ref_serie_origem;
    public $ref_serie_destino;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public function Gerar()
    {
        $this->titulo = 'Sequ&ecirc;ncia Enturma&ccedil;&atilde;o - Detalhe';

        $this->ref_serie_origem = $_GET['ref_serie_origem'];
        $this->ref_serie_destino = $_GET['ref_serie_destino'];

        $tmp_obj = new SequenciaSerie($this->ref_serie_origem, $this->ref_serie_destino);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_sequencia_serie_lst.php');
        }

        $obj_ref_serie_origem = new Serie($registro['ref_serie_origem']);
        $det_ref_serie_origem = $obj_ref_serie_origem->detalhe();
        $nm_serie_origem = $det_ref_serie_origem['nm_serie'];
        $registro['ref_curso_origem'] = $det_ref_serie_origem['ref_cod_curso'];
        $obj_ref_curso_origem = new Curso($registro['ref_curso_origem']);
        $det_ref_curso_origem = $obj_ref_curso_origem->detalhe();
        $nm_curso_origem = $det_ref_curso_origem['nm_curso'];
        $registro['ref_cod_instituicao'] = $det_ref_curso_origem['ref_cod_instituicao'];

        $obj_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $det_instituicao = $obj_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $det_instituicao['nm_instituicao'];

        $obj_ref_serie_destino = new Serie($registro['ref_serie_destino']);
        $det_ref_serie_destino = $obj_ref_serie_destino->detalhe();
        $nm_serie_destino = $det_ref_serie_destino['nm_serie'];
        $registro['ref_curso_destino'] = $det_ref_serie_destino['ref_cod_curso'];

        $obj_ref_curso_destino = new Curso($registro['ref_curso_destino']);
        $det_ref_curso_destino = $obj_ref_curso_destino->detalhe();
        $nm_curso_destino = $det_ref_curso_destino['nm_curso'];

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            if ($registro['ref_cod_instituicao']) {
                $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
            }
        }
        if ($nm_curso_origem) {
            $this->addDetalhe([ 'Curso Origem', "{$nm_curso_origem}"]);
        }
        if ($nm_serie_origem) {
            $this->addDetalhe([ 'S&eacute;rie Origem', "{$nm_serie_origem}"]);
        }
        if ($nm_curso_destino) {
            $this->addDetalhe([ 'Curso Destino', "{$nm_curso_destino}"]);
        }
        if ($nm_serie_destino) {
            $this->addDetalhe([ 'S&eacute;rie Destino', "{$nm_serie_destino}"]);
        }

        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(587, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_sequencia_serie_cad.php';
            $this->url_editar = "educar_sequencia_serie_cad.php?ref_serie_origem={$registro['ref_serie_origem']}&ref_serie_destino={$registro['ref_serie_destino']}";
        }

        $this->url_cancelar = 'educar_sequencia_serie_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da sequência de enturmação', [
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
