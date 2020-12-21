<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

/**
 * Class MultipleSearchAjax
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class MultipleSearchAjax extends Core
{
    public function multipleSearchAjax($objectName, $attrName, $options = [])
    {
        $defaultOptions = [
            'options' => [],
            'apiModule' => 'Api',
            'apiController' => ucwords($objectName),
            'apiResource' => $objectName . '-search',
            'searchPath' => ''
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        if (empty($options['searchPath'])) {
            $options['searchPath'] = '/Module/' . $options['apiModule'] . '/' . $options['apiController'] .
                '?oper=get&resource=' . $options['apiResource'];
        }

        $this->selectInput($objectName, $attrName, $options);
        $this->loadAssets();
        $this->js($objectName, $attrName, $options);
    }

    protected function selectInput($objectName, $attrName, $options)
    {
        $textHelperOptions = ['objectName' => $objectName];

        $this->inputsHelper()->select($attrName, $options['options'], $textHelperOptions);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        Application::loadAjaxChosenLib($this->viewInstance);

        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearchAjax.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }

    protected function js($objectName, $attrName, $options)
    {
        $resourceOptions = 'multipleSearchAjax' . Utils::camelize($objectName) . 'Options';

        $js = "
            $resourceOptions = typeof $resourceOptions == 'undefined' ? {} : $resourceOptions;
            multipleSearchAjaxHelper.setup('$objectName', '$attrName', '" . $options['searchPath'] . "', $resourceOptions);
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
