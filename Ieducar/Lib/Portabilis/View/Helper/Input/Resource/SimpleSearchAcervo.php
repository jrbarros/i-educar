<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchAcervo
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchAcervo extends SimpleSearch
{
    public function simpleSearchAcervo($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'acervo',
            'apiController' => 'Acervo',
            'apiResource' => 'acervo-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Digite um nome para buscar';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchAcervo.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
