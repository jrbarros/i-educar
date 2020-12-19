<?php

namespace iEducarLegacy\Modules\Docente\Views;

use iEducarLegacy\Lib\Core\Controller\Page\CoreControllerPageViewController;
use iEducarLegacy\Lib\CoreExt\Entity;
use Symfony\Component\HttpFoundation\UrlHelper;

/**
 * Class ViewController
 * @package iEducarLegacy\Modules\Docente\Views
 */
class ViewController extends CoreControllerPageViewController
{
    protected $_dataMapper = 'LicenciaturaDataMapper';
    protected $_titulo     = 'Detalhes da licenciatura';
    protected $_processoAp = 635;
    protected $_tableMap   = [
        'Licenciatura'     => 'licenciatura',
        'Curso'            => 'curso',
        'Ano de conclusão' => 'anoConclusao',
        'IES'              => 'ies'
    ];

    public function setUrlEditar(Entity $entry)
    {
        $this->url_editar = UrlHelper::url(
            'edit',
            [
                'query' => [
                    'id'          => $entry->id,
                    'servidor'    => $entry->servidor,
                    'instituicao' => $this->getRequest()->instituicao
                ]
            ]
        );
    }

    public function setUrlCancelar(CoreExt_Entity $entry)
    {
        $this->url_cancelar = UrlHelper::url(
            'index',
            [
                'query' => [
                    'id'          => $entry->id,
                    'servidor'    => $entry->servidor,
                    'instituicao' => $this->getRequest()->instituicao
                ]
            ]
        );
    }
}
