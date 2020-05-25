<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 45%"><?php echo Yii::t('app', 'Item') ?></th>
            <th style="text-align: right;width: 20%"><?php echo Yii::t('app', 'Price') ?></th>
            <th style="text-align: center; width: 15%"><?php echo Yii::t('app', 'Qty') ?></th>
            <th style="text-align:"><?php echo Yii::t('app', 'Amount') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        if($invoice){
            $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where('invoice_id = '.$invoice_id.' AND (payment_id is Null OR payment_id = 0)')->all();
            foreach ($invoice_item_data as $items) { 
                $show_tax = "";
                if(intval($items->invoice_item_tax)<=0)
                    $show_tax = 'style="display: none;"';
                $show_qty_cancel = "";
                if(intval($items->invoice_item_delete)<=0)
                    $show_qty_cancel = 'style="display: none;"';
                ?>
                <tr id="o_pro_<?php echo $items->invoice_item_entity_id ?>">
                    <td id="stt">
                        <?php if($items->invoice_item_printed>0 ){ ?>
                        <?php if($items->invoice_item_quantity > 0){ ?>
                        <a href="#" style="top: 6px; position: relative;" id="o_item_<?php echo $items->invoice_item_entity_id ?>" onclick="blockItem(<?php echo $items->invoice_item_entity_id; ?>)">
                                <i class="glyphicon glyphicon-ban-circle"></i>
                            </a>
                        <?php } ?>
                        <?php }else { ?>
                            <a href="#" id="o_item_<?php echo $items->invoice_item_entity_id ?>" onclick="removeItem(<?php echo $items->invoice_item_entity_id; ?>)">
                                <i class="glyphicon glyphicon-trash"></i>
                            </a>
                        <?php } ?>
                        <span hidden="">
                            <input type="text" class="product_id " id="product_id" name="product_id[]" value="<?php echo $items->invoice_item_entity_id ?>" />
                            <input type="text" class="item_id" id="item_id" name="item_id[]" value="<?php echo $items->invoice_item_id ?>" />
                        </span>
                    </td>
                    <td>
                        <input type="text" class="product_name view-text-input" readonly id="product_name" name="product_name[]" value="<?php echo $items->invoice_item_description ?>" />
                        <a href="#" product_id="<?php echo $items->invoice_item_entity_id ?>" price_id="<?php echo $i ?>" class="edit_price_note" id="edit_price_note_<?php echo $i ?>"><i class="glyphicon glyphicon-edit"></i></a>
                        <input type="text" style="text-align: left; width: 80%; font-size: 11px; color: red" class="price_note view-text-input" readonly id="price_note_<?php echo $i ?>" name="price_note[]" value="<?php echo $items->invoice_item_note ?>" />
                    </td>
                    <td>
                        <input type="text" product_id="<?php echo $items->invoice_item_entity_id ?>" price_id="<?php echo $i ?>" style="text-align: right; width: 100%" readonly="" class="price" id="price_<?php echo $i ?>" name="price[]" value="<?php echo $items->invoice_item_price ?>" />
                        <span id="nav_price_tax_<?php echo $i ?>" <?php echo $show_tax; ?>><input type="text" style="text-align: right; width: 80%; font-size: 12px; color: red" readonly="" class="price_tax view-text-input" id="price_tax_<?php echo $i ?>" name="price_tax[]" value="<?php echo $items->invoice_item_tax ?>" /> 
                            <span style="color: red; position: relative; top: 2px;">%</span></span>
                    </td>
                    <td>
                        
                        <input type="number" product_id="<?php echo $items->invoice_item_entity_id ?>" style="text-align: center" id="quantity_<?php echo $i ?>" class="quantity" onchange="saveQty(<?php echo $i ?>,<?php echo $items->invoice_item_entity_id ?>);" name="quantity[]" value="<?php echo intval($items->invoice_item_quantity); ?>" />
                        <span <?php echo $show_qty_cancel; ?> style="font-size: 12px; color: red; position: relative; left: 35px;">-<?php echo intval($items->invoice_item_delete); ?></span>
                    </td>
                    <td><input type="text" style="text-align: right" readonly="" class="amount view-text-input" id="amount_<?php echo $i ?>" name="amount[]" value="<?php echo $items->invoice_item_amount ?>" /></td>
                </tr>
        <?php $i++;} } ?>
    </tbody>
    <tfoot>

    </tfoot>
</table>
<script type="text/javascript">
    $(document).ready(function(){
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
                    TotalAmount(0);
    });
</script>