<?php

require_once 'CoreExt/Entity.php';

class Transporte_Model_Aluno extends CoreExt_Entity
{
    protected $_data = [
        'aluno' => null,
        'responsavel' => null,
        'user' => null,
        'created_at' => null,
        'updated_at' => null
    ];

    protected $_references = [
        'responsavel' => [
            'value' => null,
            'class' => 'Transporte_Model_Responsavel',
            'file' => 'Transporte/Model/Responsavel.php'
        ]
    ];

    public function __construct($options = [])
    {
        parent::__construct($options);
        unset($this->_data['id']);
    }

    public function getDefaultValidatorCollection()
    {
        require_once 'Transporte/Model/Responsavel.php';

        $responsavel = Transporte_Model_Responsavel::getInstance();

        return [
            'aluno' => new _Validate_Numeric(),
            'responsavel' => new _Validate_Choice(['choices' => $responsavel->getKeys()]),
            'user' => new _Validate_Numeric()
        ];
    }
}
