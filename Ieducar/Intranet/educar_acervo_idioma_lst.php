<?php

require_once('Source/Base.php');
require_once('Source/Listagem.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Idioma");
        $this->processoAp = '590';
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

    public $cod_acervo_idioma;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $nm_idioma;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;
    public $ref_cod_biblioteca;

    public function Gerar()
    {
        $this->titulo = 'Idioma - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null: $val;
        }

        $this->addCabecalhos([
            'Idioma',
            'Biblioteca'
        ]);

        // Filtros de Foreign Keys
        $get_escola = true;
        $get_biblioteca = true;
        $get_cabecalho = 'lista_busca';
        include('Source/pmieducar/educar_campo_lista.php');

        // outros Filtros
        $this->campoTexto('nm_idioma', 'Idioma', $this->nm_idioma, 30, 255, false);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

        if (!is_numeric($this->ref_cod_biblioteca)) {
            $obj_bib_user = new clsPmieducarBibliotecaUsuario();
            $this->ref_cod_biblioteca = $obj_bib_user->listaBibliotecas($this->pessoa_logada);
        }

        $obj_acervo_idioma = new AcervoIdioma();
        $obj_acervo_idioma->setOrderby('nm_idioma ASC');
        $obj_acervo_idioma->setLimite($this->limite, $this->offset);

        $lista = $obj_acervo_idioma->lista(
            null,
            null,
            null,
            $this->nm_idioma,
            null,
            null,
            null,
            null,
            1,
            $this->ref_cod_biblioteca
        );

        $total = $obj_acervo_idioma->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $obj_biblioteca = new Biblioteca($registro['ref_cod_biblioteca']);
                $det_biblioteca = $obj_biblioteca->detalhe();
                $registro['ref_cod_biblioteca'] = $det_biblioteca['nm_biblioteca'];
                $this->addLinhas([
                    "<a href=\"educar_acervo_idioma_det.php?cod_acervo_idioma={$registro['cod_acervo_idioma']}\">{$registro['nm_idioma']}</a>",
                    "<a href=\"educar_acervo_idioma_det.php?cod_acervo_idioma={$registro['cod_acervo_idioma']}\">{$registro['ref_cod_biblioteca']}</a>"
                ]);
            }
        }
        $this->addPaginador2('educar_acervo_idioma_lst.php', $total, $_GET, $this->nome, $this->limite);
        $obj_permissoes = new Permissoes();
        if ($obj_permissoes->permissao_cadastra(590, $this->pessoa_logada, 11)) {
            $this->acao = 'go("educar_acervo_idioma_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->largura = '100%';

        $this->breadcrumb('Listagem de idiomas', [
            url('Intranet/educar_biblioteca_index.php') => 'Biblioteca',
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
