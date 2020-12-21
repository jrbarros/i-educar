<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class AnoEscolarDataMapper
 * @package iEducarLegacy\Modules\ComponenteCurricular\Model
 */
class AnoEscolarDataMapper extends DataMapper
{
    protected $_entityClass = 'AnoEscolar';

    protected $_tableName = 'componente_curricular_ano_escolar';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'componenteCurricular' => 'componente_curricular_id',
        'anoEscolar' => 'ano_escolar_id',
        'cargaHoraria' => 'carga_horaria',
        'tipo_nota' => 'tipo_nota',
        'anosLetivos' => 'anos_letivos'
    ];

    protected $_primaryKey = [
        'componenteCurricular' => 'componente_curricular_id',
        'anoEscolar' => 'ano_escolar_id',
    ];

    /**
     * @var ComponenteDataMapper
     */
    protected $_componenteDataMapper = null;

    /**
     * Setter.
     *
     * @param ComponenteDataMapper $mapper
     *
     * @return CoreExt_DataMapper Provê interface fluída
     */
    public function setComponenteDataMapper(ComponenteDataMapper $mapper)
    {
        $this->_componenteDataMapper = $mapper;

        return $this;
    }

    /**
     * Getter.
     *
     * @return ComponenteDataMapper
     */
    public function getComponenteDataMapper()
    {
        if (is_null($this->_componenteDataMapper)) {
            require_once 'ComponenteCurricular/Model/ComponenteDataMapper.php';
            $this->_componenteDataMapper = new ComponenteDataMapper();
        }

        return $this->_componenteDataMapper;
    }

    /**
     * Finder para componentes por curso.
     *
     * @param int $cursoId
     *
     * @return array
     *
     * @throws Exception
     */
    public function findComponentePorCurso($cursoId)
    {
        $sql = '
            SELECT
              DISTINCT(mca.%s)
            FROM
              %s mca, pmieducar.serie ps
            WHERE
              mca.%s = ps.cod_serie AND ps.ref_cod_curso = \'%d\'
        ';

        $sql = sprintf(
            $sql,
            $this->_getTableColumn('componenteCurricular'),
            $this->_getTableName(),
            $this->_getTableColumn('anoEscolar'),
            $cursoId
        );

        $this->getDbAdapter()->Consulta($sql);

        $list = [];

        while ($this->_getDbAdapter()->ProximoRegistro()) {
            $row = $this->_getDbAdapter()->Tupla();
            $list[] = $this->getComponenteDataMapper()->find(
                $row[$this->_getTableColumn('componenteCurricular')]
            );
        }

        return $list;
    }

    /**
     * Finder para componentes por série (ano escolar).
     *
     * @param int $serieId
     *
     * @return array
     *
     * @throws Exception
     */
    public function findComponentePorSerie($serieId)
    {
        $componentesAnoEscolar = $this->findAll([], ['anoEscolar' => $serieId]);
        $list = [];

        foreach ($componentesAnoEscolar as $key => $componenteAnoEscolar) {
            $id = $componenteAnoEscolar->get('componenteCurricular');
            $list[$id] = $this->getComponenteDataMapper()->find(
                $componenteAnoEscolar->get('componenteCurricular')
            );
            $list[$id]->cargaHoraria = $componenteAnoEscolar->cargaHoraria;
        }

        ksort($list);

        return $list;
    }
}
