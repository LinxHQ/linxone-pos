<?php

$invoice_item = new \app\modules\invoice\models\InvoiceItem();
$listsetup = new \app\models\ListSetup();
$table_id = (isset($_POST['table_id'])) ? $_POST['table_id'] : 0;
$invoice = false;$invoice_gst = 0;$invoice_discount = 0;$total_sub=0;
$total_last_discount=0;$total_last_paid=0;$total_need_to_pay=0;
$guest_number = false;
if($invoice_id>0){
    $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
    $invoice_gst = $invoice->invoice_gst;
    $invoice_discount = $invoice->invoice_discount;
    $total_sub = $invoice->invoice_subtotal;
    $total_need_to_pay = $invoice->invoice_total_last_tax;
    $total_last_discount = $invoice->invoice_total_last_discount;
    $total_last_paid = $invoice->invoice_total_last_paid;
    $guest_number = $invoice->invoice_guest_number;
}
$count_item = $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where('invoice_id = '.$invoice_id.' AND (payment_id is Null OR payment_id = 0)')->count();
$count_print = \app\modules\invoice\models\InvoiceItem::find()->where('invoice_id = '.$invoice_id.' AND '
                . 'invoice_item_quantity > invoice_item_printed')->count();
?>

<div>
    <form method="post" action="#" id="form_order">
        <div id="order_item" style="height: 290px;overflow:auto;margin-bottom: 5px">
            
        </div>
        <div hidden="">
            <?php echo yii\bootstrap\Html::input('text', 'table_id', $table_id,['id'=>'table_id']); ?>
            <?php echo yii\bootstrap\Html::input('text', 'invoice_id', $invoice_id,['id'=>'invoice_id']); ?>
            <?php echo yii\bootstrap\Html::input('text', 'count_price', $count_item,['id'=>'count_price']); ?>
        </div>
        <div style="text-align: right;"><a href="#" id="add_order_product" onclick="addItemProduct();return false;">
                <i class="glyphicon glyphicon-plus-sign"></i> <?php echo Yii::t('app', 'Add item'); ?></a>
        </div>
        <br>
        <div class="order_active col-sm-5">
            <button class="btn btn-success" style="width: 100%; margin-bottom: 10px;" onclick="popPayment(); return false;"><?php echo Yii::t('app', 'Pay'); ?></button>
            <button class="btn btn-success" style="width: 100%; margin-bottom: 10px;" onclick="popPaymentByDePosit(); return false;"><?php echo Yii::t('app', 'Pay by deposit'); ?></button>
            <?php if($count_print>0){ ?>
                <button class="btn btn-warning" id="print_notification_order" style="width: 100%; margin-bottom: 10px;" onclick="popOrder(); return false;"><?php echo Yii::t('app', 'Print order'); ?></button>
            <?php }else { ?>
                <button class="btn btn-warning" disabled="disabled" id="print_notification_order" style="width: 100%; margin-bottom: 10px;" onclick="popOrder(); return false;"><?php echo Yii::t('app', 'Print order'); ?></button>
            <?php } ?>
            
			<button class="btn btn-warning" style="width: 100%; margin-bottom: 10px;" onclick="popReceipt(); return false;"><?php echo Yii::t('app', 'Print receipt'); ?></button>
			
			<!--
			<a target="_blank" href='<?php echo Yii::$app->urlManager->createUrl('/pos/default/print-receipt?invoice_id='.$invoice_id) ?>' ><button class="btn btn-warning" style="width: 100%; margin-bottom: 10px;"><?php echo Yii::t('app', 'Print receipt'); ?></button></a>
			-->
			<button class="btn btn-info" style="width: 100%; margin-bottom: 10px;" onclick="popChangeTable(); return false;"><?php echo Yii::t('app', 'Shift/Join table'); ?></button>
        </div>
        <div class="order_total col-sm-7" style="text-align: right;padding-right:10px;">
            <table class="order-total" cellspacing="2" cellpadding="2">
                <tr>
                    <td><?php echo Yii::t('app', 'Total billed'); ?></td>
                    <td >
                        <?php echo yii\bootstrap\Html::input('text', 'total_sub', $total_sub, ['id'=>'total_sub','style'=>'text-align:right','class'=>'view-text-input']) ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Discount'); ?></td>
                    <td><?php echo yii\bootstrap\Html::input('text','order_discount',$invoice_discount,['id'=>'order_discount','style'=>'width:70%;text-align:right;float: left;']); ?> %</td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Tax'); ?></td>
                    <td style="text-align: right;"><?php echo $listsetup->getSelectOptionList('Tax',false,false,"style='width:70%;text-align:right;float: left; height:30px;margin: 0;'",$invoice_gst,false,'form-control select-none'); ?>%</td>
                </tr>
                <tr>
                    <td><?php echo Yii::t('app', 'Guest number'); ?></td>
                    <td><input type="text" name="txt" value="<?php echo $guest_number; ?>" onkeyup="saveGuestNumber(this.value)" style="width:70%;text-align:right;float: left;"></td>
                </tr>
                <tr>
                    <td><b><?php echo Yii::t('app', 'Payables'); ?></b></td>
                    <td><b><?php echo yii\bootstrap\Html::input('text', 'total_need_to_pay', $total_last_paid, 
                            ['id'=>'total_need_to_pay','style'=>'text-align:right','class'=>'view-text-input','readonly'=>""]) ?></b></td>
                </tr>
                    <tr hidden="">
                        <td><?php echo Yii::t('app', 'Paid amount'); ?></td>
                        <td><?php echo yii\bootstrap\Html::input('text','total_guest_pay',0,['id'=>'total_guest_pay','style'=>'width:80%','class'=>'view-text-input']); ?></td>
                    </tr>
                    <tr hidden="">
                        <td><?php echo Yii::t('app', 'Change given'); ?></td>
                        <td><?php echo yii\bootstrap\Html::input('text', 'total_excess_cash', '0.00', ['id'=>'total_excess_cash','class'=>'view-text-input']) ?></td>
                    </tr>
            </table>
            <div hidden="">
                <?php echo yii\bootstrap\Html::input('text', 'total_last_discount', $total_last_discount, ['id'=>'total_last_discount']) ?>
                <?php echo yii\bootstrap\Html::input('text', 'total_last_paid', $total_last_paid, ['id'=>'total_last_paid']) ?>
            </div>
        </div>
    </form>
