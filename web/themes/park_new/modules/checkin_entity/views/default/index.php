<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ListSetup;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\checkin_entity\models\MemberRegisterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Member Registers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-register-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'register_entity_id',
//            'entity_id',
//            'entity_type',
            'member_code',
            'member_name',
             'member_email:email',
             'member_phone',
            // 'member_date',
            // 'member_status',
             'member_note',
            [
                'attribute'=>'member_status',
                'format'=>'raw',
                'value' => function($model) {
                    if($model->member_status == 1)
                        return '<a href="#" onclick="updateStatus('.$model->register_entity_id.',0,'.$model->entity_id.');return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                    else
                        return '<a href="#" onclick="updateStatus('.$model->register_entity_id.',1,'.$model->entity_id.');return false;"><i class="glyphicon glyphicon-remove"></i></a>';
                },

            ], 
            [
                'attribute'=>'payment_amount',
                'format'=>'raw',
                'value' => function($model) {
                    $listsetup = new ListSetup();
                    return $listsetup->getDisplayPrice($model->payment_amount);
                },

            ],
            [
                'attribute'=>'',
                'format'=>'raw',
                'value'=>function($model){
                    return '<button type="button" onclick="payment('.$model->register_entity_id.',\''.$model->getMemberName().'\','.$model->entity_id.','.$model->payment_amount.')" class="btn btn-success btn-small">'.Yii::t('app', 'pay').'</button>';
                }
            ],            
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<!-- MODAL PAYMENT-->
    <div id="bs-model-payment" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%; margin-top: 200px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Payment") ?></h4>
                    </div>
               </div>
                <div class="modal-body form-horizontal" id="modal-content-group" style="padding: 20px;">
                    <div class="hidden">
                        <input type="text" id="member_class_id" value="0" />
                        <input type="text" id="class_id" value="0" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo Yii::t('app','Name');?></label>
                        <div class="col-sm-10" id="member_class_name" style="padding-top: 7px;"></div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo Yii::t('app','Paid');?></label>
                        <div class="col-sm-10" id="member_paid" style="padding-top: 7px;">0</div>
                    </div>
                    <div class="form-group">
                        <label for="payment_amount" class="col-sm-2 control-label"><?php echo Yii::t('app','Amount');?></label>
                        <div class="col-sm-10">
                            <input type="text" id="payment_amount" value="0" class="form-control" placeholder="<?php echo Yii::t('app','Amount');?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" onclick="savePayment();return false;"><?php echo Yii::t('app','Save');?></button>
                  <button type="button" class="btn btn-default" id="model-close" data-dismiss="modal"><?php echo Yii::t('app','Close');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL PAYMENT -->
<script type="text/javascript">
    function updateStatus(id,status,class_id){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('checkin_entity/default/update-status'); ?>',
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#view-member').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/index') ?>',{entity_id:class_id,entity_type:'class'});
                }
            }
        });
    }
    
    function payment(id,name,class_id,amount){
        $('#member_class_id').val(id);
        $('#class_id').val(class_id);
        $('#member_class_name').html(name);
        $('#member_paid').html(amount);
        $('#bs-model-payment').modal('show');
    }
    
    function savePayment(){
        var id = $('#member_class_id').val();
        var amount = $('#payment_amount').val();
        var class_id = $('#class_id').val();

        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('checkin_entity/default/payment'); ?>',
            data:{id:id,amount:amount},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $('#bs-model-payment').modal('hide');
                    $('#bs-model-payment').on('hidden.bs.modal', function (e) {
                        $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                        $('#view-member').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/index') ?>',{entity_id:class_id,entity_type:'class'});
                    })

                }
            }
        });
    }
</script>