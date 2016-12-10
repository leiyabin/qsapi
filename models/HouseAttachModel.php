<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/12/8
 * Time: 23:19
 */

namespace app\models;

use app\components\LModel;


class HouseAttachModel extends LModel
{
    public static function tableName()
    {
        return '{{%house_attach}}';
    }

    /**
     * @return HouseAttachModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['id', 'sale_time', 'last_sale_time', 'deed_year', 'is_only', 'mortgage_info', 'right_info', 'community_name',
                'community_img', 'community_introduction', 'door_model_introduction', 'school_info', 'traffic_info',
                'community_average_price', 'build_year', 'total_building', 'total_door_model', 'build_type'], 'trim'],
            [['sale_time', 'last_sale_time', 'deed_year', 'mortgage_info', 'right_info', 'community_name',
                'community_img', 'community_introduction', 'door_model_introduction', 'school_info', 'traffic_info',
                'community_average_price', 'build_year', 'total_building', 'total_door_model', 'build_type'], 'required'],
            [['id', 'sale_time', 'last_sale_time', 'deed_year', 'is_only', 'community_average_price', 'build_year',
                'total_building', 'total_door_model', 'build_type'], 'integer'],
            [['right_info'], 'string', 'max' => 10],
            [['mortgage_info', 'community_name'], 'string', 'max' => 20],
            [['community_img'], 'string', 'max' => 100],
            [['community_introduction', 'door_model_introduction', 'school_info', 'traffic_info'], 'string', 'max' => 255],
        ];
    }

}