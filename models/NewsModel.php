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

    public function rules()
    {
        return [
            [['class_id', 'title', 'summary', 'content', 'hot', 'recommend'], 'trim'],
            [['class_id', 'title', 'summary', 'content'], 'required'],
            ['title', 'string', 'max' => 50],
            ['summary', 'string', 'max' => 160],
            ['img', 'string', 'max' => 50],
            ['recommend_img', 'string', 'max' => 50],
            ['hot_img', 'string', 'max' => 50],
        ];
    }

}