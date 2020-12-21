<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchComponenteCurricular
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchComponenteCurricular extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = Database::fetchPreparedQuery('SELECT id, nome FROM Modules.componente_curricular');
            $resources = Utils::setAsIdValue($resources, 'id', 'nome');
        }

        return self::insertOption(null, '', $resources);
    }

    public function multipleSearchComponenteCurricular($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'componentecurricular',
            'apiController' => 'ComponenteCurricular',
            'apiResource' => 'componentecurricular-search',
            'searchForArea' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Text::camelize($options['objectName']) . 'Options';
        $searchForArea = $options['searchForArea'] ? 'true' : 'false';
        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione os componentes');
        ";
        $js .= "var searchForArea = {$searchForArea}";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = false);
    }

    protected function loadAssets()
    {
        Application::loadChosenLib($this->viewInstance);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearch.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/MultipleSearchComponenteCurricular.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
