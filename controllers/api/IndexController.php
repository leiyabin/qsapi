<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/23
 * Time: 22:41
 */

namespace app\controllers\api;

use Yii;
use app\components\LController;
use yii\web\HttpException;

class IndexController extends LController
{

    public function actionIndex()
    {
        var_dump(Yii::$app->request->post());die;
    }
}