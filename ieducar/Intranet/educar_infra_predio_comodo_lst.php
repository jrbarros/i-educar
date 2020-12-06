<?php

require_once('include/Base.php');
require_once('include/clsListagem.inc.php');
require_once('include/Banco.inc.php');
require_once('include/pmieducar/geral.inc.php');

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Ambientes ");
        $this->processoAp = '574';
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

    public $cod_infra_predio_comodo;
    public $ref_usuario_exc;
    public $ref_usuario_cad;
    public $ref_cod_infra_comodo_funcao;
    public $ref_cod_infra_predio;
    public $nm_comodo;
    public $desc_comodo;
    public $area;
    public $data_cadastro;
    public $data_exclusao;
    public $ativo;

    public $ref_cod_escola;
    public $ref_cod_instituicao;

    public function Gerar()
    {
        $this->titulo = 'Ambientes - Listagem';

        foreach ($_GET as $var => $val) { // passa todos os valores obtidos no GET para atributos do objeto
            $this->$var = ($val === '') ? null: $val;
        }

        $lista_busca = [
                    'Ambiente',
                    'Tipo de ambiente',
                    'Pr&eacute;dio',
                    'Escola',
                    'Instituição'
        ];

        $obj_permissao = new Permissoes();
        $nivel_usuario = $obj_permissao->nivel_acesso($this->pessoa_logada);

        $this->addCabecalhos($lista_busca);

        $get_escola = true;
        $this->inputsHelper()->dynamic(['instituicao','escola'], ['required' => false]);

        // Filtros de Foreign Keys
        $opcoes = [ '' => 'Selecione' ];

        // EDITAR
        if ($this->ref_cod_escola) {
            $objTemp = new InfraComodoFuncao();
            $lista = $objTemp->lista(null, null, null, null, null, null, null, null, null, 1, $this->ref_cod_escola);
            if (is_array($lista) && count($lista)) {
                foreach ($lista as $registro) {
                    $opcoes["{$registro['cod_infra_comodo_funcao']}"] = "{$registro['nm_funcao']}";
                }
            }
        }

        $this->campoLista('ref_cod_infra_comodo_funcao', 'Tipo de ambiente', $opcoes, $this->ref_cod_infra_comodo_funcao, '', false, '', '', '', false);

        // outros Filtros
        $this->campoTexto('nm_comodo', 'Ambiente', $this->nm_comodo, 30, 255, false);

        // Paginador
        $this->limite = 20;
        $this->offset = ($_GET["pagina_{$this->nome}"]) ? $_GET["pagina_{$this->nome}"]*$this->limite-$this->limite: 0;

        $obj_infra_predio_comodo = new InfraPredioComodo();

        if (App_Model_IedFinder::usuarioNivelBibliotecaEscolar($this->pessoa_logada)) {
            $obj_infra_predio_comodo->codUsuario = $this->pessoa_logada;
        }

        $obj_infra_predio_comodo->setOrderby('nm_comodo ASC');
        $obj_infra_predio_comodo->setLimite($this->limite, $this->offset);
        $lista = $obj_infra_predio_comodo->lista(
            null,
            null,
            null,
            $this->ref_cod_infra_comodo_funcao,
            $this->ref_cod_infra_predio,
            $this->nm_comodo,
            null,
            null,
            null,
            null,
            null,
            null,
            1,
            $this->ref_cod_escola,
            $this->ref_cod_instituicao
        );

        $total = $obj_infra_predio_comodo->_total;

        // monta a lista
        if (is_array($lista) && count($lista)) {
            foreach ($lista as $registro) {
                $obj_ref_cod_infra_comodo_funcao = new InfraComodoFuncao($registro['ref_cod_infra_comodo_funcao']);
                $det_ref_cod_infra_comodo_funcao = $obj_ref_cod_infra_comodo_funcao->detalhe();
                $registro['ref_cod_infra_comodo_funcao'] = $det_ref_cod_infra_comodo_funcao['nm_funcao'];

                $obj_ref_cod_infra_predio = new InfraPredio($registro['ref_cod_infra_predio']);
                $det_ref_cod_infra_predio = $obj_ref_cod_infra_predio->detalhe();
                $registro['ref_cod_infra_predio'] = $det_ref_cod_infra_predio['nm_predio'];

                $obj_ref_cod_escola = new Escola($registro['ref_cod_escola']);
                $det_ref_cod_escola = $obj_ref_cod_escola->detalhe();
                $nm_escola = $det_ref_cod_escola['nome'];

                $obj_ref_cod_instituicao = new Instituicao($registro['ref_cod_instituicao']);
                $det_ref_cod_instituicao = $obj_ref_cod_instituicao->detalhe();
                $registro['ref_cod_instituicao'] = $det_ref_cod_instituicao['nm_instituicao'];

                $lista_busca = [
                    "<a href=\"educar_infra_predio_comodo_det.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}\">{$registro['nm_comodo']}</a>",
                    "<a href=\"educar_infra_predio_comodo_det.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}\">{$registro['ref_cod_infra_comodo_funcao']}</a>",
                    "<a href=\"educar_infra_predio_comodo_det.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}\">{$registro['ref_cod_infra_predio']}</a>",

                    "<a href=\"educar_infra_predio_comodo_det.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}\">{$nm_escola}</a>",
                    "<a href=\"educar_infra_predio_comodo_det.php?cod_infra_predio_comodo={$registro['cod_infra_predio_comodo']}\">{$registro['ref_cod_instituicao']}</a>"
                ];

                $this->addLinhas($lista_busca);
            }
        }

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(574, $this->pessoa_logada, 7, null, true)) {
            $this->acao = 'go("educar_infra_predio_comodo_cad.php")';
            $this->nome_acao = 'Novo';
        }

        $this->addPaginador2('educar_infra_predio_comodo_lst.php', $total, $_GET, $this->nome, $this->limite);
        $this->largura = '100%';

        $this->breadcrumb('Infraestrutura da escola', [
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
?>
<script>

function getInfraPredioFuncao(xml_infra_comodo_funcao)
{
    /*
    var campoEscola  = document.getElementById('ref_cod_escola').value;
    var campoFuncao = document.getElementById('ref_cod_infra_comodo_funcao');

    campoFuncao.length = 1;
    campoFuncao.options[0] = new Option( 'Selecione', '', false, false );
    for (var j = 0; j < funcao.length; j++)
    {
        if (funcao[j][2] == campoEscola)
        {
            campoFuncao.options[campoFuncao.options.length] = new Option( funcao[j][1], funcao[j][0],false,false);
        }
    }
    */
    var campoFuncao = document.getElementById('ref_cod_infra_comodo_funcao');
    var DOM_array = xml_infra_comodo_funcao.getElementsByTagName( "infra_comodo_funcao" );

    if(DOM_array.length)
    {
        campoFuncao.length = 1;
        campoFuncao.options[0].text = 'Selecione uma função';
        campoFuncao.disabled = false;

        for( var i = 0; i < DOM_array.length; i++ )
        {
            campoFuncao.options[campoFuncao.options.length] = new Option( DOM_array[i].firstChild.data, DOM_array[i].getAttribute("cod_infra_comodo_funcao"),false,false);
        }
    }
    else
        campoFuncao.options[0].text = 'A escola não possui nenhuma função';
}

document.getElementById('ref_cod_escola').onchange = function()
{
    /*
    getFuncao();
    */
    var campoEscola  = document.getElementById('ref_cod_escola').value;

    var campoFuncao = document.getElementById('ref_cod_infra_comodo_funcao');
    campoFuncao.length = 1;
    campoFuncao.disabled = true;
    campoFuncao.options[0].text = 'Carregando função';

    var xml_infra_comodo_funcao = new ajax( getInfraPredioFuncao );
    xml_infra_comodo_funcao.envia( "educar_infra_comodo_funcao_xml.php?esc="+campoEscola );
}

before_getEscola = function()
{
    /*var campoPredio   = document.getElementById('ref_cod_infra_predio');
    campoPredio.length = 1;
    campoPredio.options[0].text = 'Selecione';
    campoPredio.disabled = false;*/

    var campoFuncao = document.getElementById('ref_cod_infra_comodo_funcao');
    campoFuncao.length = 1;
    campoFuncao.options[0].text = 'Selecione';
    campoFuncao.disabled = false;
}

</script>
