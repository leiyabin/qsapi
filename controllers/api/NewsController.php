<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/15
 * Time: 13:31
 */

namespace app\controllers\api;

use app\components\LController;
use app\consts\ErrorCode;
use app\exception\RequestException;
use app\manager\NewsManager;
use app\models\NewsModel;


class NewsController extends LController
{
    public function actionNewslist()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['class_id'])) {
            $condition['class_id'] = $this->params['class_id'];
        }
        if (!empty($this->params['title'])) {
            $condition['title'] = $this->params['title'];
        }
        $data = NewsManager::getNewsList($pageInfo, 'news_list', $condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionGetnews()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = NewsModel::model()->getById($id);
        return $this->success($model);
    }

    public function actionNewsadd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        NewsManager::addNews($this->params);
        return $this->success();
    }

    public function actionNewsedit()
    {
        $value = $this->params;
        $requires = ['id', 'title', 'class_id', 'summary', 'content'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        NewsModel::model()->updateById($value);
        return $this->success();
    }
}