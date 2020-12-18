<?php

namespace iEducarLegacy\Modules\Avaliacao\Service\Boletim;

/**
 * Trait NotaAluno
 * @package iEducarLegacy\Modules\Avaliacao\Service\Boletim
 */
trait NotaAluno
{
    /**
     * Uma instância de NotaAluno, que é a entrada que contém
     * o cruzamento de matrícula com as notas do aluno nos diversos componentes
     * cursados.
     *
     * @var NotaAluno
     */
    protected $_notaAluno;

    /**
     * @return Avaliacao_Model_NotaAluno|null
     */
    protected function _getNotaAluno()
    {
        if (!is_null($this->_notaAluno)) {
            return $this->_notaAluno;
        }

        $notaAluno = $this->getNotaAlunoDataMapper()->findAll(
            [],
            ['Matricula' => $this->getOption('Matricula')]
        );

        if (0 == count($notaAluno)) {
            return null;
        }

        $this->_setNotaAluno($notaAluno[0]);

        return $this->_notaAluno;
    }

    /**
     * @param Avaliacao_Model_NotaAluno $nota
     *
     * @return $this
     */
    protected function _setNotaAluno(Avaliacao_Model_NotaAluno $nota)
    {
        $this->_notaAluno = $nota;

        return $this;
    }
}
