<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\Pessoa\CadastroDeficiencia;
use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchDeficiencias
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchDeficiencias extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = new CadastroDeficiencia();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'cod_deficiencia', 'nm_deficiencia');
        }

        return self::insertOption(null, '', $resources);
    }

    public function multipleSearchDeficiencias($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'deficiencias',
            'apiController' => 'Deficiencia',
            'apiResource' => 'deficiencia-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Text::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = 'Selecione as deficiências';
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
