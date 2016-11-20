<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/20
 * Time: 22:55
 */

namespace app\models;

use app\components\LModel;

class DoorModel extends LModel
{
    public static function tableName()
    {
        return '{{%door_model}}';
    }

    /**
     * @return DoorModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['loupan_id', 'face', 'shitinwei', 'build_area', 'decoration', 'img', 'description', 'tag_1', 'tag_2', 'tag_3'], 'trim'],
            [['loupan_id', 'face', 'shitinwei', 'build_area', 'decoration', 'img', 'description', 'tag_1', 'tag_2', 'tag_3'], 'required'],
            [['loupan_id', 'build_area', 'decoration'], 'integer'],
            [['face', 'shitinwei', 'tag_1', 'tag_2', 'tag_3'], 'string', 'max' => 10],
            [['img', 'description'], 'string', 'max' => 50],
        ];
    }
}