<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/12/21
 * Time: 8:47
 */

namespace app\manager;


use app\models\SearchModel;

class SearchManager
{
    public static function add($type, $object_id)
    {
        $condition = ['type' => $type, 'object_id' => $object_id];
        $record = SearchModel::model()->getOneByCondition($condition);
        if (empty($record)) {
            $model = ['type' => $type, 'object_id' => $object_id, 'count' => 1];
            SearchModel::model()->add($model);
        } else {
            $id = $record['id'];
            SearchModel::model()->_updateById($id, ['count' => ++$record['count']]);
        }
    }

    public static function getSearchData()
    {

        $list = SearchModel::model()->getFewList();
    }
}