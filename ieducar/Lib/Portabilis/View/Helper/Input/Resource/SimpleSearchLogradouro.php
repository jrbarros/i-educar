<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchLogradouro
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchLogradouro extends SimpleSearch
{
    public function simpleSearchLogradouro($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'logradouro',
            'apiController' => 'Logradouro',
            'apiResource' => 'logradouro-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function resourceValue($id)
    {
        if ($id) {
            $sql = '
                select nome, descricao as tipo_logradouro
                from public.logradouro l
                left join urbano.tipo_logradouro tl
                on (l.idtlog = tl.idtlog)
                where idlog = $1
            ';

            $options = ['params' => $id, 'return_only' => 'first-row'];
            $resource = Database::fetchPreparedQuery($sql, $options);
            $tipo = Utils::toUtf8($resource['tipo_logradouro']);
            $nome = Utils::toUtf8($resource['nome']);

            return Utils::toLatin1($tipo . ' ' . $nome, ['transform' => true, 'escape' => false]);
        }
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do logradouro';
    }

    protected function loadAssets()
    {
        $jsFile = '/Modules/Portabilis/Assets/Javascripts/Frontend/Inputs/Resource/SimpleSearchLogradouro.js';
        Application::loadJavascript($this->viewInstance, $jsFile);
    }
}
