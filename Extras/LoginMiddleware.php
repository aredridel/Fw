<?php

namespace Fw\Extras;
use Fw\Request;
use Fw\Response;
use Fw\Middleware;

abstract class LoginMiddleware extends Middleware {

    public function __invoke(Request $req, Response $res, $next = null) {
        session_start();
        if (isset($_SESSION['user'])) {
            $req['authenticated'] = true;
        } else {
            $req['authenticated'] = false;
        }
        if($req->url->match($this->subPath('/login')) and $req->method == 'POST') {
            if ($this->authenticate($req, $res)) {
                $req['authenticated'] = true;
            }
        } else {
            $next($req, $res);
        }
    }

    abstract public function authenticate(Request $req, Response $res);
}
