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
use app\models\LouPanModel;
use Yii;

class LoupanManager
{
    public static function getNewsList($page_info, $list_name, $condition)
    {
        $data = LouPanModel::model()->getList($page_info, $list_name, $condition);
        if (!empty($data[$list_name])) {
            foreach ($data[$list_name] as $key => $val) {
                $val['sale_status_name'] = HouseConst::$sale_status[$val['sale_status']];
                $val['property_type'] = HouseConst::$property_type[$val['property_type_id']];
                $val['sale_status_name'] = HouseConst::$sale_status[$val['sale_status']];
            }
//            $news_list = $data[$list_name];
//            $class_ids = array_column($news_list, 'class_id');
//            $class_list = ValueModel::model()->getListByCondition(['id' => $class_ids]);
//            $class_list = Utils::buildIdArray($class_list);
//            foreach ($news_list as $key => $value) {
//                if (!isset($class_list[$value['class_id']])) {
//                    $error_msg = sprintf('分类不存在 news_id: %d ,class_id: %d', $value['id'], $value['class_id']);
//                    Yii::error($error_msg, LogConst::APPLICATION);
//                    throw new RequestException('获取分类信息错误', ErrorCode::SYSTEM_ERROR);
//                }
//                $news_list[$key]['class_name'] = $class_list[$value['class_id']]['value'];
//            }
//            $data[$list_name] = $news_list;
        }
        return $data;
    }
}