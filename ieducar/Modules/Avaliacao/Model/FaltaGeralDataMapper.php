<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

/**
 * Class FaltaGeralDataMapper
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class FaltaGeralDataMapper extends FaltaAbstractDataMapper
{
    protected $_entityClass = 'FaltaGeral';
    protected $_tableName = 'falta_geral';

    protected $_attributeMap = [
        'faltaAluno' => 'falta_aluno_id',
        'quantidade' => 'quantidade',
        'etapa' => 'etapa'
    ];

    protected $_primaryKey = [
        'id' => 'id',
    ];
}
