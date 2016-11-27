<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/27
 * Time: 19:03
 */

namespace app\components;


use app\consts\LogConst;
use app\consts\MsgConst;
use app\exception\RequestException;
use app\consts\ErrorCode;
use yii\web\Controller;
use Yii;

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
     * ajax成功信息
     *
     * @param mixed $data
     * @return mixed
     */
    public function success($data = MsgConst::DO_SUCCESS)
    {
        header("Content-type:application/json;charset=utf-8");
        $res = ['ret' => 1, 'data' => $data];
        $res_json = json_encode($res, JSON_UNESCAPED_UNICODE);
        $response = sprintf('【RESPONSE】 method: %s url: %s ; params: %s ; result: %s ',
            Yii::$app->request->getMethod(), Yii::$app->request->getUrl(),
            json_encode($this->params, JSON_UNESCAPED_UNICODE), $res_json);
        Yii::info($response, LogConst::RESPONSE);
        return $res_json;
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

    public function beforeAction($action)
    {
        $request = sprintf('【REQUEST】 method: %s url: %s ; params: %s',
            Yii::$app->request->getMethod(), Yii::$app->request->getUrl(), json_encode($this->params, JSON_UNESCAPED_UNICODE));
        Yii::info($request, LogConst::REQUEST);
        return parent::beforeAction($action);
    }

    public function runAction($id, $params = [])
    {
        try {
            return parent::runAction($id, $params);
        } catch (\Exception $e) {
            $error_string = sprintf('【error】 MSG:%s ;TRACE:%s ', $e->getMessage(), $e->getTraceAsString());
            Yii::error($error_string);
            $this->renderError($e->getCode(), $e->getMessage());
        }
    }

    private function renderError($error_code, $error_msg)
    {
        $controller_name = end(explode('/', $this->id));
        if ($controller_name == 'house') {
            $this->redirect('/sync/error/show/?error_msg=' . $error_msg)->send();
        }
        header("Content-type:application/json;charset=utf-8");
        $res = [
            'ret'  => 0,
            'data' => [
                'error_code' => $error_code,
                'msg'        => $error_msg
            ]
        ];
        $res_json = json_encode($res, JSON_UNESCAPED_UNICODE);
        $response = sprintf('【RESPONSE】 method: %s url: %s ; params: %s ; result: %s ',
            Yii::$app->request->getMethod(), Yii::$app->request->getUrl(),
            json_encode($this->params, JSON_UNESCAPED_UNICODE), $res_json);
        Yii::error($response, LogConst::RESPONSE);
        echo $res_json;
        exit ();
    }

    protected function getRequestParam($field, $default = null)
    {
        if (empty($this->params[$field])) {
            return $default;
        }
        return $this->params[$field];
    }

    protected function checkEmpty($params)
    {
        foreach ($params as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }

    }

    protected function checkIsset($params)
    {
        foreach ($params as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
    }

}