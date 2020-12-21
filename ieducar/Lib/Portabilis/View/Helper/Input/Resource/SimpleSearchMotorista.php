<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchMotorista
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchMotorista extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select nome from Modules.motorista, cadastro.Pessoa where ref_idpes = idpes and cod_motorista = $1';
            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toUtf8($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchMotorista($attrName = '', $options = [])
    {
        $defaultOptions = [
            'objectName' => 'motorista',
            'apiController' => 'Motorista',
            'apiResource' => 'motorista-search'
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome do motorista';
    }
}
