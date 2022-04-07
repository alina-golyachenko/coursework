<?php

namespace Core;

class DB
{
    public function __construct($server, $login, $password, $database){
        $this -> pdo = new \PDO("mysql:host={$server};dbname={$database};charset=UTF8", $login, $password);
    }

    /* CRUD-операции (ф-ции для работы с БД)
     * Create
     * Reade
     * Update
     * Delete
    */

    # $limit - количество записей, которые нужно вернуть
    # $offset - с какой записи в таблице начинаем получать данные
    public function select($table, $fields = '*', $where = null, $orderBy = null, $limit = null, $offset = null){

        $fieldsStr = "*";
        if (is_string($fields)){
           $fieldsStr = $fields;
       }
       if (is_array($fields)) {
           $fieldsStr = implode(', ', $fields);
       }

       $sql = "SELECT {$fieldsStr} FROM {$table}";
       if (is_array($where) && count($where) > 0){
           $whereParts = [];

           foreach ($where as $key => $value) {
               $whereParts [] = "{$key} = ?";
           }
           $whereStr = implode(' AND ', $whereParts);
           $sql .= ' WHERE '.$whereStr;
       }
       if (is_string($where)){
           $sql .= ' WHERE '.$where;
       }

       if (is_array($orderBy)){
           $orderByParts = [];
           foreach ($orderBy as $key => $value){
               $orderByParts [] = "{$key} {$value}";
           }
           $sql .= ' ORDER BY '.implode(', ', $orderByParts);
       }

       # если задан лимит и с какой записи отображаем всё
        # добавляем это в запрос, чтобы не перегружать сайт
       if (!empty($limit)){
           if (!empty($offset)){
               $sql .= " LIMIT {$offset}, {$limit}";
           }
           else{
               $sql .= " LIMIT {$limit}";
           }
       }

        $sth = $this-> pdo -> prepare($sql);

       if (is_array($where) && count($where) > 0){
           $sth -> execute(array_values($where));
       }
       else{
           $sth -> execute();
       }

       # сюда вместо ассоциативного массива пойдёт массив значений
           # получаем результирующие строки, которые подходят
           # условию $where
       return $sth -> fetchAll();
    }

    public function insert($table, $row){
        $fieldsStr = implode(', ', array_keys($row));

        $valuesParts = [];
        foreach ($row as $key => $value){
            $valuesParts [] = '?';
        }
        $valuesStr = implode(', ', $valuesParts);

        $sql = "INSERT INTO {$table} ({$fieldsStr}) VALUES ($valuesStr)";

        $sth = $this -> pdo -> prepare($sql);

        # эти значения вставятся вместо знаков вопроса в подготовленный запрос
        $sth -> execute(array_values($row));

        return $this -> pdo -> lastInsertId();
    }

    public function delete($table, $where){
        $sql = "DELETE FROM {$table}";
        if (is_array($where) && count($where) > 0){
            $whereParts = [];

            foreach ($where as $key => $value) {
                $whereParts [] = "{$key} = ?";
            }
            $whereStr = implode(' AND ', $whereParts);
            $sql .= ' WHERE '.$whereStr;
        }
        if (is_string($where)){
            $sql .= ' WHERE '.$where;
        }
        $sth = $this -> pdo -> prepare($sql);

        if (is_array($where) && count($where) > 0){
            $sth -> execute(array_values($where));
        }
        else{
            $sth -> execute();
        }
    }

    public function update($table, $newRow, $where){

        $sql = "UPDATE {$table} SET ";
        $setParts = [];
        $paramsArr = [];
        foreach ($newRow as $key => $value){
            $setParts [] = "{$key} = ?";
            # знак вопроса, потому что это подготовленный
            # запрос и вместо "?" потом будет подставляться
            # параметр
            $paramsArr [] = $value;
        }

        $sql .= implode(', ', $setParts);

        if (is_array($where) && count($where) > 0){
            $whereParts = [];

            foreach ($where as $key => $value) {
                $whereParts [] = "{$key} = ?";
                $paramsArr [] = $value;
            }
            $whereStr = implode(' AND ', $whereParts);
            $sql .= ' WHERE '.$whereStr;
        }
        if (is_string($where)){
            $sql .= ' WHERE '.$where;
        }

        $sth = $this -> pdo -> prepare($sql);
        $sth -> execute($paramsArr);

    }

}