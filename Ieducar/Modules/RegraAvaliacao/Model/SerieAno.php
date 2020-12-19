<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class SerieAno
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class SerieAno extends Entity
{
    protected $_data = [
        'regraAvaliacao' => null,
        'regraAvaliacaoDiferenciada' => null,
        'anoLetivo' => null,
        'serie' => null,
    ];
    public function getDefaultValidatorCollection()
    {
        return [];
    }

    // Override para Entity não forçar a coluna id no attributo $_data
    protected function _createIdentityField()
    {
        return $this;
    }
}
