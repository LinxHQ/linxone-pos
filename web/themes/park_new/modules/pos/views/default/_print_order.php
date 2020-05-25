<?php 
use app\modules\invoice\models\InvoiceItem;
use app\modules\pos\models\Tables;
$user = new \app\models\User();
$tax = \app\models\ListSetup::getItemByList('Tax');
?>
<style>

	.visible-print-block{
		display:none!important;
	}
	@media print {
		.visible-print-block{
			display:block !important;
		}
	}
	
	#duplicate-receipt-holder{
		page-break-before: always;
		page-break-after: always;
	}
	
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
    .body{
        font-size: 14px;
		/* border: 1px solid black; */
        width: 100%;
    }
    .footer{
        font-weight: bold;
        padding-top: 20px;
        text-align: center;
    }
</style>
<div class = "body">
    <div class = "receipt">
        <h3>The ParkCity Club Hanoi Cafe</h3>
        <div style="margin-top: -10px">ĐT: 0432030999</div>
    </div>
    <div style="text-align: center;"><h3>PHIẾU THANH TOÁN</h3></div>
    <div>
        <?php echo Yii::t('app','Table '); if($invoice->invoice_type_id ==0){ echo "";}else {echo app\modules\pos\models\Tables::findOne($invoice->invoice_type_id)->table_name; }?><br>
        <?php echo Yii::t('app','Invoice no');?>: <?php echo $invoice->invoice_no; ?><br>
        <?php echo Yii::t('app','Date');?>: <?php echo date('d/m/Y H:i:s'); ?><br>
        <?php echo Yii::t('app','Cashier');?>: <?php echo "3wewr"; ?>
    </div><br>
    <table style="width: 100%;border-bottom: 2px dashed #333333; padding: 5px;" class="table-item">
        <tbody>
            <?php  
            $subtotal = 0;$i=1;
            foreach ($invoice_item_data as $item) {
                $subtotal = $subtotal + $item->invoice_item_amount;
                ?>
                <tr>
                    <td style="text-align: left"><?php echo $i; ?></td>
                    <td style="text-align: left">
                        <?php echo $item->invoice_item_description; ?><br>
                        <?php if($item->invoice_item_tax>0){ ?>
                            (- <?php echo $item->invoice_item_tax; ?>%)
                        <?php } ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo number_format($item->invoice_item_quantity, 0, '', ''); ?>
                        <?php if($item->invoice_item_delete>0){ ?>
                        (- <?php echo intval($item->invoice_item_delete); ?>)
                        <?php } ?>x
                    </td>
                    <td>
                       <?php echo app\models\ListSetup::getDisplayPrice($item->invoice_item_price,2); ?> 
                    </td>
                    <td style="text-align: right">=<?php echo app\models\ListSetup::getDisplayPrice($item->invoice_item_amount,2); ?></td> 
                </tr>
            <?php $i++;} ?>
        </tbody>
    </table>
    <br>
    <?php
        $total_paid = $subtotal;
        if(intval($invoice->invoice_discount)!=0)
            $total_paid = $total_paid - ($total_paid*$invoice->invoice_discount)/100;
        if(intval($invoice->invoice_gst_value)!=0)
            $total_paid = $total_paid + ($total_paid*$invoice->invoice_gst_value)/100;
    ?>
    <table style="float: right; width: 100%;" class="table-total">
        <tr>
            <td style="width: 70%;">Tổng số tiền chưa có GTGT:</td>
            <td style="text-align: right; width: 30%"><?php echo app\models\ListSetup::getDisplayPrice($subtotal,2); ?></td>
        </tr>
        <tr <?php echo (intval($invoice->invoice_discount) <=0) ? 'class="hidden"' : ""; ?>>
            <td style="padding-left:30px;"><?php echo Yii::t('app', 'Discount') ?>:</td>
            <td style="text-align: right;"><?php echo $invoice->invoice_discount; ?>%</td>
        </tr>
        <tr <?php echo (intval($invoice->invoice_gst_value) <=0) ? 'class="hidden"' : ""; ?>>
            <td style="padding-left:30px;"><?php echo Yii::t('app', 'Tax') ?>:</td>
            <td style="text-align: right;"><?php echo number_format($invoice->invoice_gst_value, 0, '', ''); ?>%</td>
        </tr>
        <tr>
            <td>Số tiền cần thanh toán:</td>
            <td style="text-align: right;"><?php echo app\models\ListSetup::getDisplayPrice($total_paid,2); ?></td>
        </tr>
		<?php if(isset($payment) && $payment != null) {?>
        <tr>
            <td>Đã thanh toán:</td>
            <td style="text-align: right;"><?php echo app\models\ListSetup::getDisplayPrice($total_paid,2); ?></td>
        </tr>
        <tr>
            <td style="padding-left:30px;">
			<?php
			if($payment) {
				switch($payment->payment_method){
					case 0:
						echo 'Master Card';break;
					case 1:
						echo 'Visa Card';break;
					case 2:
						echo 'Check';break;
					case 3:
						echo 'Tiền mặt';break;
					case 4:
						echo 'ATM';break;
					case 5:
						echo 'Khác';break;		
				}
			}
			?> :
			</td>
            <td style="text-align: right;"><?php echo app\models\ListSetup::getDisplayPrice($total_paid,2); ?></td>
        </tr>
		<?php } ?>
    </table>
    <span style="padding-top:30px;">Quý khách vui lòng:</span>
    <div style="padding-left:30px;">-Cung cấp thông tin viết hóa đơn GTGT trong ngày.</div>
    <div class = "footer">Cảm ơn quý khách & hẹn gặp lại.</div>
</div>