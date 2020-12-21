<?php

namespace iEducarLegacy\Lib\CoreExt\Validate;



class ChoiceMultiple extends Choice
{
    /**
     * @see Choice::_getDefaultOptions()
     */
    protected function _getDefaultOptions()
    {
        return array_merge(
            parent::_getDefaultOptions(),
            ['multiple' => true]
        );
    }
}
