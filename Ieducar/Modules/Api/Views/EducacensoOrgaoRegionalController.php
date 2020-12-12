<?php

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'lib/Portabilis/Collection/AppDateUtils.php';

class EducacensoOrgaoRegionalController extends ApiCoreController
{
    protected function getOrgaosRegionais()
    {
        $consulta = 'SELECT codigo
                    FROM Modules.educacenso_orgao_regional
                    WHERE sigla_uf = $1';

        $orgaos = $this->fetchPreparedQuery($consulta, [$this->getRequest()->sigla_uf]);
        $attrs = ['codigo'];
        $orgaos = Utils::filterSet($orgaos, $attrs);

        return ['orgaos' => $orgaos];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'orgaos_regionais')) {
            $this->appendResponse($this->getOrgaosRegionais());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
