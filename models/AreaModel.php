<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 14:15
 */

namespace app\models;


use app\components\LModel;

class AreaModel extends LModel
{
    public static function tableName()
    {
        return '{{%area}}';
    }

    /**
     * @return AreaModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['name', 'class_id'], 'trim'],
            [['name', 'class_id'], 'required'],
            ['name', 'string', 'max' => 20],
            ['class_id', 'integer'],
        ];
    }
}