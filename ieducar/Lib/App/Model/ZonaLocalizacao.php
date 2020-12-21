<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

class ZonaLocalizacao extends Enum
{
    public const URBANA = 1;
    public const RURAL = 2;

    protected $_data = [
        self::URBANA => 'Urbana',
        self::RURAL => 'Rural'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
