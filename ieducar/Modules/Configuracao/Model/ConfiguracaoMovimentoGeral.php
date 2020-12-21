<?php

namespace iEducarLegacy\Modules\Configuracao\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class ConfiguracaoMovimentoGeral
 * @package iEducarLegacy\Modules\Configuracao\Model
 */
class ConfiguracaoMovimentoGeral extends Entity
{
    protected $_data = [
        'serie'     => null,
        'coluna'    => null
    ];

    public function getDefaultValidatorCollection()
    {
        return [];
    }
}
