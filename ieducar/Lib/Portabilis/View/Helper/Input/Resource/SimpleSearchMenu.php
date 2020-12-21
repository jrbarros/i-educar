<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchMenu
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchMenu extends SimpleSearch
{
    public function simpleSearchMenu($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'menu',
            'apiController' => 'Menu',
            'apiResource' => 'menu-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do menu';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/simpleSearchMenu.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $style = '/Modules/Portabilis/Assets/Stylesheets/Frontend/Inputs/Resource/simpleSearchMenu.css';
        Application::loadStylesheet($this->viewInstance, $style);
    }
}
