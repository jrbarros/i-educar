<?php

namespace iEducarLegacy\Modules\DynamicInput\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;

/**
 * AreaConhecimento class.
 *
 * @author      Gabriel Matos de Souza <gabriel@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Avaliacao
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão ??
 *
 * @version     @@package_version@@
 */
class AreaConhecimentoController extends ApiCoreController
{
    protected function getAreasConhecimento()
    {
        $instituicaoId = $this->getRequest()->instituicao_id;
        $sql    = 'SELECT ac.id AS id,
                       ac.nome AS nome
                  FROM Modules.area_conhecimento ac
                 WHERE instituicao_id = $1
              ORDER BY (lower(nome)) ASC';

        $paramsSql = ['params' => $instituicaoId];
        $areasConhecimento = $this->fetchPreparedQuery($sql, $paramsSql);
        $options = [];

        foreach ($areasConhecimento as $areaConhecimento) {
            $options['__' . $areaConhecimento['id']] = $this->toUtf8($areaConhecimento['nome']);
        }

        return ['options' => $options];
    }

    protected function searchOptions()
    {
        return ['namespace' => 'Modules', 'table' => 'area_conhecimento', 'labelAttr' => 'nome', 'idAttr' => 'id'];
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'area_conhecimento')) {
            $this->appendResponse($this->getAreasConhecimento());
        } elseif ($this->isRequestFor('get', 'area_conhecimento-search')) {
            $this->appendResponse($this->search());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
