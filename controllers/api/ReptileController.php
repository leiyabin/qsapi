<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/2
 * Time: 13:52
 */
namespace app\controllers\api;

use app\components\LController;
use app\models\StatisticsModel;

class ReptileController extends LController
{
    public function actionGet()
    {
        $data = StatisticsModel::model()->get();
        $res = ['statistics' => $data];
        return $this->success($res);

    }
}