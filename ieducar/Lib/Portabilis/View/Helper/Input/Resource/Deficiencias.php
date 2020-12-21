<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearchAjax;

/**
 * Class Deficiencias
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class Deficiencias extends MultipleSearchAjax
{
    public function multipleSearchDeficiencias($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'deficiencias',
            'apiController' => 'Deficiencia',
            'apiResource' => 'deficiencia-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->multipleSearchAjax($options['objectName'], $attrName, $options);
    }
}
