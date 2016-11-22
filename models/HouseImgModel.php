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
            [['type', 'object_id', 'img_1', 'img_2', 'img_3', 'img_4', 'img_5'], 'trim'],
            [['type', 'object_id', 'img_1', 'img_2', 'img_3', 'img_4'], 'required'],
            [['type', 'object_id'], 'integer'],
            [['img_1', 'img_2', 'img_3', 'img_4', 'img_5'], 'string', 'max' => 50]
        ];
    }
}