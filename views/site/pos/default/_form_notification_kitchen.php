<?php

$invoice_item = new \app\modules\invoice\models\InvoiceItem();
$listsetup = new \app\models\ListSetup();

?>
<style>
	
	
	@page { 
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 5px;
        margin-right: 5px;
		
    }
	
	.receipt{
        text-align: center;
        border-bottom: 2px dashed #333333;
        padding: 5px;
    }
	
</style>
<div id="print-order-section">
	<div class = "receipt">
        <h3>The ParkCity Club Hanoi Cafe</h3>
        <div style="margin-top: -10px">ĐT: 0432030999</div>
    </div>
	<h3 style="font-size: 16px; text-align: center;"><?php echo ($table) ? $table->table_name : "";?> </h3> 
	<div><?php echo Yii::t('app', 'Date time'); echo":"; echo $invoice->invoice_date; ?></div>
	<br/>            

	<table class="table table-striped table" style="width:100%;text-align: center;">
		<thead>
			<tr style="background-color: #5bb75b;">
				<th>#</th>
				<th><?php echo Yii::t('app', 'Item') ?></th>
				<th><?php echo Yii::t('app', 'Quantity') ?></th>
				<th><?php echo Yii::t('app', 'Note') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_id'=>$invoice->invoice_id])->all();
				$i=1;
				foreach ($invoice_item_data as $items) { ?>
					<tr id="o_pro_<?php echo $items->invoice_item_entity_id ?>">
						<?php if($items->invoice_item_quantity - $items->invoice_item_printed >0){ ?>
						<td id="stt"><?php echo $i; $i++;?></td>
						
						<td type="text" class="product_name" id="product_name"><?php echo $items->invoice_item_description; ?></td>
					
						<td type="text" class="quantity" id="quantity" ><?php echo $items->invoice_item_quantity - $items->invoice_item_printed; ?></td>
						
						<td type="text" class="item_note" id="item_note"><?php echo $items->invoice_item_note; ?></td>
					   <?php } ?>
					</tr>
			<?php }  ?>
		</tbody>
	</table>
</div>