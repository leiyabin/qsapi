<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/18
 * Time: 13:46
 */

namespace app\models;

use app\components\LModel;

class LouPanModel extends LModel
{
    public static function tableName()
    {
        return '{{%loupan}}';
    }

    /**
     * @return LouPanModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
                'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat', 'developers', 'property_company', 'price',
                'img', 'banner_img', 'right_time', 'tag', 'remark'], 'trim'],
            [['name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
                'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat', 'developers', 'property_company', 'price',
                'img', 'banner_img', 'right_time', 'tag'], 'required'],
            [['average_price', 'opening_time', 'area_id', 'property_type_id', 'sale_status', 'min_square', 'max_square',
                'price', 'right_time'], 'integer'],
            ['tag', 'string', 'max' => 10],
            [['lan', 'lat'], 'double'],
            [['name', 'jiju'], 'string', 'max' => 30],
            [['address', 'sale_office_address', 'developers', 'property_company', 'img', 'banner_img'], 'string', 'max' => 50]
        ];
    }
}