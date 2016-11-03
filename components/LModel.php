<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/30
 * Time: 16:09
 */

namespace app\components;


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
}