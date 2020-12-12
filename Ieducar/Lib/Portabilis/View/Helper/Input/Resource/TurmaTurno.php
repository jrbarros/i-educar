<?php

require_once 'lib/Portabilis/View/Helper/Input/CoreSelect.php';

class Portabilis_View_Helper_Input_Resource_TurmaTurno extends Portabilis_View_Helper_Input_CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = $options['resources'];

        if (empty($options['resources'])) {
            $sql = 'select id, nome from pmieducar.turma_turno where ativo = 1 order by id DESC';
            $resources = Database::fetchPreparedQuery($sql);
            $resources = Utils::setAsIdValue($resources, 'id', 'nome');
        }

        return $this->insertOption(null, 'Selecione', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Turno']];
    }

    public function turmaTurno($options = [])
    {
        parent::select($options);
    }
}
