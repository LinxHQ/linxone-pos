<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$add_guest = "";
if($canAdd){
    $add_guest = "<div class='parkclub-rectangle-header-right'><button onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/members/default/create')."\"'>".Yii::t('app', 'ADD GUEST')."</button></div>";
}
?>
<div style="text-align: left;">
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => "<div class='parkclub-rectangle-header'>"
                . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                . $add_guest
            . "</div>",
//        'layout'=>"{items}\n{pager}",
        'columns' => [
            [
	            'attribute' =>'member_barcode',
	            'format' => 'html',
	            'value' => function($model) {
						//Check permission
						$m = 'crm';
						$BasicPermission = new \app\modules\permission\models\BasicPermission();
						$canUpdate = $BasicPermission->checkModules($m, 'update');
						//End check permission
						if($canUpdate)
							return \yii\bootstrap\Html::a($model->member_barcode, YII::$app->urlManager->createUrl('/crm/default/view?id='.$model->member_id));
						else 
							return $model->member_barcode;
				}
	    ],
            [
	            'attribute' => Yii::t('app','Name'),
	            'format' => 'html',
	            'value' => function($model) {
						//Check permission
						$m = 'crm';
						$BasicPermission = new \app\modules\permission\models\BasicPermission();
						$canUpdate = $BasicPermission->checkModules($m, 'update');
						//End check permission
						if($canUpdate)
							return \yii\bootstrap\Html::a($model->getMemberFullName(), YII::$app->urlManager->createUrl('/crm/default/view?id='.$model->member_id));
						else 
							return $model->getMemberFullName();							
				}
	    ],
            [
	            'attribute' => 'member_phone',
	            'format' => 'html',
	            'header' => Yii::t('app','Phone'),
	            'value' => function($model) {
                        return $model->member_phone;
	            }
	    ],
            [
	            'attribute' => 'membership_type_id',
	            'format' => 'html',
	            'value' => function($model) {
                        $memberShip = new \app\modules\members\models\Membership();
                        $memberShip_data = $memberShip->getMemberShipActiveByMember($model->member_id);
                        if($memberShip_data)
                        {
                            $membershipType = \app\modules\membership_type\models\MembershipType::findOne($memberShip_data->membership_type_id);
                            if($membershipType)
                                return $membershipType['membership_name'];
                            return "";
                        }
                        return "";
	            }
	    ],   
            [
	            'attribute' => 'membership_status',
				'header' => Yii::t('app','Membership status'),
	            'format' => 'html',
	            'value' => function($model) {
                        $memberShip = new \app\modules\members\models\Membership();
                        $memberShip_data = $memberShip->getMemberShipActiveByMember($model->member_id);
                        if($memberShip_data)
                        {
                            $membershipType = \app\modules\membership_type\models\MembershipType::findOne($memberShip_data->membership_type_id);
                            if($membershipType)
                                return Yii::t('app', $membershipType['membership_status']);
                            return "";
                        }
                        return "";
	            }
	    ],   
//            [
//	            'attribute' => 'member_crm_description',
//	            'format' => 'html',
//                    'header'=>Yii::t('app','Description'),
//	            'value' => function($model) {
//                        return $model->member_crm_description;
//	            }
//	    ],
            [
	            'attribute' => Yii::t('app','Most recent note'),
	            'format' => 'raw',
	            'value' => function($model) {
                        $list_setup = new \app\models\ListSetup();
                        $required_action = $list_setup->getItemByList('required_action');
                        $crm_note_status = $list_setup->getItemByList('crm_note_status');
                        $member_note = new app\modules\members\models\MembersNote();
                        $note = $member_note->getMostCrmNote($model->member_id);
                        $user = new app\models\User();
                        if($note){
                            $action = (isset($required_action[$note->required_action])) ? $required_action[$note->required_action] : "";
                            $status = (isset($crm_note_status[$note->status])) ? $crm_note_status[$note->status] : "";
                            $crm_note = (isset($note->note) && trim($note->note)!="") ? '<br><b>'.Yii::t('app', 'Note').':</b>'.$note->note : "";
                            $create_by = (isset($note->created_by)) ? $user->getFullName($note->created_by) : "";
                            return '('.$list_setup->getDisplayDate ($note->note_date).'): '.$create_by.'<br><b>'.Yii::t('app','Action').':</b> '.$action.
                                    '<br><b>'.Yii::t('app','Status').':</b> '.$status.$crm_note;
                        }
                        return "";
	            }
	    ],
            [
	            'attribute' => 'member_crm_status',
	            'format' => 'html',
	            'header' => Yii::t('app','Status'),
	            'value' => function($model) {
                        $listSetup = new \app\models\ListSetup();
                        return Yii::t('app', \app\models\ListSetup::getItemByList('crm_status')[$model->member_crm_status]);
	            }
	    ],
            [
	            'format' => 'html',
	            'header' => Yii::t('app','Level'),
	            'value' => function($model) {
                        $listSetup = new \app\models\ListSetup();
                        $crm_level = $listSetup->getItemByList('crm_level');
                        $level = (isset($crm_level[$model->member_crm_level])) ? $crm_level[$model->member_crm_level] : "";
                        return Yii::t('app', $level);
	            }
	    ],         
            [
	            'format' => 'html',
	            'header' => Yii::t('app','Days to expiry'),
	            'value' => function($model) {
                        $listSetup = new \app\models\ListSetup();
                        $array = $listSetup->getdate($model->membership_enddate);
                         if($array['diff']<0){
                             return "<span class='label label-danger'>".Yii::t('app', 'Overdue for')." &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br/>".$array['day_return']." </span>";
                         }else{
                             return "<span class='label label-warning'>".$array['day_return']."</span>";
                         }
	            }
	    ],  

            
        ],
    
    ]); ?>
</div>