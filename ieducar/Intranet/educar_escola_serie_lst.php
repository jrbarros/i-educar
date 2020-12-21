<?php

require_once 'Source/Base.php';
require_once 'Source/Listagem.php';
require_once 'Source/Banco.php';
require_once 'Source/pmieducar/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Séries da escola');

        $this->processoAp = 585;
    }
}

class indice extends clsListagem
{
    public $limite;
    public $offset;
    public $ref_cod_serie;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $hora_inicial;
    public $hora_final;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $hora_inicio_intervalo;
    public $hora_fim_intervalo;
    public $ref_cod_curso;
    public $ref_ref_cod_serie;

    public function Gerar()
    {
        $this->titulo = 'Escola Série - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null : $val;
        }

        $lista_busca = [
            'Série',
            'Curso'
        ];

        $obj_permissao = new Permissoes();
        $nivel_usuario = $obj_permissao->nivel_acesso($this->pessoa_logada);

        $lista_busca[] = 'Escola';
        $lista_busca[] = 'Instituição';
        $lista_busca[] = 'Escola';
        $this->addCabecalhos($lista_busca);

        $this->inputsHelper()->dynamic(['instituicao', 'escola', 'curso', 'serie']);

        // Paginador
        $this->limite = 20;
        $this->offset = $_GET["pagina_{$this->nome}"]
            ? $_GET["pagina_{$this->nome}"] * $this->limite - $this->limite
            : 0;

        $obj_escola_serie = new EscolaSerie();
        $obj_escola_serie->setOrderby('nm_serie ASC');
        $obj_escola_serie->setLimite($this->limite, $this->offset);

        if (Finder::usuarioNivelBibliotecaEscolar($this->pessoa_logada)) {
            $obj_escola_serie->codUsuario = $this->pessoa_logada;
        }

        $lista = $obj_escola_serie->lista(
            $this->ref_cod_escola,
            $this->ref_cod_serie,
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
            1,
            null,
            null,
            null,
            null,
            $this->ref_cod_instituicao,
            $this->ref_cod_curso
        );

        $total = $obj_escola_serie->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $obj_ref_cod_serie = new Serie($registro['ref_cod_serie']);
                $det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
                $nm_serie = $det_ref_cod_serie['nm_serie'];

                $obj_curso = new Curso($registro['ref_cod_curso']);
                $det_curso = $obj_curso->detalhe();
                $registro['ref_cod_curso'] = $det_curso['nm_curso'];

                $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
                $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                $nm_escola = $det_ref_cod_escola['nome'];

                $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
                $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
                $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];

                $lista_busca = [
                    "<a href=\"educar_escola_serie_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_serie}</a>",
                    "<a href=\"educar_escola_serie_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['ref_cod_curso']}</a>"
                ];

                $lista_busca[] = "<a href=\"educar_escola_serie_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_escola}</a>";
                $lista_busca[] = "<a href=\"educar_escola_serie_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['ref_cod_instituicao']}</a>";
                $lista_busca[] = "<a href=\"educar_escola_serie_det.php?ref_cod_escola={$registro['ref_cod_escola']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$nm_escola}</a>";

                $this->addLinhas($lista_busca);
            }
        }

        $this->addPaginador2(
            'educar_escola_serie_lst.php',
            $total,
            $_GET,
            $this->nome,
            $this->limite
        );

        if ($obj_permissao->permissao_cadastra(585, $this->pessoa_logada, 7)) {
            $this->acao = 'go("educar_escola_serie_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->largura = '100%';

        $this->breadcrumb('Séries da escola', [
            url('Intranet/educar_index.php') => 'Escola',
        ]);
    }
}

$pagina = new clsIndexBase();
$miolo = new indice();

$pagina->addForm($miolo);
$pagina->MakeAll();
