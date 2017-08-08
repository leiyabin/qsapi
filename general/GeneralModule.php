<?php

/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2017/7/12
 * Time: 18:26
 */
class GeneralModule extends CWebModule
{
    public $layout = false;
    protected $contextData = null;

    public function init()
    {
        $this->setControllerPath(Yii::app()->basePath . '/modules/general/controllers');
        $this->setContextData($this->getUrlParams());
    }

    private function  getUrlParams()
    {
        $method = Yii::app()->request->getRequestType();

        if ($method == 'POST') {
            $postStr = file_get_contents("php://input");
            //解决swagger Post传值,接不到的问题
            if (empty($postStr)) {
                $postStr = json_encode($_GET);
            }
        } else {
            $postStr = $_GET;
        }

        return $postStr;
    }

    protected function  setContextData($params)
    {
        $this->contextData = $params;
    }

    public function getContextData($decode = true)
    {
        if ($decode && !is_array($this->contextData)) {
            $this->contextData = json_decode((string)$this->contextData, true);
        }
        return $this->contextData;
    }

    protected function  getFilters()
    {
    }
}