<?php

namespace iEducarLegacy\Modules\Educacenso\Model;

use iEducarLegacy\Lib\CoreExt\Entity;
use iEducarLegacy\Lib\CoreExt\Validate\Numeric;
use iEducarLegacy\Lib\CoreExt\Validate\Text;

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
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Educacenso
 * @subpackage  Modules
 *
 * @since       Arquivo disponível desde a versão 1.2.0
 *
 * @version     $Id$
 */

/**
 * CursoSuperior class.
 *
 * @author      Eriksen Costa Paixão <eriksen.paixao_bs@cobra.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Educacenso
 * @subpackage  Modules
 *
 * @since       Classe disponível desde a versão 1.2.0
 *
 * @version     @@package_version@@
 */
class CursoSuperior extends Entity
{
    protected $_data = [
        'curso'      => null,
        'nome'       => null,
        'classe'     => null,
        'user'       => null,
        'created_at' => null,
        'updated_at' => null
    ];

    public function getDefaultValidatorCollection()
    {
        return [
            'curso'  => new Text(['min' => 0]),
            'nome'   => new Text(['min' => 1]),
            'classe' => new Numeric(['min' => 0]),
            'user'   => new Numeric(['min' => 0])
        ];
    }

    public function __toString()
    {
        return $this->nome;
    }
}
