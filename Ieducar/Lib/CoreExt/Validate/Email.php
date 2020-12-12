<?php

namespace iEducarLegacy\Lib\CoreExt\Validate;

/**
 * Class Email
 * @package iEducarLegacy\Lib\CoreExt\Validate
 */
class Email extends Validate
{
    /**
     * @see Validate#_getDefaultOptions()
     */
    protected function _getDefaultOptions()
    {
        return [
            'invalid' => 'Email inválido.'
        ];
    }

    /**
     * @see DataMapper#_getFindStatment($pkey) Sobre a conversão com floatval()
     * @see Validate#_validate($value)
     */
    protected function _validate($value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception($this->_getErrorMessage('invalid'));
        }

        return true;
    }

    /**
     * Mensagem padrão para erros de valor obrigatório.
     *
     * @var Text
     */
    protected $_requiredMessage = 'Informe um email válido.';
}
