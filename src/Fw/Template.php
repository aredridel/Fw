<?php

namespace Fw;

abstract class Template {
    protected $app, $req, $res;

    public function __construct(Handler $app, Request $req, Response $res) {
        $this->app = $app;
        $this->req = $req;
        $this->res = $res;
    }

    abstract public function render($templateName);
}
