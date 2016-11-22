<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 23:48
 */

namespace app\manager;

use app\components\Utils;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\consts\LogConst;
use app\exception\RequestException;
use app\models\AreaModel;
use app\models\DoorModel;
use app\models\HouseImgModel;
use app\models\LouPanModel;
use app\models\ValueModel;
use Yii;

class LoupanManager
{
    public static function getList($page_info, $list_name, $condition)
    {
        $condition[] = ['is_deleted' => 0];
        $data = LouPanModel::model()->getList($page_info, $list_name, $condition);
        if (!empty($data[$list_name])) {
            foreach ($data[$list_name] as $key => $val) {
                $data[$list_name][$key]['sale_status_name'] = HouseConst::$sale_status[$val['sale_status']];
                $data[$list_name][$key]['property_type'] = HouseConst::$property_type[$val['property_type_id']];
                $data[$list_name][$key]['tag_map'] = self::buildTagMap($val['tag']);
            }
        }
        return $data;
    }

    public static function getDoorModelList($loupan_id)
    {
        $condition = ['loupan_id' => $loupan_id];
        $list = DoorModel::model()->getListByCondition($condition);
        foreach ($list as $key => $value) {
            $list[$key]['decoration_name'] = HouseConst::$decoration[$value['decoration']];
        }
        return $list;
    }

    public static function getDoorModel($id)
    {
        $door_model = DoorModel::model()->getById($id);
        $door_model['decoration_name'] = HouseConst::$decoration[$door_model['decoration']];
        return $door_model;
    }

    private static function buildTagMap($tag_str)
    {
        $map = [];
        $tag_array = explode(',', $tag_str);
        foreach ($tag_array as $tag_id) {
            $map[$tag_id] = HouseConst::$feature[$tag_id];
        }
        return $map;
    }

    public static function addLoupan($loupan)
    {
        self::checkLoupan($loupan);
        $loupan_filed = [
            'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id',
            'property_type_id', 'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat',
            'developers', 'property_company', 'img', 'banner_img', 'right_time', 'remark', 'tag', 'is_deleted'
        ];
        $loupan_model = self::getFiled($loupan, $loupan_filed);
        $loupan_id = LouPanModel::model()->add($loupan_model);
        $house_img_field = ['img_1', 'img_2', 'img_3', 'img_4', 'img_5'];
        $house_img_model = self::getFiled($loupan, $house_img_field);
        $house_img_model['object_id'] = $loupan_id;
        $house_img_model['type'] = HouseConst::HOUSE_TYPE_NEW;
        HouseImgModel::model()->add($house_img_model);
    }

    public static function editLoupan($loupan)
    {
        self::checkLoupan($loupan);
        $loupan_filed = [
            'id', 'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id',
            'property_type_id', 'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat',
            'developers', 'property_company', 'img', 'banner_img', 'right_time', 'remark', 'tag', 'is_deleted'
        ];
        $loupan_model = self::getFiled($loupan, $loupan_filed);
        LouPanModel::model()->updateById($loupan_model);
        $house_img_field = ['img_1', 'img_2', 'img_3', 'img_4', 'img_5'];
        $house_img_model = self::getFiled($loupan, $house_img_field);
        $condition = ['object_id' => $loupan['id'], 'type' => HouseConst::HOUSE_TYPE_NEW];
        HouseImgModel::model()->updateByCondition($condition, $house_img_model);
    }

    public static function addDoorModel($door_model)
    {
        self::checkDoorModel($door_model);
        DoorModel::model()->add($door_model);
    }

    public static function editDoorModel($door_model)
    {
        self::checkDoorModel($door_model);
        DoorModel::model()->updateById($door_model);
    }

    private static function checkDoorModel($door_model)
    {
        $loupan_id = $door_model['loupan_id'];
        $loupan = LouPanModel::model()->getById($loupan_id);
        if (empty($loupan)) {
            throw new RequestException('楼盘不存在！', ErrorCode::ACTION_ERROR);
        }
        if (!in_array($door_model['decoration'], HouseConst::$decoration)) {
            throw new RequestException('装修情况不存在！', ErrorCode::ACTION_ERROR);
        }
    }

    private static function checkLoupan($loupan)
    {
        $area_id = $loupan['area_id'];
        $property_type_id = $loupan['property_type_id'];
        $sale_status = $loupan['sale_status'];
        if (!isset(HouseConst::$sale_status[$sale_status])) {
            throw new RequestException('销售状态不正确', ErrorCode::ACTION_ERROR);
        }
        if (!isset(HouseConst::$property_type[$property_type_id])) {
            throw new RequestException('物业类型不正确', ErrorCode::ACTION_ERROR);
        }
        $area = AreaModel::model()->getById($area_id);
        if (empty($area)) {
            throw new RequestException('片区不正确', ErrorCode::ACTION_ERROR);
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

    public static function getLoupan($id)
    {
        //楼盘详情页信息
        $loupan = LouPanModel::model()->getById($id);
        $area = ValueModel::model()->getById($loupan['area_id']);
        if (empty($area)) {
            $error_msg = sprintf('片区不存在： loupan_id: %d ,area_id: %d', $id, $loupan['area_id']);
            Yii::error($error_msg, LogConst::APPLICATION);
            throw new RequestException('片区不存在', ErrorCode::SYSTEM_ERROR);
        }
        $loupan['sale_status_name'] = HouseConst::$sale_status[$loupan['sale_status']];
        $loupan['property_type'] = HouseConst::$property_type[$loupan['property_type_id']];
        $loupan['tag_map'] = self::buildTagMap($loupan['tag']);
        $loupan['door_model_list'] = self::getDoorModelList($id);
        $house_imgs = self::getLoupanImgs($id);
        $loupan = array_merge($loupan, $house_imgs);
        return $loupan;
    }

    private static function getLoupanImgs($loupan_id)
    {
        $condition = ['object_id' => $loupan_id, 'type' => HouseConst::HOUSE_TYPE_NEW];
        $select = ['img_1', 'img_2', 'img_3', 'img_4', 'img_5'];
        return HouseImgModel::model()->getOneByCondition($condition, $select);
    }

}