<?php

namespace Fw;

abstract class Handler {

    abstract public function __invoke(Request $req, Response $res, $next);

    public function subPath($path) {
        return $this->basePath() .$path;
    }

    protected function basePath() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }

}
