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
use app\exception\RequestException;
use yii\base\Exception;
use app\consts\LogConst;

include_once dirname(__FILE__) . '/../extend/phpQuery/phpQuery.php';

class StatisticsModel extends LModel
{

    public $date_timestamp;

    public static function tableName()
    {
        return '{{%quotation}}';
    }

    /**
     * @return StatisticsModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->date_timestamp = Utils::getDateTimestamp();
    }

    public function getResult()
    {
        $yes_data = $this->getYesterdayData();
        $today_data = $this->getTodayData();
        $res = $today_data;
        $yzcjl_difference = $today_data['yzcjl'] - $yes_data['yzcjl'];
        $yzfydkl_difference = $today_data['yzfydkl'] - $yes_data['yzfydkl'];
        if ($yzcjl_difference > 0) {
            $res['yzcjl_change'] = 'up';
        } elseif ($yzcjl_difference < 0) {
            $res['yzcjl_change'] = 'down';
        }
        if ($yzfydkl_difference > 0) {
            $res['yzfydkl_change'] = 'up';
        } elseif ($yzfydkl_difference < 0) {
            $res['yzfydkl_change'] = 'down';
        }
        return $res;
    }

    public function getYesterdayData()
    {
        $date_timestamp = $this->date_timestamp - 86400;
        $data = $this->get($date_timestamp);
        if (empty($data)) {
            $data = [
                'cjjj'      => 0,
                'cjjj_name' => '上月成交均价',
                'gpjj'      => 0,
                'gpjj_name' => '上月份挂牌均价',
                'yzcjl'     => 0,
                'yzfydkl'   => 0,
            ];
        }
        return $data;
    }

    public function getTodayData()
    {
        $data = $this->get($this->date_timestamp);
        if (empty($data)) {
            $data = $this->getFromHtml();
        }
        return $data;
    }

    public function get($data)
    {
        $select = ['id', 'date', 'cjjj', 'cjjj_name', 'gpjj', 'gpjj_name', 'yzcjl', 'yzfydkl'];
        $where = ['date' => $data];
        $statistics = $this->find()
            ->addSelect($select)
            ->where($where)
            ->asArray()
            ->one();
        return $statistics;
    }

    public function rules()
    {
        return [
            [['date', 'cjjj', 'cjjj_name', 'gpjj', 'gpjj_name', 'yzcjl', 'yzfydkl'], 'required'],
            [['date', 'cjjj', 'gpjj', 'yzcjl', 'yzfydkl'], 'integer'],
            [['cjjj_name', 'gpjj_name'], 'string', 'max' => 20],
        ];
    }

    private function getFromHtml()
    {
        $statistics = [];
        try {
            $pq = \phpQuery::newDocumentFile('http://bj.5i5j.com/');
            $lis = $pq->find('div.data_box')->find('ul.overflow')->find('li');
            $result = [];
            foreach ($lis as $li) {
                $result[] = [
                    'value' => pq($li)->find('span')->text(),
                    'key'   => pq($li)->find('p.f16')->text(),
                ];
            }
            $statistics['cjjj'] = $result[0]['value'];
            $statistics['cjjj_name'] = $result[0]['key'];
            $statistics['gpjj'] = $result[1]['value'];
            $statistics['gpjj_name'] = $result[1]['key'];
            $statistics['yzcjl'] = $result[2]['value'];
            $statistics['yzfydkl'] = $result[3]['value'];
            $statistics['date'] = $this->date_timestamp;
            $this->set($statistics);
        } catch (\Exception $e) {
            \Yii::error($e->getMessage(), LogConst::RPC);
            $statistics = [
                'cjjj'      => 0,
                'cjjj_name' => '上月成交均价',
                'gpjj'      => 0,
                'gpjj_name' => '上月份挂牌均价',
                'yzcjl'     => 0,
                'yzfydkl'   => 0,
                'date'      => $this->date_timestamp,
            ];
        }
        return $statistics;
    }

    public function set($statistics)
    {
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

}