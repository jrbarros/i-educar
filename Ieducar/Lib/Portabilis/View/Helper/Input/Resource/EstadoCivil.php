<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

require_once 'lib/Portabilis/View/Helper/Input/CoreSelect.php';

/**
 * Class EstadoCivil
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class EstadoCivil extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($resources)) {
            $resources = new \iEducarLegacy\Intranet\Source\Pessoa\EstadoCivil();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'ideciv', 'descricao');
        }

        return self::insertOption(null, 'Estado civil', $resources);
    }

    public function estadoCivil($options = [])
    {
        $this->select($options);
    }
}
