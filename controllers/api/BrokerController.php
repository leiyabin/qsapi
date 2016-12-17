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
use app\exception\ResponseException;
use app\models\ValueModel;

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
        $this->checkBroker();
        if (empty($this->params['code'])) {
            $this->params['code'] = $this->getPraiseNum();
        }
        $broker = $this->params;
        BrokerModel::model()->add($broker);
        return $this->success();
    }

    public function actionEdit()
    {
        if (empty($this->params['id'])) {
            throw new ResponseException('id不能为空！', ErrorCode::INVALID_PARAM);
        }
        $attributes = [
            'name'        => $this->params['name'],
            'position_id' => $this->params['position_id'],
            'phone'       => $this->params['phone'],
            'tag'         => $this->params['tag'],
        ];
        if (!empty($this->params['img'])) {
            $attributes['img'] = $this->params['img'];
        }
        if (!empty($this->params['email'])) {
            $attributes['email'] = $this->params['email'];
        }
        $this->checkBroker();
        BrokerModel::model()->_updateById($this->params['id'], $attributes);
        return $this->success();
    }

    private function checkBroker()
    {
        $requires = ['name', 'position_id', 'phone', 'tag'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        $tag_str = $this->params['tag'];
        $tag_arr = explode(',', $tag_str);
        foreach ($tag_arr as $tag_val) {
            if (!isset(HouseConst::$broker_type[$tag_val])) {
                throw new ResponseException('经纪人标签不正确', ErrorCode::INVALID_PARAM);
            }
        }
        $position_id = $this->params['position_id'];
        $position = ValueModel::model()->getById($position_id);
        if (empty($position)) {
            throw new RequestException('职位不存在！', ErrorCode::ACTION_ERROR);
        }
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