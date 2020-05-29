<?php

namespace app\modules\pos\controllers;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
class ApiController extends \yii\web\Controller
{
    const PUBLIC_KEY_API = 'cmdsw@$24784$%SA';
    
    public $starttime = 0;
    public $model = null;
    
//    public $format_api = 'json';
    
    /*
     * Danh sach cac api khong can truyen token
     * format array('controller1' => 'action1,action2,action3',controller2=>'action1,action2',...)
     */
    private $public_apis = [
        'invitation'=>'login,join',
        'system'=>'get-server-date-time,get-build-version'
    ];
   
    function verbs() {
        return[
            'update' => ['POST'],
        ];
        parent::verbs();
    }
    

    private function isAllowConnectApi()
    {
        if (!isset($_SERVER['HTTP_PUBLICKEY']) || $_SERVER['HTTP_PUBLICKEY'] != self::PUBLIC_KEY_API)
            return false;
        return true;
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    // public function actions() {
    //     if($this->isAllowConnectApi()){
    //         return parent::actions();
    //     } else{
            
    //         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //         throw new \yii\web\UnauthorizedHttpException('You do not possess the right PUBLIC KEY');
    //     }

    // }
    public function beforeAction($action) {
        Yii::$app->request->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actionIndex()
    { 
     
    }
      public function actionLogin(){  
        $request = Yii::$app->getRequest();
        $params = $request->getBodyParams(); 
        
        // check post Json data
        $account_request = $request->getRawBody();
        $jsondata = (array) json_decode($account_request);
        $username = $jsondata['username'];
        $password = $jsondata['password']; 
        $model = new LoginForm(); 
        // posting data or login has failed
        $login['LoginForm']['username'] = $jsondata['username'];
        $login['LoginForm']['password'] =  $jsondata['password'];
        $login['LoginForm']['rememberMe'] = 1;

        if (!$model->load($login) || !$model->login(true)) {
            $result = array('status'=>500,
                'message'=>'false', 
            );
        }
         else{
            $result = array('status'=>200,
                'message'=>'true', 
            );
        }  
        return  json_encode($result);   
      }
      
    

}
