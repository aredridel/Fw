<?php

namespace Fw;

abstract class Page {
    protected $app, $req, $res;

    public function __construct(Middleware $app, Request $req, Response $res) {
        $this->app = $app;
        $this->req = $req;
        $this->res = $res;
    }

    abstract public function render(array $params);
}
