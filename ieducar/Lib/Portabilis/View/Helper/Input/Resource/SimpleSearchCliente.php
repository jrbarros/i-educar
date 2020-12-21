<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchCliente
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchCliente extends SimpleSearch
{
    /**
     * @param string $attrName
     * @param array  $options
     */
    public function simpleSearchCliente($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'cliente',
            'apiController' => 'Cliente',
            'apiResource' => 'cliente-search'
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
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchCliente.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
