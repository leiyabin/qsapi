<?php

/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/27
 * Time: 22:17
 */
namespace app\controllers\sync;

use \app\components\LController;

class ErrorController extends LController
{
    public $layout = false;
    public function actionShow()
    {
        $error_msg = $this->params['error_msg'];
        return $this->render('index', ['error_msg' => $error_msg]);
    }

}