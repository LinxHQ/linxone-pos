
<?php
use yii\helpers\Html;
$ListSetup = new \app\models\ListSetup();
$user = new app\models\User();

$item = \app\modules\booking\models\Booking::findOne($book_id); 
?>
<div class="parkclub-rectangle-content">
<table class="parkclub-check-table"> 
    <tr class="dautien " style="line-height: 2.0;">
        <th style="" align="center"><?php echo Yii::t('app', 'Member'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Barcode'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Facility'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Price'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Date'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Time'); ?></th>
        <th style="" align="center"><?php echo Yii::t('app', 'Member Checkin'); ?></th>
        <th style=""><?php echo Yii::t('app', 'Witness'); ?></th>
    </tr>
    <?php
        $facility = app\modules\facility\models\Facility::findOne($item->facility_id); 
        if($facility){
            $member = app\modules\members\models\Members::findOne($item->member_id);
    ?>
    <tr>
        <td align="center">
                <?php echo $member->getMemberFullName(); ?>
            </td>
            <td>
                <?php 
                if($member->member_barcode =="" && $member->guest_code!="")
                    echo $member->guest_code;
                else
                    echo $member->member_barcode; 
                
                ?>
            </td>
            <td>
                <?php 
                     echo $facility->facility_name;
                ?>
            </td>
            <td>
                <?php
                    $invoice = new app\modules\invoice\models\invoice();
                    $invoice_data = $invoice->find()->where(['invoice_type'=>'booking','invoice_type_id'=>$item->book_id])->one();
                    if($invoice_data)
                        echo '(VNĐ) '.$ListSetup->getDisplayPrice ($invoice->getSubtotalInvocie($invoice_data->invoice_id)); 
                    else
                        echo 0;
                ?>
            </td>
            <td align="center">
                <?php echo $ListSetup->getDisplayDate($item->book_date); ?>
            </td>
            <td align="center">
                <?php echo $item->book_startdate.' - '.$item->book_enddate; ?>
            </td>
            <td align="center" id="member_checkin_<?php echo $item->book_id; ?>"><?php 
                if($item->book_member_checkin=="0000-00-00 00:00:00")
                    echo Html::button(Yii::t ('app', 'Check in'), ['onclick'=>'memberCheckin('.$item->book_id.');','class'=>"btn btn-primary",'id'=>'booking-member-check']);
                else
                    echo $ListSetup->getDisplayDateTime($item->book_member_checkin);
            ?></td>
            <td id="witness_check_<?php echo $item->book_id; ?>"><?php 
                if($item->book_witness_check==0)
                    echo Html::button(Yii::t ('app', 'Confirm'), ['onclick'=>'witnessCheckin('.$item->book_id.');','class'=>"btn btn-primary",'id'=>'booking-witness-check']);
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
            'url':'<?php echo YII::$app->urlManager->createUrl('/booking/default/member-checkin');  ?>',
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

