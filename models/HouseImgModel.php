<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 22:54
 */

namespace app\models;

use app\components\LModel;

class HouseImgModel extends LModel
{
    public static function tableName()
    {
        return '{{%house_img}}';
    }

    /**
     * @return HouseImgModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['type', 'object_id', 'img'], 'trim'],
            [['type', 'object_id', 'img'], 'required'],
            [['type', 'object_id'], 'integer'],
            ['img', 'string', 'max' => 50]
        ];
    }
}