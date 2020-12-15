<?php

require_once('Source/Base.php');
require_once('Source/Cadastro.php');
require_once('Source/Banco.php');
require_once('Source/pmieducar/geral.inc.php');
require_once 'Source/modules/ItinerarioTransporteEscolar.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} i-Educar - Itinerário");
        $this->processoAp = '21238';
    }
}

class indice extends clsCadastro
{
    public $cod_rota;

    public function Inicializar()
    {
        $retorno = 'Novo';

        $this->cod_rota=$_GET['cod_rota'];

        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(578, $this->pessoa_logada, 7, "transporte_itinerario_cad.php?cod_rota={$this->cod_rota}");

        $obj  = new clsModulesItinerarioTransporteEscolar();
        $excluiu = $obj->excluirTodos($this->cod_rota);

        if ($excluiu) {
            echo "<script>
                window.location='transporte_rota_det.php?cod_rota={$this->cod_rota}';
                </script>";
        }

        die();

        return;
    }

    public function Gerar()
    {
    }

    public function Novo()
    {
    }

    public function Excluir()
    {
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
