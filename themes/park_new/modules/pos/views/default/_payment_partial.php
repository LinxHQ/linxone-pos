<?php
use app\models\ListSetup;
use kartik\select2\Select2;

$Method = ListSetup::getItemByList('Method');

?>

<div hidden="">
    <?php echo yii\bootstrap\Html::input('text', 'pop_invoice_id',$invoice->invoice_id, ['id'=>'pop_invoice_id']) ?>
</div>
<table class="table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 50%"><?php echo Yii::t('app', 'Item') ?></th>
            <th style="text-align: right;width: 25%"><?php echo Yii::t('app', 'Price') ?></th>
            <th style="text-align: center; width: 15%"><?php echo Yii::t('app', 'Qty') ?></th>
            <th style="text-align:"><?php echo Yii::t('app', 'Amount') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        if($invoice){
            foreach ($invoice_item as $items) {
                ?>
                <tr>
                    <td id="stt"></a>
                        <input type="checkbox" class="pay_item_id" item-price-p="<?php echo $items->invoice_item_amount?>" onclick="checkItem();" id="pay_item_id" name="pay_item_id[]" value="<?php echo $items->invoice_item_id ?>" />
                    </td>
                    <td><?php echo $items->invoice_item_description ?></td>
                    <td style="text-align: right;"><?php echo $items->invoice_item_price ?><?php echo ((intval($items->invoice_item_tax)>0) ? "(-".$items->invoice_item_tax.'%)' : ""); ?></td>
                    <td style="text-align: center;"><?php echo intval($items->invoice_item_quantity); ?></td>
                    <td style="text-align: right;"><?php echo $items->invoice_item_amount ?></td>
                </tr>
        <?php $i++;} } ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
<table>
    <tr>
        <td><label class="control-label"><?php echo Yii::t('app', 'Payment type'); ?>:</label></td>
        <td style="width: 55%;"><?php 
            echo Select2::widget([
                'name' => 'id123',
                'data' => $Method,
                'options' => [

                    'id'=>'payment_method_partial'
                ],
            ]);
            ?></td>
    </tr>
</table>
<div style="width: 100%;overflow: hidden;">
<table style="text-align: left; width: 60%; float: right">
    <tr class="hidden">
        <td><?php echo Yii::t('app', 'Total billed') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_sub_pay',0.00,['id'=>'pop_sub_pay','class'=>'view-text-input','style'=>'text-align:right;height:15px;','readonly'=>""]) ?></td>
    </tr>
    <tr <?php echo ((intval($invoice->invoice_discount)<=0) ? 'class="hidden"' : ""); ?>>
        <td><?php echo Yii::t('app', 'Discount') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_discount_pay',$invoice->invoice_discount,['id'=>'pop_discount_pay','class'=>'view-text-input','style'=>'text-align:right;height:15px;','readonly'=>""]) ?></td>
    </tr>
    <tr <?php echo ((intval($invoice->invoice_gst_value)<=0) ? 'class="hidden"' : ""); ?>>
        <td><?php echo Yii::t('app', 'Tax') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_tax_pay',$invoice->invoice_gst_value,['id'=>'pop_tax_pay','class'=>'view-text-input','style'=>'text-align:right;height:15px;','readonly'=>""]) ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Payables') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_need_pay',0.00,['id'=>'pop_need_pay','class'=>'view-text-input','style'=>'text-align:right;height:15px;','readonly'=>""]) ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Paid amount') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_guest_pay',0.00,['id'=>'pop_guest_pay','onkeyup'=>'calculatePayment()','style'=>'text-align:right']) ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('app', 'Change') ?></td>
        <td><?php echo yii\bootstrap\Html::input('text', 'pop_excess_cash','0.00',['id'=>'pop_excess_cash','class'=>'view-text-input','style'=>'text-align:right;height:15px;','readonly'=>""]) ?></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
            <?php echo yii\bootstrap\Html::button(Yii::t('app', 'Pay'), ['class'=>'btn btn-success','onclick'=>'savePaymentPartial();return false;']) ?>
            <?php echo yii\bootstrap\Html::button(Yii::t('app', 'Cancel'), ['class'=>'btn btn-danger','onclick'=>'cancelPayment();return false;']) ?>
        </td>
    </tr>
</table>
</div>
<script type="text/javascript">
    function checkItem(){
        var price = 0;
        var pop_tax_pay = $('#pop_tax_pay').val();
        var pop_discount_pay = $('#pop_discount_pay').val();
        $("#bs-model-payment input:checkbox").each(function(){
            var $this = $(this);
            if($this.is(":checked")){
                price = parseFloat(price) + parseFloat($this.attr("item-price-p")) ;
            }
        });
        if(pop_discount_pay!=0)
            price = parseFloat(price) - (parseFloat(price)*parseFloat(pop_discount_pay))/100;
        if(pop_tax_pay!=0)
            price = parseFloat(price) + (parseFloat(price)*parseFloat(pop_tax_pay))/100;
        $('#pop_need_pay').val(price);
        $('#pop_guest_pay').val(price);
    }
    
    function savePaymentPartial(){
        var pop_guest_pay = $('#pop_guest_pay').val();
        var pop_invoice_id = $('#pop_invoice_id').val();
        var pop_need_pay = $('#pop_need_pay').val();
        var payment_method = $('#payment_method_partial').val();
        var item_id = [];
        $("#bs-model-payment input:checkbox").each(function(){
            var $this = $(this);
            if($this.is(":checked")){
                item_id.push($this.val());
            }
        });
		$.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/add-payment') ?>',
            data:{pop_guest_pay:pop_guest_pay,invoice_id:pop_invoice_id,pop_need_pay:pop_need_pay,item_id:item_id, payment_method:payment_method},
            success:function(data){
				$.unblockUI();
                data = jQuery.parseJSON(data);
				if(data.status!='fail') {
					printOder(data.payment_id,pop_invoice_id);
					$('#bs-model-payment').modal('hide');
					var count_order = $('#order .nav.nav-tabs li').length;
					if(count_order<=2){
						$('#bs-model-group').modal('hide');
					} else {
						var anchor = $('#order .nav-tabs .active span').siblings('a');
						$(anchor.attr('href')).remove();
						$('#order .nav-tabs .active span').parent().remove();
						$("#order .nav-tabs li").children('a').first().click();
					}
				}
            }
        });
    }
</script>