<?php

namespace iEducarLegacy\Modules\Biblioteca\Model;

use iEducarLegacy\Lib\CoreExt\Entity;

/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *                     <ctima@itajai.sc.gov.br>
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
 * @author      Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     ComponenteCurricular
 * @subpackage  Modules
 *
 * @since       Arquivo disponível desde a versão 1.1.0
 *
 * @version     $Id$
 */

/**
 * ComponenteCurricular_Model_Componente class.
 *
 * @author      Lucas D'Avila <lucasdavila@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     ComponenteCurricular
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.1.0
 *
 * @version     @@package_version@@
 */
class TipoExemplar extends Entity
{
    protected $_data = [
    'cod_exemplar_tipo'  => null,
    'ref_cod_biblioteca' => null,
    'ref_usuario_exc'    => null,
    'ref_usuario_cad'    => null,
    'nm_tipo'            => null,
    'descricao'          => null,
    'data_cadastro'      => null,
    'data_exclusao'      => null,
    'ativo'              => null
  ];

    protected $_dataTypes = [
    'nm_tipo' => 'string'
  ];

    protected $_references = [
  ];

    public function getDataMapper()
    {
        if (is_null($this->_dataMapper)) {
            require_once 'Biblioteca/Model/TipoExemplarDataMapper.php';
            $this->setDataMapper(new TipoExemplarDataMapper());
        }

        return parent::getDataMapper();
    }

    public function getDefaultValidatorCollection()
    {
        return [];
    }

    protected function _createIdentityField()
    {
        $id = ['cod_exemplar_tipo' => null];
        $this->_data = array_merge($id, $this->_data);

        return $this;
    }
}
