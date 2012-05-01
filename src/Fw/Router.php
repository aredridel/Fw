<?php

namespace Fw;

class Router extends Handler {
    protected $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function __invoke(Request $req, Response $res, $next) {
        if (!is_array($req['route'])) $req['route'] = array();
        foreach ($this->routes as $match => $route) {
            if (preg_match($match, $req->url, $matches)) {
                foreach ($matches as $key => $value) {
                    $req[$key] = $value;
                }
                return $route($req, $res, $next);
            }
        }
        if ($next) return $next();
    }
}
