    <?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Faltas/Notas Aluno");
        $this->processoAp = '642';
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

    public $cod_nota_aluno;
    public $ref_sequencial;
    public $ref_ref_cod_tipo_avaliacao;
    public $ref_cod_serie;
    public $ref_cod_escola;
    public $ref_cod_disciplina;
    public $ref_cod_matricula;
    public $ref_sequencial_matricula_turma;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public $ref_cod_turma;
    public $ref_cod_curso;

    public function Gerar()
    {
        $this->titulo = 'Faltas/Notas Aluno - Detalhe';

        $this->ref_cod_matricula=$_GET['ref_cod_matricula'];
        $this->ref_cod_turma=$_GET['ref_cod_turma'];
        $this->ref_sequencial_matricula_turma=$_GET['sequencial'];

        $obj_matricula_turma = new MatriculaTurma();
        $lst_matricula_turma = $obj_matricula_turma->lista($this->ref_cod_matricula, $this->ref_cod_turma, null, null, null, null, null, null, 1, null, null, null, null, null, null, null, null, $this->ref_sequencial_matricula_turma);
        if (is_array($lst_matricula_turma)) {
            $registro = array_shift($lst_matricula_turma);
        }
        if (! $registro) {
            $this->simpleRedirect('educar_falta_nota_aluno_lst.php');
        }

        $obj_ref_ref_cod_turma = new Turma($registro['ref_cod_turma']);
        $det_ref_ref_cod_turma = $obj_ref_ref_cod_turma->detalhe();
        $nm_turma = $det_ref_ref_cod_turma['nm_turma'];

        $obj_ref_cod_serie = new Serie($registro['ref_ref_cod_serie']);
        $det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
        $nm_serie = $det_ref_cod_serie['nm_serie'];

        $obj_ref_cod_curso = new Curso($registro['ref_cod_curso']);
        $det_ref_cod_curso = $obj_ref_cod_curso->detalhe();
        $nm_curso = $det_ref_cod_curso['nm_curso'];
        $padrao_ano_escolar = $det_ref_cod_curso['padrao_ano_escolar'];
        $falta_ch_globalizada = $det_ref_cod_curso['falta_ch_globalizada'];
        if ($padrao_ano_escolar) {
            $obj_ano_letivo = new EscolaAnoLetivo();
            $lst_ano_letivo = $obj_ano_letivo->lista($registro['ref_ref_cod_escola'], null, null, null, 1, null, null, null, null, 1);
            if (is_array($lst_ano_letivo)) {
                $det_ano_letivo = array_shift($lst_ano_letivo);
                $ano_letivo = $det_ano_letivo['ano'];

                $obj_ano_letivo_modulo = new clsPmieducarAnoLetivoModulo();
                $lst_ano_letivo_modulo = $obj_ano_letivo_modulo->lista($ano_letivo, $registro['ref_ref_cod_escola']);
                if (is_array($lst_ano_letivo_modulo)) {
                    $qtd_modulos = count($lst_ano_letivo_modulo);
                }
            }
        } else {
            $obj_turma_modulo = new TurmaModulo();
            $lst_turma_modulo = $obj_turma_modulo->lista($registro['ref_cod_turma']);
            if (is_array($lst_turma_modulo)) {
                $qtd_modulos = count($lst_turma_modulo);
            }
        }

        $obj_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $obj_cod_instituicao_det = $obj_cod_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $obj_cod_instituicao_det['nm_instituicao'];

        $obj_ref_cod_escola = new Escola($registro['ref_ref_cod_escola']);
        $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
        $nm_escola = $det_ref_cod_escola['nome'];

        $obj_aluno = new Aluno();
        $lst_aluno = $obj_aluno->lista($registro['ref_cod_aluno'], null, null, null, null, null, null, null, null, null, 1);
        if (is_array($lst_aluno)) {
            $det_aluno = array_shift($lst_aluno);
            $registro['ref_cod_aluno'] = $det_aluno['nome_aluno'];
        }

        if ($registro['ref_cod_aluno']) {
            $this->addDetalhe([ 'Aluno', "{$registro['ref_cod_aluno']}"]);
        }
        if ($registro['ref_cod_matricula']) {
            $this->addDetalhe([ 'Matr&iacute;cula', "{$registro['ref_cod_matricula']}"]);
        }

        $obj_permissoes = new Permissoes();
        $nivel_usuario = $obj_permissoes->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            if ($registro['ref_cod_instituicao']) {
                $this->addDetalhe([ 'Institui&ccedil;&atilde;o', "{$registro['ref_cod_instituicao']}"]);
            }
        }
        if ($nivel_usuario == 1 || $nivel_usuario == 2) {
            if ($nm_escola) {
                $this->addDetalhe([ 'Escola', "{$nm_escola}"]);
            }
        }
        if ($nm_curso) {
            $this->addDetalhe([ 'Curso', "{$nm_curso}"]);
        }
        if ($nm_serie) {
            $this->addDetalhe([ 'S&eacute;rie', "{$nm_serie}"]);
        }
        if ($nm_turma) {
            $this->addDetalhe([ 'Turma', "{$nm_turma}"]);
        }
        if ($qtd_modulos) {
            $this->addDetalhe([ 'Quantidade de M&oacute;dulos', "{$qtd_modulos}"]);
        }

        $obj_matricula = new Matricula($registro['ref_cod_matricula']);
        $det_matricula = $obj_matricula->detalhe();
        $modulo = $det_matricula['modulo'];
        $aprovado = $det_matricula['aprovado'];
        $ano_matricula = $det_matricula['ano'];

        $max_qtd_nota = 0;
        $min_qtd_nota = 10;
        //************************************* DETALHE - MATRICULADO NUMA SERIE *************************************//
        if ($registro['ref_ref_cod_serie']) {
            $obj_dispensa = new DispensaDisciplina();
            $lst_dispensa = $obj_dispensa->lista($registro['ref_cod_matricula'], $registro['ref_ref_cod_serie'], $registro['ref_ref_cod_escola'], null, null, null, null, null, null, null, null, 1);
            if (is_array($lst_dispensa)) {
                foreach ($lst_dispensa as $key => $disciplina) {
                    $dispensa[$disciplina['ref_cod_disciplina']] = $disciplina['ref_cod_disciplina'];
                }
            }

            $obj_esd = new EscolaSerieDisciplina();
            $obj_esd->setOrderby('nm_disciplina');
            $lst_disciplinas = $obj_esd->lista($registro['ref_ref_cod_serie'], $registro['ref_ref_cod_escola'], null, 1, true);
            if ($lst_disciplinas) {
                foreach ($lst_disciplinas as $disciplinas) {
                    $obj_nota_aluno = new NotaAluno();
                    $qtd_notas = $obj_nota_aluno->getQtdNotas(null, null, $disciplinas['ref_cod_disciplina'], $this->ref_cod_matricula);

                    if ($max_qtd_nota < $qtd_notas) {
                        $max_qtd_nota = $qtd_notas;
                    }

                    if ($min_qtd_nota > $qtd_notas) {
                        $min_qtd_nota = $qtd_notas;
                    }
                }
            }

            if (is_array($lst_disciplinas)) {
                $tabela = '<table>
                               <tr align=\'center\'>
                                   <td rowspan=\'2\' bgcolor=\'#ccdce6\'><b>Nome</b></td>';

                for ($i = 1; $i <= $max_qtd_nota; $i++) {
                    if ($qtd_modulos < $i) {
                        $tabela .= '<td colspan=\'2\' bgcolor=\'#ccdce6\'><b>Exame</b></td>';
                    } else {
                        $tabela .= "<td colspan='2' bgcolor='#ccdce6'><b>M&oacute;dulo {$i}</b></td>";
                    }
                }
                $tabela .= '</tr>';

                $tabela .= '<tr align=center>';
                for ($i = 1; $i <= $max_qtd_nota; $i++) {
                    if ($qtd_modulos < $i) {
                        $tabela .= '<td colspan=\'2\' bgcolor=\'#ccdce6\'><b>Nota</b></td>';
                    } else {
                        $tabela .= '<td bgcolor=\'#ccdce6\'><b>Nota</b></td><td bgcolor=\'#ccdce6\'><b>Faltas</b></td>';
                    }
                }
                $tabela .= '</tr>';

                $cont = 0;
                $qtd_disciplinas = count($lst_disciplinas);
                $prim_disciplina = false;
                foreach ($lst_disciplinas as $valor) {
//                  echo "<pre>"; print_r($lst_disciplinas); die();
                    $parar=false;
                    if (!strcmp($valor['nm_disciplina'], 'Matemática')) {
                        $parar = true;
                    }
                    if (!$dispensa[$valor['ref_cod_disciplina']]) {
                        if (($cont % 2) == 0) {
                            $color = ' bgcolor=\'#f5f9fd\' ';
                        } else {
                            $color = ' bgcolor=\'#FFFFFF\' ';
                        }

                        unset($notas_aluno);
                        $obj_nota_aluno = new NotaAluno();
                        $obj_nota_aluno->setOrderby('modulo ASC');
                        $lst_nota_aluno = $obj_nota_aluno->lista(null, null, null, $registro['ref_ref_cod_serie'], $registro['ref_ref_cod_escola'], $valor['ref_cod_disciplina'], $registro['ref_cod_matricula'], null, null, null, null, null, null, 1);

                        if (is_array($lst_nota_aluno)) {
                            foreach ($lst_nota_aluno as $key => $nota_aluno) {
                                if ($nota_aluno['nota']) {
                                    $notas_aluno[] = $nota_aluno['nota'];
                                } else {
                                    $obj_avaliacao_valores = new TipoAvaliacaoValores($nota_aluno['ref_ref_cod_tipo_avaliacao'], $nota_aluno['ref_sequencial']);
                                    $det_avaliacao_valores = $obj_avaliacao_valores->detalhe();
                                    $notas_aluno[] = $det_avaliacao_valores['nome'];
                                }
                            }
                        }
                        unset($faltas_aluno);
                        if ($falta_ch_globalizada) {
                            $obj_faltas = new Faltas();
                            $obj_faltas->setOrderby('sequencial asc');
                            $lst_faltas = $obj_faltas->lista($registro['ref_cod_matricula']);
                            if (is_array($lst_faltas)) {
                                foreach ($lst_faltas as $key => $faltas) {
                                    $faltas_aluno[] = $faltas['falta'];
                                }
                            }
                        } else {
                            $obj_falta_aluno = new FaltaAluno();
                            $obj_falta_aluno->setOrderby('cod_falta_aluno ASC');
                            $lst_falta_aluno = $obj_falta_aluno->lista(null, null, null, $registro['ref_ref_cod_serie'], $registro['ref_ref_cod_escola'], $valor['ref_cod_disciplina'], $registro['ref_cod_matricula'], null, null, null, null, null, 1);
                            if (is_array($lst_falta_aluno)) {
                                foreach ($lst_falta_aluno as $key => $falta_aluno) {
                                    $faltas_aluno[] = $falta_aluno['faltas'];
                                }
                            }
                        }

                        $obj_disciplina = new Disciplina($valor['ref_cod_disciplina']);
                        $det_disciplina = $obj_disciplina->detalhe();
                        $nm_disciplina = $det_disciplina['nm_disciplina'];
                        $apura_falta = $det_disciplina['apura_falta'];

                        $tabela .= "<tr align='left'>
                                        <td {$color} align='left'>{$nm_disciplina}</td>";

                        for ($i = 0; $i < $max_qtd_nota; $i++) {
                            if (($qtd_modulos - 1) < $i) {
                                if ($notas_aluno[$i]) {
                                    $notas_aluno[$i] = number_format($notas_aluno[$i], 2, ',', '.');
                                    $tabela .= "<td align='center' colspan='2' {$color} align='left'>{$notas_aluno[$i]}</td>";
                                } else {
                                    $tabela .= "<td align='center' colspan='2' {$color} align='left'>-</td>";
                                }
                            } else {
                                if ($notas_aluno[$i]) {
                                    $tabela .= "<td align='center' {$color} align='left'>{$notas_aluno[$i]}</td>";
                                } else {
                                    $tabela .= "<td align='center' {$color} align='left'>-</td>";
                                }

                                if ($falta_ch_globalizada && !$prim_disciplina) {
                                    $tabela .= "<td align='center' rowspan='{$qtd_disciplinas}' {$color} align='left'>{$faltas_aluno[$i]}</td>";
                                } elseif (!$falta_ch_globalizada) {
                                    if (is_numeric($faltas_aluno[$i])) {
                                        $tabela .= "<td align='center' {$color} align='left'>{$faltas_aluno[$i]}</td>";
                                    } else {
                                        $tabela .= "<td align='center' {$color} align='left'>-</td>";
                                    }
                                }
                            }
                        }
                        $prim_disciplina = true;
                        $tabela .= '</tr>';

                        $cont++;
                    }
                }

                if (!$ano_letivo || ($ano_letivo == $ano_matricula)) {
                    if ($max_qtd_nota > 0) {
                        $tabela .= '<tr align=\'center\'>
                                        <td align=\'center\'></td>';

                        for ($i = 1; $i <= $max_qtd_nota; $i++) {
                            //if ( ($max_qtd_nota != $min_qtd_nota) && ($min_qtd_nota < $i) && ($qtd_modulos >= $modulo) && false)
                            if (!$det_ref_cod_curso['edicao_final']) {
                                $tabela .= '<td colspan=\'2\'></td>';
                            } else {
                                $tabela .= "<td align='center' colspan='2' bgcolor='#ccdce6' align='center'><a href='educar_falta_nota_aluno_cad.php?ref_cod_matricula={$registro['ref_cod_matricula']}&ref_cod_turma={$registro['ref_cod_turma']}&ref_sequencial_matricula_turma={$registro['sequencial']}&modulo={$i}'>Editar</a></td>";
                            }
                        }
                        $tabela .= '</tr>';
                    }
                }
                $tabela .= '</table>';
            }
        }
        //************************************* DETALHE - MATRICULADO NUM CURSO *************************************//
        else {
            $obj_disciplinas = new Disciplina();
            $obj_disciplina->setOrderby('nm_disciplina');
            $lst_disciplinas = $obj_disciplinas->lista(null, null, null, null, null, null, null, null, null, null, null, null, 1, null, $registro['ref_cod_curso']);
            foreach ($lst_disciplinas as $disciplinas) {
                $obj_nota_aluno = new NotaAluno();
                $qtd_notas = $obj_nota_aluno->getQtdNotas(null, null, null, $this->ref_cod_matricula, $disciplinas['cod_disciplina']);

                if ($max_qtd_nota < $qtd_notas) {
                    $max_qtd_nota = $qtd_notas;
                }
            }

            if (is_array($lst_disciplinas)) {
                $tabela = '<table>
                               <tr align=\'center\'>
                                   <td rowspan=\'2\' bgcolor=\'#ccdce6\'><b>Nome</b></td>';

                for ($i = 1; $i <= $max_qtd_nota; $i++) {
                    if ($qtd_modulos < $i) {
                        $tabela .= '<td colspan=\'2\' bgcolor=\'#ccdce6\'><b>Exame</b></td>';
                    } else {
                        $tabela .= "<td colspan='2' bgcolor='#ccdce6'><b>M&oacute;dulo {$i}</b></td>";
                    }
                }
                $tabela .= '</tr>';

                $tabela .= '<tr align=center>';
                for ($i = 1; $i <= $max_qtd_nota; $i++) {
                    if ($qtd_modulos < $i) {
                        $tabela .= '<td colspan=\'2\' bgcolor=\'#ccdce6\'><b>Nota</b></td>';
                    } else {
                        $tabela .= '<td bgcolor=\'#ccdce6\'><b>Nota</b></td><td bgcolor=\'#ccdce6\'><b>Faltas</b></td>';
                    }
                }
                $tabela .= '</tr>';

                $cont = 0;
                $qtd_disciplinas = count($lst_disciplinas);
                $prim_disciplina = false;
                foreach ($lst_disciplinas as $valor) {
                    if (($cont % 2) == 0) {
                        $color = ' bgcolor=\'#f5f9fd\' ';
                    } else {
                        $color = ' bgcolor=\'#FFFFFF\' ';
                    }

                    unset($notas_aluno);
                    $obj_nota_aluno = new NotaAluno();
                    $obj_nota_aluno->setOrderby('modulo ASC');
                    $lst_nota_aluno = $obj_nota_aluno->lista(null, null, null, null, null, null, $registro['ref_cod_matricula'], null, null, null, null, null, null, 1, null, $valor['cod_disciplina']);

                    if (is_array($lst_nota_aluno)) {
                        foreach ($lst_nota_aluno as $key => $nota_aluno) {
                            if ($nota_aluno['nota']) {
                                $notas_aluno[] = $nota_aluno['nota'];
                            } else {
                                $obj_avaliacao_valores = new TipoAvaliacaoValores($nota_aluno['ref_ref_cod_tipo_avaliacao'], $nota_aluno['ref_sequencial']);
                                $det_avaliacao_valores = $obj_avaliacao_valores->detalhe();
                                $notas_aluno[] = $det_avaliacao_valores['nome'];
                            }
                        }
                    }

                    unset($faltas_aluno);
                    if ($falta_ch_globalizada) {
                        $obj_faltas = new Faltas();
                        $lst_faltas = $obj_faltas->lista($registro['ref_cod_matricula']);
                        if (is_array($lst_faltas)) {
                            foreach ($lst_faltas as $key => $faltas) {
                                $faltas_aluno[] = $faltas['falta'];
                            }
                        }
                    } else {
                        $obj_falta_aluno = new FaltaAluno();
                        $obj_falta_aluno->setOrderby('cod_falta_aluno ASC');
                        $lst_falta_aluno = $obj_falta_aluno->lista(null, null, null, null, null, null, $registro['ref_cod_matricula'], null, null, null, null, null, 1, null, $valor['cod_disciplina']);
                        if (is_array($lst_falta_aluno)) {
                            foreach ($lst_falta_aluno as $key => $falta_aluno) {
                                $faltas_aluno[] = $falta_aluno['faltas'];
                            }
                        }
                    }

                    $nm_disciplina = $valor['nm_disciplina'];
                    $apura_falta = $valor['apura_falta'];

                    $tabela .= "<tr align='center'>
                                    <td {$color} align='center'>{$nm_disciplina}</td>";

                    for ($i = 0; $i < $max_qtd_nota; $i++) {
                        if (($qtd_modulos - 1) < $i) {
                            if ($notas_aluno[$i]) {
                                $notas_aluno[$i] = number_format($notas_aluno[$i], 2, ',', '.');
                                $tabela .= "<td align='center' colspan='2' {$color} align='left'>{$notas_aluno[$i]}</td>";
                            } else {
                                $tabela .= "<td align='center' colspan='2' {$color} align='left'>-</td>";
                            }
                        } else {
                            if ($notas_aluno[$i]) {
                                $tabela .= "<td align='center' {$color} align='left'>{$notas_aluno[$i]}</td>";
                            } else {
                                $tabela .= "<td align='center' {$color} align='left'>-</td>";
                            }

                            if ($falta_ch_globalizada && !$prim_disciplina) {
                                $tabela .= "<td align='center' rowspan='{$qtd_disciplinas}' {$color} align='left'>{$faltas_aluno[$i]}</td>";
                            } elseif (!$falta_ch_globalizada) {
                                if (is_numeric($faltas_aluno[$i])) {
                                    $tabela .= "<td align='center' {$color} align='left'>{$faltas_aluno[$i]}</td>";
                                } else {
                                    $tabela .= "<td align='center' {$color} align='left'>-</td>";
                                }
                            }
                        }
                    }
                    $prim_disciplina = true;
                    $tabela .= '</tr>';

                    $cont++;
                }

                if ($max_qtd_nota > 0) {
                    $tabela .= '<tr align=\'center\'>
                                    <td align=\'center\'></td>';

                    for ($i = 1; $i <= $max_qtd_nota; $i++) {
                        //if ( ($max_qtd_nota != $min_qtd_nota) && ($min_qtd_nota < $i) && ($qtd_modulos <= $modulo) )
                        if (!$det_ref_cod_curso['edicao_final']) {
                            $tabela .= '<td colspan=\'2\'></td>';
                        } else {
                            $tabela .= "<td align='center' colspan='2' bgcolor='#ccdce6' align='center'><a href='educar_falta_nota_aluno_cad.php?ref_cod_matricula={$registro['ref_cod_matricula']}&ref_cod_turma={$registro['ref_cod_turma']}&ref_sequencial_matricula_turma={$registro['sequencial']}&modulo={$i}'>Editar</a></td>";
                        }
                    }
                    $tabela .= '</tr>';
                }
                $tabela .= '</table>';
            }
        }

        if ($tabela) {
            $this->addDetalhe([ 'Disciplina', "{$tabela}"]);
        }

        if ($aprovado) {
            if ($aprovado == 1) {
                $aprovado_ = 'Aprovado';
            } elseif ($aprovado == 2) {
                $aprovado_ = 'Reprovado';
            } elseif ($aprovado == 3) {
                if (($qtd_modulos < $modulo)) {
                    $aprovado_ = 'Em Exame';
                } else {
                    $aprovado_ = 'Cursando';
                }
            }
            $this->addDetalhe([ 'Situa&ccedil;&atilde;o', "{$aprovado_}"]);
        }

        if ($obj_permissoes->permissao_cadastra(642, $this->pessoa_logada, 7)) {
            if (($qtd_modulos >= $modulo) && ($aprovado == 3)) {
                $this->array_botao = ['Nova Nota/Falta'];
                $this->array_botao_url = ["educar_falta_nota_aluno_cad.php?ref_cod_matricula={$registro['ref_cod_matricula']}&ref_cod_turma={$registro['ref_cod_turma']}&ref_sequencial_matricula_turma={$registro['sequencial']}"];
            } elseif (($qtd_modulos < $modulo) && ($aprovado == 3)) {
                $this->array_botao = ['Nota Exame'];
                if ($qtd_modulos < $max_qtd_nota) {
                    $this->array_botao_url = ["educar_falta_nota_aluno_cad.php?ref_cod_matricula={$registro['ref_cod_matricula']}&ref_cod_turma={$registro['ref_cod_turma']}&ref_sequencial_matricula_turma={$registro['sequencial']}&modulo={$max_qtd_nota}"];
                } else {
                    $this->array_botao_url = ["educar_falta_nota_aluno_cad.php?ref_cod_matricula={$registro['ref_cod_matricula']}&ref_cod_turma={$registro['ref_cod_turma']}&ref_sequencial_matricula_turma={$registro['sequencial']}"];
                }
            }
        }

        $this->url_cancelar = 'educar_falta_nota_aluno_lst.php';
        $this->largura = '100%';
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
