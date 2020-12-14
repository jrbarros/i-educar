<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchBairro
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchBairro extends SimpleSearch
{
    public function simpleSearchBairro($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'bairro',
            'apiController' => 'Bairro',
            'apiResource' => 'bairro-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        $this->simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select nome, zona_localizacao from public.bairro where idbai = $1';
            $options = ['params' => $id, 'return_only' => 'first-row'];
            $municipio = Database::fetchPreparedQuery($sql, $options);
            $nome = $municipio['nome'];
            $zona = ($municipio['zona_localizacao'] == 1 ? 'Urbana' : 'Rural');

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]) . " / Zona $zona";
        }
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do bairro';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchBairro.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
