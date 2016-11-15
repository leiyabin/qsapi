<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/14
 * Time: 23:37
 */

namespace app\models;
use app\components\LModel;

class NewsModel extends LModel
{
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @return NewsModel
     */
    public static function model()
    {
        return parent::model();
    }

}