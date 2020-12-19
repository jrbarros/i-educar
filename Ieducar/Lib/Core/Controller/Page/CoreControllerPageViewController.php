<?php

namespace iEducarLegacy\Lib\Core\Controller\Page;

use iEducarLegacy\Intranet\Source\Detalhe;
use iEducarLegacy\Lib\Core\View\CoreViewTabulable;
use Illuminate\Support\Facades\Session;

class CoreControllerPageViewController extends Detalhe implements CoreViewTabulable
{
    /**
     * Mapeia um nome descritivo a um atributo de Entity retornado pela
     * instância DataMapper retornada por getDataMapper().
     *
     * Para uma instância de Entity que tenha os seguintes atributos:
     * <code>
     * <?php
     * $_data = array(
     *   'nome' => NULL
     *   'idade' => NULL,
     *   'data_validacao' => NULL
     * );
     * </code>
     *
     * O mapeamento poderia ser feito da seguinte forma:
     * <code>
     * <?php
     * $_tableMap = array(
     *   'Nome' => 'nome',
     *   'Idade (anos)' => 'idade'
     * );
     * </code>
     *
     * Se um atributo não for mapeado, ele não será exibido por padrão durante
     * a geração de HTML na execução do método Gerar().
     *
     * @var array
     */
    protected $_tableMap = [];

    /**
     * Construtor.
     */
    public function __construct()
    {
        $this->titulo = $this->getBaseTitulo();
        $this->largura = '100%';
    }

    /**
     * Getter.
     *
     * @see CoreViewTabulable#getTableMap()
     */
    public function getTableMap()
    {
        return $this->_tableMap;
    }

    /**
     * Configura a URL padrão para a ação de Edição de um registro.
     *
     * Por padrão, cria uma URL "edit/id", onde id é o valor do atributo "id"
     * de uma instância Entity.
     *
     * @param Entity $entry A instância atual recuperada
     *                              ViewController::Gerar().
     */
    public function setUrlEditar(CoreExt_Entity $entry)
    {
        if ($this->_hasPermissaoCadastra()) {
            $this->url_editar = CoreExt_View_Helper_UrlHelper::url(
                'edit',
                ['query' => ['id' => $entry->id]]
            );
        }
    }

    /**
     * Configura a URL padrão para a ação Cancelar da tela de Edição de um
     * registro.
     *
     * Por padrão, cria uma URL "index".
     *
     * @param CoreExt_Entity $entry A instância atual recuperada
     *                              ViewController::Gerar().
     */
    public function setUrlCancelar(CoreExt_Entity $entry)
    {
        $this->url_cancelar = CoreExt_View_Helper_UrlHelper::url('index');
    }

    /**
     * Getter.
     *
     * @return Permissoes
     */
    public function getClsPermissoes()
    {
        require_once 'Source/pmieducar/Permissoes.php';

        return new Permissoes();
    }

    /**
     * Verifica se o usuário possui privilégios de cadastro para o processo.
     *
     * @return bool|void Redireciona caso a opção 'nivel_acesso_insuficiente' seja
     *                   diferente de NULL.
     *
     * @throws CoreControllerPageException
     */
    protected function _hasPermissaoCadastra()
    {
        return $this->getClsPermissoes()->permissao_cadastra(
            $this->getBaseProcessoAp(),
            $this->getPessoaLogada(),
            7
        );
    }

    protected function getPessoaLogada()
    {
        return Session::get('id_pessoa');
    }

    public function Gerar()
    {
        $headers = $this->getTableMap();

        $this->titulo = $this->getBaseTitulo();
        $this->largura = '100%';

        try {
            $entry = $this->getEntry();
        } catch (Exception $e) {
            $this->mensagem = $e;

            return false;
        }

        foreach ($headers as $label => $attr) {
            $value = $entry->$attr;
            if (!is_null($value)) {
                $this->addDetalhe([$label, $value]);
            }
        }

        $this->setUrlEditar($entry);
        $this->setUrlCancelar($entry);
    }

    public function getEntry()
    {
        return $this->getDataMapper()->find($this->getRequest()->id);
    }
}
