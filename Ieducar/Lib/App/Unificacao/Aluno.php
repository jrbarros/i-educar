<?php

namespace iEducarLegacy\Lib\App\Unificacao;

use App\Models\LogUnificationOldData;
use iEducarLegacy\Intranet\Source\Banco;
use iEducarLegacy\Lib\CoreExt\CoreExtensionException;
use Illuminate\Support\Facades\DB;

/**
 * Class Aluno
 * @package iEducarLegacy\Lib\App\Unificacao
 */
class Aluno
{
    public static function unifica($codAlunoPrincipal, $codAlunos, $codPessoa, Banco $db, $unificationId)
    {
        self::validaParametros($codAlunoPrincipal, $codAlunos, $codPessoa);

        $codAlunosString = implode(',', $codAlunos);

        self::logData($codAlunos, $unificationId);

        foreach ($codAlunos as $codAluno) {
            DB::statement(
                "
                UPDATE pmieducar.historico_escolar
                SET
                    ref_cod_aluno = {$codAlunoPrincipal},
                    sequencial = he.seq+he.max_seq
                FROM
                (
                    SELECT
                        ref_cod_aluno AS aluno,
                        sequencial AS seq,
                        COALESCE
                        (
                            (
                                SELECT max(sequencial)
                                FROM pmieducar.historico_escolar
                                WHERE ref_cod_aluno = {$codAlunoPrincipal}
                            ),
                            0
                        ) AS max_seq
                    FROM pmieducar.historico_escolar
                    WHERE ref_cod_aluno = {$codAluno}
                ) AS he
                WHERE sequencial = he.seq
                AND ref_cod_aluno = he.aluno
            "
            );
        }

        DB::statement("UPDATE pmieducar.Matricula SET ref_cod_aluno = {$codAlunoPrincipal} where ref_cod_aluno in ({$codAlunosString})");
        DB::statement("UPDATE pmieducar.aluno SET ativo = 0, data_exclusao = now(), ref_usuario_exc = {$codPessoa} where cod_aluno in ({$codAlunosString})");

        return true;
    }

    private static function validaParametros($codAlunoPrincipal, $codAlunos, $codPessoa)
    {
        if (!is_numeric($codAlunoPrincipal)) {
            throw new CoreExtensionException('Parâmetro $codAlunoPrincipal deve ser um inteiro');
        }

        if (!is_array($codAlunos) || !count($codAlunos)) {
            throw new CoreExtensionException('Parâmetro $codAlunos deve ser um array de códigos de alunos');
        }

        if (!is_numeric($codPessoa)) {
            throw new CoreExtensionException('Parâmetro $codPessoa deve ser um inteiro');
        }
    }

    /**
     * @param $codAlunos
     * @param $unificationId
     */
    private static function logData($codAlunos, $unificationId)
    {
        self::logHistoricos($codAlunos, $unificationId);
        self::logMatriculas($codAlunos, $unificationId);
        self::logAlunos($codAlunos, $unificationId);
    }

    /**
     * @param $duplicatesId
     * @param $unificationId
     */
    private static function logHistoricos($duplicatesId, $unificationId)
    {
        $historicos = DB::table('pmieducar.historico_escolar')->whereIn('ref_cod_aluno', $duplicatesId)->get();
        foreach ($historicos as $historico) {
            $logData = new LogUnificationOldData();
            $logData->unification_id = $unificationId;
            $logData->table = 'pmieducar.historico_escolar';
            $logData->keys = json_encode([['id' => $historico->id]]);
            $logData->old_data = json_encode($historico);
            $logData->save();
        }
    }

    /**
     * @param $duplicatesId
     * @param $unificationId
     */
    private static function logMatriculas($duplicatesId, $unificationId)
    {
        $matriculas = DB::table('pmieducar.Matricula')->whereIn('ref_cod_aluno', $duplicatesId)->get();
        foreach ($matriculas as $matricula) {
            $logData = new LogUnificationOldData();
            $logData->unification_id = $unificationId;
            $logData->table = 'pmieducar.Matricula';
            $logData->keys = json_encode([['cod_matricula' => $matricula->cod_matricula]]);
            $logData->old_data = json_encode($matricula);
            $logData->save();
        }
    }

    /**
     * @param $duplicatesId
     * @param $unificationId
     */
    private static function logAlunos($duplicatesId, $unificationId)
    {
        $alunos = DB::table('pmieducar.aluno')->whereIn('cod_aluno', $duplicatesId)->get();
        foreach ($alunos as $aluno) {
            $logData = new LogUnificationOldData();
            $logData->unification_id = $unificationId;
            $logData->table = 'pmieducar.aluno';
            $logData->keys = json_encode([['cod_aluno' => $aluno->cod_aluno]]);
            $logData->old_data = json_encode($aluno);
            $logData->save();
        }
    }
}
