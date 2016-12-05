<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/12/4
 * Time: 17:59
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\exception\RequestException;
use app\manager\HouseManager;
use app\models\HouseModel;

class HouseController extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['area_id'])) {
            $condition['area_id'] = $this->params['area_id'];
        }
        $average_price_condition = '';
        if (!empty($this->params['price_interval']) && is_array($this->params['price_interval'])) {
            $price_interval = $this->params['price_interval'];
            $condition_str_arr = [];
            foreach ($price_interval as $value) {
                if (!isset(HouseConst::$price_interval[$value])) {
                    throw new RequestException('均价区间不对!', ErrorCode::INVALID_PARAM);
                }
                $condition_str_arr[] = sprintf(' (`total_price` >= %s and `total_price` <= %s ) ', HouseConst::$price_interval[$value][0],
                    HouseConst::$price_interval[$value][1]);
            }
            $average_price_condition = implode('or', $condition_str_arr);
        }
        $build_area_condition = '';
        if (!empty($this->params['build_area']) && is_array($this->params['build_area'])) {
            $build_area = $this->params['build_area'];
            $condition_str_arr = [];
            foreach ($build_area as $value) {
                if (!isset(HouseConst::$area_interval[$value])) {
                    throw new RequestException('面积区间不对!', ErrorCode::INVALID_PARAM);
                }
                $condition_str_arr[] = sprintf(' (`build_area` >= %d and `build_area` <= %d ) ', HouseConst::$area_interval[$value][0],
                    HouseConst::$area_interval[$value][1]);
            }
            $build_area_condition = implode('or', $condition_str_arr);
        }
        if (!empty($this->params['property_type_id'])) {
            $condition['property_type_id'] = $this->params['property_type_id'];
        }
        $order_by = $this->getRequestParam('order_by');
        if (!empty($this->params['recommend'])) {
            $condition['recommend'] = $this->params['recommend'];
        }
        $data = HouseManager::getList($pageInfo, 'house_list', $condition, [],
            $average_price_condition, $build_area_condition, $order_by);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $model = HouseManager::getHouse($this->params['id']);
        return $this->success($model);
    }

    public function actionGetrecommend()
    {
        $requires = ['size'];
        $this->checkEmpty($requires);
        $condition['recommend'] = 1;
        $model = HouseModel::model()->getListByCondition($condition, 0, $this->params['size']);
        return $this->success($model);
    }
}