<?php

namespace Fw;

class Request {
    private $params;
    private $server;
    private $url;
    private $method;

    private function __construct($vars, $server) {
        $this->params = $vars;
        $this->server = $server;
        $this->method = $server['REQUEST_METHOD'];
        $this->url = URL::fromServerVars($server);
    }

    public static function createInstance(array $vars, array $servervars) {
        return new static($vars, $servervars);
    }

    public function __get($property) {
        if ($property == 'url') {
            return $this->url;
        } elseif ($property == 'method') {
            return $this->method;
        }

        throw new \LogicException("Unknown property '$property'");
    }
}
