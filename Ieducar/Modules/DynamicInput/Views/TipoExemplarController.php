<?php

namespace iEducarLegacy\Modules\DynamicInput\Views;

use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;
use iEducarLegacy\Lib\Portabilis\String\Utils;

/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *     <ctima@itajai.sc.gov.br>
 *
 * Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versão 2 da Licença, como (a seu critério)
 * qualquer versão posterior.
 *
 * Este programa é distribuí­do na expectativa de que seja útil, porém, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implí­cita de COMERCIABILIDADE OU
 * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral
 * do GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto
 * com este programa; se não, escreva para a Free Software Foundation, Inc., no
 * endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category  i-Educar
 *
 * @license   @@license@@
 *
 * @package   Avaliacao
 * @subpackage  Modules
 *
 * @since   Arquivo disponível desde a versão ?
 *
 * @version   $Id$
 */

/**
 * TipoExemplarController class.
 *
 * @author      Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Avaliacao
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.1.0
 *
 * @version     @@package_version@@
 */
class TipoExemplarController extends ApiCoreController
{
    protected $_dataMapper  = 'TipoExemplarDataMapper';

    protected function canGetTiposExemplar()
    {
        return $this->validatesId('biblioteca');
    }

    protected function getTiposExemplar()
    {
        if ($this->canGetTiposExemplar()) {
            $columns = ['cod_exemplar_tipo', 'nm_tipo'];

            $where   = ['ref_cod_biblioteca' => $this->getRequest()->biblioteca_id,
                       'ativo'              => '1'];

            $records = $this->getDataMapper()->findAll(
          $columns,
          $where,
          $orderBy = ['nm_tipo' => 'ASC'],
          $addColumnIdIfNotSet = false
      );

            $options = [];

            foreach ($records as $record) {
                $options[$record->cod_exemplar_tipo] = Utils::toUtf8($record->nm_tipo);
            }

            return ['options' => $options];
        }
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'tipos_exemplar')) {
            $this->appendResponse($this->getTiposExemplar());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
