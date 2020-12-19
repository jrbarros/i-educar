<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class Componente
 * @package iEducarLegacy\Modules\ComponenteCurricular\Model
 */
class Componente extends Entity
{
    protected $_data = [
        'instituicao' => null,
        'nome' => null,
        'abreviatura' => null,
        'tipo_base' => null,
        'area_conhecimento' => null,
        'cargaHoraria' => null,
        'codigo_educacenso' => null,
        'ordenamento' => 99999
    ];

    protected $_references = [
        'area_conhecimento' => [
            'value' => null,
            'class' => 'AreaConhecimento_Model_AreaDataMapper',
            'file' => 'AreaConhecimento/Model/AreaDataMapper.php'
        ],
        'tipo_base' => [
            'value' => null,
            'class' => 'TipoBase',
            'file' => 'ComponenteCurricular/Model/TipoBase.php'
        ],
        'codigo_educacenso' => [
            'value' => null,
            'class' => 'CodigoEducacenso',
            'file' => 'ComponenteCurricular/Model/CodigoEducacenso.php'
        ]
    ];

    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            require_once 'ComponenteCurricular/Model/ComponenteDataMapper.php';
            $this->setDataMapper(new ComponenteDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getDefaultValidatorCollection()
    {
        $instituicoes = array_keys(App_Model_IedFinder::getInstituicoes());

        $tipoBase = TipoBase::getInstance();
        $tipos = $tipoBase->getKeys();

        $codigoEducacenso = CodigoEducacenso::getInstance();
        $codigos = $codigoEducacenso->getKeys();

        $areas = $this->getDataMapper()->findAreaConhecimento();
        $areas = CoreExt_Entity::entityFilterAttr($areas, 'id');

        return [
            'instituicao' => new _Validate_Choice(['choices' => $instituicoes]),
            'nome' => new _Validate_String(['min' => 5, 'max' => 200]),
            'abreviatura' => new _Validate_String(['min' => 2, 'max' => 15]),
            'tipo_base' => new _Validate_Choice(['choices' => $tipos]),
            'area_conhecimento' => new _Validate_Choice(['choices' => $areas]),
            'codigo_educacenso' => new _Validate_Choice(['choices' => $codigos]),
        ];
    }

    /**
     * @see Entity::__toString()
     */
    public function __toString()
    {
        return $this->nome;
    }
}
