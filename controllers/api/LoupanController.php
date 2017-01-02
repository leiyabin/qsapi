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
use app\consts\HouseConst;
use app\models\LouPanModel;

class LoupanController extends LController
{
    public function actionActive()
    {
        $requires = ['id', 'active'];
        $this->checkIsset($requires);
        LoupanManager::active($this->params['id'], $this->params['active']);
        return $this->success();
    }

    public function actionList()
    {
        $page_info = $this->pageInfo();
        //condition
        $condition = [];
        if (!empty($this->params['area_id'])) {
            $condition['area_id'] = $this->params['area_id'];
        }
        if (!empty($this->params['property_type_id'])) {
            $condition['property_type_id'] = $this->params['property_type_id'];
        }
        if (!empty($this->params['sale_status'])) {
            $condition['sale_status'] = $this->params['sale_status'];
        }
        //str_condition
        $str_condition = ' 1=1 ';
        if (!empty($this->params['average_price']) && is_array($this->params['average_price'])) {
            $average_price = $this->params['average_price'];
            $condition_str_arr = [];
            foreach ($average_price as $value) {
                if (!isset(HouseConst::$price_interval[$value])) {
                    throw new RequestException('均价区间不对!', ErrorCode::INVALID_PARAM);
                }
                $condition_str_arr[] = sprintf(' (`average_price` >= %d and `average_price` <= %d ) ', HouseConst::$price_interval[$value][0],
                    HouseConst::$price_interval[$value][1]);
            }
            $str_condition .= ' and ( ' . implode('or', $condition_str_arr) . ' ) ';
        }
        //filter conditions
        $filter_conditions = [];
        if (!empty($this->params['name'])) {
            $filter_conditions[] = ['like', 'name', $this->params['name']];
        }
        if (!empty($this->params['room_type'])) {
            $filter_conditions[] = ['like', 'jiju', $this->params['room_type']];
        }
        //order by
        $order_by = $this->getRequestParam('order_by', 'id');
        $sort = $this->getRequestParam('sort', SORT_DESC);
        $order_by_condition = [$order_by => $sort];
        $data = LoupanManager::getPageList($condition, $str_condition, $filter_conditions, $order_by_condition, $page_info);
        return $this->renderPage($data, $page_info);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $model = LoupanManager::getLoupan($this->params['id']);
        return $this->success($model);
    }

    public function actionAdd()
    {
        $requires = [
            'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
            'sale_status', 'jiju', 'min_square', 'max_square', 'lon', 'lat', 'developers',
            'property_company', 'img', 'banner_img', 'right_time', 'tag', 'img_1', 'img_2', 'img_3', 'img_4'
        ];
        $this->checkEmpty($requires);
        $this->params['img_5'] = $this->getRequestParam('img_5', '');
        $this->params['recommend'] = $this->getRequestParam('recommend', 0);
        LoupanManager::addLoupan($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $requires = [
            'id', 'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
            'sale_status', 'jiju', 'min_square', 'max_square', 'lon', 'lat', 'developers',
            'property_company', 'img', 'banner_img', 'right_time', 'tag', 'img_1', 'img_2', 'img_3', 'img_4'
        ];
        $this->checkEmpty($requires);
        $this->params['img_5'] = $this->getRequestParam('img_5', '');
        $this->params['recommend'] = $this->getRequestParam('recommend', 0);
        LoupanManager::editLoupan($this->params);
        return $this->success();
    }

    public function actionGetsimple()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $model = LoupanManager::getLoupanSimple($this->params['id']);
        return $this->success($model);
    }

    public function actionGetrecommend()
    {
        $requires = ['size'];
        $this->checkEmpty($requires);
        $condition['recommend'] = 1;
        $model = LouPanModel::model()->getFewList($condition, $this->params['size']);
        return $this->success($model);
    }
}