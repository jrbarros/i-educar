<?php

namespace iEducarLegacy\Modules\Docente\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class LicenciaturaDataMapper
 * @package iEducarLegacy\Modules\Docente\Model
 */
class LicenciaturaDataMapper extends DataMapper
{
    protected $_entityClass = 'Licenciatura';
    protected $_tableName   = 'docente_licenciatura';
    protected $_tableSchema = 'Modules';
    protected $_attributeMap = [
        'id'           => 'id',
        'servidor'     => 'servidor_id',
        'licenciatura' => 'licenciatura',
        'curso'        => 'curso_id',
        'anoConclusao' => 'ano_conclusao',
        'ies'          => 'ies_id',
        'user'         => 'user_id',
        'created_at'   => 'created_at',
        'updated_at'   => 'updated_at'
    ];
}
