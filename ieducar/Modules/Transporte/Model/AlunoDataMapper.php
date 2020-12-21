<?php

namespace iEducarLegacy\Modules\Transporte\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class AlunoDataMapper
 * @package iEducarLegacy\Modules\Transporte\Model
 */
class AlunoDataMapper extends DataMapper
{
    protected $_entityClass = 'Aluno';

    protected $_tableName   = 'transporte_aluno';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'aluno' => 'aluno_id',
        'responsavel' => 'responsavel',
        'user' => 'user_id',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at'
    ];

    protected $_primaryKey = [
        'aluno' => 'aluno_id',
    ];
}
