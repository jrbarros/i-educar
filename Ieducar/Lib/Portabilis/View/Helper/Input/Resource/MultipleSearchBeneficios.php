<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\PmiEducar\AlunoBeneficio;
use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

class MultipleSearchBeneficios extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = new AlunoBeneficio();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'cod_aluno_beneficio', 'nm_beneficio');
        }

        return self::insertOption(null, '', $resources);
    }

    public function multipleSearchBeneficios($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'beneficios',
            'apiController' => 'Beneficio',
            'apiResource' => 'beneficio-search'
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
            $optionsVarName.placeholder = 'Selecione os benefícios';
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
