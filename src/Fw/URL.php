<?php

namespace Fw;

class URL {
    private $protocol;
    private $host;
    private $path;
    private $port;

    private function __construct($protocol, $host, $port, $path) {
        $this->protocol = $protocol;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
    }

    public static function fromServerVars(array $vars) {
        return new static(
            isset($vars['HTTPS']) and $vars['HTTPS'] == 'on' ? 'https' : 'http',
            $vars['HTTP_HOST'],
            $vars['SERVER_PORT'],
            $vars['REQUEST_URI']);
        
    }

    public function __toString() {
        $displayPort = '';
        if ($this->port) {
            if (($this->protocol == 'http' and $this->port != 80) or
                ($this->protocol == 'https' and $this->port != 443)) {
                $displayPort = $this->port;
            }
        }
        return $this->protocol . "://" . $this->host . ($displayPort ? ":$displayPort" : '') . $this->path; // FIXME query string
    }

    public function __get($property) {
        if (!in_array($property, array('protocol', 'host', 'port', 'path'))) {
            throw new \LogicException("Unknown property '$property'");
        }
        
        return $this->$property;
    }

    private function couldBeUrl($str) {
        return preg_match('!^[^/]+//!', $str);
    }

    public function match($glob) {
        if (!$glob instanceof self and $this->couldBeUrl($glob)) {
            $glob = static::fromString($glob);
        }
        if ($glob instanceof self) {
            return $this->matchesUrl($glob);
        } else {
            return $this->matchesPath($glob);
        }
    }

    private function matchesUrl($other) {
        return 
            (!$other->protocol or fnmatch($other->protocol, $this->protocol))
            and (!$other->host or fnmatch($other->host, $this->host))
            and ($other->port == $this->port)
            and $this->matchesPath($other->path);
    }

    private function matchesPath($path) {
        return fnmatch($path, $this->path);
    }
}
