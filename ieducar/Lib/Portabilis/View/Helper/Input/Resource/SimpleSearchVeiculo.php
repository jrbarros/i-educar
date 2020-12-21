<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchVeiculo
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchVeiculo extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select (descricao || \',Placa: \' || placa) from Modules.veiculo where cod_veiculo = $1';
            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchVeiculo($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'veiculo',
            'apiController' => 'Veiculo',
            'apiResource' => 'veiculo-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou a descrição do veiculo';
    }
}
