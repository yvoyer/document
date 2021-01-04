<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\App;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class responsible to create a request object pre-configured with the correct data for a uri.
 */
abstract class RequestFactory {
    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_method;

    /**
     * @param string $url
     * @param string $method
     */
    final private function __construct(string $url, string $method) {
        $this->_url = $url;
        $this->_method = $method;
    }

    final public function createRequest(
        array $parameters = [],
        string $content = '',
        array $cookies = [],
        array $files = [],
        array $server = []
    ): Request {
        return Request::create($this->_url, $this->_method, $parameters, $cookies, $files, $server, $content);
    }

    final public function createAjaxRequest(
        array $parameters = [],
        string $content = '',
        array $cookies = [],
        array $files = [],
        array $server = []
    ): Request {
        $request = Request::create($this->_url, $this->_method, $parameters, $cookies, $files, $server, $content);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        return $request;
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function get(string $url): RequestFactory {
        return new static($url, Request::METHOD_GET);
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function options(string $url): RequestFactory {
        return new static($url, Request::METHOD_OPTIONS);
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function post(string $url): RequestFactory {
        return new static($url, Request::METHOD_POST);
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function put(string $url): RequestFactory {
        return new static($url, Request::METHOD_PUT);
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function delete(string $url): RequestFactory {
        return new static($url, Request::METHOD_DELETE);
    }

    /**
     * @param string $url
     *
     * @return RequestFactory
     */
    protected static function patch(string $url): RequestFactory {
        return new static($url, Request::METHOD_PATCH);
    }
}
