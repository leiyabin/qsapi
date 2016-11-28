<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/27
 * Time: 20:47
 */

namespace app\manager;

use Yii;

use app\models\HouseModel;
use app\models\AreaModel;
use app\exception\RequestException;
use app\consts\LogConst;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\models\HouseImgModel;

class HouseManager
{
    public static function getHouse($id)
    {
        //楼盘详情页信息
        $house = HouseModel::model()->getById($id);
        if (!empty($house)) {
            $area = AreaModel::model()->getById($house['area_id']);
            if (empty($area)) {
                $error_msg = sprintf('片区不存在： house_id: %d ,area_id: %d', $id, $house['area_id']);
                Yii::error($error_msg, LogConst::APPLICATION);
                throw new RequestException($error_msg, ErrorCode::SYSTEM_ERROR);
            }
            $house['quxian_id'] = $area['class_id'];
            $house['area_name'] = $area['name'];
            $house['decoration_name'] = HouseConst::$decoration[$house['decoration']];
            $house['property_type'] = HouseConst::$property_type[$house['property_type_id']];
            $house_imgs = self::getHouseImgs($id);
            $house = array_merge($house, $house_imgs);
        }
        return $house;
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
        if(isset($house_img['object_id'])){
            unset($house_img['object_id']);
        }
        if(isset($house_img['type'])){
            unset($house_img['type']);
        }
        $house_img_id = HouseImgModel::model()->getOneByCondition($condition,['id']);
        $house_img['id'] = $house_img_id;
        HouseImgModel::model()->updateById($house_img);
    }
}