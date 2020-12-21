<?php

namespace iEducarLegacy\Modules\FormulaMedia\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoFormula
 * @package iEducarLegacy\Modules\FormulaMedia\Model
 */
class TipoFormula extends Enum
{
    public const MEDIA_FINAL = 1;
    public const MEDIA_RECUPERACAO = 2;

    protected $_data = [
        self::MEDIA_FINAL => 'Média final',
        self::MEDIA_RECUPERACAO => 'Média para recuperação'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
