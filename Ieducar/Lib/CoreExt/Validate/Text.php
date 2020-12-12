<?php

namespace iEducarLegacy\Lib\CoreExt\Validate;

use Validate;

class Text extends Validate
{
    /**
     * @see Validate#_getDefaultOptions()
     */
    protected function _getDefaultOptions()
    {
        return [
            'min' => null,
            'max' => null,
            'min_error' => '"@value" é muito curto (@min caracteres no mínimo)',
            'max_error' => '"@value" é muito longo (@max caracteres no máximo)',
        ];
    }

    /**
     * @see Validate#_validate($value)
     */
    protected function _validate($value)
    {
        $length = strlen($value);

        if ($this->_hasOption('min') && $length < $this->getOption('min')) {
            throw new Exception(
                $this->_getErrorMessage(
                    'min_error',
                    ['@value' => $this->getSanitizedValue(), '@min' => $this->getOption('min')]
                )
            );
        }

        if ($this->_hasOption('max') && $length > $this->getOption('max')) {
            throw new Exception(
                $this->_getErrorMessage(
                    'max_error',
                    ['@value' => $this->getSanitizedValue(), '@max' => $this->getOption('max')]
                )
            );
        }

        return true;
    }

    /**
     * @see Validate#_sanitize($value)
     */
    protected function _sanitize($value)
    {
        return (string) parent::_sanitize($value);
    }
}
