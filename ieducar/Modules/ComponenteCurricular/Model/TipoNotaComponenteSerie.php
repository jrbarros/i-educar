<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoNotaComponenteSerie
 * @package iEducarLegacy\Modules\ComponenteCurricular\Model
 */
class TipoNotaComponenteSerie extends Enum
{
    public const NUMERICA = 1;
    public const CONCEITUAL = 2;

    protected $_data = [
        self::NUMERICA => 'Nota numérica',
        self::CONCEITUAL => 'Nota conceitual',
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
