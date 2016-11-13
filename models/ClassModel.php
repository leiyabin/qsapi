<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/13
 * Time: 19:14
 */

namespace app\models;
use app\components\LModel;
use app\components\Utils;
use app\consts\ErrorCode;
use app\exception\RequestException;

class ClassModel extends LModel
{
    public static function tableName()
    {
        return '{{%config_class}}';
    }

    /**
     * @return AdminModel
     */
    public static function model()
    {
        return parent::model();
    }
}