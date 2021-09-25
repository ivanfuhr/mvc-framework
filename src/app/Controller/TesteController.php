<?php

namespace src\app\controller;

use src\app\util\Loader;

class TesteController extends Loader
{
    public function teste(){
        $this->load('home/main', []);
    }
}