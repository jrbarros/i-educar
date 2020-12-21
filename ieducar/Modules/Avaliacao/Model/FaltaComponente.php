<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

/**
 * Class FaltaComponente
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class FaltaComponente extends FaltaAbstractDataMapper
{
    protected $_entityClass = 'FaltaComponente';
    protected $_tableName = 'falta_componente_curricular';

    protected $_attributeMap = [
        'faltaAluno' => 'falta_aluno_id',
        'componenteCurricular' => 'componente_curricular_id',
        'quantidade' => 'quantidade',
        'etapa' => 'etapa'
    ];

    protected $_primaryKey = [
        'id' => 'id',
    ];
}
