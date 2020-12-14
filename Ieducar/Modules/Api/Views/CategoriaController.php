<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Intranet\Source\PmiEducar\CategoriaAcervo;
use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * Class CategoriaController
 */
class CategoriaController extends ApiCoreController
{
    protected function getCategorias()
    {
        $obj = new CategoriaAcervo();
        $arrayCategorias = [];

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
