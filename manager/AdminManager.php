<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/9
 * Time: 9:30
 */

namespace app\manager;


use app\consts\ErrorCode;
use app\exception\RequestException;
use app\models\AdminModel;

class AdminManager
{
    public static function add($admin)
    {

        $username = $admin['username'];
        $user_info = AdminModel::model()->getByUsername($username);
        if (empty($user_info)) {
            AdminModel::model()->add($admin);
        } else {
            throw  new RequestException('用户名已存在！', ErrorCode::ACTION_ERROR);
        }
    }

}