<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

/**
 * Class Select
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class Select extends Core
{
    public function select($attrName, $options = [])
    {
        $defaultOptions = ['options' => [], 'objectName' => '', 'resources' => []];

        $options = $this->mergeOptions($options, $defaultOptions);

        $spacer = !empty($options['objectName']) && !empty($attrName) ? '_' : '';

        $defaultInputOptions = [
            'id' => $options['objectName'] . $spacer . $attrName,
            'label' => ucwords($attrName),
            'resources' => $options['resources'],
            'value' => '',
            'callback' => '',
            'inline' => false,
            'label_hint' => '',
            'input_hint' => '',
            'disabled' => false,
            'required' => true,
            'multiple' => false
        ];

        $inputOptions = $this->mergeOptions($options['options'], $defaultInputOptions);
        $inputOptions['label'] = Utils::toLatin1($inputOptions['label'], ['escape' => false]);

        call_user_func_array([$this->viewInstance, 'campoLista'], $inputOptions);
    }
}
