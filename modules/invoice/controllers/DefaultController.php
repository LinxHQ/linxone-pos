<?php

namespace app\modules\invoice\controllers;

use Yii;
use app\modules\invoice\models\invoice;
use app\modules\invoice\models\InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\invoice\models\InvoiceItem;
use app\modules\invoice\models\Payment;
use kartik\mpdf\Pdf;
/**
 * DefaultController implements the CRUD actions for invoice model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                // 'only' => ['printcontract','create','printinvoice', 'update','index','view','create','update','delete','getpaymentnextnumber','void_payment',
                    // 'update_void_csdl_old','add_payment','get-item-by-invoice-id'],
                'rules' => [
                    // deny all POST requests
//                    [
//                        'allow' => false,
//                        'verbs' => ['POST']
//                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];

    }

    /**
     * Lists all invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $model = new invoice();
        $nextId = new \app\models\NextIds();
        $listSetup = \app\models\ListSetup::getItemByList('Tax');
        if($model->load(Yii::$app->request->post()) && isset($_REQUEST['member_id']))
        {
            $model->member_id=$_REQUEST['member_id'];
            if(isset($_REQUEST['membership_id']))
                $model->invoice_type_id=$_REQUEST['membership_id'];
            elseif(isset ($_REQUEST['training_id']))
                $model->invoice_type_id=$_REQUEST['training_id'];
            $model->invoice_type=$_POST['invoice_type'];
            $model->invoice_term=$_POST['invoice']['invoice_term'];
            $model->created_by=Yii::$app->user->id;
            $model->invoice_no=$nextId->getNextInvoice();
            $model->invoice_gst_value = (isset($listSetup[$model->invoice_gst]) ? $listSetup[$model->invoice_gst] : 0);
          
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //save invoice item
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id=$model->invoice_id;
            $invoiceItem->invoice_item_quantity=$_POST['invoice_item_quantity'];
            $invoiceItem->invoice_item_price= str_replace('.','',$_POST['invoice_item_price']);
            $invoiceItem->invoice_item_amount=$_POST['invoice_item_quantity']*$invoiceItem->invoice_item_price;
            $invoiceItem->invoice_item_description= trim(strip_tags($_POST['invoice_item_description']));
            
            $invoiceItem->save();
            
            //save payment
            $payment_no_array=array();
            if(isset($_POST['payment_no']))
            {
                
                $payment_no_array = $_POST['payment_no'];
                $payment_date_array = $_POST['payment_date'];
                $payment_Method_array = $_POST['Method'];
                $payment_amount_array = $_POST['payment_amount'];
                $payment_note_array = $_POST['payment_note'];
                $reference_array = $_POST['reference'];
                
                foreach ($payment_no_array as $key=>$value)
                {
                    $payment = new Payment();
                    $payment->payment_no=$value;
                    $payment->payment_amount=str_replace('.','',$payment_amount_array[$key]);
                    $payment->payment_note=$payment_note_array[$key];
                    $payment->payment_method=$payment_Method_array[$key];
                    $payment->payment_date=$payment_date_array[$key];
                    $payment->invoice_id=$model->invoice_id;
                    $payment->member_id=$_REQUEST['member_id'];
                    $payment->created_by=Yii::$app->user->id;
                    $payment->reference=$reference_array[$key];
                    $payment->save();
                }
                
                //Update trang thai Tranning sau khi thanh toan
                $outstanding = $model->getInvoiceOustanding($model->invoice_id);
                $tranning = \app\modules\training\models\MemberTrainings::findOne ($model->invoice_type_id);
                if($tranning){
                    if($outstanding<=0)
                        $tranning->member_training_status = \app\modules\members\models\Members::STATUS_ACTIVE_MEMBERS_TRAINING;
                    else
                        $tranning->member_training_status = \app\modules\members\models\Members::STATUS_INACTIVE_MEMBERS_TRAINING;
                    $tranning->save();
                }
                
//                //Update trang thai MemberShip sau khi thanh toan
//                $memberShip = \app\modules\members\models\Membership::findOne ($model->invoice_type_id);
//                if($memberShip){
//                    if($outstanding<1){
//                        $memberShip->membership_status = \app\modules\members\models\Membership::STATUS_ACTIVE_MEMBERSHIP;
//                        $memberShip->save();
//                    }
//                }
            }
            
            $status=$model->getStatusInvoice($model->invoice_id);
            if(isset($_POST['written_off']))
            {
                $status=  invoice::INVOICE_STATUS_WRITTEN_OFF;
            }
            
            $model->invoice_status=$status;
            $model->save();
            
            //Update total
            $model->update_total($model->invoice_id);

            if(isset($_GET['training_id']))
                 $url = Yii::$app->urlManager->createUrl('/invoice/default/update?id='.$model->invoice_id.'&training_id='.$model->invoice_type_id);
            else
                $url = Yii::$app->urlManager->createUrl('/invoice/default/update?id='.$model->invoice_id.'&membership_type='.$_REQUEST['membership_type'].'&member_id='.$model->member_id.'&membership_id='.$_REQUEST['membership_id'].'');
            return $this->redirect($url);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         
        $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$id])->one();
        $invoicePayment = Payment::find()->where(["invoice_id"=>$id,"payment_void_parent"=>0])->orderBy(['payment_void'=>'DESC'])->all();
        $listSetup = \app\models\ListSetup::getItemByList('Tax');
        $model->invoice_gst_value = (isset($listSetup[$model->invoice_gst]) ? $listSetup[$model->invoice_gst] : 0);
        
        $booking = array();
        $memberShip_type_id = 0;$membership_id=0;$member_id=0;$booking_id=0;
        if($model->invoice_type=="booking"){
            $booking = \app\modules\booking\models\Booking::findOne($model->invoice_type_id);
            $memberShip = \app\modules\members\models\Membership::findOne($booking->membership_id);
            //$memberShip_type_id = $memberShip->membership_type_id;
            $membership_id = $booking->membership_id;
            $member_id = $booking->member_id;
            $booking_id = $booking->book_id;
            
        }
        else
        {
            $memberShip = \app\modules\members\models\Membership::findOne($model->invoice_type_id);
            if($memberShip)
            {
            $memberShip_type_id = $memberShip->membership_type_id;
            $membership_id = $memberShip->membership_id;
            $member_id = $memberShip->member_id;
            }
//            $booking_id = $memberShip->book_id;
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateStatusInvocie($model->invoice_id);
            if(isset($_POST['void_invoice']))
            {
                $model->invoice_status=  invoice::INVOICE_STATUS_VOID_INVOICE;
                if($model->save()){
                    $payment = new Payment();
                    $payment->voidPaymentByInvoice($model->invoice_id);
                }
            }
            if(isset($_REQUEST['member_id']))
                $model->member_id=$_REQUEST['member_id'];
            if(isset($_REQUEST['membership_id']))
                $model->invoice_type_id=$_REQUEST['membership_id'];
            $model->invoice_type=$_POST['invoice_type'];
            $model->invoice_term=$_POST['invoice']['invoice_term'];
            $model->created_by=Yii::$app->user->id;
            
            //save invoice item
            $invoiceItem=new InvoiceItem();
            if(isset($_POST['invoice_item_id']) && $_POST['invoice_item_id']>0)
            {
            $invoiceItem = InvoiceItem::findOne($_POST['invoice_item_id']);
            }
            $invoiceItem->invoice_id=$model->invoice_id;
            $invoiceItem->invoice_item_quantity=$_POST['invoice_item_quantity'];
            $invoiceItem->invoice_item_price= str_replace('.','',$_POST['invoice_item_price']);
            $invoiceItem->invoice_item_amount=$_POST['invoice_item_quantity'] * $invoiceItem->invoice_item_price;
//            $invoiceItem->invoice_item_description=$_POST['invoice_item_description'];
            $invoiceItem->save();
            
            //save payment
            $payment_no_array=array();
            if(isset($_POST['payment_id']))
            {
                $payment_id_array = $_POST['payment_id'];
                $payment_date_array = $_POST['payment_date'];
                $payment_Method_array = $_POST['Method'];
                $payment_amount_array = $_POST['payment_amount'];
                $payment_note_array = $_POST['payment_note'];
                $payment_no_array = $_POST['payment_no'];
                $reference_array = $_POST['reference'];
                foreach ($payment_id_array as $key=>$value)
                {
                    if($payment_id_array[$key]>0)
                    {
                        $payment = Payment::findOne($payment_id_array[$key]);
                        $payment->payment_amount=str_replace('.','',$payment_amount_array[$key]);
                        $payment->payment_note=$payment_note_array[$key];
                        $payment->payment_method=$payment_Method_array[$key];
                        $payment->payment_date=$payment_date_array[$key];
                        $payment->reference=$reference_array[$key];
                        $payment->save();
                    }
                    else{
                        $payment = new Payment();
                        $payment->payment_no = $payment_no_array[$key];
                        $payment->payment_amount=str_replace('.','',$payment_amount_array[$key]);
                        $payment->payment_note=$payment_note_array[$key];
                        $payment->payment_method=$payment_Method_array[$key];
                        $payment->payment_date=$payment_date_array[$key];
                        $payment->invoice_id = $model->invoice_id;
                        $payment->reference=$reference_array[$key];
                        $payment->member_id = $model->member_id;
                        $payment->created_by=Yii::$app->user->id;
                        $payment->save();
                    }
                    
                }
                //Update trang thai Tranning sau khi thanh toan
                $outstanding = $model->getInvoiceOustanding($model->invoice_id);
                $tranning = \app\modules\training\models\MemberTrainings::findOne ($model->invoice_type_id);
                if($tranning){
                    if($outstanding<=0)
                        $tranning->member_training_status = \app\modules\members\models\Members::STATUS_ACTIVE_MEMBERS_TRAINING;
                    else
                        $tranning->member_training_status = \app\modules\members\models\Members::STATUS_INACTIVE_MEMBERS_TRAINING;
                    $tranning->save();
                }
            }
            if(!isset($_POST['void_invoice']))
            {
                $payment = new Payment();
                $amount_payment_invoice = $payment->getAmountByInvoice($model->invoice_id);
                $subtotalInvoice = $model->getSubtotalInvocie($model->invoice_id);
                $oustanding = $subtotalInvoice-$amount_payment_invoice;
                if($oustanding<=0)
                {
                    $status = invoice::INVOICE_STATUS_PAID;
                }
                else
                    $status = invoice::INVOICE_STATUS_OUSTANDING;
                $model->invoice_status=$status;
                $model->save();
            }
            if(isset($_POST['void_invoice']))
                return $this->redirect(YII::$app->urlManager->createUrl("/members/default/update?id=".$model->member_id));
            
            //Update total
            $model->update_total($model->invoice_id);
               
            if($model->invoice_type=="booking")
                return $this->redirect(YII::$app->urlManager->createUrl("/invoice/default/update?id=". $model->invoice_id."&member_id=".$model->member_id."&book_id=".$booking_id));
            elseif($model->invoice_type=="trainer")
                return $this->redirect(YII::$app->urlManager->createUrl("/invoice/default/update?id=". $model->invoice_id."&training_id=".$model->invoice_type_id));
                
            else
                return $this->redirect(YII::$app->urlManager->createUrl("/invoice/default/update?id=". $model->invoice_id."&membership_type=".$memberShip_type_id."&membership_id=".$membership_id."&member_id=".$model->member_id));
        } else {
            return $this->render('update', [
                'model' => $model,
                'booking' => $booking,
                'invoiceItem'=>$invoiceItem,
                'booking'=>$booking,
                'invoicePayment'=>$invoicePayment
            ]);
        }
    }

    public function actionPrintinvoice($id)
    {
        $model = $this->findModel($id);
        $booking = array();
        $memberShip_type_id = 0;$membership_id=0;$member_id=0;$booking_id=0;
        if($model->invoice_type=="booking"){
            $booking = \app\modules\booking\models\Booking::findOne($model->invoice_type_id);
            $memberShip = \app\modules\members\models\Membership::findOne($booking->membership_id);
            //$memberShip_type_id = $memberShip->membership_type_id;
            $membership_id = $booking->membership_id;
            $member_id = $booking->member_id;
            $booking_id = $booking->book_id;
        }
        elseif($model->invoice_type_id)
        {
            $memberShip = \app\modules\members\models\Membership::findOne($model->invoice_type_id);
            
//            $memberShip_type_id = $memberShip->membership_type_id;
            if(isset($memberShip)){
                $membership_id = $memberShip->membership_id;
                $member_id = $memberShip->member_id;
            }
        }
        $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$id])->one();
        $invoicePayment = Payment::find()->where(["invoice_id"=>$id,'payment_void'=>0])->all();
        $content= $this->renderPartial('invoice_pdf', [
                'model' => $model,
                'booking' => $booking,
                'invoiceItem'=>$invoiceItem,
                'booking'=>$booking,
                'invoicePayment'=>$invoicePayment
        ]);
        
        $mpdf = new \mPDF('utf-8-s', 
            [76,200],0,'vi',5,5,5,5);

        
//        $mpdf->SetFont('arial', '', 6, true, true);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit();
    }
    
    public function actionPrintinvoice_a4($id)
    {
        $model = $this->findModel($id);
        $booking = array();
        $memberShip_type_id = 0;$membership_id=0;$member_id=0;$booking_id=0;
       
        $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$id])->one();
        $invoicePayment = Payment::find()->where(["invoice_id"=>$id,'payment_void'=>0])->all();
        $content= $this->renderPartial('invoice_pdf_a4', [
                'model' => $model,
                'booking' => $booking,
                'invoiceItem'=>$invoiceItem,
                'booking'=>$booking,
                'invoicePayment'=>$invoicePayment
        ]);
        
       $mpdf = new \mPDF('utf-8-s',    // mode - default ''
                "A4",    // format - A4, for example, default ''
                15,     // font size - default 0
                '',    // default font family
                20,    // margin_left
                20,    // margin right
                20,     // margin top
                16,    // margin bottom
                9,     // margin header
                9,     // margin footer
                'L'); 
             
              // 'utf-8-s', "A4",20,'',2,2,2,0);

        
//        $mpdf->SetFont('arial', '', 6, true, true);
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit();
    }
    
    public function actionPrintcontract($id)
    {
        $model = $this->findModel($id);
        $booking = array();
        $memberShip_type_id = 0;$membership_id=0;$member_id=0;$booking_id=0;
        if($model->invoice_type=="booking"){
            $booking = \app\modules\booking\models\Booking::findOne($model->invoice_type_id);
            $memberShip = \app\modules\members\models\Membership::findOne($booking->membership_id);
            $memberShip_type_id = $memberShip->membership_type_id;
            $membership_id = $booking->membership_id;
            $member_id = $booking->member_id;
            $booking_id = $booking->book_id;
        }
        else
        {
            $memberShip = \app\modules\members\models\Membership::findOne($model->invoice_type_id);
            
            $memberShip_type_id = $memberShip->membership_type_id;
            $membership_id = $memberShip->membership_id;
            $member_id = $memberShip->member_id;
        }
        $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$id])->one();
        $invoice = invoice::find()->where(["invoice_id"=>$id])->one();
        $invoicePayment = Payment::find()->where(["invoice_id"=>$id])->all();
        $modelMembershipType=false;
        if($memberShip_type_id>0)
            $modelMembershipType = \app\modules\membership_type\models\MembershipType::findOne ($memberShip_type_id);
        if(isset($_GET['template']) && $_GET['template']==1)
        {
            $language = "En";
            $view="contract_pdf";
        }
        else
        {
            $language = "Vi";
            $view="contract_pdf_vi";
        }
        $content= $this->renderPartial($view, [
                'model' => $model,
                'booking' => $booking,
                'invoiceItem'=>$invoiceItem,
                'booking'=>$booking,
                'invoicePayment'=>$invoicePayment,
                'invoice'=>$invoice,
                'modelMembershipType'=>$modelMembershipType,
                'language'=>$language
        ]);
        
        $mpdf = new \mPDF('utf-8-s', "A4",'','',2,2,2,0);
       
        $mpdf->WriteHTML($content);
        $mpdf->Output();
        exit();
    }
    /**
     * Deletes an existing invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //Check permission 
        $m = 'invoice';
        $BasicPermission = new \app\modules\permission\models\BasicPermission();
        $canDelete = $BasicPermission->checkModules($m, 'delete');

        if(!$canDelete){
            echo "You don't have permission with this action.";
            return false;
        }
        //End check permission
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetpaymentnextnumber()
    {
        $payment = new Payment();
        $payment_now=$payment->get_numerics($_GET['next_number_payment']);
        echo $payment->FormatPaymentNo($payment_now[0]);
    }
    
    public function actionVoid_payment(){
        $payment_id = $_POST['payment_id'];
        $status = $_POST['status'];
        $invoice_id = $_POST['invoice_id'];
        $payment = Payment::findOne($payment_id,$status);
        $payment->payment_void = $status;
        $invoice = new invoice();
        if($payment->save())
        {
            $payment->voidPayment($payment_id);
            $invoice->update_total($invoice_id);
            $invoice->updateStatusInvocie($invoice_id);
            echo "success";
        }
        else
            echo "fail";
    }
    
    public function actionUpdate_void_csdl_old(){
        $invoice = invoice::findAll(['invoice_status'=>  invoice::INVOICE_STATUS_VOID_INVOICE]);
        $payment = new Payment();
        foreach ($invoice as $item) {
            $payment->voidPaymentByInvoice($item->invoice_id);
        }
    }
    
    public function actionAdd_payment(){
        $next_number_payment = (isset($_POST['next_number_payment']) ? $_POST['next_number_payment'] : '');
        $now_oustanding = (isset($_POST['now_oustanding']) ? $_POST['now_oustanding'] : '');
        return $this->renderAjax('_add_payment',[
            'next_number_payment'=>$next_number_payment,
            'now_oustanding'=>$now_oustanding
        ]);
    }
    public function actionSaveGuestNumber(){
        $number = 1;
        $invoice_id = false;
        if(isset($_POST['invoice_id'])){
            $invoice_id = $_POST['invoice_id'];
        }
        if(isset($_POST['number'])){
            $number = $_POST['number'];
        }
        $invoice = invoice::findOne($invoice_id);
        if($invoice){
            $invoice->invoice_guest_number = $number;
            $invoice->save();
        }
    }
    public function actionGetItemByInvoiceId(){
        $id = false;
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }
        $invoice_item = new InvoiceItem();
        $dataProvider = $invoice_item->search(Yii::$app->request->queryParams,false,false,false,$id);
        
        return $this->renderAjax('_get_item_by_invoice_id', [
            'dataProvider' => $dataProvider,
            'id'=>$id
        ]);
    }
    
}
