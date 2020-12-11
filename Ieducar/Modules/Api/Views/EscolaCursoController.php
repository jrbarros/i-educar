<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'Source/pmieducar/EscolaCurso.php';

class EscolaCursoController extends ApiCoreController
{
    public function getAnosLetivos()
    {
        $anosLetivos = [];
        $objeto = new EscolaCurso($this->getRequest()->cod_escola, $this->getRequest()->cod_curso);
        if ($escolaCurso = $objeto->detalhe()) {
            $anosLetivos = json_decode($escolaCurso['anos_letivos']);
        }

        return ['anos_letivos' => $anosLetivos];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'anos-letivos')) {
            $this->appendResponse($this->getAnosLetivos());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
