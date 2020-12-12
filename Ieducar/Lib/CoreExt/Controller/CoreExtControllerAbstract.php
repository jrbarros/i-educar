<?php

namespace iEducarLegacy\Lib\CoreExt\Controller;

use iEducarLegacy\Lib\CoreExt\Session;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;

/**
 * Class CoreExtControllerAbstract
 * @package iEducarLegacy\Lib\CoreExt\Controller
 */
abstract class CoreExtControllerAbstract implements CoreExtControllerInterface
{
    /**
     * Uma instância de RequestInterface
     *
     * @var RequestInterface
     */
    protected $_request = null;

    /**
     * Uma instância de Session
     *
     * @var Session
     */
    protected $_session = null;

    /**
     * Uma instância de DispatcherInterface
     *
     * @var DispatcherInterface
     */
    protected $_dispatcher = null;

    /**
     * @see CoreExtConfigurable#setOptions($options)
     */
    public function setOptions(array $options = [])
    {
        $defaultOptions = array_keys($this->getOptions());
        $passedOptions = array_keys($options);

        if (0 < count(array_diff($passedOptions, $defaultOptions))) {
            throw new InvalidArgumentException(
                sprintf('A classe %s não suporta as opções: %s.', get_class($this), implode(', ', $passedOptions))
            );
        }

        $this->_options = array_merge($this->getOptions(), $options);

        return $this;
    }

    /**
     * @see CoreExtConfigurable#getOptions()
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Verifica se uma opção está setada.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function _hasOption($key)
    {
        return array_key_exists($key, $this->_options);
    }

    /**
     * Retorna um valor de opção de configuração ou NULL caso a opção não esteja
     * setada.
     *
     * @param string $key
     *
     * @return mixed|NULL
     */
    public function getOption($key)
    {
        return $this->_hasOption($key) ? $this->_options[$key] : null;
    }

    /**
     * Setter.
     *
     * @param CoreExt_Controller_Request_Interface $request
     *
     * @return CoreExt_Controller_Interface
     */
    public function setRequest(CoreExt_Controller_Request_Interface $request)
    {
        $this->_request = $request;

        return $this;
    }

    /**
     * Getter para uma instância de RequestInterface.
     *
     * Instância via lazy initialization uma instância de
     * RequestInterface caso nenhuma seja explicitamente
     * atribuída a instância atual.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        if (is_null($this->_request)) {
            require_once 'CoreExt/Controller/PageRequest.php';
            $this->setRequest(new Request());
        }

        return $this->_request;
    }

    /**
     * Setter.
     *
     * @param CoreExt_Session_Abstract $session
     *
     * @return CoreExt_Controller_Interface
     */
    public function setSession(CoreExt_Session_Abstract $session)
    {
        $this->_session = $session;

        return $this;
    }

    /**
     * Getter para uma instância de Session.
     *
     * Instância via lazy initialization uma instância de Session caso
     * nenhuma seja explicitamente atribuída a instância atual.
     *
     * @return Session
     */
    public function getSession()
    {
        if (is_null($this->_session)) {
            require_once 'CoreExt/Session.php';
            $this->setSession(new CoreExt_Session());
        }

        return $this->_session;
    }

    /**
     * Setter.
     *
     * @param CoreExt_Controller_Dispatcher_Interface $dispatcher
     *
     * @return CoreExt_Controller_Interface Provê interface fluída
     */
    public function setDispatcher(CoreExt_Controller_Dispatcher_Interface $dispatcher)
    {
        $this->_dispatcher = $dispatcher;

        return $this;
    }

    /**
     * Getter.
     *
     * @return CoreExt_Controller_Dispatcher_Interface
     */
    public function getDispatcher()
    {
        if (is_null($this->_dispatcher)) {
            require_once 'CoreExt/Controller/Dispatcher/Standard.php';
            $this->setDispatcher(new CoreExt_Controller_Dispatcher_Standard());
        }

        return $this->_dispatcher;
    }

    /**
     * Redirect HTTP simples (espartaníssimo).
     *
     * Se a URL for relativa, prefixa o caminho com o baseurl configurado para
     * o objeto PageRequest.
     *
     * @param string $url
     *
     * @todo Implementar opções de configuração de código de status de
     *       redirecionamento. {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
     */
    public function redirect($url)
    {
        $parsed = parse_url($url, PHP_URL_HOST);

        if ('' == $parsed['host']) {
            $url = $this->getRequest()->getBaseurl() . '/' . $url;
        }

        $this->simpleRedirect($url);
    }

    /**
     * Função de redirecionamento simples que leva em consideração
     * o status code.
     *
     * @param string $url
     * @param int    $code
     *
     * @return void
     *
     * @throws HttpResponseException
     */
    public function simpleRedirect(string $url, int $code = 302)
    {
        $codes = [
            301 => 'HTTP/1.1 301 Moved Permanently',
            302 => 'HTTP/1.1 302 Found',
            303 => 'HTTP/1.1 303 See Other',
            304 => 'HTTP/1.1 304 Not Modified',
            305 => 'HTTP/1.1 305 Use Proxy'
        ];

        if (empty($codes[$code])) {
            $code = 302;
        }

        throw new HttpResponseException(
            new RedirectResponse($url, $code)
        );
    }

    /**
     * Faz redirecionamento caso condição seja válida e encerra aplicação
     *
     * @param bool   $condition
     * @param string $url
     *
     * @return void
     *
     * @throws HttpResponseException
     */
    public function redirectIf($condition, $url)
    {
        if ($condition) {
            throw new HttpResponseException(
                new RedirectResponse($url)
            );
        }
    }
}
