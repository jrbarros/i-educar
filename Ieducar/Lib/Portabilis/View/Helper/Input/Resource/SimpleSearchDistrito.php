<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use App\Models\District;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchDistrito
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchDistrito extends SimpleSearch
{
    public function simpleSearchDistrito($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'distrito',
            'apiController' => 'Distrito',
            'apiResource' => 'distrito-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function resourceValue($id)
    {
        if ($id) {
            $district = District::query()->find($id);
            $distrito = $id . ' - ' . $district->name;

            return $distrito;
        }
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do distrito';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchDistrito.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
