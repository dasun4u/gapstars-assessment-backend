<?php
/**
 * Created by PhpStorm
 * User: Dasun Dissanayake
 * Date: 2021-11-27
 * Time: 6:39 PM
 */

class Router
{
    private $request;
    private $supportedHttpMethods = ["GET", "POST"];

    function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            return $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param $route
     * @return mixed|string
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }

        // Remove GET params
        if (strpos($result, '?') !== false) {
            $result = explode("?", $result)[0];
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        $message = "{$this->request->serverProtocol} 405 Method Not Allowed";
        header($message);
        return apiResponse("error", $message, null, 405);
    }

    private function defaultRequestHandler()
    {
        $message = "{$this->request->serverProtocol} 404 Not Found";
        header($message);
        return apiResponse("error", $message, null, 404);
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        if (isset($this->{strtolower($this->request->requestMethod)})) {
            $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        } else {
            return $this->invalidMethodHandler();
        }
        $formattedRoute = $this->formatRoute($this->request->requestUri);

        if (!isset($methodDictionary[$formattedRoute])) {
            return $this->defaultRequestHandler();
        }

        $method = $methodDictionary[$formattedRoute];

        echo call_user_func_array($method, [$this->request]);
    }

    function __destruct()
    {
        return $this->resolve();
    }
}
