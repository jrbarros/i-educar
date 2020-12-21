<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoProgressao
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class TipoProgressao extends Enum
{
    public const CONTINUADA = 1;
    public const NAO_CONTINUADA_MEDIA_PRESENCA = 2;
    public const NAO_CONTINUADA_SOMENTE_MEDIA = 3;
    public const NAO_CONTINUADA_MANUAL = 4;
    public const NAO_CONTINUADA_MANUAL_CICLO = 5;

    protected $_data = [
        self::CONTINUADA => 'Continuada',
        self::NAO_CONTINUADA_MEDIA_PRESENCA => 'Não-continuada: média e presença',
        self::NAO_CONTINUADA_SOMENTE_MEDIA => 'Não-continuada: somente média',
        self::NAO_CONTINUADA_MANUAL => 'Não-continuada manual',
        self::NAO_CONTINUADA_MANUAL_CICLO => 'Não-continuada manual (ciclo)'
    ];

    public static function getInstance(): \iEducarLegacy\Lib\CoreExt\Singleton
    {
        return self::_getInstance(__CLASS__);
    }
}
