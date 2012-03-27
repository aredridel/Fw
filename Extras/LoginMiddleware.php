<?php

namespace Fw\Extras;
use Fw\Request;
use Fw\Response;
use Fw\Middleware;

class LoginMiddleware extends Middleware {

    public function __invoke(Request $req, Response $res, $next = null) {
        session_start();
        if (isset($_SESSION['user'])) {
            $req['authenticated'] = true;
        } else {
            $req['authenticated'] = false;
        }
        if($req->url->match($this->subPath('/login')) and $req->method == 'POST') {
            if ($req->params['username'] == 'a' and $req->params['password'] == 'test') {
                $_SESSION['user'] = $req->params['username'];
                $req['authenticated'] = true;
            }
            echo "Hi!";
        } else {
            $next($req, $res);
        }


    }
}
