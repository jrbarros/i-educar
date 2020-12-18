<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class NotaAlunoDataMapper
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class NotaAlunoDataMapper extends DataMapper
{
    protected $_entityClass = 'NotaAluno';
    protected $_tableName = 'nota_aluno';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'Matricula' => 'matricula_id'
    ];
}
