<?php

namespace iEducarLegacy\Lib\CoreExt;

use ArrayAccess;

/**
 * Class Enum
 * @package iEducarLegacy\Lib\CoreExt
 */
abstract class Enum extends Singleton implements ArrayAccess
{
    /**
     * Collection que emula um enum.
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Retorna o valor para um dado índice de Enum.
     *
     * @param string|int $key
     *
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->_data[$key];
    }

    /**
     * Retorna todos os valores de Enum.
     *
     * @return array
     */
    public function getValues()
    {
        return array_values($this->_data);
    }

    /**
     * Retorna o valor da índice para um determinado valor.
     *
     * @param mixed $value
     *
     * @return int|string
     */
    public function getKey($value)
    {
        return array_search($value, $this->_data);
    }

    /**
     * Retorna todos os índices de Enum.
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->_data);
    }

    /**
     * Retorna o array de enums.
     *
     * @return array
     */
    public function getEnums()
    {
        return $this->_data;
    }

    /**
     * Implementa offsetExists da interface ArrayAccess.
     *
     * @link   http://br2.php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param string|int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_data[$offset]);
    }

    /**
     * Implementa offsetUnset da interface ArrayAccess.
     *
     * @link  http://br2.php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @throws CoreExtensionException
     */
    public function offsetUnset($offset)
    {
        require_once 'CoreExt/CoreExtensionException.php';
        throw new CoreExtensionException('Um "' . get_class($this) . '" é um objeto read-only.');
    }

    /**
     * Implementa offsetSet da interface ArrayAccess.
     *
     * Uma objeto Enum é apenas leitura.
     *
     * @link   http://br2.php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param string|int $offset
     * @param mixed      $value
     *
     * @throws CoreExtensionException
     */
    public function offsetSet($offset, $value)
    {
        require_once 'CoreExt/CoreExtensionException.php';
        throw new CoreExtensionException('Um "' . get_class($this) . '" é um objeto read-only.');
    }

    /**
     * Implementa offsetGet da interface ArrayAccess.
     *
     * @link   http://br2.php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param string|int $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_data[$offset];
    }
}
