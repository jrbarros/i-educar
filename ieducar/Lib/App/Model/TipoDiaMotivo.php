<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

class TipoDiaMotivo extends Enum
{
    public const DIA_LETIVO = 'l';
    public const DIA_NAO_LETIVO = 'n';
    public const DIA_EXTRA_LETIVO = 'e';

    protected $_data = [
        self::DIA_LETIVO => 'Dia letivo',
        self::DIA_NAO_LETIVO => 'Dia não letivo',
        self::DIA_EXTRA_LETIVO => 'Dia extra letivo',
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
