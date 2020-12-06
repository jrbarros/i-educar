<?php

use App\Models\Country;
use iEducar\Legacy\InteractWithDatabase;

require_once 'include/Base.php';
require_once 'include/clsDetalhe.inc.php';
require_once 'include/Banco.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Pais");
        $this->processoAp = 753;
    }
}

class indice extends clsDetalhe
{
    use InteractWithDatabase;

    public $idpais;
    public $nome;
    public $geom;

    public function model()
    {
        return Country::class;
    }

    public function index()
    {
        return 'public_pais_lst.php';
    }

    public function Gerar()
    {
        $this->titulo = 'Pais - Detalhe';

        $this->idpais = $_GET['idpais'];

        $country = $this->find($this->idpais);

        if ($country->name) {
            $this->addDetalhe(['Nome', "{$country->name}"]);
        }

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(753, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = 'public_pais_cad.php';
            $this->url_editar = "public_pais_cad.php?idpais={$country->id}";
        }

        $this->url_cancelar = 'public_pais_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do país', [
            url('Intranet/educar_enderecamento_index.php') => 'Endereçamento',
        ]);
    }
}

$pagina = new clsIndexBase();
$miolo = new indice();

$pagina->addForm($miolo);
$pagina->MakeAll();
