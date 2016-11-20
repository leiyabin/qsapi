<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 14:17
 */

namespace app\manager;

use app\components\Utils;
use app\consts\ErrorCode;
use app\consts\LogConst;
use app\exception\RequestException;
use app\models\ValueModel;
use app\models\AreaModel;
use Yii;

class AreaManager
{
    public static function add($config)
    {
        $id = $config['class_id'];
        $class = ValueModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('分类不存在！', ErrorCode::ACTION_ERROR);
        } else {
            AreaModel::model()->add($config);
        }
    }

    public static function getList($page_info, $list_name, $condition = [])
    {
        $data = AreaModel::model()->getList($page_info, $list_name, $condition);
        if (!empty($data[$list_name])) {
            $news_list = $data[$list_name];
            $class_ids = array_column($news_list, 'class_id');
            $class_list = ValueModel::model()->getListByCondition(['id' => $class_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($news_list as $key => $value) {
                if (!isset($class_list[$value['class_id']])) {
                    $error_msg = sprintf('分类不存在 area_id: %d ,class_id: %d', $value['id'], $value['class_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取分类信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $news_list[$key]['class_name'] = $class_list[$value['class_id']]['value'];
            }
            $data[$list_name] = $news_list;
        }
        return $data;
    }
}