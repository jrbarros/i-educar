<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\PmiEducar\Religiao as ReligiaoDbHelper;
use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

class Religiao extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($options['resources'])) {
            $resources = new ReligiaoDbHelper();
            $resources = $resources->lista(null, null, null, null, null, null, null, null, 1);
            $resources = Utils::setAsIdValue($resources, 'cod_religiao', 'nm_religiao');
        }

        return self::insertOption(null, Text::toLatin1('Religião'), $resources);
    }

    public function religiao($options = [])
    {
        parent::select($options);
    }
}
