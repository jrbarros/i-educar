<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class TabelaDataMapper
 * @package iEducarLegacy\Modules\TabelaArredondamento\Model
 */
class TabelaDataMapper extends DataMapper
{
    protected $_entityClass = 'Tabela';
    protected $_tableName = 'tabela_arredondamento';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'instituicao' => 'instituicao_id',
        'nome' => 'nome',
        'tipoNota' => 'tipo_nota',
        'arredondarNota' => 'arredondar_nota',
    ];

    protected $_primaryKey = [
        'id' => 'id',
        'instituicao' => 'instituicao_id'
    ];

    /**
     * @var TabelaValorDataMapper
     */
    protected $_tabelaValorDataMapper = null;

    /**
     * Setter.
     *
     * @param TabelaValorDataMapper $mapper
     *
     * @return CoreExt_DataMapper Provê interface fluída
     */
    public function setTabelaValorDataMapper(TabelaValorDataMapper $mapper)
    {
        $this->_tabelaValorDataMapper = $mapper;

        return $this;
    }

    /**
     * Getter.
     *
     * @return TabelaArredondamento_Model_TabelaValorDataMappers
     */
    public function getTabelaValorDataMapper()
    {
        if (is_null($this->_tabelaValorDataMapper)) {
            require_once 'TabelaArredondamento/Model/TabelaValorDataMapper.php';
            $this->setTabelaValorDataMapper(
                new TabelaValorDataMapper()
            );
        }

        return $this->_tabelaValorDataMapper;
    }

    /**
     * Finder para instâncias de TabelaValor que tenham
     * referências a instância Tabela passada como
     * parâmetro.
     *
     * @param Tabela $instance
     *
     * @return array Um array de instâncias TabelaValor
     */
    public function findTabelaValor(TabelaArredondamento_Model_Tabela $instance)
    {
        $where = ['tabelaArredondamento' => $instance->id];

        return $this->getTabelaValorDataMapper()->findAll([], $where);
    }
}
