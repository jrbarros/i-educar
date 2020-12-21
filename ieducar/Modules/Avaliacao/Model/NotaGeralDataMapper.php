<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class NotaGeralDataMapper
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class NotaGeralDataMapper extends DataMapper
{
    protected $_entityClass = 'NotaGeral';
    protected $_tableName = 'nota_geral';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'notaAluno' => 'nota_aluno_id',
        'nota' => 'nota',
        'notaArredondada' => 'nota_arredondada',
        'etapa' => 'etapa'
    ];

    protected $_primaryKey = [
        'id' => 'id',
    ];
}
