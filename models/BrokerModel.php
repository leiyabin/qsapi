<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/18
 * Time: 13:43
 */
namespace app\models;

use app\components\LModel;

class BrokerModel extends LModel
{
    public static function tableName()
    {
        return '{{%broker}}';
    }

    /**
     * @return BrokerModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'mobilephone', 'praise', 'position', 'img'], 'trim'],
            [['name', 'phone', 'email', 'mobilephone', 'praise', 'position', 'img'], 'required'],
//            ['value', 'string', 'max' => 20],
//            ['class_id', 'integer'],
        ];
    }
}