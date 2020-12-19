<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class RegraRecuperacaoDataMapper
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class RegraRecuperacaoDataMapper extends DataMapper
{
    protected $_entityClass = 'RegraRecuperacao';
    protected $_tableName = 'regra_avaliacao_recuperacao';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'regraAvaliacao' => 'regra_avaliacao_id',
        'etapasRecuperadas' => 'etapas_recuperadas',
        'substituiMenorNota' => 'substitui_menor_nota',
        'notaMaxima' => 'nota_maxima'
    ];
}
