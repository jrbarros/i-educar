<?php

namespace iEducar\Support\Exceptions;

use Exception as BaseException;

class Exception extends BaseException
{
    /**
     * Return more information about error.
     *
     * @return array
     */
    public function getExtraInfo()
    {
        return [];
    }
}
