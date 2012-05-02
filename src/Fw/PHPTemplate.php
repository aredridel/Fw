<?php

namespace Fw;

use Fw\Template;

class PHPTemplate extends Template {
    public function __construct(Handler $app, Request $req, Response $res, $dir = NULL) {
        if ($dir) {
            $this->dir = $dir;
        } else {
            $ref = new \ReflectionObject($this);
            $this->dir = dirname($ref->getFileName());
        }
        parent::__construct($app, $req, $res);
    }

    public function render($templateName) {
        include "$this->dir/templates/$templateName.php";
    }
}
