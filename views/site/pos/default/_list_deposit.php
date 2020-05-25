<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$search = "<div class='parkclub-rectangle-header-right' style='width:40%'>"
        . yii\bootstrap\Html::input('input', 'search_key',$search_key,array('id'=>'search_key','style'=>'width:72%','placeholder'=>Yii::t('app', 'Enter name or deposit no or phone')))
        . "<button id='add-guest' onclick='searchDeposit(); return false;'>".Yii::t('app','Search')."</button>"
        . "</div>";
?>
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => false,
        'id'=>'gridview-deposit',
//        'layout'=>"{items}\n{pager}",  
        'tableOptions' => ['class' => 'parkclub-check-table table table-striped'],
        'summary' => "<div class='parkclub-rectangle-header'>"
                . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                . $search
            . "</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header'=>Yii::t('app','Picture'),
                'attribute' => 'member_picture',
                'format' => ['image',['width'=>'80','class'=>'img-circle']],
                'value' => function($model) {
                    return $model->getMemberImages();
                },
                'contentOptions'=>['style'=>'margin-top:5px'],
            ], 
            'deposit_no',
            [
                'attribute'=>'deposit_name',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->member_id>0){
                        $member = app\modules\members\models\Members::findOne($model->member_id);
                        if($member)
                            return $member->getMemberFullName ();
                    }
                    return $model->deposit_name;
                }
            ],
            [
                'attribute'=>'deposit_phone',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->member_id>0){
                        $member = app\modules\members\models\Members::findOne($model->member_id);
                        if($member)
                            return $member->member_mobile;
                    }
                    return $model->deposit_phone;
                }
            ], 
            [
                'attribute'=>'deposit_email',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->member_id>0){
                        $member = app\modules\members\models\Members::findOne($model->member_id);
                        if($member)
                            return $member->member_email;
                    }
                    return $model->deposit_email;
                }
            ],  
             'deposit_note',    
            [
                'attribute'=>'deposit_balance',
                'format'=>'raw',
                'value'=>function($model){
                    return app\models\ListSetup::getDisplayPrice($model->deposit_balance,2);
                }
            ],  
            [
                'header'=>'',
                'format'=>'raw',
                'value' => function($model) {
                    return '<a href="#" class="btn btn-success btn-small" onclick="PaymentByDepopsit('.$model->deposit_id.');return false;">'
                        .Yii::t('app', 'Paid').'</a>';
                } 
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script type="text/javascript">
    function PaymentByDepopsit(deposit_id){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/add-payment') ?>',
            data:{invoice_id:invoice_id,deposit_id:deposit_id},
            success:function(data){
                $.unblockUI();
                data = jQuery.parseJSON(data);
                if(data.status1 == "fail"){
                    $('#bs-model-payment-by-deposit').modal('hide');
                    swal({
                    title:'<?php echo Yii::t('app',"Deposit balance is not enough to pay the bill."); ?>',
                    type:'error'
                    });
                    
                }else{
                    printOder(data.payment_id,invoice_id);
                    $('#bs-model-payment').modal('hide');
                    var count_order = $('#order .nav.nav-tabs li').length;
                    if(count_order<=2){
                        $('#bs-model-group').modal('hide');
                    }
                    var anchor = $('#order .nav-tabs .active span').siblings('a');
                    $(anchor.attr('href')).remove();
                    $('#order .nav-tabs .active span').parent().remove();
                    $("#order .nav-tabs li").children('a').first().click();
                    $('#bs-model-payment-by-deposit').modal('hide');
                }
            }
        });
    }
    
    function searchDeposit(){
        var  value = $('#search_key').val();
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $.blockUI();
        $('#form-payment-by-deposit').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/list-deposit') ?>',
        {invoice_id:invoice_id,search_key:value},function(){
            $.unblockUI();
        });
    }
</script>