</div>
<div id="order_print" style="display: none;"></div>
<script type="text/javascript">

    $(document).ready(function(){
          $('#order .tab-pane.active #total_guest_pay, #order .tab-pane.active #order_discount').keyup(function() {
                   TotalAmount(undefined,'update_total');
               });
          $('#order .tab-pane.active #Tax').change(function(){
              TotalAmount();
          });

          $(".price").popover({
              html : true,
              title :'<?php echo Yii::t('app', 'Change price'); ?>',
              content: function() {
                  var price_id = $(this).attr('price_id');
                  var price_value = $('#price_'+price_id).val();
                  var price_tax = $('#price_tax_'+price_id).val();
                  var product_id = $(this).attr('product_id');
                    var html='<?php echo Yii::t('app', 'Price') ?>: <input type="text" name="edit_price_'+price_id+'" id="edit_price_'+price_id+'" value="'+price_value+'" data-selected-all="true">\n\
                                <?php echo Yii::t('app', 'Discount') ?>: <input type="text" name="edit_tax_'+price_id+'" id="edit_tax_'+price_id+'" value="'+price_tax+'" ><div style="padding-top:5px;">\n\
                                <button type="button" class="btn btn-success btn-small" onclick="savePopEditPrice('+price_id+','+product_id+')"><?php echo Yii::t('app','Save');?></button>\n\
                                <button type="button" class="btn btn-danger btn-small" onclick="cancelPopEditPrice('+price_id+')"><?php echo Yii::t('app','Cancel');?></button>\n\
                                </div>';
                  return html;
              },
              placement:'left'
          }).on('show.bs.popover', function() {  });

          $(".edit_price_note").popover({
              html : true,
              title :'<?php echo Yii::t('app', 'Add note'); ?>',
              content: function() {
                  var price_id = $(this).attr('price_id');
                  var price_note= $('#price_note_'+price_id).val();
                  var product_id = $(this).attr('product_id');
                              var html='<input type="text" name="edit_price_note_'+price_id+'" id="edit_p_price_note_'+price_id+'" value="'+price_note+'">\n\
                                       <div style="padding-top:5px;">\n\
                                       <button type="button" class="btn btn-success btn-small" onclick="savePopEditPriceNote('+price_id+','+product_id+')"><?php echo Yii::t('app','Save');?></button>\n\
                                       <button type="button" class="btn btn-danger btn-small" onclick="cancelPopEditPriceNote('+price_id+')"><?php echo Yii::t('app','Cancel');?></button>\n\
                                       </div>';
                  return html;
              },
              placement:'left'
          }).on('show.bs.popover', function() {  });
          
          var current_invoice_id = <?php echo $invoice_id; ?>;
		  var invoice_id = $('#order .tab-pane.active #form_order #invoice_id').val();
		  if(invoice_id == current_invoice_id) {
			loadOrderItem();
		  }
    });
	
	$(document).on('shown.bs.modal', '.modal', function () {
		$(document.body).addClass('modal-open');
	});
	
    function cancelPopEditPrice(price_id){
        $("#price_"+price_id).click();
    }
    function savePopEditPrice(price_id,product_id){
          var edit_price = parseFloat($('#edit_price_'+price_id).val());
          var edit_tax = parseFloat($('#edit_tax_'+price_id).val());
          $("#price_"+price_id).val(edit_price.toFixed(2));
          $("#price_tax_"+price_id).val(edit_tax.toFixed(1));

          if(parseFloat(edit_tax)>0)
              $('#nav_price_tax_'+price_id).show();
          else
              $('#nav_price_tax_'+price_id).hide();

          $("#price_"+price_id).click();
          var product_price = $('#price_'+price_id).val();
          var tax = $('#price_tax_'+price_id).val();
          var quantity = $('#quantity_'+price_id).val();
          product_price = parseFloat(product_price) - (parseFloat(product_price) * parseFloat(tax))/100;
          var amount = parseFloat(product_price) * parseFloat(quantity);
          $('#amount_'+price_id).val(amount.toFixed(2));
          TotalAmount(undefined,'update_item',product_id);
    }
    
    function saveQty(price_id,product_id){
          var product_price = $('#price_'+price_id).val();
          var tax = $('#price_tax_'+price_id).val();
          var quantity = $('#quantity_'+price_id).val();
          product_price = parseFloat(product_price) - (parseFloat(product_price) * parseFloat(tax))/100;
          var amount = parseFloat(product_price) * parseFloat(quantity);
          $('#amount_'+price_id).val(amount.toFixed(2)); 
          TotalAmount(undefined,'update_item',product_id);
    }
    function cancelPopEditPriceNote(price_id){
        $("#edit_price_note_"+price_id).click();
    }
    function savePopEditPriceNote(price_id,product_id){
          var edit_price_note = $('#edit_p_price_note_'+price_id).val();
          $("#price_note_"+price_id).val(edit_price_note);
          $("#edit_price_note_"+price_id).click();
          TotalAmount(undefined,'add_item',product_id);
    }
    function popOrder(){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        var table_id = <?php echo $table_id; ?>;
        printOrderKitchen(invoice_id,table_id);

		// $.ajax({
			// type:'POST',
			// url:'<?php echo Yii::$app->urlManager->createUrl('/pos/default/save_printed'); ?>',
			// data:{invoice_id:invoice_id,table_id:table_id},
			// success:function(data){
				// $('#order .tab-pane.active #form_order #order_item').load('<?php echo Yii::$app->urlManager->createUrl("pos/default/load-order-item"); ?>',{invoice_id:invoice_id,table_id:table_id});
				// $('#order .tab-pane.active #print_notification_order').attr('disabled','disabled');
				// $("#w0-container").load(location.href+" #w0-container>*","");
			// }
		// });

    }
	
    function printOrderKitchen(invoice_id,table_id){
        $('#order_print').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/notification-kitchen') ?>',{invoice_id:invoice_id, table_id:table_id},function(){
            //$.print('#order_print');
            $('#order_print').print();
			$(this).html("");
			$.ajax({
				type:'POST',
				url:'<?php echo Yii::$app->urlManager->createUrl('/pos/default/save_printed'); ?>',
				data:{invoice_id:invoice_id,table_id:table_id},
				success:function(data){
					// $('#order .tab-pane.active #form_order #order_item').load('<?php echo Yii::$app->urlManager->createUrl("pos/default/load-order-item"); ?>',{invoice_id:invoice_id,table_id:table_id});
					$('#order .tab-pane.active #print_notification_order').attr('disabled','disabled');
					// $("#w0-container").load(location.href+" #w0-container>*","");
				}
			});
        });

    }
    
    function loadOrderItem(){
        var invoice_id = $('#order .tab-pane.active #form_order #invoice_id').val();
        var table_id = $('#order .tab-pane.active #form_order #table_id').val();
        $('#order .tab-pane.active #form_order #order_item').load('<?php echo Yii::$app->urlManager->createUrl("pos/default/load-order-item"); ?>',{invoice_id:invoice_id,table_id:table_id});
    }
    
    function popPaymentByDePosit(){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $('#form-payment-by-deposit').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/list-deposit') ?>',
        {invoice_id:invoice_id},function(){
            $('#bs-model-payment-by-deposit').modal('show');
        });
    }
    
    // function popReceipt(){
        // var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        // $('#order_print').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/print-receipt') ?>',{invoice_id:invoice_id},function(){
           // // loadOrderItem();
            // $.print('#order_print');
            // $(this).html("");
        // });
    // }
	function popReceipt(){
		var invoice_id = $('#order .tab-pane.active #invoice_id').val();
		if(parseInt(invoice_id)) {
			$('#print-receipt-content').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/print-receipt') ?>',{invoice_id:invoice_id},function(){
				var receipt = $('#print-receipt-content').clone();
				$('#duplicate-receipt-holder').html(receipt);
				$("#duplicate-receipt-holder").addClass('visible-print-block');
				$('#bs-model-print-receipt').modal('show');
			});
		} else {
			alert('Order without invoice');
		}
	}
	//var oldPage = document.body.innerHTML;
	function printReceipt(){
		$('#print-receipt-2').print();
		//Get the HTML of div
		//var divElements = document.getElementById('print-receipt-2').innerHTML;
		//Get the HTML of whole page
		// document.getElementById('bs-model-print-receipt').style.display='none';
		// $('#bs-model-group').modal('show');
		// var oldPage = document.body.innerHTML;

		//Reset the page's HTML with div's HTML only
		// document.body.innerHTML = 
		  // "<html><head><title></title></head><body>" + 
		  // divElements + "</body>";

		//Print Page
		// window.print();
		
		//Restore orignal HTML
		// document.body.innerHTML = oldPage;
		
		$('#bs-model-print-receipt').modal('hide');
		$('#print-receipt-content').html("");
		$('#duplicate-receipt-holder').html("");
		// $("#w0-container").load(location.href+" #w0-container>*","");
	}
    
    function saveGuestNumber(number){
        var invoice_id = $('#order .tab-pane.active #form_order #invoice_id').val();
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('/invoice/default/save-guest-number'); ?>',
            data:{invoice_id:invoice_id,number:number},
            success:function(data){
            }
        });
    }
</script>