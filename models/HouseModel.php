<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/27
 * Time: 20:55
 */

namespace app\models;

use app\components\LModel;

class HouseModel extends LModel
{
    public static function tableName()
    {
        return '{{%house}}';
    }

    /**
     * @return HouseModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['area_id', 'property_company', 'address', 'property_type_id', 'house_age', 'in_floor', 'total_floor', 'broker_id',
                'jishi', 'jitin', 'jiwei', 'jichu', 'jiyangtai', 'decoration', 'right_type', 'buy_type', 'unit_price', 'tag',
                'total_price', 'face', 'build_area', 'use_area', 'house_facility', 'house_description', 'floor_unit', 'keywords',
                'school_info', 'is_school_house'
            ], 'trim'],
            [['id', 'property_company', 'property_type_id', 'area_id', 'decoration'], 'required'],
            [['id', 'area_id', 'property_type_id', 'house_age', 'in_floor', 'total_floor', 'jishi', 'jitin', 'jiwei', 'jichu',
                'jiyangtai', 'decoration', 'right_type', 'buy_type', 'broker_id'], 'integer'],
            [['lon', 'lat', 'unit_price', 'total_price', 'build_area', 'use_area'], 'double'],
            [['face'], 'string', 'max' => 10],
            [['floor_unit'], 'string', 'max' => 20],
            [['property_company', 'address'], 'string', 'max' => 50],
            [['house_img'], 'string', 'max' => 100],
            [['house_facility', 'house_description', 'keywords', 'school_info'], 'string', 'max' => 255],
        ];
    }

    public function getPageList($condition = [], $str_condition = '', $operate_conditions = [], $order_by = [],
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
            $model = $model->andWhere($str_condition['sql'], $str_condition['params']);
        }
        if (!empty($operate_conditions)) {
            $model = $model->andWhere($operate_conditions);
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

}