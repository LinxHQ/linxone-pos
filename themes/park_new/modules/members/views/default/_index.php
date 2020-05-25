<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\membership_type;
use app\modules\members\models\Membership;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\members\models\memberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$canView = $BasicPermission->checkModules($m, 'view');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canList = $BasicPermission->checkModules($m, 'list');

//Check permission 
if(!$canList){
    echo Yii::t('app',"You don't have permission with this action.");
    return;
}

$add_guest = "";
if($canAdd && $status == \app\modules\members\models\Members::STATUS_MEMBER_ACTIVATED){
    $add_guest = "<div class='parkclub-rectangle-header-right'><button id='add-guest' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/members/default/create')."\"'>".Yii::t('app','Add member')."</button></div>";
}


?>
<div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}\n{pager}",
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'><h3>".Yii::t('app', $status)."</h3> ".Yii::t('app',"Showing <b>{begin}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, =0{member} one{member} other{members}}")."</div>"
                        . $add_guest
                    . "</div>",
        'tableOptions' =>['id' => 'receivable','class'=>'scroll-report'],
        'columns' => [
            [
	            'attribute' => 'member_name',
	            'format' => 'raw',
	            'value' => function($model) {
                        return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_search_id)."'>".$model->surname." ".$model->first_name."</a>";
	            },
                    'contentOptions'=>['style'=>'text-align:center;width:100px;', 'class'=> 'models->member_name' ],
	    ],
            [
                'header'=>Yii::t('app','Picture'),
                'attribute' => 'member_picture',
                'format' => ['image',['width'=>'80','class'=>'img-circle']],
                'value' => function($model) {
                    $member = new \app\modules\members\models\Members();
                    return $member->getMemberImages($model->member_id);
                },
                'contentOptions'=>['style'=>'margin-top:5px'],
            ],      
            [
	            'attribute' => 'membership_type_id',
	            'format' => 'html',
	            'value' => function($model) {
                        if($model->membership_type_id>0)
                        {
                            $membershipType = membership_type\models\MembershipType::findOne($model->membership_type_id);
                            if($membershipType)
                                return $membershipType['membership_name'];
                            return "";
                        }
                        return "";
	            }
	    ],
            [
	            'attribute' => 'member_barcode',
	            'format' => 'html',
  
	            'value' => function($model) {
                        return (($model->members) ? $model->members->member_barcode : "");
	            }
	    ],
            [
	            'attribute' => 'membership_barcode',
	            'format' => 'html',
                   
	            'value' => function($model) {
                        return $model->membership_barcode;
	            }
	    ],    
            [
	            'attribute' => 'membership_code',
	            'format' => 'html',
	            'header' => Yii::t('app','Card No'),
	            'value' => function($model) {
                        $url = Yii::$app->urlManager->createUrl('/members/default/addmembership?id='.$model->member_search_id.'&membership_id='.$model->membership_id);
                        return '<a href='.$url.'>'.$model->membership_code.'</a>';
	            }
	    ],
            [
	            'attribute' => 'membership_code',
	            'format' => 'html',
                    'header'=>Yii::t('app','Status'),
	            'value' => function($model) {
                        return Yii::t('app',$model->getStatus());
	            }
	    ],
            [
	            'attribute' => 'member_mobile',
	            'format' => 'html',
                    'header'=>Yii::t('app','Mobile'),
	            'value' => function($model) {
                        return $model->member_mobile;
	            }
	    ],
            [
	            'attribute' => 'id_card',
	            'format' => 'html',
	            'header' => Yii::t('app','Identity Card'),
	            'value' => function($model) {
                        return $model->member_card_id;
	            }
	    ],
            [
	            'format' => 'raw',
	            'header' => ' ',
	            'value' => function($model) {
                        if($model->member_status == \app\modules\members\models\Members::STATUS_MEMBER_ACTIVE)
                            return '<a href="#" onclick="updateStatus('.$model->member_search_id.',\''.\app\modules\members\models\Members::STATUS_MEMBER_DEACTIVATE.'\');return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                        else
                            return '<a href="#" onclick="updateStatus('.$model->member_search_id.',\''.\app\modules\members\models\Members::STATUS_MEMBER_ACTIVE.'\');return false;"><i class="glyphicon glyphicon-remove"></i></a>';
	            }
	    ],
        ],
    
    ]); ?>
<?php Pjax::end(); ?>
</div>
    <div class="parkclub-footer"></div>
</div>
<script type="text/javascript">
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('/members/default/update-status'); ?>',
            beforeSend:function(){
				if(status=='Active')
					if(confirm('<?php echo Yii::t('app','Are you sure you want to activate this member?') ?>'))
						return true;
				if(status=='Deactivate')
					if(confirm('<?php echo Yii::t('app','Are you sure you want to deactivate this member?') ?>'))
						return true;	
                return false;
            },
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success')
                    search_member();
            }
        });
    }
</script>