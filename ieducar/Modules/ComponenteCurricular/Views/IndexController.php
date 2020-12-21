<?php

namespace iEducarLegacy\Modules\ComponenteCurricular\Views;

use iEducarLegacy\Lib\Portabilis\Controller\Page\ListController;

/**
 * Class IndexController
 * @package iEducarLegacy\Modules\ComponenteCurricular\Views
 */
class IndexController extends ListController
{
    protected $_dataMapper = 'ComponenteDataMapper';

    protected $_titulo = 'Listagem de componentes curriculares';

    protected $_processoAp = 946;

    protected $_tableMap = [
        'Nome' => 'nome',
        'Abreviatura' => 'abreviatura',
        'Base' => 'tipo_base',
        'Área de conhecimento' => 'area_conhecimento'
    ];

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Listagem de componentes curriculares', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }
}
