<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\Database;
use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchProjeto
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchProjeto extends SimpleSearch
{
    public function simpleSearchProjeto($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'projeto',
            'apiController' => 'Projeto',
            'apiResource' => 'projeto-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function resourceValue($id)
    {
        if ($id) {
            $sql = 'select nome from pmieducar.projeto where cod_projeto = $1';
            $options = ['params' => $id, 'return_only' => 'first-field'];
            $nome = Database::fetchPreparedQuery($sql, $options);

            return Utils::toLatin1($nome, ['transform' => true, 'escape' => false]);
        }
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome do projeto';
    }
}
