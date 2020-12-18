<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

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
 * @author      Gabriel Matos de Souza <gabriel@portabilis.com.br>
 *
 * @category    i-Educar
 *
 * @license     @@license@@
 *
 * @package     Avaliacao
 * @subpackage  Modules
 *
 * @since       Arquivo disponível desde a versão 1.1.0
 *
 * @version     $Id$
 */

/**
 * NotaGeral class.
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
 * @since       Classe disponível desde a versão 1.1.0
 *
 * @version     @@package_version@@
 */
class NotaGeral extends Etapa
{
    protected $_data = [
    'notaAluno'               => null,
    'nota'                    => null,
    'notaArredondada'         => null
  ];

    protected $_dataTypes = [
    'nota' => 'numeric'
  ];

    protected $_references = [
    'notaAluno' => [
      'value' => null,
      'class' => 'NotaAluno',
      'file'  => 'Avaliacao/Model/NotaAluno.php'
    ]
  ];

    /**
     * @see Validatable#getDefaultValidatorCollection()
     */
    public function getDefaultValidatorCollection()
    {
        // Aceita etapas de 0 a 10 + a string Rc
        $etapas = range(0, 10, 1) + ['Rc'];

        return [
            'nota' => new Numeric(['min' => 0, 'max' => 10]),
            'notaArredondada'  => new Text(['max' => 5])
        ];
    }
}
