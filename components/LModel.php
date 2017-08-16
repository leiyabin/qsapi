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
use Yii;
use app\consts\LogConst;

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

    //todo 逐步替换掉
    public function getList($page_info, $list_name, $condition = [], $select = ['*'],
                            $add_condition_1 = [], $add_condition_2 = [], $order_by = '', $filter_conditions = [])
    {
        //list
        $model = $this->find()
            ->addSelect($select)
            ->where($condition)
            ->andWhere($add_condition_1)
            ->andWhere($add_condition_2);
        if (!empty($filter_conditions)) {
            foreach ($filter_conditions as $filter_condition) {
                $model = $model->andFilterWhere($filter_condition);
            }
        }
        $total_model = clone $model;
        $limit = $page_info['limit'];
        $offset = $page_info['offset'];
        $model = $model->limit($limit)
            ->offset($offset)
            ->addOrderBy(empty($order_by) ? ['id' => SORT_DESC] : [$order_by => SORT_DESC])
            ->asArray();
        $this->outputSql($model);
        $list = $model->all();
        //total
        $total = $total_model->count('id');
        $res = [$list_name => $list, 'total' => $total];
        return $res;
    }

    //todo 逐渐废弃
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

    //============================以下是重构方法=============================
    public function _updateById($id, array $attributes)
    {
        $condition = ['id' => $id];
        return $this->updateByCondition($condition, $attributes);
    }

    public function getListByCondition($condition, $select = ['*'])
    {
        $model = $this->find()
            ->addSelect($select)
            ->where($condition)
            ->asArray();
        $this->outputSql($model);
        $list = $model->all();
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

    public function add($model)
    {
        $model['c_t'] = $model['u_t'] = time();
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
        if (YII_DEBUG) {
            $sql_log = sprintf('【sql】: %s , 【params】: %s', $sql, json_encode($fields, true));
            Yii::trace($sql_log, LogConst::SQL);
        }
        $command->query();
        $id = static::getDb()->getLastInsertId();
        return $id;
    }

    public function getById($id, $select = ['*'])
    {
        $condition = ['id' => $id];
        return $this->getOneByCondition($condition, $select);
    }

    public function getListByIds($ids, $select = ['*'])
    {
        $condition = ['id' => $ids];
        return $this->getListByCondition($condition, $select);
    }

    public function getPageList($condition = [], $str_condition = '', $filter_conditions = [], $order_by = [],
                                $select = ['*'], $page_info = null)
    {
        if (empty($order_by)) {
            $order_by = ['id' => SORT_DESC];
        }
        $limit = 20;
        $offset = 0;
        if (!empty($page_info)) {
            $limit = $page_info['limit'];
            $offset = $page_info['offset'];
        }
        $model = $this->find()
            ->addSelect($select)
            ->where($condition);
        if (!empty($str_condition)) {
            $model = $model->andWhere($str_condition);
        }
        if (!empty($filter_conditions)) {
            foreach ($filter_conditions as $filter_condition) {
                $model = $model->andFilterWhere($filter_condition);
            }
        }
        $total_model = clone $model;
        $model = $model->limit($limit)
            ->offset($offset)
            ->addOrderBy($order_by)
            ->asArray();
        $this->outputSql($model);
        $list = $model->all();
        $total = $total_model->count('*');
        $res = ['list' => $list, 'total' => $total];
        return $res;

    }

    public function updateByCondition($condition, $attributes)
    {
        $attributes = array_merge($attributes, ['c_t' => time(), 'u_t' => time()]);
        $class = get_called_class();
        return $class::updateAll($attributes, $condition);
    }

    public function getFewList($condition, $limit, $select = ['*'])
    {
        $order_by = ['id' => SORT_DESC];
        $model = $this->find()
            ->addSelect($select)
            ->where($condition)
            ->offset(0)
            ->limit($limit)
            ->addOrderBy($order_by)
            ->asArray();
        $this->outputSql($model);
        $list = $model->all();
        return $list;
    }

    //tools function
    public function outputSql(&$model)
    {
        if (YII_DEBUG) {
            $clone_model = clone $model;
            $sql = $clone_model->createCommand()->getRawSql();
            Yii::trace('【sql】:' . $sql, LogConst::SQL);
        }
    }

}