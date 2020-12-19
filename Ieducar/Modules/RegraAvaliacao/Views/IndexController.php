<?php

namespace iEducarLegacy\Modules\RegraAvaliacao\Views;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageListController;

/**
 * Class IndexController
 * @package iEducarLegacy\Modules\RegraAvaliacao\Views
 */
class IndexController extends CoreControllerPageListController
{
    protected $_dataMapper = 'RegraDataMapper';
    protected $_titulo = 'Listagem de regras de avaliação';
    protected $_processoAp = 947;

    protected $_tableMap = [
        'Nome' => 'nome',
        'Sistema de nota' => 'tipoNota',
        'Progressão' => 'tipoProgressao',
        'Média aprovação' => 'media',
        'Média exame' => 'mediaRecuperacao',
        'Fórmula média' => 'formulaMedia',
        'Fórmula recuperação' => 'formulaRecuperacao',
        'Recuperação paralela' => 'tipoRecuperacaoParalela'
    ];

    protected function _preRender()
    {
        parent::_preRender();

        $this->breadcrumb('Listagem de regras de avalia&ccedil;&otilde;es', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }
}
