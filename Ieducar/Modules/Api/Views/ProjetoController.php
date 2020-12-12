<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Collection/AppDateUtils.php';
require_once 'Intranet/Source/Banco.php';

class ProjetoController extends ApiCoreController
{
    protected function searchOptions()
    {
        return ['namespace' => 'pmieducar', 'idAttr' => 'cod_projeto'];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'projeto-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
