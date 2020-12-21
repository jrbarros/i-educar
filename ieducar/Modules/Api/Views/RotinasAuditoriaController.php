<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * Class RotinasAuditoriaController
 * @package iEducarLegacy\Modules\Api\Views
 */
class RotinasAuditoriaController extends ApiCoreController
{

    // search options
    protected function searchOptions()
    {
        return [
            'namespace' => 'Modules',
            'table' => 'auditoria_geral',
            'idAttr' => 'rotina'
        ];
    }

    protected function formatResourceValue($resource)
    {
        return $this->toUtf8($resource['name'], ['transform' => true]);
    }

    protected function sqlsForNumericSearch()
    {
        return 'SELECT DISTINCT rotina AS id,
                       rotina AS name
                  FROM Modules.auditoria_geral
                 WHERE rotina::varchar like \'%\'||$1||\'%\'
                 ORDER BY rotina
                 LIMIT 15';
    }

    protected function sqlsForStringSearch()
    {
        return 'SELECT DISTINCT rotina AS id,
                       rotina AS name
                  FROM Modules.auditoria_geral
                 WHERE rotina::varchar like \'%\'||$1||\'%\'
                 ORDER BY rotina
                 LIMIT 15';
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'rotinas-auditoria-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
