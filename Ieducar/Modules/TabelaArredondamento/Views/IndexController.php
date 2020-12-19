<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Views;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageListController;

/**
 * Class IndexController
 * @package iEducarLegacy\Modules\TabelaArredondamento\Views
 */
class IndexController extends CoreControllerPageListController
{
    protected $_dataMapper = 'TabelaDataMapper';
    protected $_titulo = 'Listagem de tabelas de arredondamento de nota';
    protected $_processoAp = 949;
    protected $_tableMap = [
        'Nome' => 'nome',
        'Sistema de nota' => 'tipoNota'
    ];

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Listagem de tabelas de arredondamento', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }
}
