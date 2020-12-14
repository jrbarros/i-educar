<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\CoreSelect;

/**
 * Class AreaConhecimento
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class AreaConhecimento extends CoreSelect
{
    /**
     * @return string
     */
    protected function inputName()
    {
        return 'area_conhecimento_id';
    }

    /**
     * @param $options
     * @return array
     */
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        return self::insertOption(null, 'Todas', $resources);
    }

    /**
     * @param array $options
     */
    public function areaConhecimento($options = [])
    {
        $this->select($options);
    }
}
