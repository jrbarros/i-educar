<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

class SimNao extends Enum
{
    public const NAO = 0;
    public const SIM = 1;

    protected $_data = [
        self::NAO => 'Não',
        self::SIM => 'Sim'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
