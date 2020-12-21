<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchMunicipio
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchMunicipio extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select nome, sigla_uf from public.municipio where idmun = $1';
            $options = ['params' => $id, 'return_only' => 'first-row'];
            $municipio = Database::fetchPreparedQuery($sql, $options);
            $nome = $municipio['nome'];
            $siglaUf = $municipio['sigla_uf'];

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]) . " ($siglaUf)";
        }
    }

    public function simpleSearchMunicipio($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'municipio',
            'apiController' => 'Municipio',
            'apiResource' => 'municipio-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome da cidade';
    }
}
