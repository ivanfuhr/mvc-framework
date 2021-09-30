<?php

namespace src\app\util;

class Loader
{
    public static function load(string $view, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader('../app/View/');
        $twig = new \Twig\Environment($loader);
        $pathFile = 'Pages/' . $view . '.html';
        $twig->addGlobal('BASE', BASE);
        $render = $twig->render($pathFile, $params);
        echo $render;
    }
}
