<?php

namespace iEducarLegacy\Modules\AreaConhecimento\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class AreaDataMapper
 * @package iEducarLegacy\Modules\AreaConhecimento\Model
 */
class AreaDataMapper extends DataMapper
{
    protected $_entityClass = 'Area';

    protected $_tableName   = 'area_conhecimento';

    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'id' => 'id',
        'instituicao' => 'instituicao_id',
        'nome' => 'nome',
        'secao' => 'secao',
        'ordenamento_ac' => 'ordenamento_ac',
        'agrupar_descritores' => 'agrupar_descritores',
    ];

    protected $_primaryKey = [
        'id' => 'id',
        'instituicao' => 'instituicao_id'
    ];
}
