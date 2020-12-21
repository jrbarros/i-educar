<?php

namespace iEducarLegacy\Lib\CoreExt\Validate;

use iEducarLegacy\Lib\CoreExt\CoreExtConfigurable;


/**
 * Interface ValidateInterface
 * @package iEducarLegacy\Lib\CoreExt\Validate
 */
interface ValidateInterface extends CoreExtConfigurable
{
    /**
     * Verifica se um dado valor é válido de acordo com a lógica implementada
     * pela subclasse.
     *
     * @param $value
     *
     * @return bool
     */
    public function isValid($value);

    /**
     * Retorna o valor que foi para a validação.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Retorna o valor sanitizado após a validação.
     *
     * @return mixed
     */
    public function getSanitizedValue();
}
