<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/24
 * Time: 20:43
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\AdminManager;
use app\models\AdminModel;
use Yii;


class AdminController extends LController
{

    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $data = AdminModel::model()->getList($pageInfo);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = AdminModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionDel()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        AdminModel::model()->del($id);
        return $this->success();
    }

    public function actionAdd()
    {
        if (empty($this->params['username'])) {
            throw new RequestException('username参数为空！', ErrorCode::INVALID_PARAM);
        }
        AdminManager::add($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $admin = $this->params;
        $requires = ['id', 'name', 'phone', 'email'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        AdminModel::model()->modify($admin);
        return $this->success();
    }

    public function actionSetpwd()
    {
        $requires = ['id', 'old_password', 'new_password'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        $id = $this->params['id'];
        $old_password = $this->params['old_password'];
        $new_password = $this->params['new_password'];
        AdminManager::setPwd($id, $old_password, $new_password);
        return $this->success();
    }
}