<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\String\Utils as text;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchEtapaCurso
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchEtapaCurso extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = Database::fetchPreparedQuery('SELECT * FROM Modules.etapas_educacenso');
            $resources = Utils::setAsIdValue($resources, 'id', 'nome');
        }

        return self::insertOption(null, '', $resources);
    }

    public function multipleSearchEtapacurso($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'etapacurso',
            'apiController' => 'Etapacurso',
            'apiResource' => 'etapacurso-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        $this->multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Text::camelize($options['objectName']) . 'Options';
        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione as etapas');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
