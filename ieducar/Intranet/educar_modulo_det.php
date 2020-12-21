<?php

require_once('Source/Base.php');
require_once('Source/Detalhe.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Etapa");
        $this->processoAp = '584';
    }
}

class indice extends clsDetalhe
{
    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

    public $cod_modulo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_tipo;
    public $descricao;
    public $num_etapas;
    public $num_meses;
    public $num_semanas;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Etapa - Detalhe';

        $this->cod_modulo=$_GET['cod_modulo'];

        $tmp_obj = new Modulo($this->cod_modulo);
        $registro = $tmp_obj->detalhe();

        if (! $registro) {
            $this->simpleRedirect('educar_modulo_lst.php');
        }

        $obj_instituicao = new Instituicao($registro['ref_cod_instituicao']);
        $obj_instituicao_det = $obj_instituicao->detalhe();
        $registro['ref_cod_instituicao'] = $obj_instituicao_det['nm_instituicao'];

        $obj_permissao = new Permissoes();
        $nivel_usuario = $obj_permissao->nivel_acesso($this->pessoa_logada);
        if ($nivel_usuario == 1) {
            if ($registro['ref_cod_instituicao']) {
                $this->addDetalhe(
                    [
                        'Instituição',
                        "{$registro['ref_cod_instituicao']}"
                    ]
                );
            }
        }
        if ($registro['nm_tipo']) {
            $this->addDetalhe(
                ['Etapa',
                    "{$registro['nm_tipo']}"
                ]
            );
        }
        if ($registro['descricao']) {
            $this->addDetalhe(
                [
                    'Descrição',
                    "{$registro['descricao']}"
                ]
            );
        }
        $this->addDetalhe(
            [
                'Número de etapas',
                "{$registro['num_etapas']}"
            ]
        );
        if ($registro['num_meses']) {
            $this->addDetalhe(
                [
                    'Número de meses',
                    "{$registro['num_meses']}"
                ]
            );
        }
        if ($registro['num_semanas']) {
            $this->addDetalhe(
                [
                    'Número de semanas',
                    "{$registro['num_semanas']}"
                ]
            );
        }
        if ($obj_permissao->permissao_cadastra(584, $this->pessoa_logada, 3)) {
            $this->url_novo = 'educar_modulo_cad.php';
            $this->url_editar = "educar_modulo_cad.php?cod_modulo={$registro['cod_modulo']}";
        }

        $this->url_cancelar = 'educar_modulo_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da etapa', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
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
