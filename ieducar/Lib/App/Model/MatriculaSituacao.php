<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class MatriculaSituacao
 * @package iEducarLegacy\Lib\App\Model
 */
class MatriculaSituacao extends Enum
{
    public const APROVADO = 1;
    public const REPROVADO = 2;
    public const EM_ANDAMENTO = 3;
    public const TRANSFERIDO = 4;
    public const RECLASSIFICADO = 5;
    public const ABANDONO = 6;
    public const EM_EXAME = 7;
    public const APROVADO_APOS_EXAME = 8;
    public const APROVADO_SEM_EXAME = 10;
    public const PRE_MATRICULA = 11;
    public const APROVADO_COM_DEPENDENCIA = 12;
    public const APROVADO_PELO_CONSELHO = 13;
    public const REPROVADO_POR_FALTAS = 14;
    public const FALECIDO = 15;

    protected $_data = [
        self::APROVADO => 'Aprovado',
        self::REPROVADO => 'Retido',
        self::EM_ANDAMENTO => 'Cursando',
        self::TRANSFERIDO => 'Transferido',
        self::RECLASSIFICADO => 'Reclassificado',
        self::ABANDONO => 'Abandono',
        self::EM_EXAME => 'Em exame',
        self::APROVADO_APOS_EXAME => 'Aprovado após exame',
        self::PRE_MATRICULA => 'Pré-matrícula',
        self::APROVADO_COM_DEPENDENCIA => 'Aprovado com dependência',
        self::APROVADO_PELO_CONSELHO => 'Aprovado pelo conselho',
        self::REPROVADO_POR_FALTAS => 'Reprovado por faltas',
        self::FALECIDO => 'Falecido'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

    public static function getSituacao($id)
    {
        $instance = self::getInstance()->_data;

        return $instance[$id];
    }

    /**
     * Retorna todas as situação da matrícula consideradas "finais".
     *
     * @return array
     */
    public static function getSituacoesFinais()
    {
        return [
            self::APROVADO,
            self::REPROVADO,
            self::APROVADO_APOS_EXAME,
            self::APROVADO_COM_DEPENDENCIA,
            self::APROVADO_PELO_CONSELHO,
            self::REPROVADO_POR_FALTAS,
        ];
    }
}
