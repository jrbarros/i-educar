<?php
// error_reporting(E_ERROR);
// ini_set("display_errors", 1);

require_once 'Source/Base.php';
require_once 'Source/Cadastro.inc.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'lib/Portabilis/Date/AppDateUtils.php';
require_once 'Modules/Avaliacao/Model/NotaAlunoDataMapper.php';
require_once 'Modules/Avaliacao/Model/NotaComponenteMediaDataMapper.php';
require_once 'lib/App/Model/MatriculaSituacao.php';

use App\Process;

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Reclassificar Matr&iacute;cula");
        $this->processoAp = Process::RECLASSIFY_REGISTRATION;
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

    public $cod_matricula;
    public $ref_cod_reserva_vaga;
    public $ref_ref_cod_escola;
    public $ref_ref_cod_serie;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_aluno;
    public $aprovado;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ano;
    public $data_cancel;

    public $ref_cod_instituicao;
    public $ref_cod_curso;
    public $ref_cod_escola;

    public $ref_ref_cod_serie_antiga;

    public $descricao_reclassificacao;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_matricula=$_GET['ref_cod_matricula'];
        $this->ref_cod_aluno=$_GET['ref_cod_aluno'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(Process::RECLASSIFY_REGISTRATION, $this->pessoa_logada, 7, "educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");

        $obj_matricula = new Matricula($this->cod_matricula);
        $det_matricula = $obj_matricula->detalhe();

        if (!$det_matricula || $det_matricula['aprovado'] != 3) {
            $this->simpleRedirect("educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");
        }

        foreach ($det_matricula as $key => $value) {
            $this->$key = $value;
        }

        //$this->url_cancelar = "educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}";
        $this->url_cancelar = "educar_matricula_det.php?cod_matricula={$this->cod_matricula}";

        $this->breadcrumb('Registro da reclassificação da matrícula', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);

        $this->nome_url_cancelar = 'Cancelar';

        return $retorno;
    }

    public function Gerar()
    {
        if ($this->ref_cod_escola) {
            $this->ref_ref_cod_escola = $this->ref_cod_escola;
        }

        // primary keys
        $this->campoOculto('cod_matricula', $this->cod_matricula);
        $this->campoOculto('ref_cod_aluno', $this->ref_cod_aluno);
        $this->campoOculto('ref_cod_escola', $this->ref_ref_cod_escola);

        $obj_aluno = new Aluno();
        $lst_aluno = $obj_aluno->lista($this->ref_cod_aluno, null, null, null, null, null, null, null, null, null, 1);
        if (is_array($lst_aluno)) {
            $det_aluno = array_shift($lst_aluno);
            $this->nm_aluno = $det_aluno['nome_aluno'];
            $this->campoRotulo('nm_aluno', 'Aluno', $this->nm_aluno);
        }

        $cursos = [];

        $escolaAluno = $this->ref_ref_cod_escola;

        $objEscolaCurso = new EscolaCurso();

        $listaEscolaCurso = $objEscolaCurso->lista($escolaAluno);

        if ($listaEscolaCurso) {
            foreach ($listaEscolaCurso as $escolaCurso) {
                $objCurso = new Curso($escolaCurso['ref_cod_curso']);
                $detCurso = $objCurso->detalhe();
                $nomeCurso = $detCurso['nm_curso'];
                $cursos[$escolaCurso['ref_cod_curso']] = $nomeCurso;
            }
        }

        $this->campoOculto('serie_matricula', $this->ref_ref_cod_serie);
        $this->campoLista('ref_cod_curso', 'Curso', $cursos, $this->ref_cod_curso, 'getSerie();');
        $this->campoLista('ref_ref_cod_serie', 'S&eacute;rie', ['' => 'Selecione uma série'], '');
        $this->inputsHelper()->date('data_cancel', ['label' => 'Data da reclassifica&ccedil;&atilde;o', 'placeholder' => 'dd/mm/yyyy', 'value' => date('d/m/Y')]);
        $this->campoMemo('descricao_reclassificacao', 'Descri&ccedil;&atilde;o', $this->descricao_reclassificacao, 100, 10, true);

        $this->acao_enviar = 'if(confirm("Deseja reclassificar está matrícula?"))acao();';
    }

    public function Novo()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(Process::RECLASSIFY_REGISTRATION, $this->pessoa_logada, 7, "educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");

        $this->data_cancel = Utils::brToPgSQL($this->data_cancel);

        if ($this->ref_ref_cod_serie == $this->ref_ref_cod_serie_antiga) {
            $this->simpleRedirect("educar_matricula_det.php?cod_matricula={$this->cod_matricula}");
        }

        $obj_matricula = new Matricula($this->cod_matricula);
        $det_matricula = $obj_matricula->detalhe();

        if (is_null($det_matricula['data_matricula'])) {
            if (substr($det_matricula['data_cadastro'], 0, 10) > $this->data_cancel) {
                $this->mensagem = 'Data de abandono não pode ser inferior a data da matrícula.<br>';

                return false;
                die();
            }
        } else {
            if (substr($det_matricula['data_matricula'], 0, 10) > $this->data_cancel) {
                $this->mensagem = 'Data de abandono não pode ser inferior a data da matrícula.<br>';

                return false;
                die();
            }
        }

        if (!$det_matricula || $det_matricula['aprovado'] != 3) {
            $this->simpleRedirect("educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");
        }

        $obj_matricula = new Matricula($this->cod_matricula, null, null, null, $this->pessoa_logada, null, null, 5, null, null, 1, null, 0, null, null, $this->descricao_reclassificacao);
        $obj_matricula->data_cancel = $this->data_cancel;
        if (!$obj_matricula->edita()) {
            echo "<script>alert('Erro ao reclassificar matrícula'); window.location='educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}';</script>";
            die('Erro ao reclassificar matrícula');
        }
        $obj_serie = new Serie($this->ref_ref_cod_serie);
        $det_serie = $obj_serie->detalhe();

        $obj_matricula = new Matricula(null, null, $this->ref_cod_escola, $this->ref_ref_cod_serie, null, $this->pessoa_logada, $this->ref_cod_aluno, 3, null, null, 1, $det_matricula['ano'], 1, null, null, null, 1, $det_serie['ref_cod_curso']);
        $obj_matricula->data_matricula = $this->data_cancel;
        $cadastrou = $obj_matricula->cadastra();

        if (!$cadastrou) {
            echo "<script>alert('Erro ao reclassificar matrícula'); window.location='educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}';</script>";
            die('Erro ao reclassificar matrícula');
        } else {
            /**
             * desativa todas as enturmacoes da Matricula anterior
             */
            $obj_matricula_turma = new MatriculaTurma($this->cod_matricula);
            if (!$obj_matricula_turma->reclassificacao($this->data_cancel)) {
                echo "<script>alert('Erro ao desativar enturmações da matrícula: {$this->cod_matricula}\nContate o administrador do sistema informando a matrícula!');</script>";
            }

            $notaAluno = (new Avaliacao_Model_NotaAlunoDataMapper())
                ->findAll(['id'], ['matricula_id' => $this->cod_matricula])[0];

            if (!is_null($notaAluno)) {
                $notaAlunoId = $notaAluno->get('id');
                (new Avaliacao_Model_NotaComponenteMediaDataMapper())
                    ->updateSituation($notaAlunoId, App_Model_MatriculaSituacao::RECLASSIFICADO);
            }

            //window.location='educar_matricula_det.php?cod_matricula={$this->cod_matricula}&ref_cod_aluno={$this->ref_cod_aluno}';
            echo "<script>alert('Reclassificação realizada com sucesso!\\nO Código da nova matrícula é: $cadastrou.');
            window.location='educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}';
            </script>";
            die('Reclassificação realizada com sucesso!');
        }
    }

    public function Excluir()
    {
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
?>
<script>
/*
document.getElementById('ref_cod_escola').onchange = function()
{
    getEscolaCurso();
}

document.getElementById('ref_cod_curso').onchange = function()
{
    getEscolaCursoSerie();
}*/
function getSerie()
{
    var campoCurso = document.getElementById('ref_cod_curso').value;
    var campoSerie = document.getElementById('serie_matricula').value;
    var xml1 = new ajax(getSerie_XML);
    strURL = "educar_sequencia_serie_curso_xml.php?cur="+campoCurso+"&ser_dif="+campoSerie;
    xml1.envia(strURL);

}

function getSerie_XML(xml)
{
    //var campoCurso = document.getElementById('ref_cod_curso').value;
    var campoSerie = document.getElementById('ref_ref_cod_serie');
    //var campoSerieMatricula = document.getElementById('serie_matricula').value;

    var seq_serie = xml.getElementsByTagName( "serie" );
    campoSerie.length = 1;

    for( var ct = 0;ct < seq_serie.length;ct++ )
    {
    //  if( curso == sequencia_serie[ct][0] && sequencia_serie[ct][1] != campoSerieMatricula)
        //{
        campoSerie[campoSerie.length] = new Option(seq_serie[ct].firstChild.nodeValue,seq_serie[ct].getAttribute("cod_serie"),false,false);
    //  }
    }
}
getSerie();
</script>
