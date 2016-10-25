<?php
/**
 * ControllerBase.php.
 * @author keepeye <carlton.cheng@foxmail>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
namespace app\controllers\api;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Response;

abstract class ControllerBase extends Controller
{
    /**
     * ajax错误信息
     *
     * @param $msg
     * @param $code
     * @return mixed
     */
    public function error($msg, $code = 3000)
    {
        $res = ['error_code' => $code, 'error_msg' => $msg];
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * ajax成功信息
     *
     * @param mixed $data
     * @return mixed
     */
    public function success($data = "")
    {
        $res = ['error_code' => 0, 'data' => $data];
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}