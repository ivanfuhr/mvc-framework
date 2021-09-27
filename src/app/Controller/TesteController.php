<?php

namespace src\app\controller;

use src\app\util\Loader;

class TesteController extends Loader
{
    public function teste($args){
        $this->load('home/main', $args);
    }
}