<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/19
 * Time: 22:50
 */

namespace app\models;

use app\components\LModel;

class IntroductionModel extends LModel
{
    public static function tableName()
    {
        return '{{%introduction}}';
    }

    /**
     * @return IntroductionModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function rules()
    {
        return [
            [['class_id', 'title', 'summary', 'content'], 'trim'],
            [['class_id', 'title', 'summary', 'content'], 'required'],
            ['title', 'string', 'max' => 50],
            ['summary', 'string', 'max' => 160],
        ];
    }
}