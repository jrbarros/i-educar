<?php

namespace iEducarLegacy\Lib\Core\Controller\Page;

use App\User;
use iEducar\Modules\Navigation\Breadcrumb;
use iEducarLegacy\Lib\CoreExt\Controller\CoreExtControllerAbstract;
use Illuminate\Support\Facades\Auth;

/**
 * Class CoreControllerPageAbstract
 */
abstract class CoreControllerPageAbstract extends CoreExtControllerAbstract implements CoreControllerPageInterface
{
    /**
     * Opções de configuração geral da classe.
     *
     * @var array
     */
    protected $_options = [
        'id_usuario' => null,
        'new_success' => 'index',
        'new_success_params' => [],
        'edit_success' => 'view',
        'edit_success_params' => [],
        'delete_success' => 'index',
        'delete_success_params' => [],
        'url_cancelar' => null,
    ];

    /**
     * Coleção de mensagens de erros retornados pelos validadores de
     * Entity.
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * Instância de CoreView
     *
     * @var CoreView
     */
    protected $_view = null;

    /**
     * Instância de DataMapper
     *
     * @var DataMapper
     */
    protected $_dataMapper = null;

    /**
     * Instância de Entity
     *
     * @var Entity
     */
    protected $_entity = null;

    /**
     * Identificador do número de processo para verificação de autorização.
     *
     * @see Base::verificaPermissao()
     *
     * @var int
     */
    protected $_processoAp = null;

    /**
     * Título a ser utilizado na barra de título.
     *
     * @var string
     */
    protected $_titulo = null;

    /**
     * Collection com labels para botões, inseridos no HTML via RenderHTML(). Marcado
     * como public para manter compatibilidade com as classes cls(Cadastro|Detalhe|
     * Listagem) que acessam o array diretamente.
     *
     * @var array|NULL
     */
    public $array_botao = null;

    /**
     * Collection com labels para botões, inseridos no HTML via RenderHTML(). Marcado
     * como public para manter compatibilidade com as classes cls(Cadastro|Detalhe|
     * Listagem) que acessam o array diretamente.
     *
     * @var array|NULL
     */
    public $array_botao_url = null;

    /**
     * @var string
     */
    public $url_cancelar = null;

    /**
     * @var array
     */
    private $_output = [];

    /**
     * @var integer
     */
    public $pessoa_logada = null;

    /**
     * @var string
     */
    public $locale = null;

    /**
     * Construtor.
     */
    public function __construct()
    {
        $this->_options['id_usuario'] = $this->getSession()->id_pessoa;
        $this->pessoa_logada = $this->getSession()->id_pessoa ?? null;
    }

    /**
     * Retorna o usuário autenticado.
     *
     * @return User
     */
    public function user()
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    /**
     * @see CoreExtConfigurable::setOptions($options)
     */
    public function setOptions(array $options = [])
    {
        $options = array_change_key_case($options, CASE_LOWER);

        if (isset($options['datamapper'])) {
            $this->setDataMapper($options['datamapper']);
            unset($options['datamapper']);
        }

        if (isset($options['processoap'])) {
            $this->setBaseProcessoAp($options['processoap']);
            unset($options['processoap']);
        }

        if (isset($options['titulo'])) {
            $this->setBaseTitulo($options['titulo']);
            unset($options['titulo']);
        }

        $defaultOptions = array_keys($this->getOptions());
        $passedOptions = array_keys($options);

        if (0 < count(array_diff($passedOptions, $defaultOptions))) {
            throw new CoreExt_Exception_InvalidArgumentException(
                sprintf('A classe %s não suporta as opções: %s.', get_class($this), implode(', ', $passedOptions))
            );
        }

        $this->_options = array_merge($this->getOptions(), $options);

        return $this;
    }

