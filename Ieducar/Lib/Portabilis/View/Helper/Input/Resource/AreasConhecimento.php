<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearchAjax;

/**
 * Class MultipleSearchAreasConhecimento
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class AreasConhecimento extends MultipleSearchAjax
{
    public function multipleSearchAjaxAreasConhecimento($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'areasconhecimento',
            'apiController' => 'AreaConhecimentoController',
            'apiResource' => 'areaconhecimento-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->multipleSearchAjax($options['objectName'], $attrName, $options);
    }
}
