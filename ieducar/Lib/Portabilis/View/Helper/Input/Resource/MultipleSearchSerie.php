<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;
use iEducarLegacy\Lib\Utils\SafeJson;

/**
 * Class MultipleSearchSerie
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchSerie extends MultipleSearch
{
    protected function getOptions($resources)
    {
        return self::insertOption(null, '', $resources);
    }

    public function multipleSearchSerie($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'multiple_search_serie',
            'apiController' => 'Serie',
            'apiResource' => 'series-curso-grouped'
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        $this->multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';
        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione');
        ";

        $json = SafeJson::encode($options['options']['values']);

        $js .= 'arrayOptions.push({element : $j("#' . $options['objectName'] . '_serie-' . $options['options']['coluna'] . '"),values : ' . $json . '})';

        Application::embedJavascript($this->viewInstance, $js, $afterReady = false);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchSerie.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
