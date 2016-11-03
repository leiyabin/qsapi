<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/27
 * Time: 19:03
 */

namespace app\components;


use app\consts\ErrorCode;
use app\consts\MsgConst;
use yii\web\Controller;
use Yii;
use app\components\Utils;

class LController extends Controller
{
    protected $params;

    public function init()
    {
        $getParams = Yii::$app->request->get();
        $postParams = Yii::$app->request->post();
        $this->params = array_merge($getParams, $postParams);
    }

    public $enableCsrfValidation = false;

    /**
     * ajax错误信息
     *
     * @param $msg
     * @param $code
     * @return mixed
     */
    public function error($msg = MsgConst::DO_FAILED, $code = ErrorCode::SYSTEM_ERROR)
    {
        header("Content-type:application/json;charset=utf-8");
        $res = [
            'ret'  => 0,
            'data' => [
                'code' => $code,
                'msg'  => $msg
            ]
        ];
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * ajax成功信息
     *
     * @param mixed $data
     * @return mixed
     */
    public function success($data = MsgConst::DO_SUCCESS)
    {
        header("Content-type:application/json;charset=utf-8");
        $res = ['ret' => 1, 'data' => $data];
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function renderPage($data, $page_info)
    {
        $data['total_pages'] = empty($data['total']) ? 0 : ceil($data['total'] / $page_info['per_page']);
        $data['per_page'] = $page_info['per_page'];
        $data['page'] = $page_info['page'];
        return $this->success($data);
    }

    public function pageInfo()
    {
        $info['per_page'] = max(1, (int)Utils::getDefault($this->params, 'per_page', 20));
        $info['page'] = max(1, (int)Utils::getDefault($this->params, 'page', 1));
        $info['offset'] = ($info['page'] - 1) * $info['per_page'];
        $info['limit'] = $info['per_page'];
        return $info;
    }
}