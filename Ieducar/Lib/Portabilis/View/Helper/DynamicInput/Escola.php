<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducarLegacy\Intranet\Source\PmiEducar\Permissoes;
use iEducarLegacy\Lib\App\Model\Finder;
use iEducarLegacy\Lib\App\Model\NivelTipoUsuario;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

require_once 'lib/Portabilis/View/Helper/DynamicInput/CoreSelect.php';
require_once 'Portabilis/Business/Professor.php';
require_once 'App/Model/NivelTipoUsuario.php';

class Escola extends CoreSelect
{
    protected function inputValue($value = null)
    {
        return $this->getEscolaId($value);
    }

    protected function inputName()
    {
        return 'ref_cod_escola';
    }

    protected function inputOptions($options)
    {
        $resources = $options['resources'];
        $instituicaoId = $this->getInstituicaoId($options['instituicaoId'] ?? null);
        $userId = $this->getCurrentUserId();

        if ($instituicaoId && empty($resources)) {
            $permissao = new Permissoes();
            $nivel = $permissao->nivel_acesso($userId);

            if (
                $nivel === NivelTipoUsuario::ESCOLA
                || $nivel === NivelTipoUsuario::BIBLIOTECA
            ) {
                $escolas_usuario = [];
                $escolasUser = Finder::getEscolasUser($userId);

                foreach ($escolasUser as $e) {
                    $escolas_usuario[$e['ref_cod_escola']] = $e['nome'];
                }

                return $this->insertOption(null, 'Selecione uma escola', $escolas_usuario);
            }

            $resources = Finder::getEscolas($instituicaoId);
        }

        return $this->insertOption(null, 'Selecione uma escola', $resources);
    }

    public function escola($options = [])
    {
        $this->select($options);
        Application::loadChosenLib($this->viewInstance);
        Application::loadJavascript($this->viewInstance, '/Modules/DynamicInput/Assets/Javascripts/Escola.js');
    }
}
