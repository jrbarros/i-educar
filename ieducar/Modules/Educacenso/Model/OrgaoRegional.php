<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class OrgaoRegional
 * @package iEducarLegacy\Modules\Educacenso\Model
 */
class OrgaoRegional extends Entity
{
    protected $_data = [
        'sigla_uf' => null,
        'codigo' => null,
    ];

    protected $_dataTypes = [
        'sigla_uf' => 'string',
        'codigo' => 'string',
    ];

    public function getDefaultValidatorCollection()
    {
        return [];
    }

    public function __toString()
    {
        return $this->codigo;
    }
}
