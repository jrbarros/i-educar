<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * Class IesController
 * @package iEducarLegacy\Modules\Api\Views
 */
class IesController extends ApiCoreController
{

    // search options
    protected function searchOptions()
    {
        return ['namespace' => 'Modules', 'table' => 'educacenso_ies', 'idAttr' => 'id'];
    }

    protected function formatResourceValue($resource)
    {
        return $resource['ies_id'] . ' - ' . $this->toUtf8($resource['name'], ['transform' => true]);
    }

    protected function sqlsForNumericSearch()
    {
        return 'select id, ies_id, nome as name from Modules.educacenso_ies
            where ies_id::varchar like $1||\'%\' order by ies_id limit 15';
    }

    protected function sqlsForStringSearch()
    {
        return 'select id, ies_id, nome as name from Modules.educacenso_ies
            where f_unaccent(nome) ilike f_unaccent(\'%\'||$1||\'%\') order by name limit 15';
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'ies-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
