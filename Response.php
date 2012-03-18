<?php

namespace Fw;

class Response {
    public static function createInstance() {
        return new static();
    }

    public function redirect($url) {
        header("Location: $url");
    }
}
