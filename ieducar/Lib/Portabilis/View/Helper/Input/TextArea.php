<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

/**
 * Class TextArea
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class TextArea extends Core
{
    public function textArea($attrName, $options = [])
    {
        $defaultOptions = ['options' => [], 'objectName' => ''];

        $options = self::mergeOptions($options, $defaultOptions);
        $spacer = !empty($options['objectName']) && !empty($attrName) ? '_' : '';

        $label = !empty($attrName) ? $attrName : $options['objectName'];
        $label = str_replace('_id', '', $label);

        $defaultInputOptions = [
            'id' => $options['objectName'] . $spacer . $attrName,
            'label' => ucwords($label),
            'value' => null,
            'cols' => 49,
            'rows' => 5,
            'required' => true,
            'label_hint' => '',
            'max_length' => '',
            'inline' => false,
            'script' => false,
            'event' => 'onClick',
            'disabled' => false
        ];

        $inputOptions = self::mergeOptions($options['options'], $defaultInputOptions);

        call_user_func_array([$this->viewInstance, 'campoMemo'], $inputOptions);
        $this->fixupPlaceholder($inputOptions);
    }
}
