<?php

require_once 'CoreExt/Entity.php';

class ConfiguracaoMovimentoGeral extends CoreExt_Entity
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
