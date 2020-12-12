<?php

require_once 'CoreExt/Entity.php';

class Calendario_Model_Turma extends CoreExt_Entity
{
    protected $_data = [
        'calendarioAnoLetivo' => null,
        'ano' => null,
        'mes' => null,
        'dia' => null,
        'turma' => null
    ];

    public function getDefaultValidatorCollection()
    {
        return [
            'calendarioAnoLetivo' => new _Validate_Numeric(['min' => 0]),
            'ano' => new _Validate_Numeric(['min' => 0]),
            'mes' => new _Validate_Numeric(['min' => 0]),
            'dia' => new _Validate_Numeric(['min' => 0]),
            'turma' => new _Validate_Numeric(['min' => 0])
        ];
    }

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        unset($this->_data['id']);
    }
}
