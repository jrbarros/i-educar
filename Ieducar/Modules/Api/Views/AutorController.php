<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Collection/AppDateUtils.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';
require_once 'Intranet/Source/pmieducar/AcervoAcervoAutor.php';

class AutorController extends ApiCoreController
{

    // search options
    protected function searchOptions()
    {
        return ['namespace' => 'pmieducar', 'table' => 'acervo_autor', 'labelAttr' => 'nm_autor', 'idAttr' => 'cod_acervo_autor'];
    }

    protected function formatResourceValue($resource)
    {
        return $this->toUtf8($resource['name'], ['transform' => true]);
    }

    protected function getAutor()
    {
        $obj = new AcervoAcervoAutor();
        $arrayAutores;

        foreach ($obj->listaAutoresPorObra($this->getRequest()->id) as $reg) {
            $arrayAutores[] = [
                'id' => $reg['id'],
                'text' => $reg['nome'],
            ];
        }

        return ['autores' => $arrayAutores];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'autor-search')) {
            $this->appendResponse($this->search());
        } elseif ($this->isRequestFor('get', 'autor')) {
            $this->appendResponse($this->getAutor());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
