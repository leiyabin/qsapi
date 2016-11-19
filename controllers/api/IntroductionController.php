<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/19
 * Time: 22:41
 */

namespace app\controllers\api;

use app\components\LController;
use app\manager\IntroductionManager;
use app\exception\RequestException;
use app\consts\ErrorCode;
use app\models\IntroductionModel;

class IntroductionController  extends LController
{
    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['class_id'])) {
            $condition['class_id'] = $this->params['class_id'];
        }
        $data = IntroductionManager::getIntroductionList($pageInfo, 'introduction_list', $condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = IntroductionModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionAdd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        IntroductionManager::add($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $value = $this->params;
        $requires = ['id', 'title', 'class_id', 'summary', 'content'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        IntroductionModel::model()->updateById($value);
        return $this->success();
    }

    public function actionBatchdel()
    {
        if (empty($this->params['ids']) && !is_array($this->params['ids'])) {
            throw new RequestException('ids参数不正确！', ErrorCode::INVALID_PARAM);
        }
        $ids = $this->params['ids'];
        IntroductionModel::model()->batchDel($ids);
        return $this->success();
    }
}