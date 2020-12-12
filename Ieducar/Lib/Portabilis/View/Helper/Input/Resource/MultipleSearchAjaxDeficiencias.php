<?php

require_once 'lib/Portabilis/View/Helper/Input/MultipleSearchAjax.php';
require_once 'lib/Portabilis/Utils/Database.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';

class Portabilis_View_Helper_Input_Resource_MultipleSearchAjaxDeficiencias extends Portabilis_View_Helper_Input_MultipleSearchAjax
{
    public function multipleSearchDeficiencias($attrName, $options = [])
    {
        $defaultOptions = [
            'objectName' => 'deficiencias',
            'apiController' => 'Deficiencia',
            'apiResource' => 'deficiencia-search'
        ];

        $options = $this->mergeOptions($options, $defaultOptions);

        parent::multipleSearchAjax($options['objectName'], $attrName, $options);
    }
}
