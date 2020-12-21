<?php

namespace iEducarLegacy\Modules\Transporte\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class Responsavel
 * @package iEducarLegacy\Modules\Transporte\Model
 */
class Responsavel extends Enum
{
    public const NENHUM = 0;
    public const ESTADUAL = 1;
    public const MUNICIPAL = 2;

    protected $_data = [
        self::NENHUM    => 'Não utiliza',
        self::ESTADUAL  => 'Estadual',
        self::MUNICIPAL => 'Municipal'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
