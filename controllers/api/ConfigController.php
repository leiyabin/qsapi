<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/13
 * Time: 19:11
 */

namespace app\controllers\api;
use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\models\ClassModel;
use app\models\ValueModel;
use app\components\Utils;

class ConfigController extends LController
{
    public function actionClasslist()
    {
        $pageInfo = $this->pageInfo();
        $data = ClassModel::model()->getList($pageInfo, 'class');
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGetclass()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = ClassModel::model()->getById($id);
        return $this->success($model);
    }
    public function actionAdd()
    {
        if (empty($this->params['name'])) {
            throw new RequestException('name参数为空！', ErrorCode::INVALID_PARAM);
        }
        ClassModel::model()->add($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $model = $this->params;
        $requires = ['id', 'name'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        ClassModel::model()->modify($model);
        return $this->success();
    }

    public function actionValuelist()
    {
        $pageInfo = $this->pageInfo();
        $data = ValueModel::model()->getList($pageInfo, 'class_info');
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGetvalue()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = ValueModel::model()->getById($id);
        return $this->success($model);
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
}