<?php

namespace Fw;

abstract class App {
    public function dispatch() {
        $req = Request::createInstance($_REQUEST, $_SERVER);
        $res = Response::createInstance();
        
        $this->route($req, $res);

    }

    abstract protected function route(Request $req, Response $res);

    protected function subPath($path) {
        return $this->basePath() .$path;
    }

    protected function basePath() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }
}