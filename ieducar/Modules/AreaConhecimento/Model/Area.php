<?php

namespace iEducarLegacy\Modules\AreaConhecimento\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class Area
 */
class Area extends Entity
{
    protected $_data = [
        'instituicao' => null,
        'nome' => null,
        'secao' => null,
        'ordenamento_ac' => null,
        'agrupar_descritores' => null,
    ];

    public function getDefaultValidatorCollection()
    {
        $instituicoes = array_keys(Finder::getInstituicoes());

        return [
            'instituicao' => new _Validate_Choice(['choices' => $instituicoes]),
            'nome' => new _Validate_String(['min' => 5, 'max' => 60]),
            'secao' => new _Validate_String(['min' => 0, 'max' => 50]),
            'ordenamento_ac' => new _Validate_Choice(['min' => 0, 'max' => 50])
        ];
    }

    public function __toString()
    {
        return $this->nome;
    }
}
