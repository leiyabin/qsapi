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
use app\exception\ResponseException;
use app\manager\NewsManager;
use app\models\NewsModel;


class NewsController extends LController
{
    const NEWS_TAG_HOT = 1;
    const NEWS_TAG_RECOMMEND = 2;

    public function actionList()
    {
        $pageInfo = $this->pageInfo();
        $condition = [];
        if (!empty($this->params['class_id'])) {
            $condition['class_id'] = $this->params['class_id'];
        }
        if (!empty($this->params['title'])) {
            $condition['title'] = $this->params['title'];
        }
        if (!empty($this->params['tag'])) {
            if ($this->params['tag'] == self::NEWS_TAG_HOT) {
                $condition['hot'] = 1;
            }
            if ($this->params['tag'] == self::NEWS_TAG_RECOMMEND) {
                $condition['recommend'] = 1;
            }
        }
        $data = NewsManager::getNewsList($pageInfo, 'news_list', $condition);
        return $this->renderPage($data, $pageInfo);
    }

    public function actionFewlist()
    {
        if (empty($this->params['limit'])) {
            throw new ResponseException('limit不能为空！', ErrorCode::INVALID_PARAM);
        }
        $condition = [];
        if (!empty($this->params['tag'])) {
            if ($this->params['tag'] == self::NEWS_TAG_HOT) {
                $condition['hot'] = 1;
            }
            if ($this->params['tag'] == self::NEWS_TAG_RECOMMEND) {
                $condition['recommend'] = 1;
            }
        }
        if (!empty($this->params['class_id'])) {
            $condition['class_id'] = $this->params['class_id'];
        }
        $list = NewsManager::getList($condition, $this->params['limit']);
        return $this->success($list);
    }

    public function actionGet()
    {
        if (empty($this->params['id'])) {
            throw new RequestException('id参数为空！', ErrorCode::INVALID_PARAM);
        }
        $id = $this->params['id'];
        $model = NewsManager::get($id);
        return $this->success($model);
    }

    public function actionAdd()
    {
        if (empty($this->params['class_id'])) {
            throw new RequestException('class_id不能为空', ErrorCode::INVALID_PARAM);
        }
        NewsManager::addNews($this->params);
        return $this->success();
    }

    public function actionEdit()
    {
        $news = $this->params;
        $requires = ['id', 'title', 'class_id', 'summary', 'content'];
        foreach ($requires as $require) {
            if (empty($this->params[$require])) {
                throw new RequestException($require . '不能为空', ErrorCode::INVALID_PARAM);
            }
        }
        NewsModel::model()->updateById($news);
        return $this->success();
    }

    public function actionBatchdel()
    {
        if (empty($this->params['ids']) && !is_array($this->params['ids'])) {
            throw new RequestException('ids参数不正确！', ErrorCode::INVALID_PARAM);
        }
        $ids = $this->params['ids'];
        NewsModel::model()->batchDel($ids);
        return $this->success();
    }
}