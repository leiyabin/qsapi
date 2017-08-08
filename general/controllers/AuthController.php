<?php

/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2017/7/12
 * Time: 18:32
 */
class AuthController extends BaseController
{
    protected $user;
    protected $company;

    protected function beforeAction($action)
    {
        //微信使用的cookie
        $params = array(
            'tickets' => array('required' => true)
        );
        $params = $this->checkParams($params);
        $checkResult = Yii::app()->api->mallauth->checkAccess($params['tickets']);
        if (empty($checkResult)) {
            $this->errorResult(ResponseCode::USER_ERROR_TOKEN_NOT_EXIST, ResponseMsg::USER_LOGIN_ERROR);
        } else {
            $this->user = $checkResult['user'];
            $this->company = $checkResult['company'];
        }
        return parent::beforeAction($action);
    }
}