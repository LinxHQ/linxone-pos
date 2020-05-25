<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use ptrnov\fullcalendar\FullcalendarScheduler;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\tabs\TabsX;

//Check permission 
$m = 'booking';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canCanbooking = $DefinePermission->checkFunction($m, 'Cancel booking');
//End check permission

?>
<?php 

    if(!isset($_GET['key']))
    { 
    ?>
        <div class="book-facility-search">
            <span class="book-label"><?php echo Yii::t('app', 'Confirmation code');?>:</span>
            <input value="<?php echo (isset($_GET['key']) ? $_GET['key'] : ""); ?>" size="40" id="keysearch" placeholder="<?php echo Yii::t('app', 'Member or Confirmation code'); ?>" class="form-control" style="width: 55%;display: inline;margin-right: 10px;" type="text"> 
            <button class="btn btn-success" onclick="searchBook();"><?php echo Yii::t('app', 'Search');?></button>
        </div>
    <?php 
    } 
?>
    
    <div  id='report_booking'>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
                'summary'=>false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'book_id',
            [
                'attribute'=>'confirmation_code',
                'format'=>'raw',
                'value'=>function($model){
                    $m = 'booking';
                    $BasicPermission = new \app\modules\permission\models\BasicPermission();
                    $canAdd = $BasicPermission->checkModules($m, 'add');
                    $invoice = app\modules\invoice\models\invoice::find()->where(['invoice_type_id'=>$model->book_id,'invoice_type'=>'booking'])->one();
                    if($canAdd && $invoice)
                        return '<a href="'.YII::$app->urlManager->createUrl("/invoice/default/update?id=".$invoice->invoice_id."&member_id=".$model->member_id."&book_id=".$model->book_id).'">'.$model->confirmation_code.'</a>';
                    return $model->confirmation_code;
                }
            ],
            [
                'attribute'=>'member_id',
                'format'=>'raw',
                'value'=>function($model){
                    $member = new \app\modules\members\models\Members();
                    return $member->getMemberFullName($model->member_id);
                }
            ],
            [
                'attribute'=>'facility_id',
                'format'=>'raw',
                'value'=>function($model){
                    $facility = app\modules\facility\models\Facility::findOne($model->facility_id);
                    if($facility)
                        return $facility->facility_name;
                    else
                        return "";
                }
            ],
            [
                'attribute'=>'book_date',
                'format'=>'raw',
                'value'=>function($model){
                    return date(YII::$app->params['defaultDate'],strtotime($model->book_date));
                }
            ],
            [
                'attribute'=>'book_startdate',
                'format'=>'raw',
                'value'=>function($model){
                    return date(YII::$app->params['defaultTime'],strtotime($model->book_startdate));
                }
            ],
            [
                'attribute'=>'book_enddate',
                'format'=>'raw',
                'value'=>function($model){
                    return date(YII::$app->params['defaultTime'],strtotime($model->book_enddate));;
                }
            ],
            [
                'header'=>Yii::t('app','Payment'),
                'format'=>'raw',
                'value'=>function($model){
                    $invoiceManage=new app\modules\invoice\models\invoice();
                    $invoice = app\modules\invoice\models\invoice::find()->where(['invoice_type_id'=>$model->book_id,'invoice_type'=>'booking'])->one();
                    if($invoice)
                        return Yii::t('app', $invoiceManage->getStatusInvoice($invoice->invoice_id));
                }
            ],
            [
                'header'=>Yii::t('app', 'Actions'),
                'format'=>'raw',
                'value'=>function($model){
                    $DefinePermission = new app\modules\permission\models\DefinePermission();
                    $m = 'booking';
                    $canCancelBooking = $DefinePermission->checkFunction($m, 'Cancel booking');
                    if(!$canCancelBooking) {
						$status = $model->getDropdownStatusBooking();
						$label_status = (isset($status[$model->book_status])) ? $status[$model->book_status] : "";
                        return Yii::t('app', $label_status);
                    }
                    $status = 1;
                    if($model->book_status==1)
                        $status = 0;
                    return '<div class="form-group">'.
                                Html::dropDownList('book_status', $model->book_status, $model->getDropdownStatusBooking(),['class'=>'form-control select-none','style'=>'width:100px;','onchange'=>'updateStatus('.$model->book_id.',this.value)'])
                            .'</div>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
            ],
        ],
    ]); ?>
    </div>