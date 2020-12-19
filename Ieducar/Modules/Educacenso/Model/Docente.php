<?php

namespace iEducarLegacy\Modules\Educacenso\Model;
use iEducarLegacy\Lib\CoreExt\Validate\Numeric;

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
 * Docente class.
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
class Docente extends CodigoReferencia
{
    public function getDefaultValidatorCollection()
    {
        $validators = [
            'docente'     => new Numeric(['min' => 0]),
            'docenteInep' => new Numeric(['min' => 0]),
        ];

        return array_merge($validators, parent::getDefaultValidatorCollection());
    }

    public function __construct(array $options = [])
    {
        $this->_data['docente'] = null;
        $this->_data['docenteInep'] = null;

        parent::__construct($options);
    }
}
