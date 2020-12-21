<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource;

use iEducarLegacy\Lib\Portabilis\View\Helper\Input\SimpleSearch;

/**
 * Class SimpleSearchRotinasAuditoria
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input\Resource
 */
class SimpleSearchRotinasAuditoria extends SimpleSearch
{
    protected function resourceValue($id)
    {
        return $id;
    }

    public function simpleSearchRotinasAuditoria($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'rotinas-auditoria',
            'apiController' => 'RotinasAuditoria',
            'apiResource' => 'rotinas-auditoria-search',
            'showIdOnValue' => false
        ];

        $options = self::mergeOptions($options, $defaultOptions);

        parent::simpleSearch($options['objectName'], $attrName, $options);
    }

    protected function inputPlaceholder($inputOptions)
    {
        return 'Informe o nome da rotina';
    }
}
