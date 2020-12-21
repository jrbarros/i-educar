<?php

namespace iEducarLegacy\Modules\Avaliacao\Model;

use iEducarLegacy\Lib\CoreExt\DataMapper;

/**
 * Class NotaComponenteMediaDataMapper
 * @package iEducarLegacy\Modules\Avaliacao\Model
 */
class NotaComponenteMediaDataMapper extends DataMapper
{
    protected $_entityClass = 'NotaComponenteMedia';
    protected $_tableName = 'nota_componente_curricular_media';
    protected $_tableSchema = 'Modules';

    protected $_attributeMap = [
        'notaAluno' => 'nota_aluno_id',
        'componenteCurricular' => 'componente_curricular_id',
        'mediaArredondada' => 'media_arredondada'
    ];

    protected $_primaryKey = [
        'notaAluno' => 'nota_aluno_id',
        'componenteCurricular' => 'componente_curricular_id'
    ];

    public function updateSituation($notaAlunoId, $situacao)
    {
        $entities = $this->findAll([], ['nota_aluno_id' => $notaAlunoId]);

        if (empty($entities)) {
            return true;
        }

        foreach ($entities as $entity) {
            $entity->situacao = $situacao;
            $this->save($entity);
        }

        return true;
    }
}
