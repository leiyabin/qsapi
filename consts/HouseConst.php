<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/21
 * Time: 8:57
 */

namespace app\consts;


class HouseConst
{
    //房屋类别
    const HOUSE_TYPE_NEW = 1;
    const HOUSE_TYPE_OLD = 2;

    //楼盘销售状态
    const SALE_STATUS_WAIT = 1;
    const SALE_STATUS_QUEUE = 2;
    const SALE_STATUS_SALING = 3;
    const SALE_STATUS_OVER = 4;
    public static $sale_status = [
        self::SALE_STATUS_WAIT   => '待售',
        self::SALE_STATUS_QUEUE  => '排卡中',
        self::SALE_STATUS_SALING => '在售',
        self::SALE_STATUS_OVER   => '售罄',
    ];

    //物业类型
    const PROPERTY_TYPE_HOUSE = 1;
    const PROPERTY_TYPE_SHOP = 2;
    const PROPERTY_TYPE_OFFICE = 3;
    const PROPERTY_TYPE_SOHO = 4;
    const PROPERTY_TYPE_FACTORY = 5;
    const PROPERTY_TYPE_OTHER = 6;
    public static $property_type = [
        self::PROPERTY_TYPE_HOUSE   => '住宅',
        self::PROPERTY_TYPE_SHOP    => '商用',
        self::PROPERTY_TYPE_OFFICE  => '写字楼',
        self::PROPERTY_TYPE_SOHO    => '商住两用',
        self::PROPERTY_TYPE_FACTORY => '厂房',
        self::PROPERTY_TYPE_OTHER   => '其它',
    ];

    //楼盘特色
    const FEATURE_1_CODE = 1;
    const FEATURE_2_CODE = 2;
    const FEATURE_3_CODE = 3;
    const FEATURE_4_CODE = 4;
    public static $feature = [
        self::FEATURE_1_CODE => '精装修',
        self::FEATURE_2_CODE => '离地铁近',
        self::FEATURE_3_CODE => '车位充足',
        self::FEATURE_4_CODE => '老城区房',
    ];

    //售价区间
    const PRICE_INTERVAL_1 = 1;
    const PRICE_INTERVAL_2 = 2;
    const PRICE_INTERVAL_3 = 3;
    const PRICE_INTERVAL_4 = 4;
    const PRICE_INTERVAL_5 = 5;
    const PRICE_INTERVAL_6 = 6;
    const PRICE_INTERVAL_7 = 7;
    const PRICE_INTERVAL_8 = 8;
    public static $price_interval = [
        self::PRICE_INTERVAL_1 => [0, 200],
        self::PRICE_INTERVAL_2 => [200, 300],
        self::PRICE_INTERVAL_3 => [300, 400],
        self::PRICE_INTERVAL_4 => [400, 500],
        self::PRICE_INTERVAL_5 => [500, 600],
        self::PRICE_INTERVAL_6 => [600, 700],
        self::PRICE_INTERVAL_7 => [700, 800],
        self::PRICE_INTERVAL_8 => [800, 10000],
    ];

    //面积区间
    const AREA_INTERVAL_1 = 1;
    const AREA_INTERVAL_2 = 2;
    const AREA_INTERVAL_3 = 3;
    const AREA_INTERVAL_4 = 4;
    const AREA_INTERVAL_5 = 5;
    const AREA_INTERVAL_6 = 6;
    const AREA_INTERVAL_7 = 7;
    const AREA_INTERVAL_8 = 8;
    public static $area_interval = [
        self::AREA_INTERVAL_1 => [0, 50],
        self::AREA_INTERVAL_2 => [50, 70],
        self::AREA_INTERVAL_3 => [70, 90],
        self::AREA_INTERVAL_4 => [90, 110],
        self::AREA_INTERVAL_5 => [110, 130],
        self::AREA_INTERVAL_6 => [130, 150],
        self::AREA_INTERVAL_7 => [150, 200],
        self::AREA_INTERVAL_8 => [200, 1000],
    ];

    //装修情况
    const DECORATION_BLANK = 1;
    const DECORATION_SIMPLE = 2;
    const DECORATION_GENERAL = 3;
    const DECORATION_HARDCOVER = 4;
    const DECORATION_LUXURY = 5;
    public static $decoration = [
        self::DECORATION_BLANK     => '毛坯',
        self::DECORATION_SIMPLE    => '简装',
        self::DECORATION_GENERAL   => '中装',
        self::DECORATION_HARDCOVER => '精装',
        self::DECORATION_LUXURY    => '豪装',
    ];

    //付款方式
    const BUY_TYPE_ALL = 1;
    const BUY_TYPE_BUSSINESS_LOAN = 2;
    const BUY_TYPE_FUND_LOAN = 3;

    public static $buy_type = [
        self::BUY_TYPE_ALL            => '全款',
        self::BUY_TYPE_BUSSINESS_LOAN => '商贷',
        self::BUY_TYPE_FUND_LOAN => '公积金',
    ];

    //默认经纪人
    const DEFAULT_BROKER_ID = 10000000;
    const DEFAULT_BROKER_NAME = '李凯';

    //产权类型
    const RIGHT_TYPE_CONST_PRICE = 1;
    const RIGHT_TYPE_SPECIAL_PRICE = 2;
    const RIGHT_TYPE_BUSINESS_HOUSE = 3;
    const RIGHT_TYPE_USE_POWER = 4;
    const RIGHT_TYPE_AFFORDABLE_HOUSE = 5;
    const RIGHT_TYPE_CHANGE_END = 6;
    const RIGHT_TYPE_TOWNSHIP_PROPERTY = 7;
    const RIGHT_TYPE_ARMY_PROPERTY = 8;
    public static $right_type = [
        self::RIGHT_TYPE_CONST_PRICE       => '成本价',
        self::RIGHT_TYPE_SPECIAL_PRICE     => '优惠价',
        self::RIGHT_TYPE_BUSINESS_HOUSE    => '商品房',
        self::RIGHT_TYPE_USE_POWER         => '使用权',
        self::RIGHT_TYPE_AFFORDABLE_HOUSE  => '经济适用房',
        self::RIGHT_TYPE_CHANGE_END        => '改底单',
        self::RIGHT_TYPE_TOWNSHIP_PROPERTY => '乡产',
        self::RIGHT_TYPE_ARMY_PROPERTY     => '军产',
    ];

}