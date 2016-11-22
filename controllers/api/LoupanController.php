<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/21
 * Time: 23:30
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\LoupanManager;

class LoupanController extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['area_id'])) {
            $condition['area_id'] = $this->params['area_id'];
        }
        if (!empty($this->params['name'])) {
            $condition['name'] = $this->params['name'];
        }
        if (!empty($this->params['average_price'])) {
            $condition['average_price'] = $this->params['average_price'];
        }
        if (!empty($this->params['property_type_id'])) {
            $condition['property_type_id'] = $this->params['property_type_id'];
        }
        if (!empty($this->params['sale_status'])) {
            $condition['sale_status'] = $this->params['sale_status'];
        }
        $data = LoupanManager::getList($pageInfo, 'loupan_list', $condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $model = LoupanManager::getLoupan($this->params['id']);
        return $this->success($model);
    }

    public function actionAdd()
    {
        $requires = [
            'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
            'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat', 'developers',
            'property_company', 'img', 'banner_img', 'right_time', 'remark', 'tag', 'img_1', 'img_2', 'img_3', 'img_4'
        ];
        $this->checkEmpty($requires);
        if (!isset($this->params['img_5'])) {
            $this->params['img_5'] = '';
        }
        LoupanManager::addLoupan($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $requires = [
            'id', 'name', 'average_price', 'address', 'sale_office_address', 'opening_time', 'area_id', 'property_type_id',
            'sale_status', 'jiju', 'min_square', 'max_square', 'lan', 'lat', 'developers',
            'property_company', 'img', 'banner_img', 'right_time', 'remark', 'tag', 'img_1', 'img_2', 'img_3', 'img_4'
        ];
        $this->checkEmpty($requires);
        if (!isset($this->params['img_5'])) {
            $this->params['img_5'] = '';
        }
        LoupanManager::->updateById($news);
        return $this->success();
        $requires = ['area_id', 'property_type_id', 'sale_status'];
        $this->checkEmpty($requires);
        LoupanManager::addLoupan($this->params);
        return $this->success();
    }
}