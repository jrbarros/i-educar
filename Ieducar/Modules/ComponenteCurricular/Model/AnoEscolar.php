<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class AnoEscolar
 * @package iEducarLegacy\Modules\ComponenteCurricular\Model
 */
class AnoEscolar extends Entity
{
    protected $_data = [
        'componenteCurricular' => null,
        'anoEscolar' => null,
        'cargaHoraria' => null,
        'anosLetivos' => null
    ];

    protected $_dataTypes = [
        'cargaHoraria' => 'numeric'
    ];

    protected $_references = [
        'componenteCurricular' => [
            'value' => null,
            'class' => 'ComponenteDataMapper',
            'file' => 'ComponenteCurricular/Model/ComponenteDataMapper.php'
        ]
    ];

    /**
     * Construtor. Remove o campo identidade já que usa uma chave composta.
     *
     * @see Entity::__construct($options = array())
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        unset($this->_data['id']);
    }

    /**
     * @see Entity::getDataMapper()
     */
    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            require_once 'ComponenteCurricular/Model/AnoEscolarDataMapper.php';
            $this->setDataMapper(new AnoEscolarDataMapper());
        }

        return parent::getDataMapper();
    }

    /**
     * @see Validatable::getDefaultValidatorCollection()
     */
    public function getDefaultValidatorCollection()
    {
        $validators = [];

        if (isset($this->anoEscolar)) {
            $validators['cargaHoraria'] = new _Validate_Numeric(['min' => 1]);
        }

        return $validators;
    }
}
