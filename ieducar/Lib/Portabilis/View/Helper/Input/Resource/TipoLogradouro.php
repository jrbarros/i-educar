<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class TipoLogradouro
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class TipoLogradouro extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        return self::insertOption(null, 'Tipo logradouro', $resources);
    }

    public function tipoLogradouro($options = [])
    {
        parent::select($options);
    }
}
