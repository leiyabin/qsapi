<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/15
 * Time: 13:34
 */

namespace app\manager;

use app\models\ClassModel;
use app\models\NewsModel;
use app\components\Utils;
use app\consts\LogConst;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\models\ValueModel;
use Yii;


class NewsManager
{
    public static function addNews($news)
    {
        $id = $news['class_id'];
        $class = ValueModel::model()->getById($id);
        if (empty($class)) {
            throw new RequestException('分类不存在！', ErrorCode::ACTION_ERROR);
        } else {
            NewsModel::model()->add($news);
        }
    }

    public static function getNewsList($page_info, $list_name, $condition)
    {
        $data = NewsModel::model()->getList($page_info, $list_name, $condition);
        if (!empty($data[$list_name])) {
            $news_list = $data[$list_name];
            $class_ids = array_column($news_list, 'class_id');
            $class_list = ValueModel::model()->getListByCondition(['id' => $class_ids]);
            $class_list = Utils::buildIdArray($class_list);
            foreach ($news_list as $key => $value) {
                if (!isset($class_list[$value['class_id']])) {
                    $error_msg = sprintf('分类不存在 news_id: %d ,class_id: %d', $value['id'], $value['class_id']);
                    Yii::error($error_msg, LogConst::APPLICATION);
                    throw new RequestException('获取分类信息错误', ErrorCode::SYSTEM_ERROR);
                }
                $news_list[$key]['class_name'] = $class_list[$value['class_id']]['value'];
            }
            $data[$list_name] = $news_list;
        }
        return $data;
    }

    public static function get($id)
    {
        $model = NewsModel::model()->getById($id);
        if (!empty($model)) {
            $class_id = $model['class_id'];
            $class = ValueModel::model()->getById($class_id);
            if (empty($class)) {
                $msg = sprintf('未找到百科类别。class_id=,id=', $class_id, $id);
                throw new RequestException($msg, ErrorCode::SYSTEM_ERROR);
            }
            $model['class_name'] = $class['value'];
        }
        return $model;
    }
}