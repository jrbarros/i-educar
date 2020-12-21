<?php

namespace iEducarLegacy\Lib\App\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class ColunaMovimentoGeral
 * @package iEducarLegacy\Lib\App\Model
 */
class ColunaMovimentoGeral extends Enum
{
    public const EDUCACAO_INFANTIL = 0;
    public const PRIMEIRO_ANO = 1;
    public const SEGUNDO_ANO = 2;
    public const TERCEIRO_ANO = 3;
    public const QUARTO_ANO = 4;
    public const QUINTO_ANO = 5;
    public const SEXTO_ANO = 6;
    public const SETIMO_ANO = 7;
    public const OITAVO_ANO = 8;
    public const NONO_ANO = 9;

    protected $_data = [
        self::EDUCACAO_INFANTIL => 'Educação infantil',
        self::PRIMEIRO_ANO => '1° ano',
        self::SEGUNDO_ANO => '2° ano',
        self::TERCEIRO_ANO => '3° ano',
        self::QUARTO_ANO => '4° ano',
        self::QUINTO_ANO => '5° ano',
        self::SEXTO_ANO => '6° ano',
        self::SETIMO_ANO => '7° ano',
        self::OITAVO_ANO => '8° ano',
        self::NONO_ANO => '9° ano'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
