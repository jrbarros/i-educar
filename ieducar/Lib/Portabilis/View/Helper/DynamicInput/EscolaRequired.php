<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\NivelTipoUsuario;
use Illuminate\Support\Facades\Session;

/**
 * Class EscolaRequired
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class EscolaRequired extends Escola
{
    public function __construct($options = [])
    {
        $nivelUsuario = Session::get('nivel');

        if ($nivelUsuario === NivelTipoUsuario::ESCOLA) {
            $options['options']['required'] = true;
        }

        $this->escola($options);
    }
}
