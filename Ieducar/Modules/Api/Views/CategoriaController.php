<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Array/AppDateUtils.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';
require_once 'Intranet/Source/pmieducar/CategoriaAcervo.php';

class CategoriaController extends ApiCoreController
{
    protected function getCategorias()
    {
        $obj = new CategoriaAcervo();
        $arrayCategorias;

        foreach ($obj->listaCategoriasPorObra($this->getRequest()->id) as $reg) {
            $arrayCategorias[] = $reg['categoria_id'];
        }

        return ['categorias' => $arrayCategorias];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'categoria-search')) {
            $this->appendResponse($this->search());
        } elseif ($this->isRequestFor('get', 'categorias')) {
            $this->appendResponse($this->getCategorias());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
