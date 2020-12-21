<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

/**
 * Class SimpleSearch
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class SimpleSearch extends Core
{
    protected function resourceValue($id)
    {
        throw new \Exception(
            'You are trying to get the resource value, but this is a generic class, ' .
            'please, define the method resourceValue in a resource subclass.'
        );
    }

    public function simpleSearch($objectName, $attrName, $options = [])
    {
        $defaultOptions = [
            'options' => [],
            'apiModule' => 'Api',
            'apiController' => ucwords($objectName),
            'apiResource' => $objectName . '-search',
            'searchPath' => '',
            'addHiddenInput' => true,
            'hiddenInputOptions' => [],
            'showIdOnValue' => true
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        if (empty($options['searchPath'])) {
            $options['searchPath'] = '/Module/' . $options['apiModule'] . '/' . $options['apiController'] .
                '?oper=get&resource=' . $options['apiResource'];
        }

        $resourceId = $options['hiddenInputOptions']['options']['value'];

        if ($resourceId && !$options['options']['value']) {
            if ($options['showIdOnValue']) {
                $options['options']['value'] = $resourceId . ' - ' . $this->resourceValue($resourceId);
            } else {
                $options['options']['value'] = $this->resourceValue($resourceId);
            }
        }

        $this->hiddenInput($objectName, $attrName, $options);
        $this->textInput($objectName, $attrName, $options);
        $this->js($objectName, $attrName, $options);
    }

    protected function hiddenInput($objectName, $attrName, $options)
    {
        if ($options['addHiddenInput']) {
            if ($attrName == 'id') {
                throw new CoreExtensionException(
                    'When $addHiddenInput is true the $attrName (of the visible input) ' .
                    'must be different than \'id\', because the hidden input will use it.'
                );
            }

            $defaultHiddenInputOptions = ['options' => [], 'objectName' => $objectName];
            $hiddenInputOptions = $this->mergeOptions($options['hiddenInputOptions'], $defaultHiddenInputOptions);

            $this->inputsHelper()->hidden('id', [], $hiddenInputOptions);
        }
    }

    protected function textInput($objectName, $attrName, $options)
    {
        $textHelperOptions = ['objectName' => $objectName];

        $options['options']['placeholder'] = Utils::toLatin1(
            $this->inputPlaceholder([]),
            ['escape' => false]
        );

        $this->inputsHelper()->text($attrName, $options['options'], $textHelperOptions);
    }

    protected function js($objectName, $attrName, $options)
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/SimpleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);

        $resourceOptions = 'simpleSearch' . Utils::camelize($objectName) . 'Options';

        $js = "
            $resourceOptions = typeof $resourceOptions == 'undefined' ? {} : $resourceOptions;
            simpleSearchHelper.setup('$objectName', '$attrName', '" . $options['searchPath'] . "', $resourceOptions);
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
