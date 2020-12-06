<?php

require_once 'include/Banco.inc.php';
require_once 'include/Geral.inc.php';
require_once 'include/pmieducar/Permissoes.php';
require_once 'include/pmieducar/Aluno.php';
require_once 'include/pmieducar/AlunoBeneficio.php';
require_once 'include/pmieducar/Arredondamento.php';
require_once 'include/pmieducar/Avaliacao.php';
require_once 'include/pmieducar/AvaliacaoDesempenho.php';
require_once 'include/pmieducar/CalendarioAnoLetivo.php';
require_once 'include/pmieducar/CalendarioAtividade.php';
require_once 'include/pmieducar/CalendarioDia.php';
require_once 'include/pmieducar/CalendarioDiaMotivo.php';
require_once 'include/pmieducar/CoffeeBreakTipo.php';
require_once 'include/pmieducar/Curso.php';
require_once 'include/pmieducar/Disciplina.php';
require_once 'include/pmieducar/DisciplinaDisciplinaTopico.php';
require_once 'include/pmieducar/DisciplinaSerie.php';
require_once 'include/pmieducar/DisciplinaTopico.php';
require_once 'include/pmieducar/DispensaDisciplina.php';
require_once 'include/pmieducar/DisciplinaDependencia.php';
require_once 'include/pmieducar/Documentos.php';
require_once 'include/pmieducar/Escola.php';
require_once 'include/pmieducar/EscolaCurso.php';
require_once 'include/pmieducar/EscolaDiasLetivos.php';
require_once 'include/pmieducar/EscolaLocalizacao.php';
require_once 'include/pmieducar/EscolaRedeEnsino.php';
require_once 'include/pmieducar/EscolaSerie.php';
require_once 'include/pmieducar/EscolaSerieDisciplina.php';
require_once 'include/pmieducar/FaltaAluno.php';
require_once 'include/pmieducar/FaltaAtraso.php';
require_once 'include/pmieducar/FaltaAtrasoCompensado.php';
require_once 'include/pmieducar/Funcao.php';
require_once 'include/pmieducar/Habilitacao.php';
require_once 'include/pmieducar/HabilitacaoCurso.php';
require_once 'include/pmieducar/HistoricoDisciplinas.php';
require_once 'include/pmieducar/HistoricoEscolar.php';
require_once 'include/pmieducar/InfraComodoFuncao.php';
require_once 'include/pmieducar/InfraPredio.php';
require_once 'include/pmieducar/InfraPredioComodo.php';
require_once 'include/pmieducar/Instituicao.php';
require_once 'include/pmieducar/Matricula.php';
require_once 'include/pmieducar/MatriculaExcessao.php';
require_once 'include/pmieducar/MatriculaOcorrenciaDisciplinar.php';
require_once 'include/pmieducar/MatriculaTurma.php';
require_once 'include/pmieducar/MotivoAfastamento.php';
require_once 'include/pmieducar/NivelEnsino.php';
require_once 'include/pmieducar/NotaAluno.php';
require_once 'include/pmieducar/Operador.php';
require_once 'include/pmieducar/PessoaEduc.php';
require_once 'include/pmieducar/PreRequisito.php';
require_once 'include/pmieducar/QuadroHorario.php';
require_once 'include/pmieducar/QuadroHorarioHorarios.php';
require_once 'include/pmieducar/Religiao.php';
require_once 'include/pmieducar/ReservaVaga.php';
require_once 'include/pmieducar/SequenciaCurso.php';
require_once 'include/pmieducar/SequenciaSerie.php';
require_once 'include/pmieducar/Serie.php';
require_once 'include/pmieducar/SerieDiaSemana.php';
require_once 'include/pmieducar/SeriePeriodoData.php';
require_once 'include/pmieducar/SeriePreRequisito.php';
require_once 'include/pmieducar/Servidor.php';
require_once 'include/pmieducar/ServidorAfastamento.php';
require_once 'include/pmieducar/ServidorAlocacao.php';
require_once 'include/pmieducar/ServidorCurso.php';
require_once 'include/pmieducar/ServidorFormacao.php';
require_once 'include/pmieducar/ServidorTituloConcurso.php';
require_once 'include/pmieducar/TipoAvaliacao.php';
require_once 'include/pmieducar/TipoAvaliacaoValores.php';
require_once 'include/pmieducar/TipoDispensa.inc.php';
require_once 'include/pmieducar/TipoEnsino.php';
require_once 'include/pmieducar/TipoOcorrenciaDisciplinar.php';
require_once 'include/pmieducar/TipoRegime.php';
require_once 'include/pmieducar/TipoUsuario.php';
require_once 'include/pmieducar/TransferenciaSolicitacao.php';
require_once 'include/pmieducar/TransferenciaTipo.php';
require_once 'include/pmieducar/Turma.php';
require_once 'include/pmieducar/TurmaTipo.php';
require_once 'include/pmieducar/Usuario.php';
require_once 'include/pmieducar/PessoaEducDeficiencia.php';
require_once 'include/pmieducar/Telefone.php';
require_once 'include/pmieducar/EscolaAnoLetivo.php';
require_once 'include/pmieducar/Modulo.php';
require_once 'include/pmieducar/AnoLetivoModulo.php';
require_once 'include/pmieducar/CalendarioAnotacao.php';
require_once 'include/pmieducar/CalendarioDiaAnotacao.php';
require_once 'include/pmieducar/TurmaModulo.php';
require_once 'include/pmieducar/DispensaDisciplinaEtapa.php';
require_once 'include/pmieducar/Faltas.php';
require_once 'include/pmieducar/QuadroHorarioHorariosAux.php';
require_once 'include/pmieducar/ServidorFuncao.php';
require_once 'include/pmieducar/ServidorDisciplina.php';
require_once 'include/pmieducar/CategoriaNivel.php';
require_once 'include/pmieducar/Nivel.php';
require_once 'include/pmieducar/Subnivel.php';
require_once 'include/pmieducar/ServidorCursoMinistra.php';
require_once 'include/pmieducar/clsPmieducarAbandonoTipo.inc.php';
require_once 'include/pmieducar/DistribuicaoUniforme.php';
require_once 'include/pmieducar/CandidatoReservaVaga.php';
require_once 'include/pmieducar/SerieVaga.php';
require_once 'include/pmieducar/BloqueioLancamentoFaltasNotas.php';
require_once 'include/pmieducar/ConfiguracoesGerais.php';
require_once 'include/pmieducar/CandidatoFilaUnica.php';
require_once 'include/pmieducar/EscolaCandidatoFilaUnica.php';
require_once 'include/pmieducar/ResponsaveisAluno.php';
require_once 'include/pmieducar/Biblioteca.php';
require_once 'include/pmieducar/AcervoIdioma.php';
require_once 'include/pmieducar/AcervoColecao.php';
require_once 'include/pmieducar/AcervoAssunto.php';
require_once 'include/pmieducar/AcervoAutor.inc.php';
require_once 'include/pmieducar/ClienteTipo.php';
require_once 'include/pmieducar/AcervoEditora.php';
require_once 'include/pmieducar/ExemplarTipo.php';
require_once 'include/pmieducar/Acervo.php';
require_once 'include/pmieducar/MotivoBaixa.php';
require_once 'include/pmieducar/Situacao.php';
require_once 'include/pmieducar/Cliente.php';
require_once 'include/pmieducar/Exemplar.php';
require_once 'include/pmieducar/MotivoSuspensao.php';
require_once 'include/pmieducar/Fonte.php';
require_once 'include/pmieducar/Reservas.php';
require_once 'include/pmieducar/ExemplarEmprestimo.php';
require_once 'include/pmieducar/ClienteTipoExemplarTipo.php';
require_once 'include/pmieducar/ClienteTipoCliente.php';
require_once 'include/pmieducar/PagamentoMulta.php';
require_once 'include/pmieducar/BibliotecaUsuario.php';
require_once 'include/pmieducar/ClienteSuspensao.php';
require_once 'include/pmieducar/AcervoAcervoAutor.php';
require_once 'include/pmieducar/BibliotecaDia.php';
require_once 'include/pmieducar/BibliotecaFeriados.php';
require_once 'include/pmieducar/Projeto.php';
require_once 'include/pmieducar/BloqueioAnoLetivo.php';
require_once 'include/pmieducar/Backup.php';
require_once 'include/pmieducar/AlunoCMF.php';
require_once 'include/Pessoa/clsCadastroRaca.inc.php';
require_once 'include/Pessoa/clsCadastroFisicaRaca.inc.php';
