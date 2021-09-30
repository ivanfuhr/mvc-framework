<?php

namespace src\app\controller;

use src\app\util\Loader;
use src\app\models\Querys;

class TesteController extends Querys
{
    public function teste($args)
    {
        Loader::load('home/main', $args);
    }

    public function produtos($args)
    {
        $products = Querys::select('produtos', '*', []);
        Loader::load('home/produtos', ['products' => $products]);
    }
    
    public function buscarProdutos($args)
    {
        $product = Querys::select('produtos', '*', [
            'where' => 'id = ' . $args['id']
        ]);

        Loader::load('home/produto', $product[0]);
    }
}
