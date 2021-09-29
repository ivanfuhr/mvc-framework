<?php

namespace src\app\models\connection;

abstract class Connection{
    private static $instanceConnection;

    protected static function getConnection(){

        /**
         *  Aqui estou fazendo o uso de um Padrão chamado Singleton
         *  onde o objetivo é evitar multiplas instancias de 
         *  uma conexão com o banco de dados
         */

        if(empty(self::$instanceConnection)){
            self::$instanceConnection = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        }

        return self::$instanceConnection;
    }       
}