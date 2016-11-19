<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/18
 * Time: 13:46
 */

namespace app\models;
use app\components\LModel;

class LouPanModel  extends LModel
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
            [['name', 'opening_time', 'jiju', 'area_bottom', 'area_top', 'price', 'img','address'], 'trim'],
            [['name', 'phone', 'email', 'mobilephone', 'praise', 'position', 'img'], 'required'],
//            ['value', 'string', 'max' => 20],
//            ['class_id', 'integer'],
        ];
    }
}