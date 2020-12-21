<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Intranet\Source\PmiEducar\CategoriaObra;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\MultipleSearch;

require_once 'lib/Portabilis/View/Helper/Input/MultipleSearch.php';
require_once 'lib/Portabilis/Utils/Database.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';

class MultipleSearchCategoriaObra extends MultipleSearch
{
    protected function getOptions($resources)
    {
        if (empty($resources)) {
            $resources = new CategoriaObra();
            $resources = $resources->lista();
            $resources = Utils::setAsIdValue($resources, 'id', 'descricao');
        }

        return $this->insertOption(null, '', $resources);
    }

    public function multipleSearchCategoriaObra($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'categorias',
            'apiController' => 'Categoria',
            'apiResource' => 'categoria-search'
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
            $optionsVarName.placeholder = safeUtf8Decode('Selecione as categorias');
        ";

        Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
    }
}
