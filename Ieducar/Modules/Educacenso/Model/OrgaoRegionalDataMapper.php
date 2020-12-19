<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class OrgaoRegionalDataMapper
 * @package iEducarLegacy\Modules\Educacenso\Model
 */
class OrgaoRegionalDataMapper extends DataMapper
{
    protected $_entityClass = 'OrgaoRegional';
    protected $_tableName = 'educacenso_orgao_regional';
    protected $_tableSchema = 'Modules';

    protected $_primaryKey = [
        'sigla_uf' => 'sigla_uf',
        'codigo' => 'codigo',
    ];

    protected $_attributeMap = [
        'sigla_uf' => 'sigla_uf',
        'codigo' => 'codigo',
    ];
}
