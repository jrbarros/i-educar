<?php

namespace iEducarLegacy\Modules\Avaliacao\Service\Boletim;

/**
 * Trait Validators
 * @package iEducarLegacy\Modules\Avaliacao\Service\Boletim
 */
trait Validators
{
    /**
     * Validadores para instâncias de Avaliacao_Model_FaltaAbstract e
     * NotaComponente.
     *
     * @see Boletim::_addValidators()
     *
     * @var array
     */
    protected $_validators;

    /**
     * Validadores para uma instância de ParecerDescritivoAbstract
     * adicionada no boletim.
     *
     * @see Boletim::_addParecerValidators()
     *
     * @var array
     */
    protected $_parecerValidators;

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->_validators;
    }

    /**
     * @param array $validators
     *
     * @return $this
     */
    public function setValidators(array $validators)
    {
        $this->_validators = $validators;

        return $this;
    }

    /**
     * @return array
     */
    public function getParecerValidators()
    {
        return $this->_parecerValidators;
    }

    /**
     * @param array $validators
     *
     * @return $this
     */
    public function setParecerValidators(array $validators)
    {
        $this->_parecerValidators = $validators;

        return $this;
    }
}
