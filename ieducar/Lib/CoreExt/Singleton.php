<?php

namespace iEducarLegacy\Lib\CoreExt;

/**
 * Class Singleton
 * @package iEducarLegacy\Lib\CoreExt
 */
abstract class Singleton
{
    /**
     * A instância singleton de Singleton
     *
     * @var array
     */
    private static $_instance = [];

    /**
     * Construtor.
     */
    private function __construct()
    {
    }

    /**
     * Sobrescreva esse método para garantir que a subclasse possa criar um
     * singleton. Esta deve fazer uma chamada ao método _getInstance, passando
     * uma string que tenha como valor o nome da classe. Uma forma conveniente
     * de fazer isso é chamando _getInstance passando como parâmetro a constante
     * mágica __CLASS__.
     *
     * Exemplo:
     * <code>
     * <?php
     * ... // extends Singleton
     * public static function getInstance()
     * {
     *   return self::_getInstance(__CLASS__);
     * }
     * </code>
     *
     * @return void
     *
     * @throws CoreExtensionException
     */
    public static function getInstance()
    {
        require_once 'CoreExt/CoreExtensionException.php';
        throw new CoreExtensionException('É necessário sobrescrever o método "getInstance()" de Singleton.');
    }

    /**
     * Retorna uma instância singleton, instanciando-a quando necessário.
     *
     * @param string $self Nome da subclasse de Singleton que será instanciada
     *
     * @return Singleton
     */
    protected static function _getInstance($self)
    {
        if (!isset(self::$_instance[$self])) {
            self::$_instance[$self] = new $self();
        }

        return self::$_instance[$self];
    }
}
