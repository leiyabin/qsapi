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
use app\manager\ConfigManager;
use app\models\ClassModel;
use app\models\ValueModel;

class ConfigController extends LController
{
    public function actionClasslist()
    {
        $pageInfo = $this->pageInfo();
        $data = ClassModel::model()->getList($pageInfo, 'class_list');
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

    public function actionClassadd()
    {
        ClassModel::model()->add($this->params);
        return $this->success();
    }

    public function actionClassedit()
    {
        $model = $this->params;
        $requires = ['id', 'name'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        ClassModel::model()->updateById($model);
        return $this->success();
    }

    public function actionValuelist()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['class_id'])) {
            $condition['class_id'] = $this->params['class_id'];
        }
        if (!empty($this->params['value'])) {
            $condition['value'] = $this->params['value'];
        }
        $data = ConfigManager::getValueList($pageInfo, 'value_list', $condition);
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

    public function actionValueadd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        ConfigManager::addValue($this->params);
        return $this->success();
    }

    public function actionValueedit()
    {
        $value = $this->params;
        $requires = ['id', 'value', 'class_id'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        ValueModel::model()->updateById($value);
        return $this->success();
    }
}