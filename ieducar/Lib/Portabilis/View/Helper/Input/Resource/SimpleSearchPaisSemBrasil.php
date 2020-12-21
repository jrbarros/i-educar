<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchPaisSemBrasil
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchPaisSemBrasil extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select nome from public.pais where idpais = $1';
            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchPaisSemBrasil($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'pais',
            'apiController' => 'PaisSemBrasil',
            'apiResource' => 'pais-sem-brasil-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome do pais de origem';
    }
}
