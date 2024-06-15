<?php namespace Foreverexiled\PhpWebserver;

class Request
{
    protected $method = null;
    public function method()
    {
        return $this->method;
    }

    protected $uri = null;
    public function uri()
    {
        return $this->uri;
    }

    protected $parameters = [];
    public function param($key, $default = null)
    {
        if (isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }

        return $default;
    }

    protected $headers = [];
    public function header($key, $default = null)
    {
        if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }

        return $default;
    }

    public function __construct($method, $uri, $headers = []) {
        $this->headers = $headers;
        $this->method = strtoupper($method);

        @list($this->uri, $params) = explode('?', $uri);

        parse_str($params, $this->parameters);
    }

    public static function withHeaderString($header)
    {
        $lines = explode('\n', $header);

        list($method, $uri) = explode(' ', array_shift($lines));

        $headers = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }
}
