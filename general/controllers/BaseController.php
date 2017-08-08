<?php

/**
 * Class BaseController
 */
class BaseController extends CController
{

    public function  errorResult($msg, $code)
    {
        $json['ret'] = 0;
        $json['error'] = array('code' => $code, 'msg' => $msg);
        echo json_encode($json);
        exit;
    }

    /**
     * @param $data
     * @param null $total
     */
    public function  jsonResult($data, $total = null)
    {
        $json['ret'] = 1;
        $json['data'] = $data;
        if ($total !== null) {
            $json['total'] = $total;
        }
        echo json_encode($json);
        exit;
    }

    public function checkParams($params)
    {
        Yii::log('---------checkParams START TIME-----------' . date('Y-m-d H:i:s') . "\ls -rltn");
        Yii::log("checkParams: \n");
        if ($params === null) return null;
        else {
            $postStr = $this->getModule()->getContextData();
            foreach ($params as $key => $value) {
                if (!isset($postStr[$key]) || empty($postStr[$key])) {
                    if ($value['required']) {
                        //必填
                        $this->errorResult(ResponseMsg::PARAMS_MISSING . $key, ResponseCode::ERROR_INVALID_PARAM);
                    }
                    if (isset($value['default'])) {
                        $postStr[$key] = $value['default'];
                    }
                } else {
                    //类型验证
                    if (isset($value['int']) && $value['int']) {
                        if (!is_numeric($postStr[$key])) {
                            $this->errorResult(ResponseMsg::PARAMS_TYPE_ERROR . $key, ResponseCode::ERROR_INVALID_PARAM);
                        }
                    }
                }
            }
            return $postStr;
        }
    }


    /**
     * 统一异常处理
     * add by panzhiqi
     */
    public function runAction($action)
    {
        $normal = '1';//默认正常状态
        if (SYSTEM_MAINTENANCE == $normal) {
            try {
                parent::runAction($action);
            } catch (ServiceException $e) {
                $this->errorResult($e->getMessage(), $e->getCode());
            } catch (CDbException $ce) {
                $this->errorResult(ResponseMsg::SYSTEM_ERROR . Common::CUSTOMERPHONE, $ce->getCode());
            } catch (Exception $ee) {
                $this->errorResult($ee->getMessage(), $ee->getCode());
            }
        } else {
            $this->errorResult(ResponseMsg::SYSTEM_MAINTENCE . Common::CUSTOMERPHONE, 0);
        }
    }

}