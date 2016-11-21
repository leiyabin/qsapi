<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/21
 * Time: 23:30
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\LoupanManager;
use app\models\LouPanModel;

class LoupanController extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['area_id'])) {
            $condition['area_id'] = $this->params['area_id'];
        }
        if (!empty($this->params['name'])) {
            $condition['name'] = $this->params['name'];
        }
        if (!empty($this->params['average_price'])) {
            $condition['average_price'] = $this->params['average_price'];
        }
        if (!empty($this->params['property_type_id'])) {
            $condition['property_type_id'] = $this->params['property_type_id'];
        }
        if (!empty($this->params['sale_status'])) {
            $condition['sale_status'] = $this->params['sale_status'];
        }
        $data = LoupanManager::getList($pageInfo, 'loupan_list', $condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = LouPanModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionAdd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        NewsManager::addNews($this->params);
        return $this->success();
    }
}