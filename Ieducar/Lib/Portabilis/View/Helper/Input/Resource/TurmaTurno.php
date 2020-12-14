<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class TurmaTurno
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class TurmaTurno extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($options['resources'])) {
            $sql = 'select id, nome from pmieducar.turma_turno where ativo = 1 order by id DESC';
            $resources = Database::fetchPreparedQuery($sql);
            $resources = Utils::setAsIdValue($resources, 'id', 'nome');
        }

        return self::insertOption(null, 'Selecione', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Turno']];
    }

    public function turmaTurno($options = [])
    {
        $this->select($options);
    }
}
