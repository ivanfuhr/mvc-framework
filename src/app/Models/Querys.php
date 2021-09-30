<?php

namespace src\app\models;

use \src\app\models\connection\Connection;

abstract class Querys extends Connection
{
    protected static function select(string $tableName, string $selected, $params = [])
    {
        $connection = self::getConnection();
        $queryString = 'SELECT ' . $selected . ' FROM `' . $tableName . '`';
        if (count($params) > 0) {
            if (isset($params['where'])) $queryString .= (' WHERE '   . $params['where']);
            if (isset($params['like'])) $queryString .= (' LIKE "' . $params['like'] . '"');
            if (isset($params['limit'])) $queryString .= (' LIMIT ' . $params['limit']);
        }
        $queryString .= ';';
        $resultQuery = $connection->query($queryString, \PDO::FETCH_ASSOC);

        return $resultQuery->fetchAll();
    }

    protected static function update(string $tableName, $seted = [], string $where, $params = [])
    {
        $connection = self::getConnection();

        $queryString = 'UPDATE `' . $tableName . '` SET ';

        foreach ($seted as $keySet => $set) {
            $queryString .= '`' . $keySet . '` = "' . $set . '" ';
        }

        $queryString .= ' WHERE ' . $where;
        $queryString .= ';';

        try {
            $resultQuery = $connection->query($queryString, \PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return $e;
        }
    }

    protected static function insert(string $tableName, $values = [], $params = []){
        $connection = self::getConnection();
        $queryString = 'INSERT INTO `' . $tableName . '` ';
        $keysString = '';
        $valuesString = '';

        foreach($values as $keyValue => $value){
            $keysString .= '`' . $keyValue . '`, ';
            $valuesString .= '"' . $value . '", ';
        }

        $queryString .= '('. substr($keysString, 0, -2) . ') VALUES (' . substr($valuesString, 0, -2) . ')';
        $queryString .= ';';

        try {
            $resultQuery = $connection->query($queryString, \PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return $e;
        }
    }

    protected static function delete(string $tableName, $params = [])
    {
        $connection = self::getConnection();
        $queryString = 'DELETE FROM `' . $tableName . '`';
        if (count($params) > 0) {
            if (isset($params['where'])) $queryString .= (' WHERE '   . $params['where']);
            if (isset($params['like'])) $queryString .= (' LIKE "' . $params['like'] . '"');
            if (isset($params['limit'])) $queryString .= (' LIMIT ' . $params['limit']);
        }
        $queryString .= ';';

        try {
            $resultQuery = $connection->query($queryString, \PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
