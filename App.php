<?php

namespace Fw;

class App extends Handler {
    private $stack;

    public function apply(Handler $middleware) {
        $this->stack[] = $middleware;
    }

    public function run() {
        $req = Request::createInstance($_REQUEST, $_SERVER);
        $res = Response::createInstance();

        $this($req, $res, function($req, $res, $next) {
            $res->setStatusCode("404 Not Found");
            $res->setHeader("Content-Type", "text/plain");
            $res->write("Not Found");
        });
    }

    public function __invoke(Request $req, Response $res, $next) {
        $stack =& $this->stack;

        $nextHandler = function() use ($req, $res, &$stack, &$nextHandler, &$next) {
            $current = array_shift($stack);
            if ($current) {
                $current($req, $res, $nextHandler);
            } else {
                if ($next) {
                    $next($req, $res, null); 
                }
            }
        };

        $nextHandler();
        $res->send();
        return $res;
    }
}
