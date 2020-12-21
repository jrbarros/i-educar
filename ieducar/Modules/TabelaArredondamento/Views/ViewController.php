<?php

namespace iEducarLegacy\Modules\TabelaArredondamento\Views;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageViewController;

/**
 * Class ViewController
 * @package iEducarLegacy\Modules\TabelaArredondamento\Views
 */
class ViewController extends CoreControllerPageViewController
{
    protected $_dataMapper = 'TabelaDataMapper';
    protected $_titulo = 'Detalhes da tabela de arredondamento';
    protected $_processoAp = 949;
    protected $_tableMap = [
        'Nome' => 'nome',
        'Tipo nota' => 'tipoNota'
    ];

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Detalhe da tabela de arredondamento', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }
}
