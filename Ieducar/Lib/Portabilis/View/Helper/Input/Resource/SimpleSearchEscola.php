<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchEscola
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchEscola extends SimpleSearch
{
    /**
     * @param string $attrName
     * @param array  $options
     */
    public function simpleSearchEscola($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'escola',
            'apiController' => 'Escola',
            'apiResource' => 'escola-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->simpleSearch($options['objectName'], $attrName, $options);
    }

    /**
     * @param $inputOptions
     * @return string
     */
    protected function inputPlaceholder($inputOptions)
    {
        return 'Digite um nome para buscar';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchEscola.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
