<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Model;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Numeric;

/**
 * Class Turma
 */
class Turma extends Entity
{
    protected $_data = [
        'componenteCurricular' => null,
        'anoEscolar' => null,
        'escola' => null,
        'turma' => null,
        'cargaHoraria' => null,
        'docenteVinculado' => null,
        'etapasEspecificas' => null,
        'etapasUtilizadas' => null
    ];

    protected $_dataTypes = [
        'cargaHoraria' => 'numeric',
        'docenteVinculado' => 'numeric'
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
     * @see Validatable::getDefaultValidatorCollection()
     */
    public function getDefaultValidatorCollection()
    {
        return [
            'cargaHoraria' => new Numeric(['required' => false]),
            'docenteVinculado' => new Numeric(['required' => false])
        ];
    }
}
