<?php

namespace app\controllers;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\modules\pos\models\CategoryTable;
use app\modules\invoice\models\InvoiceSearch;
use app\modules\pos\models\SesstionOrder;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\CategoryProduct;
use app\modules\pos\models\ProductSearch;
use app\models\Config;

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
     public function actionCreateOrder(){  
         
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $nextId = new \app\models\NextIds();
        
        $invoice = new \app\modules\invoice\models\invoice();
        $invoice_item = new \app\modules\invoice\models\InvoiceItem();
        $invoice->attributes = \Yii::$app->request->post();
//        var_dump($invoice->attributes);die;
        $invoice_id = $invoice->invoice_id;
        if($invoice_id>0){
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice->invoice_id);
             
        }
        else{  
            $invoice->invoice_date = date('Y-m-d H:i:s');
            $invoice->use_sale_id = Yii::$app->user->id;
            $invoice->created_by = Yii::$app->user->id;
            $invoice->invoice_no = $invoice->invoice_no=$nextId->getNextInvoice();
        }
        
        if($invoice->validate()){ 
            if($invoice->save()){
                $invoice_item->attributes = \Yii::$app->request->post();
                $invoice_item->invoice_id = $invoice->invoice_id;
                if($invoice_item->validate()){ 
                    $invoice_item->save();
                    
                    $invoice_table_count = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>$invoice->invoice_type_id,
                        'invoice_status'=> \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING])->count();
                                $sesstion = \app\modules\pos\models\Sesstion::find()->where(['user_id'=>Yii::$app->user->id,'sesstion_status'=>0])->orderBy('sesstion_start_date DESC')->one();
                    $sesstion_id = $sesstion->sesstion_id;
                    $sesstion_order = new \app\modules\pos\models\SesstionOrder();
                    $check = $sesstion_order->checkInvoiceSesstionOrder($invoice->invoice_id);
                    if($check ==0){
                        $sesstion_order->invoice_id = $invoice->invoice_id;
                        $sesstion_order->sesstion_id = $sesstion_id;
                        $sesstion_order->save();
                    }
                    
                    $result = array('status'=>$invoice->invoice_id,
                        'message'=>'true', 
                      );
                  }
            }
            
      }
       else{
            $result = array('status'=>500,
               'message'=>'false', 
            );
      }  
      return  json_encode($result);   
         
     } 
     public function actionListMenu(){  
         
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category_product = new \app\modules\pos\models\CategoryProduct();
        $dropdow_category = $category_product::find()->where('category_product_parent=0')->all();
    
        if(count($dropdow_category) > 0){
            return  array('status'=>true,    'data'=>$dropdow_category );
        }else{
            return array('status'=>true,
               'data'=>'not menu', 
            );
        }    
     }
     public function actionListMenuItem(){  
         
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(isset($_POST['category_product_id'])){
            $category_product_id = $_POST['category_product_id'];
            $product = new \app\modules\pos\models\Product();
            $dropdow_product = $product::find()->where('category_product_id='.$category_product_id)->all();

            if(count($dropdow_product) > 0){
                return  array('status'=>true, 'data'=>$dropdow_product );
            }
        }else{
            return array('status'=>true,
               'data'=>'not menu', 
            );
        }  
         
     }
     
      
    

}
