<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/10/24
 * Time: 20:46
 */
namespace app\models;

use app\components\LModel;
use app\components\Utils;
use app\consts\ErrorCode;
use app\exception\RequestException;

class AdminModel extends LModel
{
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * @return AdminModel
     */
    public static function model()
    {
        return parent::model();
    }

    public function getByUsername($username)
    {
        $where = ['username' => $username];
        $select = ['id', 'name', 'username', 'phone', 'email', 'c_t'];
        $admin = $this->find()
            ->addSelect($select)
            ->where($where)
            ->asArray()
            ->one();
        return $admin;
    }

    public function add($admin)
    {
        $this->attributes = $admin;
        if ($this->validate()) {
            try {
                $this->setAttribute('password', Utils::lMd5($this->getAttribute('password')));
                $this->save();
            } catch (\Exception $e) {
                throw new RequestException($e->getMessage(), ErrorCode::SYSTEM_ERROR);
            }
        } else {
            $error_msg = implode('', $this->getFirstErrors());
            throw new RequestException($error_msg, ErrorCode::INVALID_PARAM);
        }
    }

    public function modify($data)
    {
        $id = $data['id'];
        $admin = AdminModel::findOne($id);
        if (empty($admin)) {
            throw new RequestException('该管理员不存在', ErrorCode::ACTION_ERROR);
        } else {
            try {
                if (!empty($data['password'])) {
                    $data['password'] = Utils::lMd5($data['password']);
                }
                $admin->setAttributes($data);
                $admin->save();
            } catch (\Exception $e) {
                throw new RequestException($e->getMessage(), ErrorCode::SYSTEM_ERROR);
            }
        }
    }

    public function rules()
    {
        return [
            [['name', 'email', 'phone', 'username', 'password'], 'trim'],
            [['name', 'email', 'phone', 'username', 'password'], 'required'],
            ['email', 'email'],
            ['name', 'string', 'max' => 20],
            ['phone', 'number', 'max' => 20000000000, 'min' => 10000000000],
            ['username', 'string', 'max' => 20],
            ['password', 'string', 'min' => 6],
        ];
    }


}