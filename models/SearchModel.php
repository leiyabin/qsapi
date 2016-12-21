<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/12/21
 * Time: 8:26
 */

namespace app\models;

use app\components\LModel;


class SearchModel extends LModel
{
    public static function tableName()
    {
        return '{{%search}}';
    }

    /**
     * @return HouseModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['type', 'object_id', 'count'], 'trim'],
            [['type', 'object_id', 'count'], 'required'],
            [['type', 'object_id', 'count'], 'integer'],
        ];
    }
}