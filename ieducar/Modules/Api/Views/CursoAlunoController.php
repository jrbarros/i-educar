<?php

namespace iEducarLegacy\Modules\Api\Views;

use iEducarLegacy\Lib\Portabilis\Collection\Utils;
use iEducarLegacy\Lib\Portabilis\Controller\ApiCoreController;
use iEducarLegacy\Lib\Portabilis\String\Utils as Text;

/**
 * Class CursoAlunoController
 *
 * @package iEducarLegacy\Modules\Api\Views
 */
class CursoAlunoController extends ApiCoreController
{
    public function canGetCursoDoAluno()
    {
        return $this->validatesPresenceOf('aluno_id');
    }

    public function getCursoDoAluno()
    {
        if ($this->canGetCursoDoAluno()) {
            $alunoId = $this->getRequest()->aluno_id;
            $sql = 'SELECT \'\'\'\' || (nm_curso ) || \'\'\'\' AS id, (nm_curso ) AS nome FROM pmieducar.historico_escolar WHERE ref_cod_aluno = $1';
            $cursos = $this->fetchPreparedQuery($sql, [$alunoId]);
            $attrs = ['id', 'nome'];
            $cursos = Utils::filterSet($cursos, $attrs);
            $options = [];

            foreach ($cursos as $curso) {
                $options[$curso['id']] = Text::toUtf8($curso['nome']);
            }

            return ['options' => $options];
        }
    }

    public function Gerar()
    {
        if ($this->isRequestFor('get', 'curso-aluno')) {
            $this->appendResponse($this->getCursoDoAluno());
        } else {
            $this->notImplementedOperationError();
        }
    }
}
