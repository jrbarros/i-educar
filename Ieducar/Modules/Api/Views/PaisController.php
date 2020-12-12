<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Collection/AppDateUtils.php';
require_once 'lib/Portabilis/Text/AppDateUtils.php';

class PaisController extends ApiCoreController
{

    // search options
    protected function searchOptions()
    {
        return ['namespace' => 'public', 'idAttr' => 'idpais'];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'pais-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
