<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/23
 * Time: 22:41
 */

namespace app\controllers\api;

class IndexController extends ControllerBase
{

    public function actionIndex()
    {
        echo  $this->success('hello world!');
    }
}