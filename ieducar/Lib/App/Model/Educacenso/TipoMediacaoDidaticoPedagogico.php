<?php

namespace iEducarLegacy\Lib\App\Model\Educacenso;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoMediacaoDidaticoPedagogico
 * @package iEducarLegacy\Lib\App\Model\Educacenso
 */
class TipoMediacaoDidaticoPedagogico extends Enum
{
    public const PRESENCIAL = 1;
    public const SEMIPRESENCIAL = 2;
    public const EDUCACAO_A_DISTANCIA = 3;

    protected $_data = [
        self::PRESENCIAL => 'Presencial',
        self::SEMIPRESENCIAL => 'Semipresencial',
        self::EDUCACAO_A_DISTANCIA => 'Educação a distância',
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
