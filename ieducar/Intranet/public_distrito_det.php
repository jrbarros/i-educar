<?php

use App\Models\District;
use iEducar\Legacy\InteractWithDatabase;
use iEducar\Legacy\SelectOptions;

require_once 'Source/Base.php';
require_once 'Source/Detalhe.php';
require_once 'Source/Banco.php';
require_once 'Source/public/geral.inc.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' Distrito');
        $this->processoAp = 759;
    }
}

class indice extends clsDetalhe
{
    use InteractWithDatabase, SelectOptions;

    public $idmun;
    public $geom;
    public $iddis;
    public $nome;

    public function model()
    {
        return District::class;
    }

    public function index()
    {
        return 'public_distrito_lst.php';
    }

    public function Gerar()
    {
        $this->titulo = 'Distrito - Detalhe';
        $this->iddis = $_GET['iddis'];

        $district = $this->find($this->iddis);

        if ($district->name) {
            $this->addDetalhe(['Nome', $district->name]);
        }

        if ($district->city->name) {
            $this->addDetalhe(['Município', $district->city->name]);
        }

        if ($district->city->state->name) {
            $this->addDetalhe(['Estado', $district->city->state->name]);
        }

        if ($district->city->state->country->name) {
            $this->addDetalhe(['Pais', $district->city->state->country->name]);
        }

        if ($district->ibge_code) {
            $this->addDetalhe(['Código INEP', $district->ibge_code]);
        }

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(759, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = 'public_distrito_cad.php';
            $this->url_editar = 'public_distrito_cad.php?iddis=' . $this->iddis;
        }

        $this->url_cancelar = 'public_distrito_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe do distrito', [
            url('Intranet/educar_enderecamento_index.php') => 'Endereçamento',
        ]);
    }
}

$pagina = new clsIndexBase();
$miolo = new indice();

$pagina->addForm($miolo);
$pagina->MakeAll();
