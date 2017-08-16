<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/27
 * Time: 20:47
 */

namespace app\manager;

use app\models\BrokerModel;
use app\models\HouseAttachModel;
use Yii;

use app\models\HouseModel;
use app\models\AreaModel;
use app\exception\RequestException;
use app\consts\LogConst;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\models\HouseImgModel;
use app\models\ValueModel;
use app\components\Utils;

class HouseManager
{
    public static function getHouse($id)
    {
        $house = HouseModel::model()->getById($id);
        if (!empty($house)) {
            //get house attach
            $house_attach = self::getHouseAttach($id);
            //area
            $area = AreaModel::model()->getById($house['area_id']);
            if (empty($area)) {
                $error_msg = sprintf('片区不存在： house_id: %d ,area_id: %d', $id, $house['area_id']);
                Yii::error($error_msg, LogConst::APPLICATION);
                throw new RequestException($error_msg, ErrorCode::SYSTEM_ERROR);
            }
            //quxian
            $quxian = ValueModel::model()->getById($area['class_id']);
            if (empty($quxian)) {
                $error_msg = sprintf('区县不存在： quxian_id: %d ,area_id: %d', $area->class_id, $house['area_id']);
                Yii::error($error_msg, LogConst::APPLICATION);
                throw new RequestException($error_msg, ErrorCode::SYSTEM_ERROR);
            }
            //broker
            $broker = BrokerModel::model()->getById($house['broker_id']);
            $house['broker_name'] = empty($broker) ? HouseConst::DEFAULT_BROKER_NAME : $broker['name'];
            $house['broker_img'] = empty($broker) ? "" : $broker['img'];
            $house['broker_phone'] = empty($broker) ? HouseConst::DEFAULT_BROKER_PHONE : $broker['phone'];
            $house['quxian_id'] = $area['class_id'];
            $house['quxian_name'] = $quxian['value'];
            $house['area_name'] = $area['name'];
            $house['decoration_name'] = HouseConst::$decoration[$house['decoration']];
            $house['property_type'] = HouseConst::$property_type[$house['property_type_id']];
            $house['right_type_name'] = HouseConst::$right_type[$house['right_type']];
            $house['buy_type_name'] = HouseConst::$buy_type[$house['buy_type']];
            $house_imgs = self::getHouseImgs($id);
            $house['house_attach'] = $house_attach;
            $house = array_merge($house, $house_imgs);
        }
        return $house;
    }

    public static function getHouseAttach($id)
    {
        return HouseAttachModel::model()->getById($id);
    }

    public static function getPageList($condition, $str_condition, $operate_conditions, $order_by, $page_info)
    {
        $select = ['*'];
        $data = HouseModel::model()->getPageList($condition, $str_condition, $operate_conditions, $order_by, $select, $page_info);
        if (!empty($data['list'])) {
            $house_list = $data['list'];
            $area_ids = array_column($house_list, 'area_id');
            $area_list = AreaManager::getAreaList($area_ids);
            $area_list = Utils::buildIdArray($area_list);
            foreach ($house_list as $key => $val) {
                $house_list[$key]['decoration_name'] = HouseConst::$decoration[$val['decoration']];
                $house_list[$key]['property_type'] = HouseConst::$property_type[$val['property_type_id']];
                $house_list[$key]['right_type_name'] = HouseConst::$right_type[$val['right_type']];
                $house_list[$key]['buy_type_name'] = HouseConst::$buy_type[$val['buy_type']];
                if (!isset($area_list[$val['area_id']])) {
                    $error_msg = sprintf('二手房片区id错误。house_id : %d ; area_id : %d .', $val['id'], $val['area_id']);
                    throw new RequestException($error_msg, ErrorCode::SYSTEM_ERROR);
                }
                $house_list[$key]['area_name'] = $area_list[$val['area_id']]['name'];
                $house_list[$key]['quxian_name'] = $area_list[$val['area_id']]['class_name'];
            }
            $data['list'] = $house_list;
        }
        return $data;
    }

