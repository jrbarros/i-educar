<?php

namespace iEducarLegacy\Lib\CoreExt\View;

/**
 * Class View
 * @package iEducarLegacy\Lib\CoreExt\View
 */
abstract class View
{
    /**
     * Conteúdo gerado pela execução de um controller.
     *
     * @var string
     */
    protected $_contents = null;

    /**
     * Setter.
     *
     * @param string $contents
     *
     * @return View Provê interface fluída.
     */
    public function setContents($contents)
    {
        $this->_contents = $contents;

        return $this;
    }

    /**
     * Getter.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->_contents;
    }

    /**
     * Implementação do método mágico __toString(). Retorna o valor de $contents.
     *
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getContents();
    }
}
