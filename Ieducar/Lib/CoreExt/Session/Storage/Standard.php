<?php

namespace iEducarLegacy\Lib\CoreExt\Session\Storage;

use Illuminate\Support\Facades\Session;

/**
 * Class Standard
 * @package iEducarLegacy\Lib\CoreExt\Session\Storage
 */
class Standard extends Storage
{
    /**
     * @see StorageInterface#read($key)
     */
    public function read($key)
    {
        $returnValue = null;

        if (Session::has($key)) {
            $returnValue = Session::get($key);
        }

        return $returnValue;
    }

    /**
     * @see StorageInterface#write($key, $value)
     */
    public function write($key, $value)
    {
        Session::put($key, $value);
    }

    /**
     * @see StorageInterface#remove($key)
     */
    public function remove($key)
    {
        Session::forget($key);
    }

    /**
     * @see StorageInterface#start()
     */
    public function start()
    {
        //
    }

    /**
     * @see StorageInterface#destroy()
     */
    public function destroy()
    {
        //
    }

    /**
     * @see StorageInterface#regenerate()
     */
    public function regenerate($destroy = false)
    {
        //
    }

    /**
     * Persiste os dados da session no sistema de arquivos.
     *
     * @see StorageInterface#shutdown()
     */
    public function shutdown()
    {
        //
    }

    /**
     * @link http://br.php.net/manual/en/countable.count.php
     */
    public function count()
    {
        return count(Session::all());
    }

    /**
     * @see StorageInterface#getSessionData()
     */
    public function getSessionData()
    {
        return Session::all();
    }
}