    /**
     * @see CoreExtConfigurable::getOptions()
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Setter.
     *
     * @param CoreExt_Controller|string $dataMapper
     *
     * @return CoreControllerPageInterface Provê interface fluída
     *
     * @throws CoreControllerPageException|CoreExt_Exception_InvalidArgumentException
     */
    public function setDataMapper($dataMapper)
    {
        if (is_string($dataMapper)) {
            if (class_exists($dataMapper)) {
                $this->_dataMapper = new $dataMapper();
            } else {
                throw new CoreControllerPageException('A classe "' . $dataMapper . '" não existe.');
            }
        } elseif ($dataMapper instanceof CoreExt_DataMapper) {
            $this->_dataMapper = $dataMapper;
        } else {
            throw new CoreExt_Exception_InvalidArgumentException('Argumento inválido. São aceitos apenas argumentos do tipo string e DataMapper');
        }

        return $this;
    }

    /**
     * Getter.
     *
     * Facilita a subclassificação ao permitir herança tanto via configuração do
     * atributo $_dataMapper ou da sobrescrição de setDataMapper().
     *
     * @see CoreControllerPageInterface::getDataMapper()
     */
    public function getDataMapper()
    {
        if (is_string($this->_dataMapper)) {
            $this->setDataMapper($this->_dataMapper);
        } elseif (is_null($this->_dataMapper)) {
            throw new CoreControllerPageException('É necessário especificar um nome de classe para a propriedade "$_dataMapper" ou sobrescrever o método "getDataMapper()".');
        }

        return $this->_dataMapper;
    }

    /**
     * Setter.
     *
     * @param CoreExt_Entity $entity
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    public function setEntity(CoreExt_Entity $entity)
    {
        $this->_entity = $entity;

        return $this;
    }

    /**
     * Getter.
     *
     * Se nenhuma instância Entity existir, tenta instanciar uma através
     * de DataMapper.
     *
     * @return Entity|NULL
     *
     * @throws CoreControllerPageException
     */
    public function getEntity()
    {
        if (is_null($this->_entity)) {
            $this->setEntity($this->getDataMapper()->createNewEntityInstance());
        }

        return $this->_entity;
    }

    /**
     * @see Entity::hasError($key)
     */
    public function hasError($key)
    {
        return $this->getEntity()->hasError($key);
    }

    /**
     * @see Entity::hasErrors()
     */
    public function hasErrors()
    {
        return $this->getEntity()->hasErrors();
    }

    /**
     * @see Entity::getError($key)
     */
    public function getError($key)
    {
        return $this->getEntity()->getError($key);
    }

    /**
     * @see Entity::getErrors()
     */
    public function getErrors()
    {
        return $this->getEntity()->getErrors();
    }

    /**
     * Setter.
     *
     * @param int $processoAp
     *
     * @return CoreControllerPageAbstract
     */
    public function setBaseProcessoAp($processoAp)
    {
        $this->_processoAp = (int) $processoAp;

        return $this;
    }

    /**
     * Getter.
     *
     * Facilita a subclassificação ao permitir herança tanto via configuração do
     * atributo $_processoAp ou da sobrescrição de setBaseProcessoAp().
     *
     * @return int
     *
     * @throws CoreControllerPageException
     *@see CoreControllerPageInterface::getBaseProcessoAp()
     *
     */
    public function getBaseProcessoAp()
    {
        if (is_null($this->_processoAp)) {
            throw new CoreControllerPageException('É necessário especificar um valor numérico para a propriedade "$_processoAp" ou sobrescrever o método "getBaseProcessoAp()".');
        }

        return $this->_processoAp;
    }

    /**
     * Setter.
     *
     * @see CoreControllerPageInterface::setBaseTitulo($titulo)
     */
    public function setBaseTitulo($titulo)
    {
        $this->_titulo = (string) $titulo;

        return $this;
    }

    /**
     * Getter.
     *
     * Facilita a subclassificação ao permitir herança tanto via configuração do
     * atributo $_titulo ou da sobrescrição de setBaseTitulo().
     *
     * @return string
     *
     * @throws CoreControllerPageException
     *@see CoreControllerPageInterface::getBaseTitulo()
     *
     */
    public function getBaseTitulo()
    {
        if (is_null($this->_titulo)) {
            throw new CoreControllerPageException('É necessário especificar uma string para a propriedade "$_titulo" ou sobrescrever o método "getBaseTitulo()".');
        }

        return $this->_titulo;
    }

