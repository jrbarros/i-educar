<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchRota
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchRota extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select descricao from Modules.rota_transporte_escolar where cod_rota_transporte_escolar = $1';
            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    /**
     * @param string $attrName
     * @param array $options
     */
    public function simpleSearchRota($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'rota',
            'apiController' => 'Rota',
            'apiResource' => 'rota-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou a descrição da rota';
    }
}
