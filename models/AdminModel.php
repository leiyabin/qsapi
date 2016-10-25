<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/24
 * Time: 20:46
 */
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class AdminModel extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%admin}}';
    }

    public static function getList()
    {
        $list = AdminModel::find()->addSelect(['id'])->asArray()->all();
        return $list;
    }

    public static function modify()
    {

    }

    public static function del()
    {

    }

    public static function getOne()
    {

    }

    public static function Add()
    {

    }

}