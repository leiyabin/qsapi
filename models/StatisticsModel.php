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

include_once dirname(__FILE__) . '/../extend/phpQuery/phpQuery.php';

class StatisticsModel extends LModel
{
    private $date_timestamp;

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

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->date_timestamp = Utils::getDateTimestamp();
    }

    public function get()
    {
        $select = [
            'id', 'date', 'quanshijunjia', 'guapaijiunjia', 'zuorixinzeng', 'zuoridaikan', 'zuorichengjiao'
        ];
        $where = ['date' => $this->date_timestamp];
        $statistics = $this->find()
            ->addSelect($select)
            ->where($where)
            ->asArray()
            ->one();
        if (empty($statistics)) {
            $statistics = $this->getFromHtml();
        }
        return $statistics;
    }

    public function rules()
    {
        return [
            [['date', 'quanshijunjia', 'guapaijiunjia', 'zuorixinzeng', 'zuoridaikan', 'zuorichengjiao'], 'required'],
            [['date', 'quanshijunjia', 'guapaijiunjia', 'zuoridaikan', 'zuorichengjiao'], 'integer'],
            [['zuorixinzeng'], 'number'],
        ];
    }

    private function getFromHtml()
    {
        $statistics = [];
        try {
            $pq = \phpQuery::newDocumentFile('http://bj.lianjia.com/');
            $statistics['quanshijunjia'] = $pq->find('div.deal-price')->find('label.dataAuto')->text();
            $statistics['guapaijiunjia'] = $pq->find('div.listing-price')->find('label.dataAuto')->text();
            $statistics['quanshijunjia'] = str_replace(PHP_EOL, '', trim($statistics['quanshijunjia']));
            $statistics['guapaijiunjia'] = str_replace(PHP_EOL, '', trim($statistics['guapaijiunjia']));
            $data = $pq->find('div.main')->find('li')->find('label')->text();
            $data = str_replace(PHP_EOL, ',', trim($data));
            $arr = explode(',', $data);
            $statistics['zuorixinzeng'] = $arr[0];
            $statistics['zuorichengjiao'] = $arr[1];
            $statistics['zuoridaikan'] = $arr[2];
            $statistics['date'] = $this->date_timestamp;
            $this->set($statistics);
        } catch (\Exception $e) {
            $statistics = [
                'quanshijunjia'  => 0,
                'guapaijiunjia'  => 0,
                'zuorixinzeng'   => 0,
                'zuorichengjiao' => 0,
                'zuoridaikan'    => 0,
                'date'           => $this->date_timestamp,
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