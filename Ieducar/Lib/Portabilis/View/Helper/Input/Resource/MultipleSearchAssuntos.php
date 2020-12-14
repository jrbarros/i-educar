<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\PmiEducar\AcervoAssunto;
use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchAssuntos
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchAssuntos extends MultipleSearch
{
    /**
     * @param $resources
     * @return array
     */
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = new AcervoAssunto();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'cod_acervo_assunto', 'nm_assunto');
        }

        return self::insertOption(null, '', $resources);
    }

    /**
     * @param $attrName
     * @param array $options
     */
    public function multipleSearchAssuntos($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'assuntos',
            'apiController' => 'Assunto',
            'apiResource' => 'assunto-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        $this->multipleSearch($options['objectName'], $attrName, $options);
    }

    /**
     * @param $options
     */
    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Text::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione os assuntos');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
