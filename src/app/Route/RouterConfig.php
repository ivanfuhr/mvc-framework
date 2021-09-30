<?php
use \src\app\route\functions\Router;

//Get Routers
Router::get('/', 'TesteController@teste');
Router::get('/produto/', 'TesteController@produtos');
Router::get('/produto/buscar/[id]', 'TesteController@buscarProdutos');