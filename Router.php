<?php

namespace Fw;

class Router extends Middleware {
    protected $routes;

    public function __construct(array $routes) {
        $this->routes = $routes;
    }

    public function __invoke(Request $req, Response $res, $next) {
        foreach ($this->routes as $match => $route) {
            if (preg_match($match, $req->url, $matches)) {
                $req['route'][$route->name ? $route->name : get_class($route)] = $matches;
                return $route($req, $res, $next);
            } else {
                return $next();
            }
        }
    }
}
