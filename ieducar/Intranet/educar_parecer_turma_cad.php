<?php

require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');
require_once('Source/pmieducar/geral.inc.php');
require_once 'lib/Portabilis/Utils/Database.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Parecer da turma");
        $this->processoAp = '586';
    }
}

class indice extends clsCadastro
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

    public $cod_turma;
    public $parecer_1_etapa;
    public $parecer_2_etapa;
    public $parecer_3_etapa;
    public $parecer_4_etapa;
    public $etapas;
    public $ano;

    public function Inicializar()
    {
        $this->cod_turma=$_GET['cod_turma'];

        $obj      = new Turma($this->cod_turma);
        $registro = $obj->detalhe();

        foreach ($registro as $campo => $val) {
            $this->$campo = $val;
        }

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(586, $this->pessoa_logada, 7, 'educar_turma_lst.php');

        $this->url_cancelar = "educar_turma_det.php?cod_turma={$this->cod_turma}";

        $this->breadcrumb('Pareceres da turma', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);

        $this->nome_url_cancelar = 'Cancelar';

        $cursoId = $this->ref_cod_curso;

        $sql             = 'select padrao_ano_escolar from pmieducar.curso where cod_curso = $1 and ativo = 1';
        $padraoAnoLetivo = Database::fetchPreparedQuery($sql, ['params' => $cursoId,
                                                                                 'return_only' => 'first-field']);

        if ($padraoAnoLetivo == 1) {
            $escolaId = $this->ref_ref_cod_escola;
            $ano      = $this->ano;

            $sql = 'select padrao.sequencial as etapa, modulo.nm_tipo as nome from pmieducar.ano_letivo_modulo
              as padrao, pmieducar.modulo where padrao.ref_ano = $1 and padrao.ref_ref_cod_escola = $2
              and padrao.ref_cod_modulo = modulo.cod_modulo and modulo.ativo = 1 order by padrao.sequencial';

            $this->etapas = Database::fetchPreparedQuery($sql, [ 'params' => [$ano, $escolaId]]);
        } else {
            $sql = 'select turma.sequencial as etapa, modulo.nm_tipo as nome from pmieducar.turma_modulo as turma,
              pmieducar.modulo where turma.ref_cod_turma = $1 and turma.ref_cod_modulo = modulo.cod_modulo
              and modulo.ativo = 1 order by turma.sequencial';

            $this->etapas = Database::fetchPreparedQuery($sql, [ 'params' => $this->cod_turma]);
        }

        return 'Editar';
    }

    public function Gerar()
    {
        // primary keys
        $this->campoOculto('cod_turma', $this->cod_turma);
        $this->campoOculto('ano', $this->ano);

        $obj_aluno = new Aluno();
        // text

        $cont = 0;

        // if(!count($etapas) > 0){

        //   foreach ($this->etapas as $key => $etapa) {
        //     $cont++;
        //     if($cont > 4)
        //       break;

        //     $this->campoMemo( "parecer_{$cont}_etapa", "Parecer {$cont}º ".strtolower($etapa['nome']), $this->{'parecer_'.$cont.'_etapa'}, 60, 5, false );
        //   }
        // }else{
        $this->campoMemo('parecer_1_etapa', 'Relatório global da turma - 1° Semestre', $this->parecer_1_etapa, 60, 5, false);
        $this->campoMemo('parecer_2_etapa', 'Relatório global de educação física - 1° Semestre', $this->parecer_2_etapa, 60, 5, false);
        $this->campoMemo('parecer_3_etapa', 'Relatório global da turma - 2° Semestre', $this->parecer_3_etapa, 60, 5, false);
        $this->campoMemo('parecer_4_etapa', 'Relatório global de educação física - 2° Semestre', $this->parecer_4_etapa, 60, 5, false);
        // }
    }

    public function Editar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(586, $this->pessoa_logada, 7, 'educar_turma_lst.php');

        $obj = new Turma($this->cod_turma);
        $obj->ref_usuario_exc = $this->pessoa_logada;
        $obj->parecer_1_etapa = $this->parecer_1_etapa;
        $obj->parecer_2_etapa = $this->parecer_2_etapa;
        $obj->parecer_3_etapa = $this->parecer_3_etapa;
        $obj->parecer_4_etapa = $this->parecer_4_etapa;
        $obj->ano = $this->ano;

        if ($obj->edita()) {
            $this->simpleRedirect("educar_turma_det.php?cod_turma={$this->cod_turma}");
        }
        $this->mensagem = 'Erro ao salvar lançamentos.<br>';

        return false;
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
