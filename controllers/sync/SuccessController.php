<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/29
 * Time: 0:58
 */

namespace app\controllers\sync;

use \app\components\LController;

class SuccessController extends LController
{
    public $layout = false;
    public function actionShow()
    {
        return $this->render('index');
    }
}