<?php

namespace iEducarLegacy\Modules\Calendario\Model;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Numeric;

/**
 * Class Turma
 * @package iEducarLegacy\Modules\Calendario\Model
 */
class Turma extends Entity
{
    /**
     * @var null[]
     */
    protected $_data = [
        'calendarioAnoLetivo' => null,
        'ano' => null,
        'mes' => null,
        'dia' => null,
        'turma' => null
    ];

    /**
     * @return array
     */
    public function getDefaultValidatorCollection()
    {
        return [
            'calendarioAnoLetivo' => new Numeric(['min' => 0]),
            'ano' => new Numeric(['min' => 0]),
            'mes' => new Numeric(['min' => 0]),
            'dia' => new Numeric(['min' => 0]),
            'turma' => new Numeric(['min' => 0])
        ];
    }

    /**
     * Turma constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        unset($this->_data['id']);
    }
}
