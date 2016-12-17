<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/18
 * Time: 13:59
 */

namespace app\manager;

use app\components\Utils;
use app\consts\ErrorCode;
use app\consts\HouseConst;
use app\consts\LogConst;
use app\exception\RequestException;
use app\exception\ResponseException;
use app\models\BrokerModel;
use app\models\ValueModel;
use Yii;

class BrokerManager
{
    public static function add($broker)
    {
        $id = $broker['position_id'];
        $class = ValueModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('职位不存在！', ErrorCode::ACTION_ERROR);
        } else {
            BrokerModel::model()->add($broker);
        }
    }

    public static function getList($page_info, $list_name, $condition, $broker_type_condition)
    {
        $data = BrokerModel::model()->getList($page_info, $list_name, $condition, [], $broker_type_condition);
        if (!empty($data[$list_name])) {
            $broker_list = $data[$list_name];
            $class_ids = array_column($broker_list, 'position_id');
            $class_list = ValueModel::model()->getListByCondition(['id' => $class_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($broker_list as $key => $value) {
                if (!isset($class_list[$value['position_id']])) {
                    $error_msg = sprintf('职位不存在 broker_id: %d ,position_id: %d', $value['id'], $value['position_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取职位信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $broker_list[$key]['position_name'] = $class_list[$value['position_id']]['value'];
            }
            $data[$list_name] = $broker_list;
        }
        return $data;
    }

    public static function getListByCondition($condition, $select = ['*'])
    {
        return BrokerModel::model()->getListByCondition($condition, $select);
    }
}