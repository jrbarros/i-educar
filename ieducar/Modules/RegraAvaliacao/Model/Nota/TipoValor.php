<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model\Nota;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoValor
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model\Nota
 */
class TipoValor extends Enum
{
    public const NENHUM = 0;
    public const NUMERICA = 1;
    public const CONCEITUAL = 2;
    public const NUMERICACONCEITUAL = 3;

    protected $_data = [
        self::NENHUM => 'Não usar nota',
        self::NUMERICA => 'Nota numérica',
        self::CONCEITUAL => 'Nota conceitual',
        self::NUMERICACONCEITUAL => 'Nota conceitual e numérica'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
