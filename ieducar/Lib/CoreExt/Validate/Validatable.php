<?php

namespace iEducarLegacy\Lib\CoreExt\Validate;


interface Validatable
{
    /**
     * Retorna TRUE caso a propriedade seja válida.
     *
     * @param Text $key
     *
     * @return bool
     */
    public function isValid($key = '');

    /**
     * Configura um ValidateInterface para uma propriedade da classe.
     *
     * @param Text                     $key
     * @param ValidateInterface $validator
     *
     * @return Validatable Provê interface fluída
     */
    public function setValidator($key, ValidateInterface $validator);

    /**
     * Retorna a instância ValidateInterface para uma propriedade da
     * classe ou NULL caso nenhum validador esteja atribuído.
     *
     * @param Text $key
     *
     * @return ValidateInterface|NULL
     */
    public function getValidator($key);
}
