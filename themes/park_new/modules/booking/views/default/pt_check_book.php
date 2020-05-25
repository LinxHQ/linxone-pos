<?php
use yii\helpers\Html;
$ListSetup = new \app\models\ListSetup();
$user = new app\models\User();
$revenue = new \app\modules\revenue_type\models\RevenueItem();
$package_arr = $revenue->getRevenueItemByEntry(2,'array','index');

$item = \app\modules\booking\models\Booking::findOne($book_id); 

?>
<div class="parkclub-rectangle-content">
<table class="parkclub-check-table"> 
    <tr class="dautien " style="line-height: 2.0;">
        <th style="" align="center"><?php echo Yii::t('app', 'Book date'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Book time'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Package'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Member'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Note'); ?></th>
		<th style="" align="center"><?php echo Yii::t('app', 'Created by'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Trainer Checkin'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Member Checkin'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Witness'); ?></th>
    </tr>
    <?php
        $tranier_package = app\modules\training\models\MemberTrainings::findOne($item->traning_id);
        if($tranier_package){
            $member = app\modules\members\models\Members::findOne($tranier_package->member_id);
    ?>
        <tr>
            <td align="center">
                <?php echo $ListSetup->getDisplayDate($item->book_date); ?>
            </td>
            <td align="center"><?php echo $item->book_startdate.' - '.$item->book_enddate; ?></td>
            <td><?php echo (isset($package_arr[$tranier_package->package_id]) ? $package_arr[$tranier_package->package_id] : ""); ?></td>
            <td><?php echo $member->getMemberFullName(); ?></td>
            <td><?php echo $item->book_notes?></td>
			<td align="center"><?php echo $user->getFullName($item->book_createby);?></td>
            <td align="center" id="pt_checkin_<?php echo $item->book_id; ?>"><?php 
                if($item->book_trainer_checkin=="0000-00-00 00:00:00")
                    echo Html::button(Yii::t ('app', 'Checkin'), ['onclick'=>'PTCheckin('.$item->book_id.');','class'=>"btn btn-primary"]);
                else
                    echo $ListSetup->getDisplayDateTime($item->book_trainer_checkin);
            ?></td>
            <td align="center" id="member_checkin_<?php echo $item->book_id; ?>"><?php 
                if($item->book_member_checkin=="0000-00-00 00:00:00")
                    echo Html::button(Yii::t ('app', 'Checkin'), ['onclick'=>'memberCheckin('.$item->book_id.');','class'=>"btn btn-primary"]);
                else
                    echo $ListSetup->getDisplayDateTime($item->book_member_checkin);
            ?></td>
            <td id="witness_check_<?php echo $item->book_id; ?>"><?php 
                if($item->book_witness_check==0)
                    echo Html::button(Yii::t ('app', 'Confirm'), ['onclick'=>'witnessCheckin('.$item->book_id.');','class'=>"btn btn-primary"]);
                else
                    echo $user->getFullName($item->book_witness_check);
            ?></td>
        </tr>     
    <?php } ?>
</table>
<div class="parkclub-footer" style="text-align: center">          
</div>  
</div>
<script type="text/javascript">
    function PTCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/checkin');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success')
                    $('#pt_checkin_'+book_id).html(data.time);
                else
                    alert('Error checkin');
            }
        });
    }
    function witnessCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/witness-check');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $('#witness_check_'+book_id).html(data.name);
//                    location.reload();
                }
                else
                    alert('Error checkin');
            }
        });
    }
    function memberCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/member-checkin');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success')
                      $('#member_checkin_'+book_id).html(data.time);
//                    location.reload();
                else
                    alert('Error checkin');
            }
        });
    }
</script>

