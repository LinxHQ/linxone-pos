<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\checkin_entity\models\MemberRegisterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$search = "<div class='parkclub-rectangle-header-right' style='width:40%'>"
        . yii\bootstrap\Html::input('input', 'search_key',$search_key,array('id'=>'search_key','style'=>'width:60%','placeholder'=>Yii::t('app', 'Enter name or member no or phone')))
        . "<button id='add-guest' onclick='search($session_id,$entity_id,\"$entity_type\"); return false;'>".Yii::t('app','Search')."</button>"
        . "</div>";
?>
<div class="member-register-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped'],
        'summary' => "<div class='parkclub-rectangle-header'>"
                . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                . $search
            . "</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'member_code',
            [
                'attribute'=>'member_name',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getMemberName();
                }
            ],
            [
                'attribute'=>'member_email',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getMemberEmail();
                }
            ],
            [
                'attribute'=>'member_phone',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->getMemberPhone();
                }
            ],     
             'member_note',
            [
                'attribute'=>'payment_amount',
                'format'=>'raw',
                'value'=>function($model){
                    return \app\models\ListSetup::getDisplayPrice($model->payment_amount);
                }
            ],     
            [
                'header' => Yii::t('app',"Check in"),
                'format'=>'raw',
                'value'=>function($model) use ($session_id,$entity_type,$entity_id){
                    $member_checkin = new app\modules\checkin_entity\models\MemberCheckin();
                    if($member_checkin->isCheckin($model->register_entity_id, $session_id, $entity_type))
                            return $member_checkin->getDateCheckin ($model->register_entity_id, $session_id, $entity_type);
                    return '<button onclick="checkin(\''.$model->register_entity_id.'\',\''.$session_id.'\',\''.$entity_type.'\',\''.$entity_id.'\');" class="btn btn-success">'.Yii::t ('app','Check in').'</button>';
                } 
            ]
                
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<script type="text/javascript">
    function search(session_id,entity_id,entity_type){
        var  value = $('#search_key').val();
        $.blockUI();
        $('#modal-content-checkin').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/list-member-checkin') ?>',
        {session_id:session_id,entity_id:entity_id,entity_type:entity_type,search_key:value},
        function(){
            $.unblockUI();
        });
    }
    
    function checkin(register_entity_id,session_id,entity_type,entity_id){
        var  value = $('#search_key').val();
        $.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/checkin') ?>',
            data:{member_id:register_entity_id,session_id:session_id,entity_type:entity_type},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=="success")
                    search(session_id,entity_id,entity_type)
                else
                    alert(data.status);
                $.unblockUI();
            }
        });
    }
</script>