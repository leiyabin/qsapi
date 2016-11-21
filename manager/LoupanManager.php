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
use app\models\DoorModel;
use app\models\HouseImgModel;
use app\models\LouPanModel;
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
        $loupan_filed = ['name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id',
            'property_type_id', 'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat',
            'developers', 'property_company', 'price', 'img', 'banner_img', 'right_time', 'remark', 'tag', 'is_deleted'
        ];
        $loupan_model = self::getFiled($loupan, $loupan_filed);
        $loupan_id = LouPanModel::model()->add($loupan_model);
        $house_img_model = $loupan['house_img'];
        foreach ($house_img_model as $key => $value) {
            $house_img_model[$key]['object_id'] = $loupan_id;
            $house_img_model[$key]['type'] = HouseConst::HOUSE_IMG_TYPE_NEW;
        }
        HouseImgModel::model()->batchAdd($house_img_model);
    }

    public static function addDoorModels($door_model)
    {
        $loupan_id = $door_model['loupan_id'];
        $loupan = LouPanModel::model()->getById($loupan_id);
        if (empty($loupan)) {
            throw new RequestException('楼盘不存在！', ErrorCode::ACTION_ERROR);
        } else {
            DoorModel::model()->add($door_model);
        }
    }

    public static function edit($loupan)
    {

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
    }

}