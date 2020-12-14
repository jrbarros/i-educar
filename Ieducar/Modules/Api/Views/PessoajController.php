<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * Class PessoajController
 * @package iEducarLegacy\Modules\Api\Views
 */
class PessoajController extends ApiCoreController
{
    protected function sqlsForNumericSearch()
    {
        $sqls[] = 'select distinct idpes as id, nome as name from
            cadastro.Pessoa where tipo=\'J\' and idpes::varchar like $1||\'%\'';

        return $sqls;
    }

    protected function sqlsForStringSearch()
    {
        $sqls[] = 'select distinct idpes as id, nome as name from
            cadastro.Pessoa where tipo=\'J\' and lower((nome)) like \'%\'||lower(($1))||\'%\'';

        return $sqls;
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'pessoaj-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
