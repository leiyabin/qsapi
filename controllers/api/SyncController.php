<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/2
 * Time: 8:14
 */

namespace app\controllers\api;

use app\components\LController;

class SyncController extends LController
{
    public function actionStorage()
    {
        $path = $path = '/home/data/file_' . date('Y-m-d');
        $_str = json_encode($this->var_urlencode($this->params));
        $_str = urldecode($_str);
        file_put_contents($path, "\r\n" . $_str, FILE_APPEND);
        file_put_contents($path, "\r\n=========================", FILE_APPEND);
        die('ok');
    }

    public function var_urlencode($var)
    {

        if (empty ($var)) {
            return false;
        }
        if (is_array($var)) {
            foreach ($var as $k => $v) {
                if (is_scalar($v)) {
                    $var [$k] = urlencode($v);
                } else {
                    $var [$k] = $this->var_urlencode($v);
                }
                $new_key = urlencode($k);
                if ($new_key != $k) {
                    $var[$new_key] = $var[$k];
                    unset($var[$k]);
                }

            }
        } else {
            $var = urlencode($var);
        }
        return $var;
    }


    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }

            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }

    public function JSON($array)
    {
        $this->arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }
}