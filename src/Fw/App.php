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

        if (!$r = $this($req, $res, null)) {
            $res->setStatusCode("404 Not Found");
            $res->setHeader("Content-Type", "text/plain");
            $res->write("Not Found");
        }

        $res->send();
    }

    public function __invoke(Request $req, Response $res, $last) {
        $stack =& $this->stack;

        $do = function(Request $req, Response $res, $next) use (&$stack) {
            $current = array_shift($stack);
            if ($current and $done = $current($req, $res, $next)) {
                print_r($done);
                return $done;
            } elseif ($last) {
                return $last();
            } else {
                return null;
            }
        };

        $next = function() use ($do, $next, $req, $res) {
            return $do($req, $res, $next);
        };

        $do($req, $res, $next);

        return $res;
    }
}
