<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchServidor
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchServidor extends SimpleSearch
{
    public function simpleSearchServidor($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'servidor',
            'apiController' => 'Servidor',
            'apiResource' => 'servidor-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Digite um nome para buscar';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchServidor.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
