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

    public static function getList($page_info, $list_name, $condition, $select, $average_price_condition = [],
                                   $build_area_condition = [], $order_by = 'c_t')
    {
        $condition['is_deleted'] = 0;
        $data = HouseModel::model()->getList($page_info, $list_name, $condition, $select, $average_price_condition,
            $build_area_condition, $order_by);
        if (!empty($data[$list_name])) {
            $house_list = $data[$list_name];
            $area_ids = array_column($house_list, 'area_id');
            $areas = AreaManager::getList($page_info, 'area_list', ['id' => $area_ids]);
            $areas = Utils::buildIdArray($areas['area_list']);
            $broker_ids = array_column($house_list, 'broker_id');
            $broker_list = BrokerModel::model()->getListByCondition(['id' => $broker_ids]);
            $broker_list = Utils::buildIdArray($broker_list);
            foreach ($house_list as $key => $val) {
                $house_list[$key]['decoration_name'] = HouseConst::$decoration[$val['decoration']];
                $house_list[$key]['property_type'] = HouseConst::$property_type[$val['property_type_id']];
                $house_list[$key]['right_type_name'] = HouseConst::$right_type[$val['right_type']];
                $house_list[$key]['buy_type_name'] = HouseConst::$buy_type[$val['buy_type']];
                if (!isset($areas[$val['area_id']])) {
                    $error_msg = sprintf('二手房片区id错误。house_id : %d ; area_id : %d .', $val['id'], $val['area_id']);
                    throw new RequestException($error_msg, ErrorCode::SYSTEM_ERROR);
                }
                $house_list[$key]['area_name'] = $areas[$val['area_id']]['name'];
                $house_list[$key]['quxian_name'] = $areas[$val['area_id']]['class_name'];
                if (!isset($broker_list[$val['broker_id']])) {
                    $house_list[$key]['broker_name'] = HouseConst::DEFAULT_BROKER_NAME;
                } else {
                    $house_list[$key]['broker_name'] = $broker_list[$val['broker_id']];
                }
            }
            $data[$list_name] = $house_list;
        }
        return $data;
    }

    private static function getHouseImgs($house_id)
    {
        $condition = ['object_id' => $house_id, 'type' => HouseConst::HOUSE_TYPE_OLD];
        $select = ['img_1', 'img_2', 'img_3', 'img_4', 'img_5'];
        return HouseImgModel::model()->getOneByCondition($condition, $select);
    }

    public static function addHouse($house, $house_img)
    {
        HouseModel::model()->add($house);
        HouseImgModel::model()->addBySQL($house_img);
    }

    public static function editHouse($house, $house_img)
    {
        HouseModel::model()->updateById($house);
        $condition = ['object_id' => $house['id'], 'type' => HouseConst::HOUSE_TYPE_OLD];
        if (isset($house_img['object_id'])) {
            unset($house_img['object_id']);
        }
        if (isset($house_img['type'])) {
            unset($house_img['type']);
        }
        $house_img_id = HouseImgModel::model()->getOneByCondition($condition, ['id']);
        $house_img['id'] = $house_img_id;
        HouseImgModel::model()->updateById($house_img);
    }

    public static function editHouseAttach($house)
    {
        self::checkHouse($house);
        $house_attributes = [
            'id'        => $house['id'],
            'lon'       => $house['lon'],
            'lat'       => $house['lat'],
            'recommend' => $house['recommend'],
        ];
        HouseModel::model()->updateById($house_attributes);
        $house_attach = HouseAttachModel::model()->getById($house['id']);
        $house_attach_attributes = [
            'id', 'build_type', 'total_door_model', 'total_building', 'build_year', 'community_average_price', 'traffic_info',
            'school_info', 'door_model_introduction', 'community_introduction', 'community_img', 'community_name',
            'lon', 'lat', 'right_info', 'mortgage_info', 'deed_year', 'last_sale_time', 'sale_time', 'is_only'
        ];
        $house_attach_model = self::getFiled($house, $house_attach_attributes);
        if (empty($house_attach)) {
            HouseAttachModel::model()->add($house_attach_model);
        } else {
            unset($house_attach_attributes['id']);
            HouseAttachModel::model()->updateById($house_attach_model);
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
        if (!isset(HouseConst::$build_type[$build_type])) {
            throw new RequestException('建筑类型不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$deed_year[$deed_year])) {
            throw new RequestException('房本年限不正确', ErrorCode::ACTION_ERROR);
        }
    }
}