<?php

/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/27
 * Time: 14:22
 */
namespace app\controllers\sync;

use app\components\LController;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\exception\RequestException;
use app\manager\AreaManager;
use app\manager\BrokerManager;
use app\manager\HouseManager;

class HouseController extends LController
{
    public function actionStorage()
    {
        $params = $this->params;
        $params = $this->iconvArray($params);
        $size = $params['API_pagesize'];
        $broker_list = $this->getBrokerList();
        $area_list = AreaManager::getAllArea();
        for ($i = 1; $i <= $size; $i++) {
            if (!$params["API_check" . $i]) {
                continue;
            }
            if (!$params['API_id' . $i]) {
                throw new RequestException('操作非法！', ErrorCode::ACTION_ERROR);
            }
            $house = [];
            $house['id'] = $params['API_id' . $i];
            //broker_id
            $broker_name = $params['API_lxr' . $i];
            $broker_phone = $params['API_lxdh' . $i];
            $broker_id = $this->getBrokerIdByNameAndPhone($broker_list, $broker_name, $broker_phone);
            if ($broker_id == 0) {
                $broker_id = HouseConst::DEFAULT_BROKER_ID;
            }
            $house['broker_id'] = $broker_id;
            //area_id
            $quxian = $params['API_qx' . $i];
            $pianqu = $params['API_pq' . $i];

            $area_id = $this->getAreaIdByAreaAndQuxian($area_list, $pianqu, $quxian);
            if ($area_id == 0) {
                $error_msg = sprintf('请先设置区县和片区！【区县】：%s | 【片区】：%s', $quxian, $pianqu);
                throw new RequestException($error_msg, ErrorCode::ACTION_ERROR);
            }
            $house['area_id'] = $area_id;
            $house['property_company'] = $params['API_wymc' . $i];
            $house['address'] = $params['API_wydz' . $i];
            //property_type_id
            $house['property_type_id'] = $this->getConfigKeyByValue(HouseConst::$property_type, $params['API_yt' . $i],
                HouseConst::PROPERTY_TYPE_OTHER);
            $house['house_age'] = $this->getRequestParam('API_fl' . $i, 0);
            $house['in_floor'] = $params['API_szlc' . $i];
            $house['total_floor'] = $params['API_zlc' . $i];
            $house['jishi'] = $params['API_js' . $i];
            $house['jitin'] = $params['API_jt' . $i];
            $house['jiwei'] = $params['API_jw' . $i];
            $house['jichu'] = $params['API_jc' . $i];
            $house['jiyangtai'] = $params['API_jyt' . $i];
            $house['decoration'] = $this->getConfigKeyByValue(HouseConst::$decoration, $params['API_zx' . $i],
                HouseConst::DECORATION_HARDCOVER);
            $house['right_type'] = $this->getConfigKeyByValue(HouseConst::$right_type, $params['API_cq' . $i],
                HouseConst::RIGHT_TYPE_BUSINESS_HOUSE);
            $house['buy_type'] = $this->getConfigKeyByValue(HouseConst::$buy_type, $params['API_fkfs' . $i],
                HouseConst::BUY_TYPE_FUND_LOAN);
            $house['unit_price'] = $params['API_danj' . $i];
            $house['total_price'] = $params['API_zj' . $i];
            $house['face'] = $params['API_cx' . $i];
            $house['build_area'] = $params['API_jzmj' . $i];
            $house['use_area'] = empty($params['API_symj' . $i]) ? $params['API_jzmj' . $i] : $params['API_symj' . $i];
            $house['house_facility'] = $params['API_fwss' . $i];
            $house['house_description'] = $params['API_bz' . $i];
            $house['floor_unit'] = $params['API_blz4' . $i];
            $house['keywords'] = $params['API_keywords' . $i];
            //保存图片
            $imgs = $this->getImg($i, $params);
            $house_img = $imgs['house_img_list'];
            $house_img['object_id'] = $house['id'];
            $house_img['type'] = HouseConst::HOUSE_TYPE_OLD;
            $house['house_img'] = $imgs['img'];
            $this->opDb($house, $house_img);
        }
        $this->redirect('/sync/success/show');
    }

    private function iconvArray($params)
    {
        $conv_array = [];
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $conv_array[$key] = $this->iconvArray($param);
            }
            $conv_array[$key] = iconv('gb2312', 'utf-8', $param);
        }
        return $conv_array;
    }

    private function getBrokerList()
    {
        return BrokerManager::getListByCondition([], ['id', 'name', 'phone']);
    }

    private function getBrokerIdByNameAndPhone($broker_list, $name, $phone)
    {
        foreach ($broker_list as $key => $value) {
            if ($value['name'] == $name && $value['phone'] == $phone) {
                return $value['id'];
            }
        }
        return 0;
    }

    private function getAreaIdByAreaAndQuxian($area_list, $pianqu, $quxian)
    {
        foreach ($area_list as $key => $value) {
            if ($value['name'] == $pianqu && $value['class_name'] == $quxian) {
                return $value['id'];
            }
        }
        return 0;
    }

    private function getConfigKeyByValue(array $map, $value, $default)
    {
        $map = array_flip($map);
        if (isset($map[$value])) {
            return $map[$value];
        }
        return $default;
    }

    private function getImg($index, $params)
    {
        $img_list_str = $params['API_piclist' . $index];
        $img_type_str = $params['API_pictype' . $index];
        $img_list_arr = explode(',', $img_list_str);
        $img_type_arr = explode('|', $img_type_str);
        $img = '';
        $house_img_list = [];
        $type_str_1 = '客厅';
        $type_str_2 = '卧室';
        $img_list_arr_count = count($img_list_arr);
        foreach ($img_type_arr as $key => $value) {
            if (strpos($value, $type_str_1) !== false || strpos($value, $type_str_2) !== false) {
                $img = $img_list_arr[$key];
                break;
            }
        }
        if ($img == '' && $img_list_arr_count > 0) {
            $img = current($img_list_arr);
        }
        for ($i = 0; $i < $img_list_arr_count; $i++) {
            $house_img_list['img_' . ($i + 1)] = $img_list_arr[$i];
            if ($i == 4) {
                break;
            }
        }
        return ['img' => $img, 'house_img_list' => $house_img_list];
    }

    private function opDb($house, $house_img)
    {
        $id = $house['id'];
        $select_house = HouseManager::getHouse($id);
        if (empty($select_house)) {
//            HouseManager::addHouse($house, $house_img);
        } else {
//            HouseManager::editHouse($house, $house_img);
        }
    }
}