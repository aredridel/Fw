<?php

namespace Fw;

abstract class Middleware {

    abstract public function __invoke(Request $req, Response $res, $next);

    static private $stack;

    static public function apply(Middleware $middleware) {
        self::$stack[] = $middleware;
    }

    static public function run() {
        $req = Request::createInstance($_REQUEST, $_SERVER);
        $res = Response::createInstance();
        
        $stack =& self::$stack;

        $next = function() use ($req, $res, &$stack, &$next) {
            $current = array_shift($stack);
            if ($current) {
                $current($req, $res, $next);
            } else {
                $res->setStatusCode("404 Not Found");
                $res->setHeader("Content-Type", "text/plain");
                $res->write("Not Found");
            }
        };

        $next();
        $res->send();
        return $res;
    }

    public function subPath($path) {
        return $this->basePath() .$path;
    }

    protected function basePath() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }

}
