<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\Input;

use iEducarLegacy\Lib\Portabilis\Collection\Utils as Collection;
use iEducarLegacy\Lib\Portabilis\DataMapper\Utils as UtilDB;
use iEducarLegacy\Lib\Portabilis\String\Utils;
use iEducarLegacy\Lib\Portabilis\Utils\User;
use iEducarLegacy\Lib\Portabilis\View\Helper\Application;

/**
 * Class Core
 *
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\Input
 */
class Core
{
    /**
     * Core constructor.
     *
     * @param $viewInstance
     * @param $inputsHelper
     */
    public function __construct($viewInstance, $inputsHelper)
    {
        $this->viewInstance = $viewInstance;
        $this->_inputsHelper = $inputsHelper;

        $this->loadCoreAssets();
        $this->loadAssets();
    }

    /**
     * @return mixed
     */
    protected function inputsHelper()
    {
        return $this->_inputsHelper;
    }

    /**
     * @return mixed|string
     */
    protected function helperName()
    {
        $arrayClassName = explode('_', get_class($this));

        return end($arrayClassName);
    }

    /**
     * @return string
     */
    protected function inputName()
    {
        return Utils::underscore($this->helperName());
    }

    /**
     * @param null $value
     *
     * @return mixed|null
     */
    protected function inputValue($value = null)
    {
        if (!is_null($value)) {
            return $value;
        }

        if (isset($this->viewInstance->{$this->inputName()})) {
            return $this->viewInstance->{$this->inputName()};
        }

        return null;
    }

    /**
     * @param $inputOptions
     *
     * @return mixed|null
     */
    protected function inputPlaceholder($inputOptions)
    {
        return $inputOptions['placeholder'] ?? $inputOptions['label'] ?? null;
    }

