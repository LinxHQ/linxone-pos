<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\Tables;
use app\modules\pos\models\TablesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\pos\models\CategoryTable;
use app\modules\invoice\models\InvoiceSearch;
use app\modules\pos\models\SesstionOrder;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\CategoryProduct;
use app\modules\pos\models\ProductSearch;
use app\models\Config;  
use app\modules\invoice\models\InvoiceItem;
use app\modules\invoice\models\Payment;
 
/**
 * Default controller for the `pos` module
 */
class DefaultController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                    [
                        'actions' => [''],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],

            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $session = new Sesstion();
		$last_session = $session::find()->orderBy('sesstion_start_date DESC')->one();
               
//                var_dump($last_session);die;
		if(($last_session->user_id != Yii::$app->user->id) ||  
			(($last_session->user_id == Yii::$app->user->id) && ($last_session->sesstion_status == 1)))
			return $this->redirect('start-sesstion');
		
		$category_table = new CategoryTable();
        $droplist_category_table = $category_table->getDataArray(1);
        $category_arr = array();
        $i=0;
        $invoice_no_table_count = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>0,
            'invoice_status'=> \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING])->count();
        foreach ($droplist_category_table as $key=>$value) {
            $searchModel = new TablesSearch();
            $searchModel->category_table_id = $key;
            $searchModel->table_status=1;
            $dataProvider = $searchModel->search([]); 
            $category_arr[$i]['label'] = $value;
            $category_arr[$i]['content'] = $this->renderAjax('_index_table',['dataProvider'=>$dataProvider]);
            $i++;
        }  
        if(isset($_COOKIE['mybranch'])) $mybranch = $_COOKIE['mybranch'];
        else $mybranch = ''; 
        return $this->render('index', [
            'category_arr' => $category_arr,
            'invoice_no_table_count'=>$invoice_no_table_count,
                'mybranch' => $mybranch
        ]);
    }
    
    public function actionCreateSesstion(){
               
		//close last session
		$last_sesstion = new Sesstion();
		$last_session = $last_sesstion::find()->orderBy('sesstion_start_date DESC')->one();
		if($last_session->sesstion_status == 0){
			$last_session->sesstion_status = 1;
			$last_session->sesstion_end_date = date('Y-m-d H:i:s');
			$last_session->save();
		}
		
		$sesstion = new Sesstion();
		$sesstion->user_id = Yii::$app->user->id;
        $sesstion->sesstion_start_date = date('Y-m-d H:i:s');
        $sesstion->sesstion_status = 0;
        if($sesstion->save()){
            $sesstionorder = new SesstionOrder();
            $status = "Outstanding";
            $last_sesstion_id = $last_session->sesstion_id;
            $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status,$last_sesstion_id);
			if($dataProvider->getTotalCount()){
                foreach($dataProvider->models as $item){
                    $sesstion_order = \app\modules\pos\models\SesstionOrder::find()->where(['pos_sesstion_order_id'=>$item->pos_sesstion_order_id])->one();
                    if($sesstion_order){
                        $sesstion_order->sesstion_id = $sesstion->sesstion_id;
                        $sesstion_order->sesstion_id_old = $last_sesstion_id;
                        $sesstion_order->save();
                    }
                }
            }
        }
    }
    
    public function actionEndSesstion(){
        $sesstion = \app\modules\pos\models\Sesstion::find()->where(['user_id'=>Yii::$app->user->id,'sesstion_status'=>0])->orderBy('sesstion_start_date DESC')->one();
        if($sesstion){
            $sesstion->sesstion_status = 1;
            $sesstion->sesstion_end_date = date('Y-m-d H:i:s');
            $sesstion->save();
        }
        setcookie("mybranch", '',  time() - (86400 * 15));
    }

    public function actionStartSesstion(){
        
        $sesstionorder = new SesstionOrder();
        $status = "Outstanding";
        $sesstion = new Sesstion();
        $sesstion_id = $sesstion->getSesstionIdNow();
        $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status, $sesstion_id);
        return $this->render('start_sesstion', [
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionReportSesstion(){
        $sesstionorder = new SesstionOrder();
        $status = "Outstanding";
        
        $sesstion = new Sesstion();
        $sesstion_id = $sesstion->getSesstionIdNow();
        $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status, $sesstion_id);
        
        $status1 = "Paid";
        $dataProviderPaid = $sesstionorder->search(Yii::$app->request->queryParams, $status1, $sesstion_id);
        return $this->render('end_sesstion', [
            'dataProvider' => $dataProvider,
            'dataProviderPaid' => $dataProviderPaid
        ]);
    }
    public function actionSesstionPdf(){
        $sesstion = new Sesstion();
        $sesstion_id = $sesstion->getSesstionIdNow();
        
        $status = "Paid";
        
        $sesstionorder = new SesstionOrder();
        $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status, $sesstion_id);
        $content = $this->renderPartial('sesstion_pdf', [
            'dataProvider' => $dataProvider
        ]); 
        $mpdf = new  \Mpdf\Mpdf();  // L - landscape, P - portrai
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit();
    }
      
    public function actionSesstionExcel(){
        $sesstion = new Sesstion();
        $sesstion_id = $sesstion->getSesstionIdNow();
        
        $status = "Paid";
        
        $sesstionorder = new SesstionOrder();
        $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status, $sesstion_id);
        $dataProvider->pagination->pageSize=0;
        $dataProvider=  $dataProvider->models;
        
        return $this->render('sesstion_excel', [
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionTableOder(){
        $table_id = (isset($_POST['table_id'])) ? $_POST['table_id'] : 0;
        $invoice = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>$table_id,
            'invoice_status'=> \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING])->all();
        return $this->renderAjax('table_order', [
            'table_id'=>$table_id,
            'invoice'=>$invoice
             
        ]); 
    }
    
    public function actionFormOrder(){
        $table_id = (isset($_POST['table_id'])) ? $_POST['table_id'] : 0;
        $invoice_id = (isset($_POST['invoice_id'])) ? $_POST['invoice_id'] : 0;
        return $this->renderAjax('_form_order', ['table_id'=>$table_id,'invoice_id'=>$invoice_id]); 
    }
    
    public function actionAddOrder(){
          
        $nextId = new \app\models\NextIds();
        $invoice_id = (isset($_POST['invoice_id'])) ? $_POST['invoice_id'] : 0;
        $list_tax = \app\models\ListSetup::getItemByList('Tax');
        if($invoice_id>0){
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
        }
        
        else{
            
            $invoice = new \app\modules\invoice\models\invoice();
            $invoice->invoice_date = date('Y-m-d H:i:s');
            $invoice->use_sale_id = Yii::$app->user->id;
			$invoice->created_by = Yii::$app->user->id;
            $invoice->member_id = 0;
            $invoice->invoice_type = 'pos';
            $invoice->invoice_type_id = (isset($_POST['table_id'])) ? $_POST['table_id'] : 0;
            $invoice->invoice_no = $invoice->invoice_no=$nextId->getNextInvoice();
        }
        
        $invoice->invoice_gst = $_POST['Tax'][0];
        $invoice->invoice_gst_value = $list_tax[$_POST['Tax'][0]];
        $invoice->invoice_discount = $_POST['order_discount'];
        $invoice->invoice_subtotal = $_POST['total_sub'];
        $invoice->invoice_total_last_discount = $_POST['total_last_discount'];
        $invoice->invoice_total_last_tax = $_POST['total_need_to_pay'];
        $invoice->invoice_total_last_paid = $_POST['total_last_paid'];
       
        if(intval($invoice->invoice_total_last_paid)==0)
            $invoice->invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_PAID;
        else
            $invoice->invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        if(isset($_COOKIE['mybranch'])) {
            $invoice->branch_id =  $_COOKIE['mybranch'];
        }
        
        if($invoice->save()){ 
            $nextId->setNextId('next_invoice_id');
            if(isset($_POST['price'])){
                $price = $_POST['price'];
                $product_id = $_POST['product_id'];
                $i = count($price)-1;

                $product_update_id = isset($_POST['product_update_id'])  ? $_POST['product_update_id'] : 0;
                
                if(isset($_POST['type'])  &&  $_POST['type']=='add_item'  && $product_update_id >0)
                    $i = array_search($product_update_id, $product_id);
                if(isset($_POST['type'])  &&  $_POST['type']=='update_item'  && $product_update_id >0)
                    $i = array_search($product_update_id, $product_id);
                    
                if(isset($_POST['type'])  &&  $_POST['type']!='update_total'){
                            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_item_entity_id'=>$_POST['product_id'][$i],'invoice_id'=>$invoice->invoice_id])
                                    ->andWhere('payment_id = 0 OR payment_id is null')->andWhere('payment_qty_id is null OR payment_qty_id < '.$_POST['quantity'][$i])->one();
                            if(!$invoice_item){
                                $invoice_item = new \app\modules\invoice\models\InvoiceItem();
                            }
                            $invoice_item->invoice_id = $invoice->invoice_id;
                            $invoice_item->invoice_item_quantity = $_POST['quantity'][$i] ;
                            $invoice_item->invoice_item_price = $_POST['price'][$i];
                            $invoice_item->invoice_item_tax = $_POST['price_tax'][$i];
                            $invoice_item->invoice_item_note = $_POST['price_note'][$i];
                            $invoice_item->invoice_item_amount = $_POST['amount'][$i];
                            $invoice_item->invoice_item_entity_id = $_POST['product_id'][$i];
                            $invoice_item->invoice_item_description = $_POST['product_name'][$i];
                           $invoice_item->save();
                            
//                        foreach ($price as $value) {
//                            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_item_entity_id'=>$_POST['product_id'][$i],'invoice_id'=>$invoice->invoice_id])
//                                    ->andWhere('payment_id = 0 OR payment_id is null')->andWhere('payment_qty_id is null OR payment_qty_id < '.$_POST['quantity'][$i])->one();
//                            if(!$invoice_item){
//                                $invoice_item = new \app\modules\invoice\models\InvoiceItem();
//                            }
//                            $invoice_item->invoice_id = $invoice->invoice_id;
//                            $invoice_item->invoice_item_quantity = $_POST['quantity'][$i] ;
//                            $invoice_item->invoice_item_price = $_POST['price'][$i];
//                            $invoice_item->invoice_item_tax = $_POST['price_tax'][$i];
//                            $invoice_item->invoice_item_note = $_POST['price_note'][$i];
//                            $invoice_item->invoice_item_amount = $_POST['amount'][$i];
//                            $invoice_item->invoice_item_entity_id = $_POST['product_id'][$i];
//                            $invoice_item->invoice_item_description = $_POST['product_name'][$i];
//                            $invoice_item->save();
//                            $i++;
//                        }
                }
            }
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
            
           return  '{"invoice_id":'.$invoice->invoice_id.',"table_id":'.$invoice->invoice_type_id.',"invoice_table_count":'.$invoice_table_count.','
                    . '"invoice_no":"'.$invoice->invoice_no.'"}';
        }
    }
    
    function actionDeleteOrder(){
//       $_POST['invoice_id'] = 1735;
        if(isset($_POST['invoice_id']))
        {
	 $sesstion_order = \app\modules\pos\models\SesstionOrder::find()->where(['invoice_id'=>$_POST['invoice_id']])->one();
            if($sesstion_order)
                $sesstion_order->delete();
             
            $invoice = \app\modules\invoice\models\invoice::findOne($_POST['invoice_id']);
            if($invoice->delete())
                echo '{"status":"success"}';
            else
                echo '{"status":"fail"}'; 
        }
    }
    
    function actionAddPayment(){
        $nextId = new \app\models\NextIds();
        $payment_deposit = false;$deposit_id=0;
        if(isset($_POST['deposit_id']) && $_POST['deposit_id']>0 && isset($_POST['invoice_id'])){
            $invoice = \app\modules\invoice\models\invoice::findOne($_POST['invoice_id']);
            $_POST['pop_guest_pay'] = $invoice->invoice_total_last_paid;
            $_POST['pop_need_pay'] = $invoice->invoice_total_last_paid;
            $payment_deposit = true;
            $deposit_id = $_POST['deposit_id'];
            $deposit = \app\modules\pos\models\Deposit::findOne($deposit_id);
            if($deposit->deposit_balance < $_POST['pop_need_pay']){
                echo '{"status1":"fail"}'; 
                exit;
            }
        }
        if(isset($_POST['pop_guest_pay']) && isset($_POST['invoice_id']) && isset($_POST['pop_need_pay'])){
            $invoice = \app\modules\invoice\models\invoice::findOne($_POST['invoice_id']);
            if($invoice->invoice_total_last_paid > 0) {
				if(isset($_POST['pop_note'])){
					$invoice->invoice_note = $_POST['pop_note'];
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
				
				if($_POST['pop_guest_pay'] >= $_POST['pop_need_pay']){
					$payment->payment_amount = $_POST['pop_need_pay'];
				}else{
					$payment->payment_amount = $_POST['pop_guest_pay'];
				}
				$invoice->invoice_total_last_paid=$invoice->invoice_total_last_paid-$payment->payment_amount;
				if($invoice->invoice_total_last_paid<=0 && isset($_POST['guest_treat']) && $_POST['guest_treat'] ==1){
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
					
					echo '{"status":"success","payment_id":'.$payment->payment_id.'}';
				}
				else{
					print_r($payment->errors);
					echo '{"status":"fail"}';
				}
			}else{
				echo '{"status":"fail"}';
			}
        }
    }
    public function actionNotificationKitchen(){
        if(isset($_POST['invoice_id']) && isset($_POST['table_id'])){
            $invoice_id = $_POST['invoice_id'];
            $table_id = $_POST['table_id'];
        
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            $table =  \app\modules\pos\models\Tables::findOne($table_id);
            return $this->renderAjax('_form_notification_kitchen', [
                    'table' => $table,
                    'invoice' => $invoice
            ]);
        }
    }
    public function actionSave_printed(){
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
        }
        if(isset($_POST['table_id'])){
            $table_id = $_POST['table_id'];
        }
        
        $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_id'=>$invoice_id])->all();
        foreach ($invoice_item_data as $items) {
            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_item_id'=>$items->invoice_item_id])->one();;
            $invoice_item->invoice_item_printed = $items->invoice_item_quantity;
            $invoice_item->save();
        }
        
    }
    
    public function actionPrintOrder(){
        if(isset($_POST['payment_id']) && isset($_POST['invoice_id'])){
            $payment_id = $_POST['payment_id'];
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where(['payment_id'=>$payment_id])->all();
            $payment = \app\modules\invoice\models\Payment::findOne($payment_id);
			return $this->renderAjax('_print_order',[
                'invoice'=>$invoice,
                'invoice_item_data'=>$invoice_item_data,
				'payment' => $payment
            ]);
        }
    } 
    
    public function actionDeleteItem(){
        if(isset($_POST['invoice_id']) && $_POST['product_name']){
            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()
                    ->where(['invoice_item_description'=> $_POST['product_name'],'invoice_id'=>$_POST['invoice_id']])->one();
            if($invoice_item && $invoice_item->delete())
                echo "{'status':'success'}";
            else
                echo "{'status':'fail'}";
        }
    }
    
    public function actionOrder(){
        $searchModel = new InvoiceSearch();
        $searchModel->invoice_type = "pos";
        $searchModel->invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        //$date = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,FALSE,FALSE,FALSE);
        return $this->renderAjax('_list_order',[
            'dataProvider'=>$dataProvider
        ]);
    }
    public function actionLoad_order(){
        $table_id = false;
        if(isset($_POST['table_id'])){
            $table_id = $_POST['table_id'];
        }
        $searchModel = new \app\modules\invoice\models\InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,false,false,$table_id);
        return $this->renderPartial('detail_order', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionJoin_order(){
        if(isset($_POST['invoice_id']) && isset($_POST['invoice_id_old'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice_id_old = $_POST['invoice_id_old'];
	        $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_id'=>$invoice_id_old])->all();
	        foreach ($invoice_item as $items) {
                    $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_item_id'=>$items->invoice_item_id])->one();
                    $invoice_item->invoice_id = $invoice_id;
                    if($invoice_item->save())
                        echo '{"status":"success"}';
                    else
			echo '{"status":"fail"}';
                }
                
                $invoice =  \app\modules\invoice\models\invoice::find()->where(['invoice_id'=>$invoice_id_old])->one();
                    if($invoice->delete())
                        echo '{"status":"success"}';
                    else
			echo '{"status":"fail"}';
                $invoice_update = new \app\modules\invoice\models\invoice();
                    $invoice_update->update_total($invoice_id);
	}else{
            echo '{"status":"fail"}';
        }
    }
    
    public function actionLoadPaymentFull(){
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            return $this->renderAjax('_payment_full',[
                'invoice'=>$invoice,
            ]);
        }
    }
    
    public function actionLoadPaymentPartial(){
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()->where('invoice_id = '.$invoice_id.' AND (payment_id is Null OR payment_id = 0)')->all();
            return $this->renderAjax('_payment_partial',[
                'invoice'=>$invoice,
                'invoice_item'=>$invoice_item
            ]);
        }
    }
    
    public function actionLoadOrderItem(){
        if(isset($_POST['invoice_id']) && isset($_POST['table_id'])){
            $invoice_id = $_POST['invoice_id'];
            $table_id = $_POST['table_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            return $this->renderAjax('_form_order_item',[
                'invoice_id'=>$invoice_id,
                'table_id'=>$table_id,
                'invoice'=>$invoice
            ]);
        }
    }
    
    public function actionIndex_sell(){
        $category = 0;
        
        $searchModel = new \app\modules\pos\models\ProductSearch();
        $product_category = new \app\modules\pos\models\CategoryProduct();
        
        $array = $product_category->getArrayCategory();
        
        if(isset($_POST['category_product_id']))
            $category = $_POST['category_product_id'];
        
        $page = ((isset($_POST['page']) && $_POST['page']>0) ? $_POST['page'] : 0);
        
        $ids = $product_category->getIdItemByParrent($array,$category);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$page,true,$ids);
        
        $totalProduct = $searchModel->search(Yii::$app->request->queryParams,false)->getTotalCount();
        return $this->renderPartial('index_sell', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page'=>$page,
            'totalProduct'=>$totalProduct,
            'category' => $category,
        ]);
    }
    
    public function actionListDeposit(){
        $searchModel = new \app\modules\pos\models\DepositSearch();
        $search_key = false;
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $searchModel->search_key = $search_key;
        }
        $searchModel->deposit_status=1;
        $dataProvider = $searchModel->search([]);
        $dataProvider->sort=false;
        return $this->renderPartial('_list_deposit', [
            'searchModel' => $searchModel,
            'dataProvider'=>$dataProvider,
            'search_key'=>$search_key
        ]); 
    }
    
    public function actionSetting(){
        $model = Config::find()->one();
        if (isset($_POST['submit'])) {
            if(isset($_POST['show_img_product']))
                $model->show_img_product = $_POST['show_img_product'];
            else
                $model->show_img_product = 0;
            $model->save();
            $this->redirect('setting');
        }
        return $this->render('setting',['model'=>$model]);
    }
    public function actionVoidInvoice(){
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            $invoice->invoice_status=  "Void Invoice";
            if($invoice->save()){
                $payment = new \app\modules\invoice\models\Payment();
                $payment->voidPaymentByInvoice($invoice->invoice_id);
            }
        }
    }
    
    public function actionPrintReceipt(){
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
            $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
            $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_id'=>$invoice_id,'payment_id'=>NULL])->all();
            $payment = null;
			return $this->renderAjax('_print_order',[
                'invoice'=>$invoice,
                'invoice_item_data'=>$invoice_item_data,
				'payment' => $payment
            ]);
        }
    }
    
    public function actionLockItem(){
        if(isset($_POST['invoice_id']) && $_POST['product_name']){
            $invoice_item = \app\modules\invoice\models\InvoiceItem::find()
                    ->where(['invoice_item_description'=> $_POST['product_name'],'invoice_id'=>$_POST['invoice_id']])->one();
            if($invoice_item){
                
                $invoice_item->invoice_item_delete = $invoice_item->invoice_item_delete + 1;
                $invoice_item->invoice_item_quantity = $invoice_item->invoice_item_quantity - 1;
                $invoice_item->invoice_item_amount = $invoice_item->invoice_item_price * intval($invoice_item->invoice_item_quantity);
                if($invoice_item->save()){
                    $invoice = new \app\modules\invoice\models\invoice();
                    $invoice->update_total($invoice_item->invoice_id);
                    echo "{'status':'success'}";
                }
            }
            else
                echo "{'status':'fail'}";
        }
    }
    
    public function actionProductItem(){
        
        $product = isset($_GET['product'])?$_GET['product']:10;
        
        $sesstionorder = new SesstionOrder();
        $dataProvider = $sesstionorder->getProductBySesstionId(Yii::$app->request->queryParams,$product);
        
        return $this->render('product_sold', [
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionPos_report_product_sold(){
        $product = isset($_GET['product'])?$_GET['product']:0;
        $sesstionorder = new SesstionOrder();
        $dataProvider = $sesstionorder->getProductBySesstionId(Yii::$app->request->queryParams,$product);
        
        $dataProvider->pagination->pageSize=0;
        $dataProvider=  $dataProvider->models;
        
        return $this->render('pos_report_product_sold_excel', [
            'dataProvider' => $dataProvider
        ]);
    }
     function actionPos_report(){
        $searchModel = new InvoiceItem();
        
        $year_now = date('Y');
        $month_now = date('m');
        $day_now = date('d');
        $end_date = date('d/m/Y');
        $dateint = mktime(0, 0, 0, $month_now-1,$day_now, $year_now);
        $start_date = date('d/m/Y', $dateint);
        
        $view_start_date = false;
        $view_end_date = false;
        $status = "All";
        if(isset($_GET['start_date']) && $_GET['start_date']!="")
        {
            $start_date=$_GET['start_date'];
            
            $view_start_date = $_GET['start_date'];
                       
        }
        if(isset($_GET['end_date']) && $_GET['end_date'] !="")
        {
            //$currentTime = date("h:i:s"); 
            $end_date=$_GET['end_date'];
            $view_end_date = $_GET['end_date'];
        }
        
        
        $start_date = $this->format_date_str($start_date,"Y-m-d");
        $end_date = $this->format_date_str($end_date,"Y-m-d");
        
        if(isset($_GET['status'])){
            $status = $_GET['status'];
        }
        $branch = false;
          if(isset($_GET['branch'])){
            $branch = $_GET['branch'];
        }
        // $product_category = new \app\modules\pos\models\CategoryProduct();
        // $category = $product_category->tableCategoryProduct($parrent = 0,$space="",$start_date,$end_date);
        //edited by vanth
		$category = new CategoryProduct();
		$category = $category->getPosCategoryReport($start_date,$end_date);
		
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams,false,$start_date,$end_date,false,$status,$branch);
        
        $start_date = $this->format_date_str($start_date,"d/m/Y");
        $end_date = $this->format_date_str($end_date,"d/m/Y");
        $invoice_no_table_count = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>0,
            'invoice_status'=> \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING])->count();
        return $this->render('index_pos_report',
                [
                    'category' =>$category,
                    'dataProvider'=>$dataProvider,
                    'view_start_date'=>$view_start_date,
                    'view_end_date'=>$view_end_date,
                    'status' => $status,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'invoice_no_table_count' => $invoice_no_table_count
                    ]
                );
        
    }
            
    function actionPosReportCategoryPdf() {
        $start_date=false;
        $end_date=false;
        $view_start_date = false;
        $view_end_date = false;
        if(isset($_GET['start_date']) && $_GET['start_date']!="")
        {
            $start_date=$_GET['start_date'];
            $start_date = $this->format_date_str($start_date,"Y-m-d");
            $view_start_date = $_GET['start_date'];
                       
        }
        if(isset($_GET['end_date']) && $_GET['end_date'] !="")
        {
            //$currentTime = date("h:i:s"); 
            $end_date=$_GET['end_date'];
            $end_date = $this->format_date_str($end_date,"Y-m-d");
            $view_end_date = $_GET['end_date'];
        }
        // $product_category = new \app\modules\pos\models\CategoryProduct();
        // $category = $product_category->tableCategoryProduct($parrent = 0,$space="",$start_date,$end_date);
		$category = new CategoryProduct();
		$category = $category->getPosCategoryReport($start_date,$end_date);
        
        $content = $this->renderPartial('pos_report_category_pdf',
                ['category'=>$category,'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date]
        );
        
        $mpdf = new  \Mpdf\Mpdf();  // L - landscape, P - portrai
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit();
    }
	function actionPosReportCategoryExcel(){
		$start_date=false;
		$end_date=false;
		$view_start_date = false;
		$view_end_date = false;
		if(isset($_GET['start_date']) && $_GET['start_date']!="")
		{
			$start_date=$_GET['start_date'];
			$start_date = $this->format_date_str($start_date,"Y-m-d");
			$view_start_date = $_GET['start_date'];
					   
		}
		if(isset($_GET['end_date']) && $_GET['end_date'] !="")
		{
			//$currentTime = date("h:i:s"); 
			$end_date=$_GET['end_date'];
			$end_date = $this->format_date_str($end_date,"Y-m-d");
			$view_end_date = $_GET['end_date'];
		}
		$category_product = new CategoryProduct();
		$dataProvider = $category_product->getPosCategoryReport($start_date,$end_date);
		// print_r($dataProvider);
		return $this->render('pos_report_category_excel',['dataProvider'=>$dataProvider,'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date]);
	}
            
    function actionPos_report_pdf(){
        $searchModel = new InvoiceItem();
        $start_date=false;
        $end_date=false;
        $view_start_date = false;
        $view_end_date = false;
        $status = "Paid";
        if(isset($_GET['start_date']) && $_GET['start_date']!="")
        {
            $start_date=$_GET['start_date'];
            $start_date = $this->format_date_str($start_date,"Y-m-d");
            $view_start_date = $_GET['start_date'];
                       
        }
        if(isset($_GET['end_date']) && $_GET['end_date'] !="")
        {
            //$currentTime = date("h:i:s"); 
            $end_date=$_GET['end_date'];
            $end_date = $this->format_date_str($end_date,"Y-m-d");
            $view_end_date = $_GET['end_date'];
        }
        if(isset($_GET['status'])){
            $status = $_GET['status'];
        }
         $branch = false;
          if(isset($_GET['branch'])){
            $branch = $_GET['branch'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,false,$start_date,$end_date,false,$status,$branch);
        $dataProvider->pagination->pageSize=0;
        $content = $this->renderPartial('pos_report_pdf',
                ['dataProvider'=>$dataProvider,'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date]
        );

        $mpdf = new   \Mpdf\Mpdf( );  // L - landscape, P - portrai
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit(); 
    }
    
    function actionPos_report_excel(){
        $searchModel = new InvoiceItem();
        $start_date=false;
        $end_date=false;
        $view_start_date = false;
        $view_end_date = false;
        $status = "Paid";
        if(isset($_GET['start_date']) && $_GET['start_date']!="")
        {
            $start_date=$_GET['start_date'];
            $start_date = $this->format_date_str($start_date,"Y-m-d");
            $view_start_date = $_GET['start_date'];
                       
        }
        if(isset($_GET['end_date']) && $_GET['end_date'] !="")
        {
            //$currentTime = date("h:i:s"); 
            $end_date=$_GET['end_date'];
            $end_date = $this->format_date_str($end_date,"Y-m-d");
            $view_end_date = $_GET['end_date'];
        }

        if(isset($_GET['status'])){
            $status = $_GET['status'];
        }
         $branch = false;
          if(isset($_GET['branch'])){
            $branch = $_GET['branch'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,false,$start_date,$end_date,false,$status,$branch);
        
        $dataProvider->pagination->pageSize=0;
        $dataProvider=  $dataProvider->models;
        return $this->render('pos_report_excel',['dataProvider'=>$dataProvider,'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date]);  
    }
    public function format_date_str($originalDate,$format)
    {
        $originalDate = str_replace('/', '-', $originalDate);
        $newDate = date($format, strtotime($originalDate));
        return $newDate;
    }
    public function actionNewPdf(){
//          $mpdf = new  \Mpdf\Mpdf ;  // L - landscape, P - portrai
//        $mpdf->WriteHTML('jahjs');
//        $mpdf->Output();
//        exit();  
             $mpdf = new \kartik\mpdf('',    // mode - default ''
                '',    // format - A4, for example, default ''
                12,     // font size - default 0
                '',    // default font family
                10,    // margin_left
                10,    // margin right
                5,     // margin top
                16,    // margin bottom
                9,     // margin header
                9,     // margin footer
                'L');  // L - landscape, P - portrai
            $mpdf->WriteHTML('<h1>Hello world!</h1>');
            $mpdf->Output();
      }
}
