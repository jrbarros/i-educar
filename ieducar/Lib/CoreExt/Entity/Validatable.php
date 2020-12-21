<?php

namespace iEducarLegacy\Lib\CoreExt\Entity;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Validatable as Validate;

/**
 * Interface Validatable
 * @package iEducarLegacy\Lib\CoreExt\Entity
 */
interface Validatable extends Validate
{
    /**
     * Configura uma coleção de ValidateInterface na instância.
     *
     * @return Entity Provê interface fluída
     */
    public function setValidatorCollection(array $validators);

    /**
     * Retorna um array de itens ValidateInterface da instância.
     *
     * @return array
     */
    public function getValidatorCollection();

    /**
     * Retorna um array de ValidateInterface padrão para as propriedades
     * de Entity.
     *
     * Cada item do array precisa ser um item associativo com o mesmo nome do
     * atributo público definido pelo array $_data:
     *
     * <code>
     * <?php
     * // Uma classe concreta de Entity com as propriedades públicas
     * // nome e telefone poderia ter os seguintes validadores.
     * array(
     *   'nome' => new CoreExt_Validate_Alpha(),
     *   'telefone' => new CoreExt_Validate_Alphanum()
     * );
     * </code>
     *
     * @return array
     */
    public function getDefaultValidatorCollection();
}
