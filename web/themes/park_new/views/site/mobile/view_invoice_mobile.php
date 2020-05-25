<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
</head>
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<div class="invoice-title">
    			<h2><?php echo $model->invoice_no ; ?></h2>
    		<hr>	
    		</div>
    		
    	</div>
    </div>
    
    <div class="row">
    	<div class="col-md-12">
    		<div class="panel panel-default">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong><?php echo "Hóa đơn được tạo vào ngày ".date('d/m/Y h:i:s', strtotime($model->invoice_date)).""; ?></strong></h3>
    			</div>
    			<div class="panel-body">
    				<div class="table-responsive">
    					<table class="table table-condensed">
    						<thead>
                                <tr>
        							<td><strong>Sản phẩm</strong></td>
        							<td class="text-center"><strong>Giá</strong></td>
        							<td class="text-center"><strong>Số lượng</strong></td>
        							<td class="text-center"><strong>Trạng thái</strong></td>
                                </tr>
    						</thead>
    						<tbody>
    							<!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                        <?php foreach ($invoice_item as $value) { ?>
                                                            <tr>
    								<td class="col-md-3">
    								    <div class="media">
    								    
    								         <div class="media-body">
    								             <h4 class="media-heading"><?php echo $value->invoice_item_description; ?></h4>
    								         </div>
    								    </div>
    								</td>
    								<td class="text-center"><?php echo $value->invoice_item_amount;  ?></td>
    								<td class="text-center"><?php echo $value->invoice_item_quantity;  ?></td>
    								<td>
    								  <div class="col-md-13">
        								  <div class="progress"> 
        								        <span class="progress-type"><?php echo $model->invoice_status; ?></span>
        								  </div>
        								 </div>  
    								    
    								</td>
                                                            </tr>
                                                        <?php } ?>
    						</tbody>
    					</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    
    <div class="row">
         <div class="col-md-12">
            <div class="col-md-4">
            	<h3 class="text-center">Chi tiết hóa đơn</h3><hr>
            	<div class="pull-left"><h4>Tổng hóa đơn</h4> </div>
            	<div class="pull-right"><h4 class="text-right"><?php echo $model->invoice_subtotal; ?></h4></div>
            	<div class="clearfix"></div>
            	<div class="pull-left"><h4>Thuế</h4> </div>
            	<div class="pull-right"><h4 class="text-right"><?php echo $model->invoice_total_last_tax-$model->invoice_subtotal; ?></h4></div>
            	<div class="clearfix"></div>
            	<div class="pull-left"><h4>Tổng thanh toán</h4> </div>
            	<div class="pull-right"><h4 class="text-right"><?php echo $model->invoice_total_last_tax; ?></h4></div>
            	<div class="clearfix"></div>
        	</div>
    	</div>
    </div>
</div>