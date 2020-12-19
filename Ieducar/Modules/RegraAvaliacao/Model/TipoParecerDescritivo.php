<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Enum;

/**
 * Class TipoParecerDescritivo
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class TipoParecerDescritivo extends Enum
{
    public const NENHUM = 0;
    public const ETAPA_DESCRITOR = 1;
    public const ETAPA_COMPONENTE = 2;
    public const ETAPA_GERAL = 3;
    public const ANUAL_DESCRITOR = 4;
    public const ANUAL_COMPONENTE = 5;
    public const ANUAL_GERAL = 6;

    protected $_data = [
        self::NENHUM => 'Não usar parecer descritivo',
        self::ETAPA_COMPONENTE => 'Um parecer por etapa e por componente curricular',
        self::ETAPA_GERAL => 'Um parecer por etapa, geral',
        self::ANUAL_COMPONENTE => 'Um parecer por ano letivo e por componente curricular',
        self::ANUAL_GERAL => 'Um parecer por ano letivo, geral',
    ];

    public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }
}
