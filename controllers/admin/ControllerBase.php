<?php
/**
 * ControllerBase.php.
 * @author keepeye <carlton.cheng@foxmail>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
namespace app\controllers\admin;

use app\components\LController;
use yii\filters\AccessControl;

abstract class ControllerBase extends LController
{
    public $layout = 'admin';

    /**
     * ajax错误信息
     *
     * @param $message
     * @param array $extra
     * @return mixed
     */
    public function error($message,$extra=[])
    {
        return $this->out(0,$message,$extra);
    }

    /**
     * ajax成功信息
     *
     * @param string $message
     * @param array $extra
     * @return mixed
     */
    public function success($message="",$extra=[])
    {
        return $this->out(1,$message,$extra);
    }

    /**
     * ajax信息
     *
     * @param $status
     * @param $message
     * @param $extra
     * @return mixed
     */
    public function out($status,$message,$extra)
    {
        $data = ['status'=>$status,'message'=>$message];
        if (!empty($extra)) {
            $data = array_merge($data,$extra);
        }
        return json_encode($data);
    }
}