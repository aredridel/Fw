<?php

namespace Fw;

abstract class App extends Middleware {

    protected function subPath($path) {
        return $this->basePath() .$path;
    }

    protected function basePath() {
        return dirname($_SERVER['SCRIPT_NAME']);
    }
}
