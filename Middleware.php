<?php

namespace Fw;

abstract class Middleware {

    abstract public function __invoke(Request $req, Response $res, $next = null);

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
            if ($current) {
                return $current($req, $res, $next);
            } else {
                return '';
                //FIXME 404
            }
        };

        return $next($req, $res);

    }

    protected function subPath($path) {
        return $this->basePath() .$path;
    }

    protected function basePath() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }

}
