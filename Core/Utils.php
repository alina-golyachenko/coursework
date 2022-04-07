<?php

namespace Core;

/**
 * Общие полезные штуки, которые
 * будут использоваться во многих классах
 */
class Utils
{
    public static function arrayFilter($arr, $fields){
        $newArr = [];
        foreach ($fields as $field){
            if (isset($arr[$field])){
                $newArr[$field] = $arr[$field];
            }
        }

        return $newArr;
    }

}