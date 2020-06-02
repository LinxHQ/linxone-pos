<?php
use kartik\select2\Select2;


$category_product = new \app\modules\pos\models\CategoryProduct();
$dropdow_category = $category_product->getDataArray(); 
?>

<div>
    <div class="col-sm-7">
        <div class="container" id="menu">
            <ul class="nav nav-tabs" role="tablist">
                <?php
                if($dropdow_category){
                    $i=1;
                    foreach ($dropdow_category as $key=>$item) {
                        $active = "";
                        if($i==1)
                            $active = 'active';
                    ?>
                        <li class="<?php echo $active; ?>">
                            <a href="#menu_<?php echo $key; ?>" data-toggle="tab" id="tab-menu-<?php echo $key; ?>" tab_category_product_id = "<?php echo $key; ?>" onclick="searchProduct(0, <?php echo $key; ?>);return false;" ><b><?php echo $item;?></b></a>
                        </li>
                    <?php $i++;}
                } ?> 
            </ul>
            <div class="tab-content">
                <?php
                if($dropdow_category){
                    $i=1;
                    foreach ($dropdow_category as $key=>$item) {
                    $active = "";
                    if($i==1)
                        $active = 'active'; 
                    ?>
                <div class="tab-pane <?php echo $active; ?>" id="menu_<?php echo $key; ?>" category_product_id = "<?php echo $key; ?>" >
                            
                        </div>
                    <?php $i++;}
                } ?>
            </div>
        </div>
    </div>
    
    
    
    <div class="col-sm-5">
        <div class="container" id="order">
            <ul class="nav nav-tabs" role="tablist">
                <?php
                if($invoice){
                    $i=1;
                    foreach ($invoice as $item) {
                        $active = "";
                        if($i==1)
                            $active = 'active';
                    ?>
                        <li class="<?php echo $active; ?>">
                            <a href="#order_<?php echo $item->invoice_id; ?>" data-toggle="tab" id="tab-invoice-<?php echo $item->invoice_id; ?>"><b><?php echo $item->invoice_no;?></b></a><span invoice_id="<?php echo $item->invoice_id; ?>">x</span>
                        </li>
                    <?php $i++;}
                }else{ ?>
                    <li class="active">
                        <a href="#order_0" data-toggle="tab" class="label-contact"><b><?php echo Yii::t('app', 'Order'); ?> 1</b></a><span invoice_id="0">x</span>
                    </li>
                <?php } ?>
                <li>
                    <a href="#" class="add-contact"><i class="glyphicon glyphicon-plus-sign"></i></a>
                </li>
            </ul>
            <div class="tab-content">
                <?php
                if($invoice){
                    $i=1;
                    foreach ($invoice as $item) {
                    $active = "";
                    if($i==1)
                        $active = 'active'; 
                    ?>
                        <div class="tab-pane <?php echo $active; ?>" id="order_<?php echo $item->invoice_id; ?>">
                            <?php echo $this->renderAjax('_form_order',['invoice_id'=>$item->invoice_id,'table_id'=>$table_id]);  ?>
                        </div>
                    <?php $i++;}
                }else{ ?>
                <div class="tab-pane active" id="order_0">
                    <?php echo $this->renderAjax('_form_order',['invoice_id'=>0,'table_id'=>$table_id]);  ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div id="content_print" style="display: none">
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var menu_tab_first = $('#menu a:first').attr('tab_category_product_id');
        searchProduct(0,menu_tab_first);
        
        TotalAmount(0);
        $("#order .nav-tabs").on("click", "a", function (e) {
                e.preventDefault();
                if (!$(this).hasClass('add-contact')) {
                    $(this).tab('show');
                }
            })
            .on("click", "span", function () {
                var invoice_id = $(this).attr('invoice_id');
                var id = $(this);
                if(invoice_id==0){
                    var anchor = id.siblings('a');
                    $(anchor.attr('href')).remove();
                    id.parent().remove();
                    $("#order .nav-tabs li").children('a').first().click();
                    return true;
                }
                $.ajax({
                    'type':'POST',
                    'beforeSend':function(){
                      var label = '<?php echo Yii::t('app', 'Bạn có chắc không muốn xóa hóa đơn này không?'); ?>';
                      if(confirm(label))
                          return true;
                      return false;
                    },
                    'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/delete-order'); ?>',
                    data:{invoice_id:invoice_id},
                    success:function(){
                        var anchor = id.siblings('a');
                        $(anchor.attr('href')).remove();
                        id.parent().remove();
                        $("#order .nav-tabs li").children('a').first().click();
						// $("#w0-container").load(location.href+" #w0-container>*","");
                    }
                })
            });

        $('#order .add-contact').click(function (e) {
            e.preventDefault();
            var count_tabs = $("#order .nav-tabs").children().length; //think about it ;) wth
			var id = count_tabs;
			var neighbour_id = $('#order .tab-content .tab-pane').last().attr('id');
			if(neighbour_id) {
				var arr = neighbour_id.split('_');
				if(arr[0]=='contact') {
					if(id <= arr[1]){
						id = parseInt(arr[1]) + 1;
					}	
				}	
			}
            var tabId = 'contact_' + id;
            $(this).closest('#order li').before('<li><a href="#contact_' + id + '" class="label-contact">Order'+id+'</a> <span invoice_id="0"> x </span></li>');
            $('#order .tab-content').append('<div class="tab-pane" id="' + tabId + '"></div>');
           $('#order .nav-tabs li:nth-child(' + count_tabs + ') a').click();
           addOrder(tabId);
        });
        
        $("#bs-model-payment").on('shown.bs.modal', function(){
            $('#bs-model-group').css('z-index','1030');
        });
        $("#bs-model-payment").on('hidden.bs.modal', function(){
            $('#bs-model-group').css('z-index','1040');
        });
        
        
