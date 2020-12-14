<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

/**
 * Class MultipleSearch
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class MultipleSearch extends Core
{
    public function multipleSearch($objectName, $attrName, $options = [])
    {
        $defaultOptions = [
            'options' => [],
            'apiModule' => 'Api',
            'apiController' => ucwords($objectName),
            'apiResource' => $objectName . '-search',
            'searchPath' => '',
            'type' => 'multiple'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);

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
        Portabilis_View_Helper_Application::loadChosenLib($this->viewInstance);

        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Portabilis_View_Helper_Application::loadJavascript($this->viewInstance, $jsFile);
    }

    protected function js($objectName, $attrName, $options)
    {
        $resourceOptions = 'multipleSearch' . Utils::camelize($objectName) . 'Options';

        $js = "
            $resourceOptions = typeof $resourceOptions == 'undefined' ? {} : $resourceOptions;
            multipleSearchHelper.setup('$objectName', '$attrName', '" . $options['type'] . '\',\'' . $options['type'] . "', $resourceOptions);
        ";

        Portabilis_View_Helper_Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
