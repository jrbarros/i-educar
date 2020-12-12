<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Collection/AppDateUtils.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';
require_once 'lib/Portabilis/Date/AppDateUtils.php';
require_once 'Source/funcoes.php';

class ReservavagaController extends ApiCoreController
{
    protected function permiteMultiplasReservas()
    {
        $sql = 'SELECT instituicao.multiplas_reserva_vaga
            FROM pmieducar.instituicao
            LIMIT 1 ';

        return dbBool($this->fetchPreparedQuery($sql, [], true, 'first-field'));
    }

    protected function getCandidato()
    {
        $nome = $this->getRequest()->nome;
        $anoLetivo = $this->getRequest()->ano;
        $dataNascimento = $this->getRequest()->dataNascimento;
        $escola = $this->getRequest()->escola;

        $codigo = 0;

        if ($nome && $anoLetivo && $dataNascimento && $escola) {
            $sql = 'SELECT candidato_reserva_vaga.cod_candidato_reserva_vaga AS codigo
                  FROM pmieducar.candidato_reserva_vaga
                 INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = candidato_reserva_vaga.ref_cod_aluno)
                 INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
                 INNER JOIN cadastro.Pessoa ON (Pessoa.idpes = aluno.ref_idpes)
                  LEFT JOIN cadastro.Pessoa pessoa_responsavel ON (pessoa_responsavel.idpes = fisica.idpes_responsavel)
                  LEFT JOIN cadastro.fisica fisica_responsavel ON (fisica_responsavel.idpes = fisica.idpes_responsavel)
                 WHERE fisica.data_nasc = $3
                   AND candidato_reserva_vaga.ano_letivo = $2
                   AND candidato_reserva_vaga.ref_cod_escola = $4
                   AND ((candidato_reserva_vaga.situacao = \'A\') or candidato_reserva_vaga.situacao IS NULL)
                   AND trim(Pessoa.slug) = trim($1)';

            $params = [$nome, $anoLetivo, Utils::brToPgSQL($dataNascimento), $escola];

            $candidato = $this->fetchPreparedQuery($sql, $params);

            if (!empty($candidato)) {
                $codigo = $candidato[0]['codigo'];
            }
        } elseif ($nome && $anoLetivo && $dataNascimento) {
            $sql = 'SELECT candidato_reserva_vaga.cod_candidato_reserva_vaga AS codigo
                FROM pmieducar.candidato_reserva_vaga
               INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = candidato_reserva_vaga.ref_cod_aluno)
               INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
               INNER JOIN cadastro.Pessoa ON (Pessoa.idpes = aluno.ref_idpes)
                LEFT JOIN cadastro.Pessoa pessoa_responsavel ON (pessoa_responsavel.idpes = fisica.idpes_responsavel)
                LEFT JOIN cadastro.fisica fisica_responsavel ON (fisica_responsavel.idpes = fisica.idpes_responsavel)
               WHERE fisica.data_nasc = $3
                 AND ((candidato_reserva_vaga.situacao = \'A\') or candidato_reserva_vaga.situacao IS NULL)
                 AND candidato_reserva_vaga.ano_letivo = $2
                 AND trim(Pessoa.slug) = trim($1)';

            $candidato = $this->fetchPreparedQuery($sql, [$nome, $anoLetivo, Utils::brToPgSQL($dataNascimento)]);

            if (!empty($candidato)) {
                $codigo = $candidato[0]['codigo'];
            }
        }

        return ['codigo' => $codigo, 'escola' => $escola];
    }

    protected function getAlunoAndamento()
    {
        $nome = $this->getRequest()->nome;
        $cpfResponsavel = $this->getRequest()->cpf;
        $dataNascimento = $this->getRequest()->dataNascimento;
        $anoReserva = $this->getRequest()->anoReserva;

        if ($nome && $cpfResponsavel && $dataNascimento && $anoReserva) {
            $sql = 'SELECT aluno.cod_aluno AS codigo
                FROM pmieducar.aluno
               INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
               INNER JOIN cadastro.Pessoa ON (Pessoa.idpes = aluno.ref_idpes)
                LEFT JOIN cadastro.fisica responsavel ON (fisica.idpes_responsavel = responsavel.idpes)
                LEFT JOIN pmieducar.Matricula ON (Matricula.ref_cod_aluno = aluno.cod_aluno AND Matricula.ativo = 1)
                WHERE fisica.data_nasc = $3
                  AND responsavel.cpf = $2
                  AND Matricula.aprovado = 3
                  AND Matricula.ano = $4
                  AND trim(Pessoa.slug) = trim($1)';

            $aluno = $this->fetchPreparedQuery($sql, [$nome, idFederal2int($cpfResponsavel), Utils::brToPgSQL($dataNascimento), $anoReserva]);

            if (!empty($aluno)) {
                return ['codigo' => $aluno[0]['codigo']];
            }
        }

        return ['codigo' => 0];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'candidato')) {
            $this->appendResponse($this->getCandidato());
        } elseif ($this->isRequestFor('get', 'aluno-andamento')) {
            $this->appendResponse($this->getAlunoAndamento());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