//        $('#add_order_product').on('click',function(){
//            var count_price = $('#count_price').val();
//            addProduct(-count_price,'',0);
//            count_price = count_price+1;
//            $('#count_price').val(count_price);
//        });
        
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var invoice_id = $('#order .tab-pane.active #form_order #invoice_id').val();
            var table_id = $('#order .tab-pane.active #form_order #table_id').val();
            $('#order .tab-pane.active #form_order #order_item').load('<?php echo Yii::$app->urlManager->createUrl("pos/default/load-order-item"); ?>',{invoice_id:invoice_id,table_id:table_id});
        });
       
    });
    
    function addItemProduct(){
        var count_price = $('#count_price').val();
        addProduct(-count_price,'',0);
        count_price = count_price+1;
        $('#count_price').val(count_price); 
    }
    function searchProduct(page, key)
    {
        // $.blockUI();
        $('#menu #menu_'+key).load('<?php echo Yii::$app->urlManager->createUrl('pos/default/index_sell'); ?>',{page:page, category_product_id:key},
        function(data){
            // $.unblockUI();
        });
    }
    
    function addOrder(id_order)
    {
        $('#'+id_order).load('<?php echo Yii::$app->urlManager->createUrl('pos/default/form-order'); ?>',{table_id:'<?php echo $table_id ?>'},function(){
        });
    }
    
    function addProduct(id_product,product_name,product_price){
        var quantity = 1;
        var id = ".tab-pane.active #o_pro_" + id_product;
        var count_price = $('#count_price').val();
        var edit_name = 'class="product_name view-text-input" readonly';
        if(product_name==""){
            edit_name = 'class="product_name" onblur="TotalAmount();"';
        }
        var exist_product = 0;
        if($(id).length <= 0) {
            count_price = parseInt(count_price)+1;
            var tr='<tr id="o_pro_'+id_product+'">';
                tr+='<td id="stt">\n\
                        <a href="#" id="o_item_'+id_product+'" onclick="removeItem('+id_product+')">\n\
                                        <i class="glyphicon glyphicon-trash"></i></a>\n\
            <span hidden=""><input type="text" class="product_id " id="product_id" name="product_id[]" value="'+id_product+'" />\n\
            <input type="text" class="item_id" id="item_id" name="item_id[]" value="0" /></span></td>';
                tr+='<td>\n\
                        <input type="text" '+edit_name+'  id="product_name" name="product_name[]" value="'+product_name+'" />\n\
                        <a href="#" price_id="'+count_price+'" class="edit_price_note" id="edit_price_note_'+count_price+'"><i class="glyphicon glyphicon-edit"></i></a>\n\
                        <input type="text" style="text-align: left; width: 80%; font-size: 11px; color: red" class="price_note view-text-input" readonly id="price_note_'+count_price+'" name="price_note[]" value="" />\n\
                </td>';
                tr+='<td><input type="text" product_id="'+id_product+'" price_id="'+count_price+'" style="text-align: right" readonly="" class="price" id="price_'+count_price+'" name="price[]" value="'+product_price+'" />\n\
                        <span id="nav_price_tax_'+count_price+'" style="display:none"><input type="text" style="text-align: right; width: 80%; font-size: 11px; color: red" readonly="" class="price_tax view-text-input" id="price_tax_'+count_price+'" name="price_tax[]" value="0" />\n\
                            <span style="color: red; position: relative; top: 2px;">%</span></span>\n\
                    </td>';
                tr+='<td><input type="number" style="text-align: center" onblur="saveQty('+count_price+','+id_product+');" class="quantity" id="quantity_'+count_price+'" name="quantity[]" value="'+quantity+'" /></td>';
                tr+='<td><input type="text" style="text-align: right" class="amount view-text-input" id="amount_'+count_price+'" name="amount[]" value="0.00" /></td>';
            tr+='</tr>';
            $('#order .tab-pane.active #order_item table tfoot').append(tr);
            $('#count_price').val(count_price);
        }else{
            var quantity_order = $(id+' .quantity').val();
            quantity = parseFloat(quantity) + parseFloat(quantity_order);
            $(id+' .quantity').val(quantity);
            exist_product =id_product;
        }
        
        var total_quantity = 0;
        $('#order .tab-pane.active .quantity').each(function() {
            total_quantity =parseFloat(total_quantity) + parseFloat($(this).val());
        });
        $('#order .tab-pane.active #total_quantity').html(total_quantity);
        
        //Tính amount
        var amount = parseFloat(product_price) * parseFloat(quantity);
        $(id+' .amount').val(amount.toFixed(2));
        TotalAmount(undefined,'add_item',exist_product);
    }
    
    function TotalAmount(submit,type,id_product){
        var discount = $('#order .tab-pane.active #order_discount').val();
        var tax = $("#order .tab-pane.active #Tax option:selected").text();
        var guest_pay = $('#total_guest_pay').val();
        var amount = 0;
        var total_amount = 0;
        $('#order .tab-pane.active .amount').each(function() {
            var amount = $(this).val();
            total_amount +=parseFloat(amount);
        });
        
        var total_last_discount = 0;
        //Tổng tiền sau giảm giá
        total_last_discount = parseFloat(total_amount) - ((parseFloat(total_amount) * parseFloat(discount))/100);
        
        var total_last_tax =0;
        //Tổng tiền sau thuế
        total_last_tax = parseFloat(total_last_discount) + ((parseFloat(total_last_discount) * parseFloat(tax))/100);
        
        //Tổng tiền sau khi khách hàng trả
        var total_last_guest_pay=0;
        total_last_guest_pay = parseFloat(guest_pay) - parseFloat(total_last_tax);
        
        var total_oustangding = 0;
        //Tổng tiền còn nợ
        total_oustangding = parseFloat(total_last_tax) - parseFloat(guest_pay);
        if(total_oustangding<=0)
            total_oustangding = 0;
        
        $('#order .tab-pane.active #total_sub').val(total_amount.toFixed(2));
        $('#order .tab-pane.active #total_last_discount').val(total_last_discount.toFixed(2));
        $('#order .tab-pane.active #total_need_to_pay').val(total_last_tax.toFixed(2));
        $('#order .tab-pane.active #total_last_paid').val(total_oustangding.toFixed(2));
        $('#order .tab-pane.active #total_excess_cash').val(total_last_guest_pay.toFixed(2));
        if(submit==undefined)
            submitOrder(type,id_product);
    }
    
    function submitOrder(type,product_id){ 
        var data = $('#order .tab-pane.active #form_order').serialize();
        console.log(data);
        var invoice = $('#order .tab-pane.active #form_order #invoice_id').val();
        if(type!='update_total')
            $.blockUI();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/add-order')?>',
            data:data+'&type='+type+'&product_update_id='+product_id,
            success:function(data){
                data = jQuery.parseJSON(data);
                console.log(data);
                $('#order .tab-pane.active #form_order #invoice_id').val(data.invoice_id);
                $('#order .nav.nav-tabs .active span').attr('invoice_id',data.invoice_id);
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
                   $('#new_order_count').html(data.invoice_table_count);
                   $('#order .nav.nav-tabs .active .label-contact').html(data.invoice_no);
                   $('#order .tab-pane.active #print_notification_order').removeAttr('disabled');
                    $.unblockUI();
					
					// $("#w0-container").load(location.href+" #w0-container>*","");
            }
            
        })
    }
    function popPayment(){
        $('#type-payment').filter('[value=0]').prop('checked', true);
        $('#bs-model-payment').modal('show');
        formPaymentFull();
    }
    
    function cancelPayment(){
        $('#bs-model-payment').modal('hide');
    }
    
    function formPaymentFull(){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $('#bs-model-payment #modal-content-group #form-payment-order').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/load-payment-full') ?>',
        {invoice_id:invoice_id});
    }
    
    function formPaymentPartial(){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $('#bs-model-payment #modal-content-group #form-payment-order').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/load-payment-partial') ?>',
        {invoice_id:invoice_id});
    }
    
    function calculatePayment(){
        var pop_need_pay = $('#pop_need_pay').val();
        var pop_guest_pay = $('#pop_guest_pay').val();
        var total_last_guest_pay = 0;
        total_last_guest_pay = parseFloat(pop_guest_pay)-parseFloat(pop_need_pay);
        $('#pop_excess_cash').val(total_last_guest_pay.toFixed(2));
    }
    
    function savePayment(){
        var pop_guest_pay = $('#pop_guest_pay').val();
        var pop_invoice_id = $('#pop_invoice_id').val();
        var pop_need_pay = $('#pop_need_pay').val();
        var pop_note = $('#pop_note').val();
		var payment_method = $('#payment_method_full').val();
                if ($('#guest_treat').prop('checked')) {
                    var guest_treat = 1;
                }else{
                    var guest_treat = 0
                }
		$.blockUI();		
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/add-payment') ?>',
            data:{pop_guest_pay:pop_guest_pay,invoice_id:pop_invoice_id,pop_need_pay:pop_need_pay,payment_method:payment_method,guest_treat:guest_treat,pop_note:pop_note},
            success:function(data){
				$.unblockUI();
                data = jQuery.parseJSON(data);
                
				if(data.status!='fail') {
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
                                        console.log(data);
                                        console.log(pop_invoice_id);
					printOder(data.payment_id,pop_invoice_id);
				}
            }
        });
    }
    
    function popChangeTable(){
        var pop_invoice_id = $('#order .tab-pane.active #invoice_id').val();
        $('#bs-model-change-table').modal('show');
        $('#pop_invoice_id').val(pop_invoice_id);
    }
    
    function save_change_table(){
        var table_name = $('#table_name').val(); 
        var pop_invoice_id = $('#pop_invoice_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/tables/change_table') ?>',
            data:{table_name:table_name, invoice_id: pop_invoice_id},
            success:function(){
                $('#bs-model-change-table').modal('hide');
				// $("#w0-container").load(location.href+" #w0-container>*","");
            }
        });
    }
    function save_change_branch(){
        var branch_name = $('#branch_name').val(); 
    
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/branch/change_branch') ?>',
            data:{branch_name:branch_name },
            success:function(data){  
                $('#bs-model-change-branch').modal('hide');
				// $("#w0-container").load(location.href+" #w0-container>*","");
            }
        });
    }
    
    // function printOder(payment_id,invoice_id){
        // $('#content_print').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/print-order') ?>',{payment_id:payment_id,invoice_id:invoice_id},function(data){
            // loadOrderItem();
            // $.print('#content_print');
            // $(this).html("");
        // });
    // }
	
	function printOder(payment_id,invoice_id){
		$("#w0-container").load(location.href+" #w0-container>*","");
		
		$('#print-receipt-content').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/print-order') ?>',{payment_id:payment_id,invoice_id:invoice_id},function(data){
            // loadOrderItem();
            var receipt = $('#print-receipt-content').clone();
			$('#duplicate-receipt-holder').html(receipt);
			$("#duplicate-receipt-holder").addClass('visible-print-block');
                        //printReceipt();
			$('#bs-model-print-receipt').modal('show');
			// $.print('#content_print');
			// $(this).html("");
        });
    }
    
    function removeItem(id){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        var product_name = $('#order .tab-pane.active #o_pro_'+id+' #product_name').val();

        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/delete-item') ?>',
            data:{product_name: product_name,invoice_id:invoice_id},
            success:function(){
                $( "#order .tab-pane.active #o_pro_"+id ).remove();
                TotalAmount();
            }
        });
    }
    
    function blockItem(id){
        var invoice_id = $('#order .tab-pane.active #invoice_id').val();
        var product_name = $('#order .tab-pane.active #o_pro_'+id+' #product_name').val();
        var table_id = $('#order .tab-pane.active #form_order #table_id').val();
        
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/lock-item') ?>',
            data:{product_name: product_name,invoice_id:invoice_id},
            success:function(){
                $('#order .tab-pane.active #form_order #order_item').load('<?php echo Yii::$app->urlManager->createUrl("pos/default/load-order-item"); ?>',{invoice_id:invoice_id,table_id:table_id});
                TotalAmount(0);
            }
        });
    }
    
</script>

