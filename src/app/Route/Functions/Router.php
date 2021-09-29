<?php

namespace src\app\route\functions;

use \src\app\route\functions\RouterCore;

class Router extends RouterCore
{
    public static function get(string $route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $analyzeRoute = self::analyzeRoute($route);
            if ($analyzeRoute['convertedURI'] === $analyzeRoute['convertedRoute']) {
                self::$findedRoute = true;
                if (is_callable($callback)) {
                    $callback();
                } else {
                    self::executeController($callback, $analyzeRoute['args']);
                }
            }
        }
    }

    public static function post(string $route, $callback){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            return [
                'hello' => 'world'
            ];
        }
    }
}
