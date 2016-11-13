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
            ValueModel::model()->add($class);
        }
    }

    public static function getValueList($page_info, $list_name)
    {
        $data = ValueModel::model()->getList($page_info, $list_name);
        if (!empty($data[$list_name])) {
            $value_list = $data[$list_name];
            $value_ids = array_column($value_list, 'id');
            $class_list = ClassModel::model()->getListByCondition(['id' => $value_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($value_list as $value) {
                if (!isset($class_list[$value['class_id']])) {
                    Yii::error('value class_id has error ');
                    throw new RequestException('获取配置信息错误', ErrorCode::SYSTEM_ERROR);
                }
            }
        }
        return $data;
    }
}