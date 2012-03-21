<?php

namespace Fw;

abstract class App extends Middleware {
    public function dispatch($env) {
        $req = Request::createInstance($env['params'], $env['server']);
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
