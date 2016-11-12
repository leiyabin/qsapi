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
use app\components\Utils;

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

    public static function setPwd($id, $old_password, $new_password)
    {
        $user_info = AdminModel::model()->getById($id, ['password']);
        if (empty($user_info)) {
            throw new RequestException('用户不存在！', ErrorCode::ACTION_ERROR);
        }
        if ($user_info['password'] != Utils::lMd5($old_password)) {
            throw new RequestException('原密码不正确！', ErrorCode::ACTION_ERROR);
        }
        $data = ['id' => $id, 'password' => $new_password];
        AdminModel::model()->modify($data);
    }

}