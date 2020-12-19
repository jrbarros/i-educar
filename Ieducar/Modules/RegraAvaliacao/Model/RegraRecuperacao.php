<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class RegraRecuperacao
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class RegraRecuperacao extends Entity
{
    protected $_data = [
        'regraAvaliacao' => null,
        'descricao' => null,
        'etapasRecuperadas' => null,
        'substituiMenorNota' => null,
        'media' => null,
        'notaMaxima' => null
    ];

    protected $_dataTypes = [
        'substituiMenorNota' => 'boolean',
        'media' => 'numeric',
        'nota_maxima' => 'numeric'
    ];

    protected $_references = [
        'regraAvaliacao' => [
            'value' => null,
            'class' => 'RegraDataMapper',
            'file' => 'RegraAvaliacao/Model/RegraDataMapper.php'
        ]
    ];

    /**
     * @see Entity::getDataMapper()
     */
    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            require_once 'RegraAvaliacao/Model/RegraRecuperacaoDataMapper.php';
            $this->setDataMapper(new RegraRecuperacaoDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getEtapas()
    {
        return explode(';', $this->get('etapasRecuperadas'));
    }

    public function getLastEtapa()
    {
        return max($this->getEtapas());
    }

    /**
     * @see Validatable::getDefaultValidatorCollection()
     *
     * @todo Implementar validador que retorne um Text ou Numeric, dependendo
     *   do valor do atributo (assim como validateIfEquals().
     * @todo Implementar validador que aceite um valor de comparação como
     *   alternativa a uma chave de atributo. (COMENTADO ABAIXO)
     */
    public function getDefaultValidatorCollection()
    {
        return [
            'descricao' => new _Validate_String(['min' => 1, 'max' => 25]),
            'etapasRecuperadas' => new _Validate_String(['min' => 1, 'max' => 25]),
            'media' => new _Validate_Numeric(['min' => 0, 'max' => 100]),
            'notaMaxima' => new _Validate_Numeric(['min' => 0, 'max' => 100])
        ];
    }

    public function __toString()
    {
        return $this->descricao;
    }
}
