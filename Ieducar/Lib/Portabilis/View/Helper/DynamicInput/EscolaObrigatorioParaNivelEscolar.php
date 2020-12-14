<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Lib\App\Model\NivelTipoUsuario;
use Illuminate\Support\Facades\Session;

/**
 * Class EscolaObrigatorioParaNivelEscolar
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class EscolaObrigatorioParaNivelEscolar extends Escola
{
    /**
     * EscolaObrigatorioParaNivelEscolar constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $nivelUsuario = Session::get('nivel');

        if ($nivelUsuario === NivelTipoUsuario::ESCOLA) {
            $options['options']['required'] = true;
        }

        $this->escola($options);
    }
}
