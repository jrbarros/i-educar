<?php

use App\Models\State;
use iEducar\Legacy\InteractWithDatabase;

require_once 'Source/Base.php';
require_once 'Source/Detalhe.php';
require_once 'Source/Banco.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo("{$this->_instituicao} Uf");
        $this->processoAp = 754;
    }
}

class indice extends clsDetalhe
{
    use InteractWithDatabase;

    public $id;
    public $sigla_uf;
    public $nome;
    public $cod_ibge;
    public $idpais;

    public function model()
    {
        return State::class;
    }

    public function index()
    {
        return 'public_uf_lst.php';
    }

    public function Gerar()
    {
        $this->titulo = 'Uf - Detalhe';

        $this->id = $_GET['id'];

        $model = $this->find($this->id);

        $this->nome = $model->name;
        $this->sigla_uf = $model->abbreviation;
        $this->idpais = $model->country_id;
        $this->cod_ibge = $model->ibge_code;

        if ($this->sigla_uf) {
            $this->addDetalhe(['Sigla Uf', $this->sigla_uf]);
        }
        if ($this->nome) {
            $this->addDetalhe(['Nome', $this->nome]);
        }
        if ($this->idpais) {
            $this->addDetalhe(['Pais', $model->country->name]);
        }
        if ($this->cod_ibge) {
            $this->addDetalhe(['Código INEP', $this->cod_ibge]);
        }

        $obj_permissao = new Permissoes();

        if ($obj_permissao->permissao_cadastra(754, $this->pessoa_logada, 7, null, true)) {
            $this->url_novo = 'public_uf_cad.php';
            $this->url_editar = "public_uf_cad.php?id={$this->id}";
        }

        $this->url_cancelar = 'public_uf_lst.php';
        $this->largura = '100%';

        $this->breadcrumb('Detalhe da UF', [
            url('Intranet/educar_enderecamento_index.php') => 'Endereçamento',
        ]);
    }
}

$pagina = new clsIndexBase();
$miolo = new indice();

$pagina->addForm($miolo);
$pagina->MakeAll();
