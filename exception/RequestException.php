<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/30
 * Time: 9:43
 */

namespace app\exception;


use yii\base\UserException;

class RequestException extends UserException
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}