    /**
     * Adiciona uma entrada nos arrays de botões (renderizado por RenderHTML(),
     * nas classes cls(Cadastro|Detalhe|Listagem)).
     *
     * @param string $label
     * @param string $url
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    public function addBotao($label, $url)
    {
        $this->array_botao[] = $label;
        $this->array_botao_url[] = $url;

        return $this;
    }

    /**
     * Configura botões padrão de Cadastro
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    public function configurarBotoes()
    {
        // Botão Cancelar (clsDetalhe e Cadastro)
        if ($this->_hasOption('url_cancelar')) {
            $config = $this->getOption('url_cancelar');

            if (is_string($config)) {
                $this->url_cancelar = $config;
            } elseif (is_array($config)) {
                $this->url_cancelar = CoreExt_View_Helper_UrlHelper::url(
                    $config['path'],
                    $config['options']
                );
            }
        }

        return $this;
    }

    /**
     * Hook de pré-execução do método RenderHTML().
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    protected function _preRender()
    {
        return $this->configurarBotoes();
    }

    /**
     * Adiciona conteúdo HTML após o conteúdo gerado por um
     * CoreControllerPageAbstract.
     *
     * @param string $data A string HTML a ser adiciona após o conteúdo.
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    public function appendOutput($data)
    {
        if (!empty($data) && is_string($data)) {
            $this->_output['append'][] = $data;
        }

        return $this;
    }

    /**
     * Retorna todo o conteúdo acrescentado como uma string.
     *
     * @return string O conteúdo a ser acrescentado separado com uma quebra de linha.
     *
     * @see Base::MakeBody()
     */
    public function getAppendedOutput()
    {
        return $this->_getOutput('append');
    }

    /**
     * Adiciona conteúdo HTML antes do conteúdo HTML gerado por um
     * CoreControllerPageAbstract.
     *
     * @param string $data A string HTML a ser adiciona após o conteúdo.
     *
     * @return CoreControllerPageAbstract Provê interface fluída
     */
    public function prependOutput($data)
    {
        if (!empty($data) && is_string($data)) {
            $this->_output['prepend'][] = $data;
        }

        return $this;
    }

    /**
     * Retorna todo o conteúdo prefixado como uma string.
     *
     * @return string O conteúdo a ser prefixado separado com uma quebra de linha.
     *
     * @see Base::MakeBody()
     */
    public function getPrependedOutput()
    {
        return $this->_getOutput('prepend');
    }

    /**
     * Retorna o conteúdo a ser adicionado a saída de acordo com a região.
     *
     * @param string $pos Região para retornar o conteúdo a ser adicionado na saída.
     *
     * @return string|NULL Conteúdo da região separado por uma quebra de linha ou
     *                     NULL caso a região não exista.
     */
    private function _getOutput($pos = 'prepend')
    {
        if (isset($this->_output[$pos])) {
            return implode(PHP_EOL, $this->_output[$pos]);
        }

        return null;
    }

    /**
     * @see CoreExtControllerInterface::dispatch()
     */
    public function dispatch()
    {
        return $this;
    }

    /**
     * @see CoreControllerPageInterface::generate($instance)
     */
    public function generate(CoreExt_Controller_Page_Interface $instance)
    {
        require_once 'Core/CoreView.php';
        Core_View::generate($instance);
    }

    public function getQueryString($name, $default = null)
    {
        if (!isset($_GET[$name])) {
            return $default;
        }

        switch ($_GET[$name]) {
            case '':
            case null:
                $value = $default;
                break;

            default:
                $value = $_GET[$name];
        }

        return $value;
    }

    public function breadcrumb($currentPage, $breadcrumbs = [])
    {
        $breadcrumb = new Breadcrumb();
        $breadcrumb->makeBreadcrumb($currentPage, $breadcrumbs);
    }
}
