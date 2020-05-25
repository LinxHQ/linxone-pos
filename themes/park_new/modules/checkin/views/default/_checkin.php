<?php
/**
 * Model: memberShip
 */
$memberShipType_id = 0;
use app\models\Config;
use app\models\ListSetup;

$ListSetup = new ListSetup();

?>

<?php if($memberShipType && isset($member) && $member->is_trainer==0) {
    $memberShipType_id = $memberShipType->membership_type_id;
    $member_note = \app\modules\members\models\MembersNote::find()->where(['member_id'=>$member->member_id,'show_checkin'=>1])->all();
?>
    <div class="parkclub-popup parkclub-shadow">
        <div style="width:50%;float:left;margin-bottom:20px">
			<img style="margin:25px 0 0 10px;width: 80%;height: 80%" src="<?php echo $member->getMemberImages($member->member_id); ?>">
        </div>
		</br>
		<div style="width:50%;float:left;">
			<h1><?php echo '<a target="_blank" href='.Yii::$app->urlManager->createUrl('/members/default/update?id='.$member->member_id).'>'.$member->getMemberFullName($member->member_id).'</a>'; ?></h1>

			<table>
				<tr>
					<td class="parkclub-popup-left"><?php echo Yii::t('app', 'Membership type'); ?></td>
					<td></td>
					<td><?php echo $memberShipType->membership_name; ?></td>
				</tr>
				<tr>
					<td class="parkclub-popup-left"><?php echo Yii::t('app', 'Time'); ?></td>
					<td></td>
					<td><?php echo date('H:i'); ?></td>
				</tr>
				<tr>
					<td class="parkclub-popup-left"><?php echo Yii::t('app', 'Expiry'); ?></td>
					<td></td>
					<td><?php echo \app\models\ListSetup::getDisplayDate($memberShip->membership_enddate); ?></td>
				</tr>
				<tr>
					<td class="parkclub-popup-left"><?php echo Yii::t('app', 'Status'); ?></td>
					<td></td>
					<?php if($memberShip->membership_status!= app\modules\members\models\Membership::STATUS_ACTIVE_MEMBERSHIP) { ?>
					<td  style="color: red"><?php echo $memberShip->membership_status; ?></td>
					<?php } else { ?>
						<td><?php echo $memberShip->membership_status; ?></td>
					<?php } ?>
				</tr>
				<?php if(\app\modules\checkin\models\MembersCheckin::isCheckin($member->member_id,$memberShip->membership_id)){?>
					<tr>
						<td class="parkclub-popup-left"><?php echo Yii::t('app', 'Check in'); ?></td>
						<td></td>
						<td><?php echo date(YII::$app->params["defaultTime"],  strtotime($member_checkin->checkin_time)); ?></td>
					</tr>
				<?php } ?>
					<tr>
						<td class="parkclub-popup-left" ><?php echo Yii::t('app', 'Served session'); ?></td>
						<td></td>
						<td><?php $checkin = new \app\modules\checkin\models\MembersCheckin();
						echo $checkin->getTotalMemberCheckin($memberShip->membership_startdate,$memberShip->membership_enddate, $memberShip->member_id,$memberShip->membership_id); ?></td>
					</tr>
				<?php 

					$datetime1 = new DateTime(date('Y-m-d'));
					$datetime2 = new DateTime($memberShip->membership_enddate);
					$interval = $datetime1->diff($datetime2);
					$days = $interval->format('%R%a');
					$config = Config::find()->one();
					$day_expiry = $config->date_expiry_membership;
					if($days <= $day_expiry && $days > 0){
					 ?>
					<p style="font-size: 18px;color: red;">    
					   <?php echo $days.Yii::t('app', ' days to expiry date');?>
					</p>
					<?php } ?>
			
			
	 
			</table>
		</div>
		<div style="clear:both;"></div>
	<?php if($member_note){ ?>
		<div class="bg-info" style="padding: 10px; font-size: 14px; text-align: center">
			<?php
				echo Yii::t('app','Note').': ';
				foreach ($member_note as $item) {
					echo $item->note.'. ';
				}
			?>
		</div>
	<?php } ?>
	<!-- Training Packages -->
	<?php 
	if(isset($member_trainings) and count($member_trainings)) {
		$revenue = new app\modules\revenue_type\models\RevenueItem();
        $tranning_parckage = $revenue->getRevenueItemByEntry(2,'array','index');
	?>
	<div class="parkclub-rectangle-content">
		<table>
			<tbody>
				<tr>
					<th><?php echo Yii::t('app','Package');?></th>
					<th><?php echo Yii::t('app','Trainer');?></th>
					<th><?php echo Yii::t('app','End Date');?></th>
					<th><?php echo Yii::t('app','Total Ss');?></th>
					<th><?php echo Yii::t('app','Used Ss');?></th>
					<th><?php echo Yii::t('app','PT Checkin');?></th>
				</tr>
				<?php
				foreach($member_trainings as $member_training) {
					$disabled = false;
					if($member_checkin) {
						$disabled = true;
					}
				?>
				<tr>
					<td><?php echo (isset($tranning_parckage[$member_training->package_id]) ? $tranning_parckage[$member_training->package_id] : "") ;?></td>
					<td><?php echo $member_training->getTrainerName($member_training->member_training_id)?></td>
					<td><?php echo $ListSetup->getDisplayDate($member_training->training_end_date);?></td>
					<td><?php echo $member_training->training_total_sessions;?></td>
					<td><?php 
						$total_remaining_sessions = $member_training->getRemainingSession($member_training->member_training_id);
						echo $member_training->training_total_sessions - $total_remaining_sessions;
						?>
					</td>
					<td>
						<div class="checkbox">
							<label style="font-size: 1.5em">
								<?php echo yii\bootstrap\Html::checkbox('pt_checkin',
																		(isset($member_checkin) and $member_checkin->training_id==$member_training->member_training_id) ? $member_training->member_training_id : '',
																		['id'=>'pt_checkin'.$member_training->member_training_id,
																		'value'=>$member_training->member_training_id,
																		'disabled'=>$disabled,
																		'onclick'=>'set_unique_training(this)'
																		]); ?>
								<span class="cr"><i class="cr-icon fa fa-check"></i></span>
							</label>
						</div>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
	
	<?php if($memberShip->membership_status == app\modules\members\models\Membership::STATUS_ACTIVE_MEMBERSHIP && $memberShip->membership_enddate >= date('Y-m-d')
				&& !$memberShip->isLimitNumberOfSession($memberShip->membership_id)){?>
        <?php if(\app\modules\checkin\models\MembersCheckin::isCheckin($member->member_id,$memberShip->membership_id)){?>
            <button class="tour-highlight" id="pop_checktour" onclick="checkout(<?php echo $member->member_id; ?>,<?php echo $member_checkin->checkin_id; ?>);"><?php echo Yii::t('app', 'CHECK OUT'); ?></button>
        <?php } else { ?>
            <button class="tour-highlight" id="pop_checktour" onclick="checkin(<?php echo $member->member_id; ?>,<?php echo $memberShipType_id; ?>,<?php echo $memberShip->membership_id; ?>)" ><?php echo Yii::t('app', 'CHECK IN'); ?></button>
        <?php } ?>
	<?php } else { ?>
			<br>
				<?php if($memberShip->membership_enddate < date('Y-m-d')){ ?>
					<div class="bg-danger" style="padding: 10px; font-size: 16px;"><?php echo Yii::t('app', 'This membership is expired.'); ?></div>
				<?php }else { ?>
					<div class="bg-danger" style="padding: 10px; font-size: 16px;"><?php echo Yii::t('app', 'Out of session.'); ?></div>
				<?php } ?>
			<?php } ?>
    </div>
<?php 
} else {
?>
    <div style='padding-top:40px; font-size:18px;text-align:center'>
        <?php echo Yii::t('app','No result'); ?></div>   
<?php } ?> 
<script type="text/javascript">
    $('#bs-model-checkin').on('shown.bs.modal', function () {
        $('#pop_checktour').focus();
        var memberShipType_id = '<?php echo $memberShipType_id; ?>';

        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(memberShipType_id==0 && (((intall_data==2 || intall_data==1) && istour==1) || tour_step=='<?php echo app\models\Config::TOUR_CHECKIN; ?>')){
            tour_checkout.end();
            setTimeout(function(){
                $('#bs-model-checkin').modal('hide');
                $('#bs-model-checkin-endtour').modal('show');
            },1000);
        }
        if((intall_data==2 || intall_data==1) && istour==1){
            data_demo.init();
            data_demo.restart();
            data_demo.start();
            data_demo.goTo(2);
        }
        
        if(tour_step=='<?php echo app\models\Config::TOUR_CHECKIN; ?>')
        {
            tour_checkout.restart();
            tour_checkout.start();
            tour_checkout.goTo(2);
        }
    }) ;
	
	function set_unique_training(checkbox) {
		var checkbox_arr = $('input[name=pt_checkin]:checked');
		for(var i = 0; i < checkbox_arr.length; i++){
			checkbox_arr[i].checked = false;
		}
		checkbox.checked = true;
	}
</script>