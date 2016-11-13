<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/30
 * Time: 16:09
 */

namespace app\components;


use yii\db\ActiveRecord;

class LModel extends ActiveRecord
{
    private static $_models = [];

    public static function model()
    {
        $className = get_called_class();
        if (isset(self::$_models[$className]))
            return self::$_models[$className];
        else {
            $model = self::$_models[$className] = new $className(null);
            return $model;
        }
    }

    public function getListByCondition($condition, $select = ['*'])
    {
        $list = $this->find()
            ->addSelect($select)
            ->where($condition)
            ->asArray()
            ->all();
        return $list;
    }

    public function getOneByCondition($condition, $select = ['*'])
    {
        $list = $this->getListByCondition($condition, $select);
        if (!empty($list)) {
            return current($list);
        }
        return array();
    }

    public function batchDel($ids)
    {
        $class = get_called_class();
        $condition = ['id' => $ids];
        $class::deleteAll($condition);
    }

    public function getById($id, $select = ['*'])
    {
        $model = $this->find()
            ->addSelect($select)
            ->where(['id' => $id])
            ->asArray()
            ->one();
        return $model;
    }

    public function getList($page_info, $list_name, $select = ['*'])
    {
        $limit = $page_info['limit'];
        $offset = $page_info['offset'];
        $list = $this->find()
            ->addSelect($select)
            ->limit($limit)
            ->offset($offset)
            ->addOrderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
        $total = $this->find()
            ->addSelect(['id'])
            ->count('id');
        $res = [$list_name => $list, 'total' => $total];
        return $res;
    }
}