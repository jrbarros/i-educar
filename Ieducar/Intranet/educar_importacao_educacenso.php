<?php

ini_set('max_execution_time', 0);

require_once 'Source/Base.php';
require_once 'Source/Cadastro.inc.php';
require_once 'lib/Portabilis/Utils/Database.php';
require_once 'lib/Portabilis/Date/AppDateUtils.php';
require_once 'lib/Portabilis/DataMapper/AppDateUtils.php';
require_once 'Source/pmieducar/geral.inc.php';
require_once 'Source/modules/ProfessorTurma.php';

class clsIndexBase extends Base
{
    public function Formular()
    {
        $this->SetTitulo($this->_instituicao . ' i-Educar - Importação educacenso');
        $this->processoAp = 9998849;
    }
}

class indice extends clsCadastro
{
    public $pessoa_logada;

    public $arquivo;

    public function Inicializar()
    {
        $obj_permissoes = new Permissoes();
        $obj_permissoes->permissao_cadastra(
            9998849,
            $this->pessoa_logada,
            7,
            'educar_index.php'
        );
        $this->ref_cod_instituicao = $obj_permissoes->getInstituicao($this->pessoa_logada);

        $this->breadcrumb('Importação educacenso', [
            url('Intranet/educar_educacenso_index.php') => 'Educacenso',
        ]);

        $this->titulo = 'Nova importação';

        return 'Editar';
    }

    public function Gerar()
    {
        $resources = [
            null => 'Selecione',
            '2019' => '2019',
            '2020' => '2020',
        ];
        $options = [
            'label' => 'Ano',
            'resources' => $resources,
            'value' => $this->ano,
        ];
        $this->inputsHelper()->select('ano', $options);

        $this->inputsHelper()->date(
            'data_entrada_matricula',
            [
                'label' => 'Data de entrada das matrículas',
                'required' => true,
                'placeholder' => 'dd/mm/yyyy'
            ]
        );

        $this->campoArquivo('arquivo', 'Arquivo', $this->arquivo, 40, '<br/> <span style="font-style: italic; font-size= 10px;">* Somente arquivos com formato txt serão aceitos</span>');

        $this->nome_url_sucesso = 'Importar';

        Application::loadJavascript($this, '/Modules/Educacenso/Assets/Javascripts/Importacao.js');
    }

    public function Novo()
    {
        return;
    }

    public function Editar()
    {
        return;
    }
}
// Instancia objeto de página
$pagina = new clsIndexBase();

// Instancia objeto de conteúdo
$miolo = new indice();

// Atribui o conteúdo à  página
$pagina->addForm($miolo);

// Gera o código HTML
$pagina->MakeAll();