    /**
     * @param $inputOptions
     */
    protected function fixupPlaceholder($inputOptions)
    {
        $id = $inputOptions['id'];
        $placeholder = $this->inputPlaceholder($inputOptions);

        $script = '
            var $input = $j(\'#' . $id . '\');
            if ($input.is(\':enabled\')) {
                $input.attr(\'placeholder\', \'' . $placeholder . '\');
            }
        ';

        Application::embedJavascript($this->viewInstance, $script, $afterReady = true);
    }

    /**
     *
     */
    protected function loadCoreAssets()
    {
        // carrega estilo para feedback messages, devido algumas validações de inuts
        // adicionarem mensagens

        $style = '/Modules/Portabilis/Assets/Stylesheets/Frontend.css';

        Application::loadStylesheet($this->viewInstance, $style);

        $dependencies = [
            '/Modules/Portabilis/Assets/Javascripts/Utils.js',
            '/Modules/Portabilis/Assets/Javascripts/ClientApi.js',
            '/Modules/Portabilis/Assets/Javascripts/Validator.js'
        ];

        Application::loadJavascript($this->viewInstance, $dependencies);
    }

    /**
     *
     */
    protected function loadAssets()
    {
        $rootPath = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
        $style = "/Modules/DynamicInput/Assets/Stylesheets/{$this->helperName()}.css";
        $script = "/Modules/DynamicInput/Assets/Javascripts/{$this->helperName()}.js";

        if (file_exists($rootPath . $style)) {
            Application::loadStylesheet($this->viewInstance, $style);
        }

        if (file_exists($rootPath . $script)) {
            Application::loadJavascript($this->viewInstance, $script);
        }
    }

    // wrappers

    /**
     * @return mixed
     */
    protected function getCurrentUserId()
    {
        return User::currentUserId();
    }

    /**
     * @return \iEducarLegacy\Lib\Portabilis\Utils\Permissoes
     */
    protected function getPermissoes()
    {
        return User::getClsPermissoes();
    }

    /**
     * @param $nivelAcessoType
     *
     * @return bool
     */
    protected function hasNivelAcesso($nivelAcessoType)
    {
        return User::hasNivelAcesso($nivelAcessoType);
    }

    /**
     * @param $packageName
     * @param $modelName
     *
     * @return mixed
     *
     * @throws \iEducarLegacy\Lib\CoreExt\CoreExtensionException
     */
    protected function getDataMapperFor($packageName, $modelName)
    {
        return UtilDB::getDataMapperFor($packageName, $modelName);
    }

    /**
     * @param $options
     * @param $defaultOptions
     *
     * @return mixed
     */
    protected static function mergeOptions($options, $defaultOptions)
    {
        return Collection::merge($options, $defaultOptions);
    }

    /**
     * @param $key
     * @param $value
     * @param $array
     *
     * @return array
     */
    protected static function insertOption($key, $value, $array)
    {
        return Collection::insertIn($key, $value, $array);
    }

    // Ieducar helpers

    /**
     * @param null $instituicaoId
     *
     * @return int|mixed|string
     */
    protected function getInstituicaoId($instituicaoId = null)
    {
        if (!is_null($instituicaoId) && is_numeric($instituicaoId)) {
            return $instituicaoId;
        }

        if (isset($this->viewInstance->ref_cod_instituicao) && is_numeric($this->viewInstance->ref_cod_instituicao)) {
            return $this->viewInstance->ref_cod_instituicao;
        }

        if (isset($this->viewInstance->ref_cod_escola) && is_numeric($this->viewInstance->ref_cod_escola)) {
            $escola = App_Model_IedFinder::getEscola($this->viewInstance->ref_cod_escola);

            return $escola['ref_cod_instituicao'];
        }

        if (isset($this->viewInstance->ref_cod_biblioteca) && is_numeric($this->viewInstance->ref_cod_biblioteca)) {
            $biblioteca = App_Model_IedFinder::getBiblioteca($this->viewInstance->ref_cod_biblioteca);

            return $biblioteca['ref_cod_instituicao'];
        }

        return $this->getPermissoes()->getInstituicao($this->getCurrentUserId());
    }

    /**
     * @param null $escolaId
     *
     * @return int|mixed|string
     */
    protected function getEscolaId($escolaId = null)
    {
        if (!is_null($escolaId) && is_numeric($escolaId)) {
            return $escolaId;
        }

        if (isset($this->viewInstance->ref_cod_escola) && is_numeric($this->viewInstance->ref_cod_escola)) {
            return $this->viewInstance->ref_cod_escola;
        }

        if (isset($this->viewInstance->ref_cod_biblioteca) && is_numeric($this->viewInstance->ref_cod_biblioteca)) {
            $biblioteca = App_Model_IedFinder::getBiblioteca($this->viewInstance->ref_cod_biblioteca);

            return $biblioteca['ref_cod_escola'];
        }

        return $this->getPermissoes()->getEscola($this->getCurrentUserId());
    }

    /**
     * @param null $bibliotecaId
     *
     * @return int|mixed|string|null
     */
    protected function getBibliotecaId($bibliotecaId = null)
    {
        if (!is_null($bibliotecaId) && is_numeric($bibliotecaId)) {
            return $bibliotecaId;
        }

        if (isset($this->viewInstance->ref_cod_biblioteca) && is_numeric($this->viewInstance->ref_cod_biblioteca)) {
            return $this->viewInstance->ref_cod_biblioteca;
        }

        $biblioteca = $this->getPermissoes()->getBiblioteca($this->getCurrentUserId());

        if (is_array($biblioteca) && count($biblioteca) > 0) {
            return $biblioteca[0]['ref_cod_biblioteca'];
        }

        return null;
    }

    /**
     * @param null $cursoId
     *
     * @return int|string|null
     */
    protected function getCursoId($cursoId = null)
    {
        if (!is_null($cursoId) && is_numeric($cursoId)) {
            return $cursoId;
        }

        if (isset($this->viewInstance->ref_cod_curso) && is_numeric($this->viewInstance->ref_cod_curso)) {
            return $this->viewInstance->ref_cod_curso;
        }

        return null;
    }

    /**
     * @param null $serieId
     *
     * @return int|string|null
     */
    protected function getSerieId($serieId = null)
    {
        if (!is_null($serieId) && is_numeric($serieId)) {
            return $serieId;
        }

        if (isset($this->viewInstance->ref_cod_serie) && is_numeric($this->viewInstance->ref_cod_serie)) {
            return $this->viewInstance->ref_cod_serie;
        }

        return null;
    }

    /**
     * @param null $escolaridadeId
     *
     * @return int|string|null
     */
    protected function getEscolaridadesId($escolaridadeId = null)
    {
        if (!is_null($escolaridadeId) && is_numeric($escolaridadeId)) {
            return $escolaridadeId;
        }

        if (isset($this->viewInstance->idesco) && is_numeric($this->viewInstance->idesco)) {
            return $this->viewInstance->idesco;
        }

        return null;
    }
}
