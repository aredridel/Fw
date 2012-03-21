<?php

namespace Fw;

abstract class Middleware {

    abstract public function __invoke($req, $res, $next = null);

    static private $stack;

    static public function apply(Middleware $middleware) {
        self::$stack[] = $middleware;
    }

    static public function run() {
        $req = Request::createInstance($_REQUEST, $_SERVER);
        $res = Response::createInstance();
        
        $stack =& self::$stack;

        $next = function($req, $res) use (&$stack, &$next) {
            $current = array_shift($stack);
            return $current($req, $res, $next);
        };

        return $next($req, $res);

    }

}
