<?php

namespace iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput;

use iEducar\Modules\Enrollments\Model\EnrollmentStatusFilter;

/**
 * Class SituacaoMatricula
 * @package iEducarLegacy\Lib\Portabilis\View\Helper\DynamicInput
 */
class SituacaoMatricula extends CoreSelect
{
    protected function inputOptions($options)
    {
        $resources = EnrollmentStatusFilter::getDescriptiveValues();

        return $this->insertOption(10, 'Todas', $resources);
    }

    protected function defaultOptions()
    {
        return ['options' => ['label' => 'Situação']];
    }

    public function situacaoMatricula($options = [])
    {
        parent::select($options);
    }
}
