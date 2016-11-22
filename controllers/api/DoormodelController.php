<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/22
 * Time: 8:20
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\LoupanManager;
use app\models\DoorModel;

class DoormodelController extends LController
{
    public function actionList()
    {
        if (empty($this->params['loupan_id'])) {
            throw new RequestException('loupan_id不能为空！', ErrorCode::INVALID_PARAM);
        }
        $data = LoupanManager::getDoorModelList($this->params['loupan_id']);
        return $this->success($data);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id不能为空！', ErrorCode::INVALID_PARAM);
        }
        $data = LoupanManager::getDoorModel($this->params['id']);
        return $this->success($data);
    }

    public function actionBatchdel()
    {
        if (empty($this->params['ids']) && !is_array($this->params['ids'])) {
            throw new RequestException('ids参数不正确！', ErrorCode::INVALID_PARAM);
        }
        $ids = $this->params['ids'];
        DoorModel::model()->batchDel($ids);
        return $this->success();
    }

    public function actionEdit()
    {
        $requires = [
            'id', 'loupan_id', 'face', 'shitinwei', 'build_area', 'decoration',
            'img', 'description', 'tag_1', 'tag_2', 'tag_3'
        ];
        $this->checkEmpty($requires);
        LoupanManager::editDoorModel($this->params);
        return $this->success();
    }

    public function actionAdd()
    {
        $requires = ['loupan_id', 'decoration'];
        $this->checkEmpty($requires);
        LoupanManager::addDoorModel($this->params);
        return $this->success();
    }
}