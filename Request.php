<?php

namespace Fw;

class Request implements \ArrayAccess {
    private $params;
    private $properties = array();
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
        } elseif ($property == 'params') {
            return $this->params;
        }

        throw new \LogicException("Unknown property '$property'");
    }

    public function offsetGet($key) {
        return $this->properties[$key];
    }

    public function offsetSet($key, $value) {
        $this->properties[$key] = $value;
    }

    public function offsetExists($key) {
        return array_key_exists($key, $this->properties);
    }

    public function offsetUnset($key) {
        unset($this->properties[$key]);
    }

    public function ensure($property, $value = NULL) {
        if (!$this[$property]) throw new ConditionNotSatisfiedError($property);
        if (!is_null($value) and $this[$property] != $value) throw new ConditionNotSatisfiedError($propert, $value);
    }
}
