<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/3
 * Time: 9:01
 */

namespace app\models;


use app\components\LModel;
use app\components\Utils;
use app\consts\ErrorCode;
use app\Exception\RequestException;
use yii\base\Exception;

class StatisticsModel extends LModel
{
    public static function tableName()
    {
        return '{{%statistics}}';
    }

    /**
     * @return StatisticsModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function get()
    {
        $date_timestamp = Utils::getDateTimestamp();
        $select = [
            'id', 'date', 'quanshijunjia', 'guapaijiunjia','zuorixinzeng', 'zuoridaikan', 'zuorichengjiao'
        ];
        $where = ['date' => $date_timestamp];
        $statistics = $this->find()
            ->addSelect($select)
            ->where($where)
            ->asArray()
            ->one();
        if (empty($statistics)) {
            $statistics = [
                'date'           => $date_timestamp,
                'quanshijunjia'  => 56771,
                'guapaijiunjia'  => 63021,
                'zuorixinzeng'   => 2.3,
                'zuorichengjiao' => 78,
                'zuoridaikan'    => 5600,
            ];
            $this->attributes = $statistics;
            if ($this->validate()) {
                try {
                    $this->save();
                } catch (Exception $e) {
                    throw new RequestException($e->getMessage(), ErrorCode::SYSTEM_ERROR);
                }
            } else {
                $error_msg = implode('', $this->getFirstErrors());
                throw new RequestException($error_msg, ErrorCode::INVALID_PARAM);
            }
        }
        return $statistics;
    }

    public function rules()
    {
        return [
            [['date', 'quanshijunjia', 'guapaijiunjia','zuorixinzeng', 'zuoridaikan', 'zuorichengjiao'], 'required'],
            [['date', 'quanshijunjia', 'guapaijiunjia', 'zuoridaikan', 'zuorichengjiao'], 'integer'],
            [['zuorixinzeng'], 'number'],
        ];
    }
}