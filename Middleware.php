<?php

namespace Fw;

abstract class Middleware {

    abstract public function __invoke($env, $next = null);

    static private $stack;

    static public function apply(Middleware $middleware) {
        self::$stack[] = $middleware;
    }

    static public function run() {
        $env = array('params' => $_REQUEST, 'server' => $_SERVER);

        $stack =& self::$stack;

        $next = function($env) use (&$stack, &$next) {
            $current = array_shift($stack);
            return $current($env, $next);
        };

        return $next($env);

    }

}
