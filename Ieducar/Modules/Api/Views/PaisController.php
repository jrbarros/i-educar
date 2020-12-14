<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * Class PaisController
 * @package iEducarLegacy\Modules\Api\Views
 */
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
