<?php

namespace iEducarLegacy\Lib\App\Model;

/**
 * Class Matricula
 * @package iEducarLegacy\Lib\App\Model
 */
class Matricula
{
    /**
     * Atualiza os dados da matrícula do aluno, promovendo-o ou retendo-o. Usa
     * uma instância da classe legada Matricula para tal.
     *
     * @param int  $matricula
     * @param int  $usuario
     * @param bool $aprovado
     *
     * @return bool
     */
    public static function atualizaMatricula($matricula, $usuario, $aprovado = true)
    {
        $instance = CoreExt_Entity::addClassToStorage(
            'Matricula',
            null,
            'Source/pmieducar/Matricula.php'
        );

        $instance->cod_matricula = $matricula;
        $instance->ref_usuario_cad = $usuario;
        $instance->ref_usuario_exc = $usuario;

        if (is_int($aprovado)) {
            $instance->aprovado = $aprovado;
        } else {
            $instance->aprovado = $aprovado == true
                ? MatriculaSituacao::APROVADO
                : MatriculaSituacao::REPROVADO;
        }

        return $instance->edita();
    }

    public static function setNovaSituacao($matricula, $novaSituacao)
    {
        $instance = CoreExt_Entity::addClassToStorage(
            'Matricula',
            null,
            'Source/pmieducar/Matricula.php'
        );

        $instance->cod_matricula = $matricula;
        $instance->aprovado = $novaSituacao;

        return $instance->edita();
    }
}
