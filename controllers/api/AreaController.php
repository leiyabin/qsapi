<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 14:21
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\AreaManager;
use app\models\AreaModel;


class AreaController extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $is_trip_area = empty($this->params['is_trip_area']) ? false : true;
        $class_id = empty($this->params['class_id']) ? 0 : $this->params['class_id'];
        $name = empty($this->params['name']) ? '' : $this->params['name'];
        $data = AreaManager::getPageList($pageInfo, $class_id, $is_trip_area, $name);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = AreaModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionAdd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        AreaManager::add($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $news = $this->params;
        $requires = ['id', 'name', 'class_id'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        AreaModel::model()->updateById($news);
        return $this->success();
    }

    public function actionBatchdel()
    {
        if (empty($this->params['ids']) && !is_array($this->params['ids'])) {
            throw new RequestException('ids参数不正确！', ErrorCode::INVALID_PARAM);
        }
        $ids = $this->params['ids'];
        AreaModel::model()->batchDel($ids);
        return $this->success();
    }

    public function actionGetbyclassid()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $condition = ['class_id' => $this->params['class_id']];
        $model = AreaModel::model()->getListByCondition($condition);
        return $this->success($model);
    }
}