<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/30
 * Time: 16:09
 */

namespace app\components;

use app\consts\ErrorCode;
use app\exception\RequestException;

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

    public function getList($page_info, $list_name, $condition = [], $select = ['*'])
    {
        $limit = $page_info['limit'];
        $offset = $page_info['offset'];
        $list = $this->find()
            ->addSelect($select)
            ->where($condition)
            ->limit($limit)
            ->offset($offset)
            ->addOrderBy(['id' => SORT_DESC])
            ->asArray()
            ->all();
        $total = $this->find()
            ->addSelect(['id'])
            ->where($condition)
            ->count('id');
        $res = [$list_name => $list, 'total' => $total];
        return $res;
    }

    public function add($model)
    {
        $this->setOldAttributes(null);
        $this->attributes = $model;
        if ($this->validate()) {
            try {
                $this->insert();
                return $this->attributes['id'];
            } catch (\Exception $e) {
                throw new RequestException($e->getMessage(), ErrorCode::SYSTEM_ERROR);
            }
        } else {
            $error_msg = implode('', $this->getFirstErrors());
            throw new RequestException($error_msg, ErrorCode::INVALID_PARAM);
        }
    }

    public function addBySQL(array $model)
    {
        $timestamp = time();
        $model['c_t'] = $model['u_t'] = $timestamp;
        $fields = array_keys($model);
        $sql = sprintf(' INSERT INTO %s ', $this->tableName() . '(`' . join('`, `', $fields) . '`) VALUES');
        $val = [];
        foreach ($fields as $v) {
            $val[] = ':' . $v;
        }
        $value_sql = '(' . join(',', $val) . ')';
        $sql .= $value_sql;
        $command = static::getDb()->createCommand($sql);
        foreach ($fields as $v) {
            $bind_name = ':' . $v;
            $command->bindValue($bind_name, null === $model[$v] ? '' : $model[$v]);
        }
        $result = $command->query();
        return $result->count();
    }

    public function updateByCondition($condition, $attributes)
    {
        $attributes = array_merge($attributes, ['c_t' => time(), 'u_t' => time()]);
        $class = get_called_class();
        return $class::updateAllCounters($attributes, $condition);
    }

    public function updateById($data)
    {
        $id = $data['id'];
        $class = get_called_class();
        $model = $class::findOne($id);
        if (empty($model)) {
            throw new RequestException('该条记录不存在', ErrorCode::ACTION_ERROR);
        } else {
            try {
                $model->setAttributes($data);
                $model->save();
            } catch (\Exception $e) {
                throw new RequestException($e->getMessage(), ErrorCode::SYSTEM_ERROR);
            }
        }
    }
}