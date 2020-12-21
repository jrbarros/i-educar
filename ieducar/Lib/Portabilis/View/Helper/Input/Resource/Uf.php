<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use App\Models\Country;
use App\Models\State;
use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class Uf
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class Uf extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($options['resources'])) {
            $states = State::query()->where('country_id', Country::BRASIL)->get()->values();

            $resources = Utils::setAsIdValue($states->toArray(), 'abbreviation', 'abbreviation');
        }

        return self::insertOption(null, 'Estado', $resources);
    }

    public function uf($options = [])
    {
        $this->select($options);
    }
}
