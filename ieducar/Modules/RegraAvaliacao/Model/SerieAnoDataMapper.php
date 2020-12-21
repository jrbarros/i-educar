<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class SerieAnoDataMapper
 * @package iEducarLegacy\Modules\RegraAvaliacao\Model
 */
class SerieAnoDataMapper extends DataMapper
{
    protected $_entityClass = 'SerieAno';
    protected $_tableName = 'regra_avaliacao_serie_ano';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'regraAvaliacao' => 'regra_avaliacao_id',
        'regraAvaliacaoDiferenciada' => 'regra_avaliacao_diferenciada_id',
        'serie' => 'serie_id',
        'anoLetivo' => 'ano_letivo',
    ];

    protected $_primaryKey = [
        'serie' => 'serie_id',
        'anoLetivo' => 'ano_letivo',
    ];
}
