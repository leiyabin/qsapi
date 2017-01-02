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
        //page info
        $page_info = $this->pageInfo();
        //condition
        $condition = [];
        $condition['is_deleted'] = 0;
        if (!empty($this->params['area_id'])) {
            $condition['area_id'] = $this->params['area_id'];
        }
        if (!empty($this->params['property_type_id'])) {
            $condition['property_type_id'] = $this->params['property_type_id'];
        }
        //str condition
        $str_condition_sql = ' 1=1 ';
        if (!empty($this->params['room_type']) && is_array($this->params['room_type'])) {
            $room_types = $this->params['room_type'];
            $sql_interval = [];
            foreach ($room_types as $value) {
                if (!in_array($value, HouseConst::$room_type)) {
                    throw new RequestException('房型区间不对!', ErrorCode::INVALID_PARAM);
                }

                $sql_interval[] = $value;
            }
            if (in_array(HouseConst::ROOM_TYPE_6, $sql_interval)) {
                $str_condition_sql .= sprintf(' and (`jishi` in (%s) or `jishi` >= %d )', implode(',', $sql_interval), HouseConst::ROOM_TYPE_6);
            } else {
                $str_condition_sql .= sprintf(' and (`jishi` in (%s) )', implode(',', $sql_interval));
            }
        }

        if (!empty($this->params['price_interval']) && is_array($this->params['price_interval'])) {
            $price_interval = $this->params['price_interval'];
            $condition_str_arr = [];
            foreach ($price_interval as $value) {
                if (!isset(HouseConst::$price_interval[$value])) {
                    throw new RequestException('均价区间不对!', ErrorCode::INVALID_PARAM);
                }
                $condition_str_arr[] = sprintf(' (`total_price` >= %d and `total_price` <= %d ) ', HouseConst::$price_interval[$value][0],
                    HouseConst::$price_interval[$value][1]);
            }
            $str_condition_sql .= ' and ( ' . implode('or', $condition_str_arr) . ' )';
        }
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
            $str_condition_sql .= ' and ( ' . implode('or', $condition_str_arr) . ' )';
        }
        //operate_conditions
        $operate_conditions = [];
        if (!empty($this->params['school_info'])) {
            $condition['is_school_house'] = 1;
            $str_condition_sql .= ' and (`school_info` like :school_info or `address` like :address ) ';
            $str_condition_params = [
                ':school_info' => '%' . $this->params['school_info'] . '%',
                ':address'     => '%' . $this->params['school_info'] . '%'
            ];
        } else {
            if (!empty($this->params['address'])) {
                $operate_conditions[] = ['like', 'address', $this->params['address']];
            }
        }
        $str_condition = [
            'sql'    => empty($str_condition_sql) ? '' : $str_condition_sql,
            'params' => empty($str_condition_params) ? [] : $str_condition_params
        ];

        //order by
        $order_by = $this->getRequestParam('order_by', 'id');
        $sort = $this->getRequestParam('sort', SORT_DESC);
        $order_by_condition = [$order_by => $sort];


        $data = HouseManager::getPageList($condition, $str_condition, $operate_conditions, $order_by_condition, $page_info);
        return $this->renderPage($data, $page_info);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $model = HouseManager::getHouse($this->params['id']);
        return $this->success($model);
    }

    public function actionEdit()
    {
        $requires = [
            'id', 'build_type', 'total_door_model', 'total_building', 'build_year', 'community_average_price', 'traffic_info',
            'door_model_introduction', 'community_introduction', 'community_img', 'community_name',
            'lon', 'lat', 'right_info', 'mortgage_info', 'deed_year', 'last_sale_time', 'sale_time', 'tag'
        ];
        $this->checkEmpty($requires);
        $this->params['recommend'] = $this->getRequestParam('recommend', 0);
        $this->params['is_only'] = $this->getRequestParam('is_only', 0);
        $this->params['is_school_house'] = $this->getRequestParam('is_school_house', 0);
        HouseManager::editHouseAttach($this->params);
        return $this->success();
    }

    public function actionGetrecommend()
    {
        $requires = ['size'];
        $this->checkEmpty($requires);
        $condition['recommend'] = 1;
        $model = HouseModel::model()->getFewList($condition, $this->params['size']);
        return $this->success($model);
    }
}