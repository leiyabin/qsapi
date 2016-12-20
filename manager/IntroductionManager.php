<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/19
 * Time: 22:44
 */

namespace app\manager;

use app\models\ClassModel;
use app\models\IntroductionModel;
use app\exception\RequestException;
use app\consts\ErrorCode;
use app\components\Utils;
use Yii;
use app\consts\LogConst;

class IntroductionManager
{
    public static function add($introduction)
    {
        $id = $introduction['class_id'];
        $class = ClassModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('分类不存在！', ErrorCode::ACTION_ERROR);
        } else {
            IntroductionModel::model()->add($introduction);
        }
    }

    public static function getIntroductionList($page_info, $list_name, $condition)
    {
        $select = ['id', 'class_id', 'title', 'summary', 'img', 'c_t'];
        $data = IntroductionModel::model()->getList($page_info, $list_name, $condition, $select);
        if (!empty($data[$list_name])) {
            $news_list = $data[$list_name];
            $class_ids = array_column($news_list, 'class_id');
            $class_list = ClassModel::model()->getListByCondition(['id' => $class_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($news_list as $key => $value) {
                if (!isset($class_list[$value['class_id']])) {
                    $error_msg = sprintf('分类不存在 news_id: %d ,class_id: %d', $value['id'], $value['class_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取分类信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $news_list[$key]['class_name'] = $class_list[$value['class_id']]['name'];
            }
            $data[$list_name] = $news_list;
        }
        return $data;
    }
}