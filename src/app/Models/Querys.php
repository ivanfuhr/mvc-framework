<?php

namespace src\app\models;

use \src\app\models\connection\Connection;

abstract class Querys extends Connection{
    public static function select($tableName, $selected, $params = []){
        $connection = self::getConnection();
        $queryString = 'SELECT '. $selected .' FROM `' . $tableName . '`';
        if(count($params) > 0){
            if(isset($params['where'])) $queryString = $queryString . (' WHERE `'   . $params['where'] . '`');
            if(isset($params['like'])) $queryString = $queryString . (' LIKE "' . $params['like']. '"');
            if(isset($params['limit'])) $queryString = $queryString . (' LIMIT ' . $params['limit']);

        }
        $queryString = $queryString . ';';

        $sth = $connection->query($queryString, \PDO::FETCH_ASSOC);

        foreach($sth as $row) {
            Debug($row, false);
        }
    }
}


// SELECT * FROM `produtos` WHERE `id` LIKE "2%"