    private static function getHouseImgs($house_id)
    {
        $condition = ['object_id' => $house_id, 'type' => HouseConst::HOUSE_TYPE_OLD];
        $select = ['img_1', 'img_2', 'img_3', 'img_4', 'img_5'];
        return HouseImgModel::model()->getOneByCondition($condition, $select);
    }

//    public static function addHouse($house, $house_img)
//    {
//        HouseModel::model()->add($house);
//        HouseImgModel::model()->addBySQL($house_img);
//    }

//    public static function editHouse($house, $house_img)
//    {
//        HouseModel::model()->updateById($house);
//        $condition = ['object_id' => $house['id'], 'type' => HouseConst::HOUSE_TYPE_OLD];
//        if (isset($house_img['object_id'])) {
//            unset($house_img['object_id']);
//        }
//        if (isset($house_img['type'])) {
//            unset($house_img['type']);
//        }
//        $house_img_id = HouseImgModel::model()->getOneByCondition($condition, ['id']);
//        $house_img['id'] = $house_img_id;
//        HouseImgModel::model()->updateById($house_img);
//    }
    public static function addHouse($house)
    {
        self::checkHouse($house);
        $house_attributes = [
            'lon', 'lat', 'tag', 'recommend', 'is_school_house', 'school_info',
            'area_id', 'property_type_id', 'address', 'property_company', 'house_age', 'in_floor', 'total_floor',
            'decoration', 'right_type', 'buy_type', 'unit_price', 'total_price', 'face', 'build_area', 'floor_unit',
            'house_facility', 'house_description', 'keywords', 'jishi', 'jitin', 'jiwei', 'jichu', 'jiyangtai'
        ];
        $house_model = self::getFiled($house, $house_attributes);
        $house_model['house_img'] = $house['img_1'];
        $id = HouseModel::model()->addBySQL($house_model);
        $house_attach_attributes = [
            'build_type', 'total_door_model', 'total_building', 'build_year', 'community_average_price', 'traffic_info',
            'door_model_introduction', 'community_introduction', 'community_img', 'community_name',
            'lon', 'lat', 'right_info', 'mortgage_info', 'deed_year', 'last_sale_time', 'sale_time', 'is_only', 'tax_explain'
        ];
        $house_attach_model = self::getFiled($house, $house_attach_attributes);
        $house_attach_model['id'] = $id;
        HouseAttachModel::model()->addBySQL($house_attach_model);
        $img_model = [
            'img_1' => $house['img_1'],
            'img_2' => $house['img_2'],
            'img_3' => $house['img_3'],
            'img_4' => $house['img_4'],
            'img_5' => $house['img_5'],
        ];
        $condition = ['type' => HouseConst::HOUSE_TYPE_OLD, 'object_id' => $id];
        HouseImgModel::model()->add(array_merge($img_model, $condition));
    }

    public static function editHouse($house)
    {
        self::checkHouse($house);
        $id = $house['id'];
        $house_attributes = [
            'lon', 'lat', 'tag', 'recommend', 'is_school_house', 'school_info',
            'area_id', 'property_type_id', 'address', 'property_company', 'house_age', 'in_floor', 'total_floor',
            'decoration', 'right_type', 'buy_type', 'unit_price', 'total_price', 'face', 'build_area', 'floor_unit',
            'house_facility', 'house_description', 'keywords', 'jishi', 'jitin', 'jiwei', 'jichu', 'jiyangtai'
        ];
        $house_model = self::getFiled($house, $house_attributes);
        $house_model['house_img'] = $house['img_1'];
        HouseModel::model()->_updateById($id, $house_model);
        $house_attach = HouseAttachModel::model()->getById($id);
        $house_attach_attributes = [
            'build_type', 'total_door_model', 'total_building', 'build_year', 'community_average_price', 'traffic_info',
            'door_model_introduction', 'community_introduction', 'community_img', 'community_name',
            'lon', 'lat', 'right_info', 'mortgage_info', 'deed_year', 'last_sale_time', 'sale_time', 'is_only', 'tax_explain'
        ];
        $house_attach_model = self::getFiled($house, $house_attach_attributes);
        if (empty($house_attach)) {
            HouseAttachModel::model()->add($house_attach_model);
        } else {
            HouseAttachModel::model()->_updateById($id, $house_attach_model);
        }
        $img_model = [
            'img_1' => $house['img_1'],
            'img_2' => $house['img_2'],
            'img_3' => $house['img_3'],
            'img_4' => $house['img_4'],
            'img_5' => $house['img_5'],
        ];
        $condition = ['type' => HouseConst::HOUSE_TYPE_OLD, 'object_id' => $id];
        $house_img = HouseImgModel::model()->getOneByCondition($condition);
        if (empty($house_img)) {
            HouseImgModel::model()->add(array_merge($img_model, $condition));
        } else {
            HouseImgModel::model()->updateByCondition($condition, $img_model);
        }
    }

    private static function getFiled($arr, $field_list)
    {
        $res = [];
        foreach ($field_list as $value) {
            if (isset($arr[$value])) {
                $res[$value] = $arr[$value];
            }
        }
        return $res;
    }

    private static function checkHouse($house)
    {
        $build_type = $house['build_type'];
        $deed_year = $house['deed_year'];
        $area_id = $house['area_id'];
        $property_type_id = $house['property_type_id'];
        $decoration = $house['decoration'];
        $right_type = $house['right_type'];
        $buy_type = $house['buy_type'];

        if (!isset(HouseConst::$build_type[$build_type])) {
            throw new RequestException('建筑类型不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$deed_year[$deed_year])) {
            throw new RequestException('房本年限不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$property_type[$property_type_id])) {
            throw new RequestException('物业类型不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$decoration[$decoration])) {
            throw new RequestException('装修情况不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$right_type[$right_type])) {
            throw new RequestException('产权不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$buy_type[$buy_type])) {
            throw new RequestException('购买方式不正确', ErrorCode::ACTION_ERROR);
        }
        $area = AreaModel::model()->getById($area_id);
        if (empty($area)) {
            throw new RequestException('区域不正确', ErrorCode::ACTION_ERROR);
        }

        //todo 校验经纪人
    }
}