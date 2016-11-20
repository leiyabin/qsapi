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
            [['name', 'phone', 'email', 'position_id', 'img', 'code'], 'trim'],
            [['name', 'phone', 'position_id', 'code'], 'required'],
            ['name', 'string', 'max' => 10],
            ['phone', 'string', 'max' => 20],
            ['code', 'integer'],
            ['position_id', 'integer'],
        ];
    }
}