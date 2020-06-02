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
    const PUBLIC_KEY_API = 'linxonepos@$24784$%SA';
    
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
     public function actions() {
        if($this->isAllowConnectApi()){
            return parent::actions();
        } else{

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            throw new \yii\web\UnauthorizedHttpException('You do not possess the right PUBLIC KEY');
        }

     }
    public function beforeAction($action) {
        Yii::$app->request->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    public function actionIndex()
    { 
     
    }
    public function actionLogin(){  
      \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $username = $_POST['username'];
      $password = $_POST['password']; 
      $model = new LoginForm(); 
      // posting data or login has failed
      $login['LoginForm']['username'] = $username;
      $login['LoginForm']['password'] =  $password;
      $login['LoginForm']['rememberMe'] = 1;

      if (!$model->load($login) || !$model->login(true)) {
        return array('status'=>false,
              'message'=>'error', 
          );
      }
       else{
         return array('status'=>true,
              'message'=>'successful', 
          );
      }  
           
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
                    $result =  '{"invoice_id":'.$invoice->invoice_id.',"table_id":'.$invoice->invoice_type_id.',"invoice_table_count":'.$invoice_table_count.','
                    . '"invoice_no":"'.$invoice->invoice_no.'"}';
                    return  array('status'=>true,  'data'=>$result );
                  }
            } 
        }
       else{
            return  array('status'=>true,   'data'=>'do not create order'  );
        }   
     } 
     public function actionListMenu(){  
         
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category_product = new \app\modules\pos\models\CategoryProduct();
        $dropdow_category = $category_product::find()->where('category_product_parent=0')->all();
    
        if(count($dropdow_category) > 0){
            return  array('status'=>true,    'data'=>$dropdow_category );
        }else{
            return array('status'=>false,
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
            return array('status'=>false,
               'data'=>'not menu', 
            );
        }  
         
     }
    public function actionGetPayment(){
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
             
            return  array('status'=>true, 'data'=>$invoice );
        }
        else {
             return  array('status'=>false, 'data'=>'not invoice' );
        }
    }
    public function actionAddPayment(){
        \yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $nextId = new \app\models\NextIds();
        $payment_deposit = false;$deposit_id=0;
        if(isset($_POST['deposit_id']) && $_POST['deposit_id']>0 && isset($_POST['invoice_id'])){
            $invoice = \app\modules\invoice\models\invoice::findOne($_POST['invoice_id']);
            $_POST['Payables'] = $invoice->invoice_total_last_paid;
            $_POST['Paid_amount'] = $invoice->invoice_total_last_paid;
            $payment_deposit = true;
            $deposit_id = $_POST['deposit_id'];
            $deposit = \app\modules\pos\models\Deposit::findOne($deposit_id);
            if($deposit->deposit_balance < $_POST['pop_need_pay']){
                 return  array('status'=>false, 'data'=>'exit' );
            }
        }
          
        if(isset($_POST['Payables']) && isset($_POST['invoice_id']) && isset($_POST['Paid_amount'])){
             
            $invoice = \app\modules\invoice\models\invoice::findOne($_POST['invoice_id']);
          
            if($invoice->invoice_total_last_paid > 0) {
				if(isset($_POST['payment_note'])){
					$invoice->invoice_note = $_POST['payment_note'];
				}
				$payment = new \app\modules\invoice\models\Payment();
				$payment_now=$payment->get_numerics($payment->getPaymentLast());
				$payment->payment_method = 1;
				if(isset($_POST['payment_method']))
					$payment->payment_method = $_POST['payment_method'];
				$payment->payment_date=date('Y-m-d H:i:s');
				$payment->payment_no =$payment->FormatPaymentNo($payment_now[0]);
				$payment->member_id=0;
				$payment->invoice_id = $_POST['invoice_id'];
				$payment->created_by = Yii::$app->user->id;
				if($payment_deposit)
					$payment->deposit_id = $deposit_id;
				
				 if($_POST['Payables'] >= $_POST['Paid_amount']){
					$payment->payment_amount = $_POST['Paid_amount'];
				}else{
					$payment->payment_amount = $_POST['Payables'];
				}
				$invoice->invoice_total_last_paid=$invoice->invoice_total_last_paid-$payment->payment_amount;
				if($invoice->invoice_total_last_paid<=0 && isset($_POST['FOC']) && $_POST['FOC'] ==1){
					$invoice->invoice_status= \app\modules\invoice\models\invoice::INVOICE_STATUS_GUEST_TREAT;
				}else if($invoice->invoice_total_last_paid<=0){
					$invoice->invoice_status= \app\modules\invoice\models\invoice::INVOICE_STATUS_PAID;
				}
				
				if($payment->save()){
					$invoice->save();
					if(isset($_POST['item_id'])){
						$items = $_POST['item_id'];
						foreach ($items as $value) {
							$invoice_item = \app\modules\invoice\models\InvoiceItem::findOne($value);
							if($invoice_item){
								$invoice_item->payment_id = $payment->payment_id;
								$invoice_item->payment_qty_id = $invoice_item->invoice_item_quantity;
								$invoice_item->save();
							}
						}
					}
					else{
						$invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where('invoice_id = '.$invoice->invoice_id.' AND (payment_id is Null OR payment_id = 0)')->all();
						if($invoice_item){
							foreach ($invoice_item as $value) {
								$value->payment_id = $payment->payment_id;
								$value->payment_qty_id = $value->invoice_item_quantity;
								$value->save();
							}
						}
					}
					
					if($payment_deposit)
					{
						$deposit = \app\modules\pos\models\Deposit::findOne($deposit_id);
						if($deposit){
							$deposit->deposit_balance = $deposit->deposit_balance - $payment->payment_amount;
							$deposit->save();
						}
					}
					return  array('status'=>true, 'payment_id'=>$payment->payment_id ); 
				}
				else{
                                    return  array('status'=>false, 'data'=>'fail' ); 
				 
				}
			}else{
				return  array('status'=>false, 'data'=>'fail' ); 
			}
        }
    }
      
    

}
