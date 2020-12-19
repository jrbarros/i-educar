<?php

namespace iEducarLegacy\Modules\Calendario\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class TurmaDataMapper
 * @package iEducarLegacy\Modules\Calendario\Model
 */
class TurmaDataMapper extends DataMapper
{
    protected $_entityClass = 'Turma';

    protected $_tableName = 'calendario_turma';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'calendarioAnoLetivo' => 'calendario_ano_letivo_id',
        'ano' => 'ano',
        'mes' => 'mes',
        'dia' => 'dia',
        'turma' => 'turma_id'
    ];

    protected $_primaryKey = [
        'calendarioAnoLetivo' => 'calendario_ano_letivo_id',
        'ano' => 'ano',
        'mes' => 'mes',
        'dia' => 'dia',
        'turma' => 'turma_id'
    ];
}
