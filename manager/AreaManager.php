<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 14:17
 */

namespace app\manager;

use app\components\Utils;
use app\consts\ConfigConst;
use app\consts\ErrorCode;
use app\consts\LogConst;
use app\consts\UtilsConst;
use app\exception\RequestException;
use app\models\ValueModel;
use app\models\AreaModel;
use Yii;

class AreaManager
{
    public static function add($area)
    {
        $id = $area['class_id'];
        $class = ValueModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('分类不存在！', ErrorCode::ACTION_ERROR);
        }
        $condition = ['class_id' => $id, 'name' => $area['name']];
        $res = AreaModel::model()->getOneByCondition($condition);
        if (!empty($res)) {
            throw new RequestException('添加的数据已存在！', ErrorCode::ACTION_ERROR);
        }
        AreaModel::model()->add($area);
    }

    public static function getPageList($page_info, $class_id = 0, $is_trip_area = false, $name = '')
    {
        $area_condition = [];
        if (!empty($name)) {
            $area_condition = ['name' => $name];
        }
        if (!empty($class_id)) {
            $class = ValueModel::model()->getById($class_id);
            $area_condition['class_id'] = $class_id;
            $area_list = AreaModel::model()->getPageList($area_condition, '', [], [], ['*'], $page_info);
            if (!empty($area_list['list'])) {
                foreach ($area_list['list'] as $key => $list) {
                    $area_list['list'][$key]['class_name'] = $class['value'];
                }
            }
        } else {
            $condition = ['class_id' => ConfigConst::AREA_CLASS_CONST];
            if ($is_trip_area) {
                $condition = ['class_id' => ConfigConst::TRIP_AREA_CLASS_CONST];
            }
            $class_list = ValueModel::model()->getListByCondition($condition);
            $class_ids = array_column($class_list, 'id');
            $class_id_array = Utils::buildIdArray($class_list);
            $area_condition['class_id'] = $class_ids;
            $area_list = AreaModel::model()->getPageList($area_condition, '', [], [], ['*'], $page_info);
            if (!empty($area_list['list'])) {
                foreach ($area_list['list'] as $key => $list) {
                    $area_list['list'][$key]['class_name'] = $class_id_array[$list['class_id']]['value'];
                }
            }
        }
        return $area_list;
    }

    public static function getAreaList($area_ids)
    {
        $area_list = AreaModel::model()->getListByIds($area_ids);
        if (!empty($area_list)) {
            $class_ids = array_column($area_list, 'class_id');
            $class_list = ValueModel::model()->getListByIds($class_ids);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($area_list as $key => $area) {
                if (!isset($class_list[$area['class_id']])) {
                    $error_msg = sprintf('分类不存在 area_id: %d ,class_id: %d', $area['id'], $area['class_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取分类信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $area_list[$key]['class_name'] = $class_list[$area['class_id']]['value'];
            }
        }
        return $area_list;
    }

    public static function getAllArea()
    {
        $condition = ['class_id' => ConfigConst::AREA_CLASS_CONST];
        $quxian_list = ValueModel::model()->getListByCondition($condition, ['id', 'value']);
        $quxian_list = Utils::buildIdArray($quxian_list);
        $quxian_ids = array_keys($quxian_list);
        $condition = ['class_id' => $quxian_ids];
        $area_list = AreaModel::model()->getListByCondition($condition, ['id', 'name', 'class_id']);
        foreach ($area_list as $key => $value) {
            $area_list[$key]['class_name'] = $quxian_list[$value['class_id']]['value'];
        }
        return $area_list;
    }

}