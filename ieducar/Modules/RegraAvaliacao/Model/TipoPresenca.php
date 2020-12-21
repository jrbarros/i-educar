<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoPresenca
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class TipoPresenca extends Enum
{
    public const GERAL = 1;
    public const POR_COMPONENTE = 2;

    protected $_data = [
        self::GERAL => 'Apura falta no geral (unificada)',
        self::POR_COMPONENTE => 'Apura falta por componente curricular',
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
