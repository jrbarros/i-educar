<?php

require_once('include/Base.php');
require_once('include/clsListagem.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Serie Pre Requisito");
        $this->processoAp = '599';
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

    public $ref_cod_pre_requisito;
    public $ref_cod_operador;
    public $ref_cod_serie;
    public $valor;

    public function Gerar()
    {
        $this->titulo = 'Serie Pre Requisito - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null: $val;
        }

        $this->addCabecalhos([
            'Pre Requisito',
            'Operador',
            'Valor',
            'Serie'
        ]);

        // Filtros de Foreign Keys
        $opcoes = [ '' => 'Selecione' ];

        $objTemp = new Serie();
        $lista = $objTemp->lista();
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $opcoes["{$registro['cod_serie']}"] = "{$registro['nm_serie']}";
            }
        }

        $this->campoLista('ref_cod_serie', 'Serie', $opcoes, $this->ref_cod_serie);

        $opcoes = [ '' => 'Selecione' ];
        $objTemp = new Operador();
        $lista = $objTemp->lista();
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $opcoes["{$registro['cod_operador']}"] = "{$registro['nome']}";
            }
        }

        $this->campoLista('ref_cod_operador', 'Operador', $opcoes, $this->ref_cod_operador);

        $opcoes = [ '' => 'Selecione' ];

        $objTemp = new PreRequisito();
        $lista = $objTemp->lista();
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $opcoes["{$registro['cod_pre_requisito']}"] = "{$registro['nome']}";
            }
        }

        $this->campoLista('ref_cod_pre_requisito', 'Pre Requisito', $opcoes, $this->ref_cod_pre_requisito);

        // outros Filtros
        $this->campoTexto('valor', 'Valor', $this->valor, 30, 255, false);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

        $obj_serie_pre_requisito = new SeriePreRequisito();
        $obj_serie_pre_requisito->setOrderby('valor ASC');
        $obj_serie_pre_requisito->setLimite($this->limite, $this->offset);

        $lista = $obj_serie_pre_requisito->lista(
            $this->ref_cod_pre_requisito,
            $this->ref_cod_operador,
            $this->ref_cod_serie,
            $this->valor
        );

        $total = $obj_serie_pre_requisito->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $obj_ref_cod_serie = new Serie($registro['ref_cod_serie']);
                $det_ref_cod_serie = $obj_ref_cod_serie->detalhe();
                $registro['ref_cod_serie'] = $det_ref_cod_serie['cod_serie'];
                $registro['nm_serie']      = $det_ref_cod_serie['nm_serie'];

                $obj_ref_cod_operador = new Operador($registro['ref_cod_operador']);
                $det_ref_cod_operador = $obj_ref_cod_operador->detalhe();
                $registro['ref_cod_operador'] = $det_ref_cod_operador['cod_operador'];
                $registro['nm_operador']      = $det_ref_cod_operador['nome'];

                $obj_ref_cod_pre_requisito = new PreRequisito($registro['ref_cod_pre_requisito']);
                $det_ref_cod_pre_requisito = $obj_ref_cod_pre_requisito->detalhe();
                $registro['ref_cod_pre_requisito'] = $det_ref_cod_pre_requisito['cod_pre_requisito'];
                $registro['nm_pre_requisito']      = $det_ref_cod_pre_requisito['nome'];

                $this->addLinhas([
                    "<a href=\"educar_serie_pre_requisito_det.php?ref_cod_pre_requisito={$registro['ref_cod_pre_requisito']}&ref_cod_operador={$registro['ref_cod_operador']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['nm_pre_requisito']}</a>",
                    "<a href=\"educar_serie_pre_requisito_det.php?ref_cod_pre_requisito={$registro['ref_cod_pre_requisito']}&ref_cod_operador={$registro['ref_cod_operador']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['nm_operador']}</a>",
                    "<a href=\"educar_serie_pre_requisito_det.php?ref_cod_pre_requisito={$registro['ref_cod_pre_requisito']}&ref_cod_operador={$registro['ref_cod_operador']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['valor']}</a>",
                    "<a href=\"educar_serie_pre_requisito_det.php?ref_cod_pre_requisito={$registro['ref_cod_pre_requisito']}&ref_cod_operador={$registro['ref_cod_operador']}&ref_cod_serie={$registro['ref_cod_serie']}\">{$registro['nm_serie']}</a>"
                ]);
            }
        }
        $this->addPaginador2('educar_serie_pre_requisito_lst.php', $total, $_GET, $this->nome, $this->limite);
        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(599, $this->pessoa_logada, 3)) {
            $this->acao = 'go("educar_serie_pre_requisito_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->largura = '100%';
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
