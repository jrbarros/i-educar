<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

/**
 * Class MultipleSearchAutores
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class MultipleSearchAutores extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = new AcervoAutor();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'cod_acervo_autor', 'nm_autor');
        }

        return $this->insertOption(null, '', $resources);
    }

    public function multipleSearchAutores($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'autores',
            'apiController' => 'Autor',
            'apiResource' => 'autor-search'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);
        $options['options']['resources'] = $this->getOptions($options['options']['resources']);

        $this->placeholderJs($options);

        parent::multipleSearch($options['objectName'], $attrName, $options);
    }

    protected function placeholderJs($options)
    {
        $optionsVarName = 'multipleSearch' . Utils::camelize($options['objectName']) . 'Options';

        $js = "
            if (typeof $optionsVarName == 'undefined') { $optionsVarName = {} };
            $optionsVarName.placeholder = safeUtf8Decode('Selecione os autores');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
