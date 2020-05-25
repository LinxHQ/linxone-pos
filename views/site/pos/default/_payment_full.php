<?php
use app\models\ListSetup;
use kartik\select2\Select2;

$Method = ListSetup::getItemByList('Method');
if($invoice) {
?>
<div hidden="">
    <?php echo yii\bootstrap\Html::input('text', 'pop_invoice_id',$invoice->invoice_id, ['id'=>'pop_invoice_id']) ?>
</div>
<table style="text-align: left; width: 100%">
    <tr>
        <td><?php echo Yii::t('app', 'Payables') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_need_pay',$invoice->invoice_total_last_paid,['id'=>'pop_need_pay','class'=>'view-text-input','style'=>'text-align:right','readonly'=>""]) ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Paid amount') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_guest_pay',$invoice->invoice_total_last_paid,['id'=>'pop_guest_pay','onkeyup'=>'calculatePayment()','style'=>'text-align:right']) ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Change') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_excess_cash','0.00',['id'=>'pop_excess_cash','class'=>'view-text-input','style'=>'text-align:right','readonly'=>""]) ?></td>
    </tr>
    <tr>
        <td><label class="control-label"><?php echo Yii::t('app', 'Payment type'); ?>:</label></td>
        <td>
            <?php 
                echo Select2::widget([
                    'name' => 'id123',
                    'data' => $Method,
                    'options' => [
                        'width'=>'80px',
                        'id'=>'payment_method_full'
                    ],
                ]);
            ?>
        </td>
    </tr>
    <tr>
        <td style="height: 35px;"><?php echo Yii::t('app', 'FOC') ?></td>
        <td><input type="checkbox" id="guest_treat" value="1" /></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Note') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_note',$invoice->invoice_note,['id'=>'pop_note','style'=>'text-align:right']) ?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right; padding-top: 20px;">
            <?php echo yii\bootstrap\Html::button(Yii::t('app', 'Pay'), ['class'=>'btn btn-success','onclick'=>'savePayment();return false;']) ?>
            <?php echo yii\bootstrap\Html::button(Yii::t('app', 'Cancel'), ['class'=>'btn btn-danger','onclick'=>'cancelPayment();return false;']) ?>
        </td>
    </tr>
</table>
<?php } ?>