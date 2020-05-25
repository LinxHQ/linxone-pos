<?php
use yii\helpers\Html;
use app\models\ListSetup;
use kartik\datetime\DateTimePicker;
$ListSetup = new ListSetup();
$payment_note = $ListSetup->getSelectOptionList("payment_note",false,false,'',2,false,'form-control select-none');
$Method = $ListSetup->getSelectOptionList("Method",false,false,'',false,false,'form-control select-none');
$date = date('Y-m-d H:i:s');
$start_date = $ListSetup->getDisplayDateTimeSql($date);
?>
<table id="form-payment-<?php echo $next_number_payment; ?>" class="table" style="text-align: left;" cellspacing="2">
    <tr >
    <td width="14%">
        <input name='payment_no[]' value='<?php echo $next_number_payment; ?>' readonly='true'>
        <input type='hidden' name='payment_id[]' value='0' readonly='true'>
    </td>
    <td width="17%">
        <input type='text' name='payment_date[]' value='<?php echo date('Y-m-d H:i:s'); ?>' class="disabled" readonly='true'>
        <?php 
//            echo DateTimePicker::widget([
//                'name' => 'payment_date[]',
//                'type' => DateTimePicker::TYPE_INPUT,
//                'id'=>'start_date',
//                'value' => $start_date,
//                'pluginOptions' => [
//                    'autoclose'=>true,
//                    'format' => 'yyyy-mm-dd hh:ii'
//                ],
//                        'options' => [
//                        'readonly' => 'readonly'
//                    ]
//            ]);
        ?>
    </td>
    <td width="15%"><span name='payment_method[]'><?php echo $Method; ?></span></td>
    <td style="text-align: left;width:15%"><input type='text' name='reference[]' value='' ></td>
    <td style="text-align: right;width:20%"><input style='width: 100%' onChange=change_amount(); name='payment_amount[]' type='text' value="<?php echo $now_oustanding; ?>" size='5' placeholder='0.00' style='height: 30px; font-size: 12px;padding: 5px;' /></td>
    <td width="20%"><span name='payment_note[]'><?php echo $payment_note ?></td>
    <td width="15%"><span name='created_by[]'><?php echo Yii::$app->user->identity->username; ?></span></td>
    <td width="15%"><a href="#" onclick="removeFormPayment('<?php echo $next_number_payment?>'); return false;"><i class="glyphicon glyphicon-trash"></i></a></td>
</tr>
<table>

