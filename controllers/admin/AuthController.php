<?php
/**
 * AuthController.php.
 * @author keepeye <carlton.cheng@foxmail>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */

namespace app\controllers\admin;

use yii\base\Event;
use yii\web\Controller;
use yii\web\User;
use Yii;
use app\models\Administrator as Identity;
use app\events\AdminAuthEvent;
use yii\base\InvalidParamException;

class AuthController extends Controller
{
    public $layout = false;

    public function init()
    {

    }

    /**
     * 登录
     */
    public function actionLogin()
    {

        $request = Yii::$app->request;
        if (!$request->isPost) {
            return $this->render('login');
        } else {
            return json_encode(
                ['status'=>1,'redirect'=>Yii::$app->administrator->getReturnUrl('/admin/index/index')]
            );

//            if ($identify = Identity::findOne([
//                'username' => $request->post('username','')
//            ])) {
//                if ($identify->validatePassword($request->post('password',''))) {
//                    if (Yii::$app->administrator->login($identify)) {
//                        //此处登录成功
//                        return json_encode(['status'=>1,'redirect'=>Yii::$app->administrator->getReturnUrl('/')]);
//                    } else {
//                        return json_encode(['status'=>0,'error'=>'登录失败']);
//                    }
//                } else {
//                    //密码错误
//                    return json_encode(['status'=>0,'error'=>'用户名或密码错误']);
//                }
//            } else {
//                return json_encode(['status'=>0,'error'=>'用户名或密码错误']);
//            }

        }
    }

    /**
     * 注销
     */
    public function actionLogout()
    {
        Yii::$app->administrator->logout();
        return $this->redirect(['login']);
    }
}