<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/13
 * Time: 22:16
 */

namespace app\manager;

use app\components\Utils;
use app\consts\ErrorCode;
use app\consts\LogConst;
use app\exception\RequestException;
use app\models\ClassModel;
use app\models\ValueModel;
use Yii;

class ConfigManager
{
    public static function addValue($config)
    {
        $id = $config['class_id'];
        $class = ClassModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('分类不存在！', ErrorCode::ACTION_ERROR);
        } else {
            ValueModel::model()->add($config);
        }
    }

    public static function getValueList($page_info, $list_name, $condition = [])
    {
        $data = ValueModel::model()->getList($page_info, $list_name, $condition);
        if (!empty($data[$list_name])) {
            $value_list = $data[$list_name];
            $value_ids = array_column($value_list, 'class_id');
            $class_list = ClassModel::model()->getListByCondition(['id' => $value_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($value_list as $key => $value) {
                if (!isset($class_list[$value['class_id']])) {
                    $error_msg = sprintf('配置分类不存在 value: %s ,class_id: %s', $value['value'], $value['class_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取配置信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $value_list[$key]['class_name'] = $class_list[$value['class_id']]['name'];
            }
            $data[$list_name] = $value_list;
        }
        return $data;
    }
}