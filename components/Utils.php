<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/30
 * Time: 22:36
 */

namespace app\components;


class Utils
{
    public static function getDefault(array $array, $field, $default_value)
    {
        if (isset($array[$field])) {
            return $array[$field];
        } else {
            return $default_value;
        }
    }

    public static function lMd5($code)
    {
        $code .= 'lyblxy';
        return md5($code);
    }

    public static function getLength($value)
    {
        return mb_strlen($value, 'utf-8');
    }
}