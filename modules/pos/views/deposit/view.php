<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Deposit */
$name=$model->deposit_name;
$phone = $model->deposit_phone;
$email = $model->deposit_email;
$address = $model->deposit_address;
if($model->member_id>0){
    $member = app\modules\members\models\Members::findOne($model->member_id);
    if($member){
        $name = $member->getMemberFullName();
        $phone = $member->member_mobile;
        $email = $member->member_email;
        $address = $member->getMemberFullAddress($model->member_id);
    }
        
}
?>
<div class="deposit-view">

    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/deposit/index') ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo Yii::t('app', 'View deposit'); ?>
                    </div>
                </div>
               <div class="parkclub-newm">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute'=>'deposit_name',
                                'value'=>$name
                            ],
                            'deposit_no',
                            'deposit_amount',
                            'deposit_balance',
                            [
                                'attribute'=>'deposit_phone',
                                'value'=>$phone
                            ],
                            [
                                'attribute'=>'deposit_email',
                                'value'=>$email
                            ],
                            [
                                'attribute'=>'deposit_address',
                                'value'=>$address
                            ],
                            'deposit_note',
                            'deposit_status',
                        ],
                    ]) ?>
                   <br>
               </div>
           </div>
        </div>
    </div>
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <?php echo Yii::t('app', 'Payment'); ?>
                    </div>
                </div>
               <div class="parkclub-newm">
                    <?php \yii\widgets\Pjax::begin(); ?>
                                     <?= GridView::widget([
                         'dataProvider' => $dataProvider,
                         'layout' => "{summary}\n{items}\n{pager}",
                         'showFooter'=>TRUE,
                         'footerRowOptions'=>[
                             'style'=>'font-weight:bold;',

                             ],
                         'columns' => [

                             [
                                 'attribute' => 'payment_no',
                                 'format' => 'raw',
                                 'value' => function($model) {
                                     return $model->payment_no;
                                 }
                             ],
                             [
                                 'attribute' => 'invoice_id',
                                 'format' => 'raw',
                                 'header' => Yii::t('app','Invoice no'),
                                 'value' => function($model) {
                                     $invoice = \app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                     if($invoice)
                                        return $invoice->invoice_no;
                                     return "";
                                 }
                             ],
                             [
                                 'attribute' => 'payment_date',
                                 'format' => 'raw',
                                 'value' => function($model) {
                                     return app\models\ListSetup::getDisplayDate($model->payment_date);
                                 }
                             ],
                             [
                                 'attribute' => 'payment_amount',
                                 'header'=>Yii::t('app','Amount'),
                                 'format' => 'raw',
                                 'value' => function($model) {
                                     return $model->payment_amount;
                                 }
                             ],       

                        ],
                     ]); ?>
                         <?php \yii\widgets\Pjax::end(); ?> 
               </div>
           </div>
        </div>
    </div>
</div>
