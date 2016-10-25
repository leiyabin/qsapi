<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/24
 * Time: 20:43
 */

namespace app\controllers\api;

use app\models\AdminModel;


class AdminController extends ControllerBase
{
    public function actionList()
    {
        $list = AdminModel::getList();
        $data = ['admins' => $list];
        return $this->success($data);
    }

    public function actionDel()
    {
        echo  $this->success('hello world!');
    }

    public function actionEdit()
    {
        echo  $this->success('hello world!');
    }
    public function actionAdd()
    {
        echo  $this->success('hello world!');
    }
    public function actionGet()
    {
        echo  $this->success('hello world!');
    }
}