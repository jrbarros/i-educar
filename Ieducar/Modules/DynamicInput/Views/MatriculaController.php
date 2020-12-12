<?php

#error_reporting(E_ALL);
#ini_set("display_errors", 1);

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

require_once 'lib/Portabilis/Controller/ApiCoreController.php';
require_once 'Intranet/Source/pmieducar/Matricula.php';

/**
 * MatriculaController class.
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
class MatriculaController extends ApiCoreController
{
    protected function canGetMatriculas()
    {
        return $this->validatesId('turma') &&
           $this->validatesPresenceOf('ano');
    }

    protected function getMatriculas()
    {
        if ($this->canGetMatriculas()) {
            $matriculas = new Matricula();
            $matriculas->setOrderby('sequencial_fechamento , translate(nome,\''.Utils::toLatin1(åáàãâäéèêëíìîïóòõôöúùüûçÿýñÅÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÔÖÚÙÛÜÇÝÑ).'\', \''.Utils::toLatin1(aaaaaaeeeeiiiiooooouuuucyynAAAAAAEEEEIIIIOOOOOUUUUCYN).'\') ');
            $matriculas = $matriculas->lista(
          null,
          null,
          $this->getRequest()->escola_id,
          $this->getRequest()->serie_id,
          null,
          null,
          $this->getRequest()->aluno_id,
          null,
          null,
          null,
          null,
          null,
          $ativo = 1,
          $this->getRequest()->ano,
          null,
          $this->getRequest()->instituicao_id,
          null,
          null,
          null,
          null,
          null,
          null,
          null,
          null,
          $this->getRequest()->curso_id,
          null,
          $this->getRequest()->matricula_id,
          null,
          null,
          null,
          null,
          $this->getRequest()->turma_id,
          null,
          false
      ); // Mostra alunos em abandono/transferidos se não existir nenhuma matricula_turma ativa pra outra turma

            $options = [];

            foreach ($matriculas as $matricula) {
                $options['__' . $matricula['cod_matricula']] = mb_strtoupper($matricula['nome'], 'UTF-8');
            }

            return ['options' => $options];
        }
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'matriculas')) {
            $this->appendResponse($this->getMatriculas());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
