<?php
    
$this->get('/', function(){
    echo 'Estou na /';
});

$this->get('/home/', function(){
    echo 'Estou na /home';
});

$this->get('/about/teste', function(){
    echo 'Estou na /about/teste';
});