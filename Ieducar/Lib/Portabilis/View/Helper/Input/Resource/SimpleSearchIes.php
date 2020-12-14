<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchIes
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchIes extends SimpleSearch
{
    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select ies_id || \' - \' || nome AS nome from Modules.educacenso_ies where id = $1';
            $options = ['params' => $id, 'return_only' => 'first-row'];
            $ies = Database::fetchPreparedQuery($sql, $options);
            $nome = $ies['nome'];

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    public function simpleSearchIes($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'ies',
            'apiController' => 'Ies',
            'apiResource' => 'ies-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o código ou nome da instituição';
    }
}
