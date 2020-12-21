<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * Class TabelaValor
 * @package iEducarLegacy\Modules\TabelaArredondamento\Model
 */
class TabelaValor extends Entity
{
    protected $_data = [
        'tabelaArredondamento' => null,
        'nome' => null,
        'descricao' => null,
        'observacao' => null,
        'valorMinimo' => null,
        'valorMaximo' => null,
        'acao' => null,
        'casaDecimalExata' => null
    ];

    protected $_dataTypes = [
        'valorMinimo' => 'numeric',
        'valorMaximo' => 'numeric'
    ];

    protected $_references = [
        'tabelaArredondamento' => [
            'value' => null,
            'class' => 'TabelaDataMapper',
            'file' => 'TabelaArredondamento/Model/TabelaDataMapper.php'
        ],
        'acao' => [
            'value' => 0,
            'class' => 'TipoArredondamentoMedia',
            'file' => 'TabelaArredondamento/Model/TipoArredondamentoMedia.php',
            'null' => true
        ]
    ];

    /**
     * @see Entity::getDataMapper()
     */
    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            require_once 'TabelaArredondamento/Model/TabelaValorDataMapper.php';
            $this->setDataMapper(
                new TabelaValorDataMapper()
            );
        }

        return parent::getDataMapper();
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
        $validators = [];

        return $validators;
    }

    public function __toString()
    {
        return $this->nome;
    }
}
