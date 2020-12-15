<?php

use iEducar\Modules\Educacenso\Model\TipoAtendimentoAluno;
use iEducar\Modules\Educacenso\Model\TipoAtendimentoTurma;

require_once 'Source/Base.php';
require_once 'Source/Cadastro.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'lib/Portabilis/Date/AppDateUtils.php';
require_once 'lib/App/Model/Educacenso.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Tipo do AEE do aluno");
        $this->processoAp = '578';
    }
}

class indice extends clsCadastro
{
    public $cod_matricula;
    public $ref_cod_aluno;
    public $tipo_atendimento;

    public function Formular()
    {
        $this->nome_url_cancelar = 'Voltar';
        $this->url_cancelar = "educar_matricula_det.php?cod_matricula={$this->cod_matricula}";

        $this->breadcrumb('Tipo do AEE do aluno', [
            $_SERVER['SERVER_NAME'] . '/Intranet' => 'Início',
            'educar_index.php' => 'Escola',
        ]);
    }

    public function Inicializar()
    {
        $this->cod_matricula = $this->getQueryString('ref_cod_matricula');
        $this->ref_cod_aluno = $this->getQueryString('ref_cod_aluno');

        $this->validaPermissao();
        $this->validaParametros();

        return 'Editar';
    }

    public function Gerar()
    {
        $this->campoOculto('cod_matricula', $this->cod_matricula);
        $this->campoOculto('ref_cod_aluno', $this->ref_cod_aluno);

        $obj_aluno = new Aluno();
        $lst_aluno = $obj_aluno->lista($this->ref_cod_aluno, null, null, null, null, null, null, null, null, null, 1);
        if (is_array($lst_aluno)) {
            $det_aluno = array_shift($lst_aluno);
            $this->nm_aluno = $det_aluno['nome_aluno'];
            $this->campoRotulo('nm_aluno', 'Aluno', $this->nm_aluno);
        }

        $enturmacoes = $this->getEnturmacoesAee();

        foreach ($enturmacoes as $enturmacao) {
            $tipoAtendimento = explode(',', str_replace(['{', '}'], '', $enturmacao['tipo_atendimento']));

            $helperOptions = ['objectName' => "{$enturmacao['ref_cod_turma']}_{$enturmacao['sequencial']}_tipoatendimento"];
            $options = [
                'label' => "Tipo de atendimento educacional especializado do aluno na turma {$enturmacao['nm_turma']}: ",
                'options' => [
                    'values' => $tipoAtendimento,
                    'all_values' => TipoAtendimentoAluno::getDescriptiveValues(),
                ],
                'required' => false,
            ];
            $this->inputsHelper()->multipleSearchCustom('', $options, $helperOptions);
        }
    }

    public function Editar()
    {
        $this->validaPermissao();
        $this->validaParametros();

        $enturmacoes = $this->getEnturmacoesAee();

        $arrayTipoAtendimento = [];
        foreach ($enturmacoes as $enturmacao) {
            $arrayTipoAtendimento[] = [
                'value' => request($enturmacao['ref_cod_turma'] . '_' . $enturmacao['sequencial'] . '_tipoatendimento'),
                'turma' => $enturmacao['ref_cod_turma'],
                'sequencial' => $enturmacao['sequencial'],
            ];
        }

        foreach ($arrayTipoAtendimento as $data) {
            $obj = new MatriculaTurma($this->cod_matricula, $data['turma'], $this->pessoa_logada);
            $tipoAtendimento = implode(',', $data['value']);
            $obj->sequencial = $data['sequencial'];
            $obj->tipo_atendimento = $tipoAtendimento;
            $obj->edita();
        }

        $this->mensagem = 'Tipo do AEE do aluno atualizado com sucesso.<br>';
        $this->simpleRedirect("educar_matricula_det.php?cod_matricula={$this->cod_matricula}");
    }

    private function validaPermissao()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(578, $this->pessoa_logada, 7, "educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");
    }

    private function validaParametros()
    {
        $obj_matricula = new Matricula($this->cod_matricula);
        $det_matricula = $obj_matricula->detalhe();

        if (!$det_matricula) {
            $this->simpleRedirect("educar_matricula_lst.php?ref_cod_aluno={$this->ref_cod_aluno}");
        }
    }

    private function getEnturmacoesAee()
    {
        $enturmacoes = new MatriculaTurma();
        $enturmacoes = $enturmacoes->lista(
            $this->cod_matricula,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            1,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            false,
            null,
            null,
            null,
            false,
            false,
            false,
            null,
            null,
            false,
            null,
            false,
            false,
            false
        );

        $arrayEnturmacoes = [];
        foreach ($enturmacoes as $enturmacao) {
            $turma         = new Turma($enturmacao['ref_cod_turma']);
            $turma         = $turma->detalhe();

            if ($turma['tipo_atendimento'] == TipoAtendimentoTurma::AEE) {
                $arrayEnturmacoes[] = $enturmacao;
            }
        }

        return $arrayEnturmacoes;
    }
}

// cria uma extensao da classe base
$pagina = new clsIndexBase();
// cria o conteudo
$miolo = new indice();
// adiciona o conteudo na Base
$pagina->addForm($miolo);
// gera o html
$pagina->MakeAll();
