<?php

namespace src\app\route;

use \src\app\route\RouterCore;

class Router extends RouterCore
{
    public static function get(string $route, $callback)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $analyzeRoute = self::analyzeRoute($route);
            if ($analyzeRoute['convertedURI'] === $analyzeRoute['convertedRoute']) {
                if (is_callable($callback)) {
                    $callback();
                } else {
                    self::executeController($callback, $analyzeRoute['args']);
                }
            }
        }
    }
}
