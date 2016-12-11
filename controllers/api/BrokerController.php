<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 15:44
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\BrokerManager;
use app\models\BrokerModel;
use app\consts\HouseConst;

class BrokerController extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['position_id'])) {
            $condition['position_id'] = $this->params['position_id'];
        }
        if (!empty($this->params['name'])) {
            $condition['name'] = $this->params['name'];
        }
        $broker_type_condition = '';
        if (!empty($this->params['broker_type'])) {
            $broker_type = $this->params['broker_type'];
            $condition_str_arr = [];
            foreach ($broker_type as $value) {
                if (!isset(HouseConst::$broker_type[$value])) {
                    throw new RequestException('经纪人标签区间不对!', ErrorCode::INVALID_PARAM);
                }
                $condition_str_arr[] = " (`tag` like '%" . $value . "%' ) ";
            }
            $broker_type_condition = implode('or', $condition_str_arr);
        }
        $data = BrokerManager::getList($pageInfo, 'broker_list', $condition, $broker_type_condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = BrokerModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionAdd()
    {
        if (empty($this->params['position_id'])) {
            throw new RequestException('position_id不能为空', ErrorCode::INVALID_PARAM);
        }
        if (empty($this->params['code'])) {
            $this->params['code'] = $this->getPraiseNum();
        }
        BrokerManager::add($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $broker = $this->params;
        $requires = ['id', 'name', 'position_id', 'phone', 'tag'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        BrokerModel::model()->updateById($broker);
        return $this->success();
    }

    public function actionBatchdel()
    {
        if (empty($this->params['ids']) && !is_array($this->params['ids'])) {
            throw new RequestException('ids参数不正确！', ErrorCode::INVALID_PARAM);
        }
        $ids = $this->params['ids'];
        BrokerModel::model()->batchDel($ids);
        return $this->success();
    }

    private function getPraiseNum()
    {
        $arr = range(90, 99);
        shuffle($arr);
        return current($arr);
    }
}