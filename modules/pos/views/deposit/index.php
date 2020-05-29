<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\DepositSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Deposit');

$keyserch = "";
$add = "<div class='parkclub-rectangle-header-right' ><button id='btn-add-facility' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/pos/deposit/create')."\"'>".Yii::t('app', 'NEW DEPOSIT')."</button></div>";
?>
<div class="deposit-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/deposit.png" width="26" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
        <div class="parkclub-search">
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">
    <?php Pjax::begin(); ?>    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id'=>'gridview-deposit',
            'tableOptions' => ['class' => 'parkclub-check-table'],
            'summary' => "<div class='parkclub-rectangle-header'>"
                    . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                    . $add
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
                [
                    'attribute'=>'deposit_address',
                    'format'=>'raw',
                    'value'=>function($model){
                        if($model->member_id>0){
                            $member = app\modules\members\models\Members::findOne($model->member_id);
                            if($member)
                                return $member->getMemberFullAddress($member->member_id);
                        }
                        return $model->deposit_address;
                    }
                ],  
                 'deposit_note',
                [
                    'attribute'=>'deposit_status',
                    'format'=>'raw',
                    'value' => function($model) {
                        if($model->deposit_status == 1)
                            return '<a href="#" onclick="updateStatus('.$model->deposit_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                        else
                            return '<a href="#" onclick="updateStatus('.$model->deposit_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
                    } 
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('pos/deposit/update-status'); ?>',
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#gridview-deposit').yiiGridView("applyFilter");
                }
            }
        });
    }
</script>