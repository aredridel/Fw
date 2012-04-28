<?php

namespace Fw\Extras;
use Fw\Request;
use Fw\Response;
use Fw\Handler;

abstract class LoginHandler extends Handler {

    public function __invoke(Request $req, Response $res, $next) {
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
            try {
                $result = $next();
                return $result;
            } catch(\Fw\ConditionNotSatisfiedError $e) {
                if ($e->property == 'authenticated') {
                    // FIXME -- using subPath feels hacky
                    $res->redirect($this->subPath('/login'));
                } else {
                    throw $e;
                }
            }
        }
    }

    abstract public function authenticate(Request $req, Response $res);
}
