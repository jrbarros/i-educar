<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoArredondamentoMedia
 * @package iEducarLegacy\Modules\TabelaArredondamento\Model
 */
class TipoArredondamentoMedia extends Enum
{
    public const NAO_ARREDONDAR = 0;
    public const ARREDONDAR_PARA_NOTA_INFERIOR = 1;
    public const ARREDONDAR_PARA_NOTA_SUPERIOR = 2;
    public const ARREDONDAR_PARA_NOTA_ESPECIFICA = 3;

    protected $_data = [
        self::NAO_ARREDONDAR => 'N&atilde;o utilizar arredondamento para esta casa decimal',
        self::ARREDONDAR_PARA_NOTA_INFERIOR => 'Arredondar para o n&uacute;mero inteiro imediatamente inferior',
        self::ARREDONDAR_PARA_NOTA_SUPERIOR => 'Arredondar para o n&uacute;mero inteiro imediatamente superior',
        self::ARREDONDAR_PARA_NOTA_ESPECIFICA => 'Arredondar para a casa decimal espec&iacute;fica'
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
