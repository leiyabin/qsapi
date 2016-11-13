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
     * @return AdminModel
     */
    public static function model()
    {
        return parent::model();
    }
}