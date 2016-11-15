<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/13
 * Time: 19:15
 */

namespace app\models;

use app\components\LModel;

class ValueModel extends LModel
{
    public static function tableName()
    {
        return '{{%config_value}}';
    }

    /**
     * @return ValueModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['value', 'class_id'], 'trim'],
            [['value', 'class_id'], 'required'],
            ['value', 'string', 'max' => 20],
            ['class_id', 'integer'],
        ];
    }

}