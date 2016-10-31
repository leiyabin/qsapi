<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%administrator_login}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $uid
 * @property string $username
 * @property string $ip
 * @property string $created_at
 */
class AdministratorLogin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['c_t'], 'safe'],
            [['type', 'username', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'uid' => 'Uid',
            'username' => 'Username',
            'ip' => 'Ip',
            'c_t' => 'Created At',
        ];
    }
}
