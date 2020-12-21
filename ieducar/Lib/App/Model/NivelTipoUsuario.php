<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

class NivelTipoUsuario extends Enum
{
    public const POLI_INSTITUCIONAL = 1;
    public const INSTITUCIONAL = 2;
    public const ESCOLA = 4;
    public const BIBLIOTECA = 8;

    protected $_data = [
        self::POLI_INSTITUCIONAL => 'Poli-institucional',
        self::INSTITUCIONAL => 'Institucional',
        self::ESCOLA => 'Escola',
        self::BIBLIOTECA => 'Biblioteca'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
