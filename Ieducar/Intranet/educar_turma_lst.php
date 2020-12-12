<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *                                                                        *
    *   @updated 29/03/2007                                                  *
    *   @author Prefeitura Municipal de Itajaí                               *
    *   Pacote: i-PLB Software Público Livre e Brasileiro                    *
    *                                                                        *
    *   Copyright (C) 2006  PMI - Prefeitura Municipal de Itajaí             *
    *                       ctima@itajai.sc.gov.br                           *
    *                                                                        *
    *   Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
    *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
    *   publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
    *   Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
    *                                                                        *
    *   Este programa  é distribuído na expectativa de ser útil, mas SEM     *
    *   QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
    *   ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
    *   sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
    *                                                                        *
    *   Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
    *   junto  com  este  programa. Se não, escreva para a Free Software     *
    *   Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
    *   02111-1307, USA.                                                     *
    *                                                                        *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/**
 * @author Adriano Erik Weiguert Nagasava
 */
require_once('Source/Base.php');
require_once('Source/Listagem.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Turma");
        $this->processoAp = '586';
    }
}

class indice extends clsListagem
{
    /**
     * Referencia pega da session para o idpes do usuario atual
     *
     * @var int
     */
    public $pessoa_logada;

    /**
     * Titulo no topo da pagina
     *
     * @var int
     */
    public $titulo;

    /**
     * Quantidade de registros a ser apresentada em cada pagina
     *
     * @var int
     */
    public $limite;

    /**
     * Inicio dos registros a serem exibidos (limit)
     *
     * @var int
     */
    public $offset;

    public $cod_turma;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_ref_cod_serie;
    public $ref_ref_cod_escola;
    public $ref_cod_infra_predio_comodo;
    public $nm_turma;
    public $sgl_turma;
    public $max_aluno;
    public $multiseriada;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_turma_tipo;
    public $hora_inicial;
    public $hora_final;
    public $hora_inicio_intervalo;
    public $hora_fim_intervalo;

    public $ref_cod_instituicao;
    public $ref_cod_curso;
    public $ref_cod_escola;
    public $visivel;

    public function Gerar()
    {
        $this->titulo = 'Turma - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null: $val;
        }

        $lista_busca = [
            'Ano',
            'Turma',
            'Turno',
            'S&eacute;rie',
            'Curso',
            'Escola',
            'Situação'
        ];

        $this->addCabecalhos($lista_busca);

        if ($this->ref_cod_escola) {
            $this->ref_ref_cod_escola = $this->ref_cod_escola;
        }

        if (!isset($_GET['busca'])) {
            $this->ano = date('Y');
        }

        $this->inputsHelper()->dynamic(['ano', 'instituicao', 'escola', 'curso', 'serie']);

        $this->campoTexto('nm_turma', 'Turma', $this->nm_turma, 30, 255, false);
        $this->campoLista('visivel', 'Situação', ['' => 'Selecione', '1' => 'Ativo', '2' => 'Inativo'], $this->visivel, null, null, null, null, null, false);
        $this->inputsHelper()->turmaTurno(['required' => false, 'label' => 'Turno']);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

        $obj_turma = new Turma();
        $obj_turma->setOrderby('nm_turma ASC');
        $obj_turma->setLimite($this->limite, $this->offset);

        if ($this->visivel == 1) {
            $visivel = true;
        } elseif ($this->visivel == 2) {
            $visivel = false;
        } else {
            $visivel = ['true', 'false'];
        }

        if (App_Model_IedFinder::usuarioNivelBibliotecaEscolar($this->pessoa_logada)) {
            $obj_turma->codUsuario = $this->pessoa_logada;
        }

        $lista = $obj_turma->lista2(
            null,
            null,
            null,
            $this->ref_cod_serie,
            $this->ref_cod_escola,
            null,
            $this->nm_turma,
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
            $this->ref_cod_curso,
            $this->ref_cod_instituicao,
            null,
            null,
            null,
            null,
            null,
            $visivel,
            $this->turma_turno_id,
            null,
            $this->ano
        );

        $total = $obj_turma->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            $ref_cod_escola = '';
            $nm_escola = '';
            foreach ($lista as $registro) {
                $ref_cod_escola = $registro['ref_ref_cod_escola'];
                $obj_ref_cod_escola = new Escola($registro['ref_ref_cod_escola']);
                $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                $ref_cod_escola = $registro['ref_ref_cod_escola'] ;
                $nm_escola = $det_ref_cod_escola['nome'];

                $lista_busca = [
                    "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">{$registro['ano']}</a>",
                    "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">{$registro['nm_turma']}</a>"
                ];

                if ($registro['turma_turno_id']) {
                    $options = ['params' => $registro['turma_turno_id'], 'return_only' => 'first-field'];
                    $turno   = Database::fetchPreparedQuery('select nome from pmieducar.turma_turno where id = $1', $options);

                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">$turno</a>";
                } else {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\"></a>";
                }

                if ($registro['nm_serie']) {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">{$registro['nm_serie']}</a>";
                } else {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">-</a>";
                }

                $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">{$registro['nm_curso']}</a>";

                if ($nm_escola) {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">{$nm_escola}</a>";
                } else {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">-</a>";
                }

                if (dbBool($registro['visivel'])) {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">Ativo</a>";
                } else {
                    $lista_busca[] = "<a href=\"educar_turma_det.php?cod_turma={$registro['cod_turma']}\">Inativo</a>";
                }
                $this->addLinhas($lista_busca);
            }
        }

        $this->addPaginador2('educar_turma_lst.php', $total, $_GET, $this->nome, $this->limite);
        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(586, $this->pessoa_logada, 7)) {
            $this->acao = 'go("educar_turma_cad.php")';
            $this->nome_acao = 'Novo';
        }
        $this->largura = '100%';

        $this->breadcrumb('Listagem de turmas', [
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
