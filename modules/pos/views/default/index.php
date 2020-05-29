<?php
use kartik\tabs\TabsX;
use app\modules\pos\models\TablesSearch;
use app\modules\pos\models\CategoryProduct;
use kartik\select2\Select2;

//Check permission 
//$m = 'pos';
//$DefinePermission = new \app\modules\permission\models\DefinePermission();
//$canManagerTable = $DefinePermission->checkFunction($m, 'Manager table');
//$canManagerMenu = $DefinePermission->checkFunction($m, 'Manager menu');
//$canManagerDeposit = $DefinePermission->checkFunction($m, 'Manager deposit');

$keyserch = "";
$category_product = new CategoryProduct();
$dropdow_category = array(""=>Yii::t('app','All'))+$category_product->getDataArray();
?>

<div class="pos-default-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><a href="<?php echo yii\helpers\Url::toRoute(['/pos/default/index']); ?>" ><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos.png" width="22" alt=""></div> <h3><?php echo Yii::t('app', 'Point of sale') ?></h3></a></div>
        <div class="col-lg-6" style="text-align: right; margin-top: 30px;">
            <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/setting') ?>" class="btn btn-primary"  ><?php echo Yii::t('app', 'Setting') ?> </a>
            <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/branch/index') ?>" class="btn btn-primary"  ><?php echo Yii::t('app', 'Branch') ?> </a>
            <a href="#" class="btn btn-primary" onclick="order(0);return false;"><?php echo Yii::t('app', 'NEW ORDER') ?> <span class="badge" style="color: #ce1515" id="new_order_count"><?php echo $invoice_no_table_count; ?></span></a>
            <a href="#" onclick="listOrder(); return false;" class="btn btn-primary"><?php echo Yii::t('app', 'LIST ORDER') ?></a>
            <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/report-sesstion') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'SESSION') ?></a>
            <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/pos_report') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'REPORT') ?></a>
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search" id="view_only_checkin">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">
                <?php
                    echo TabsX::widget([
                        'items'=>$category_arr,
                        'position'=>TabsX::POS_ABOVE,
                        'encodeLabels'=>false
                    ]);
                ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL LIST PRODUCT-->
    <div id="bs-model-group" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 95%; margin-top: 15px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true" onclick="reloadTables();return false;">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div style="text-align: center">
                        <h4 class="modal-title"><?php echo YII::t('app', "Table") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group">
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL LIST PRODUCT -->

<!-- MODAL PAYMENT-->
    <div id="bs-model-payment" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%; margin-top: 200px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Payment") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group" style="padding: 30px;">
                    <div style="padding-bottom: 10px; border-bottom: 1px solid #ddd;">
                        <input type="radio" name="type-payment" id="type-payment" value="0" onclick="formPaymentFull();" checked=""/>
                            <?php echo Yii::t('app','Pay in full');?> &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="type-payment" id="type-payment" value="1" onclick="formPaymentPartial()();"/>
                            <?php echo Yii::t('app','Pay balance');?>
                    </div>
                    <div id="form-payment-order"></div>
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL PAYMENT -->
<!-- MODAL CHANGE TABLE-->
    <div id="bs-model-change-table" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%; margin-top: 200px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Change table") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group" style="padding: 30px;">
                    <div hidden="">
                        <?php echo yii\bootstrap\Html::input('text', 'pop_invoice_id', 0, ['id'=>'pop_invoice_id']) ?>
                    </div>
                    <div><?php echo Yii::t('app', 'Choose new table'); ?></div>
                    <div>
                        <?php
                           $table = new app\modules\pos\models\Tables();
                            $dropdow_table =$table->getDataDropdownTableName();
                            $dropdow_table_order = $table->getDataDropdownTableNameOrder();
                            echo '<select id = "table_name" onchange="myFunction(); return false;">';
                            foreach($dropdow_table as $key=>$model){
                                if (!$table->check_status_table($key)){
                                    echo '<option value = '.$key.' style = "color:red;">';
                                }else{
                                    echo '<option value = '.$key.'>';
                                }
                                echo $model;
                                echo '</option>';
                            }
                            echo '</select>';
                             
                        ?>
                    </div>
                    <div style="display: none;" id ="show_info_order">
                    </div>
                    <button class="btn btn-success" style="margin-top: 14px; margin-left: 140px;" onclick="save_change_table();return false;"><?php echo Yii::t('app', 'Create');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL CHANGE TABLE-->

<!-- MODAl LIST ORDER-->
    <div id="bs-model-list-order" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%; margin-top: 50px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "List order") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-list-order">
                    
                </div>
            </div>
        </div>
    </div>
<!-- END MODAl LIST ORDER-->

<!-- MODAL PAYMENT BY DEPOSIT-->
    <div id="bs-model-payment-by-deposit" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 70%; margin-top: 50px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Payment by deposit") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group">
                    <div id="form-payment-by-deposit"></div>
                </div>
            </div>
        </div>
    </div>
<!-- END MODAL PAYMENT BY DEPOSIT-->

<!-- Added by vanth -->
<!-- MODAL PRINT RECEIPT-->
    <div id="bs-model-print-receipt" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 30%; margin-top: 20px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Print Receipt") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group" style="padding-left: 20px; padding-right: 20px">
                    <!-- print content here -->
					<div id="print-receipt-2" style="margin-bottom:20px">
						<div id="print-receipt-content"></div>
						<div id="duplicate-receipt-holder" style="display:none;"></div>
                    </div>
					<button class="btn btn-success" style="margin-top: 14px; margin: 0 auto;display:block" onclick="printReceipt();return false;"><?php echo Yii::t('app', 'Print');?></button>
                </div>
                <br>
            </div>
        </div>
    </div>
<!-- END MODAL PRINT RECEIPT-->
<!-- departmetn -->
    <div id="bs-model-change-branch" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 50%; margin-top: 200px">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: #337ab7; color:#fff;">
                    <!--<button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>-->
                    <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
                    <div>
                        <h4 class="modal-title"><?php echo YII::t('app', "Change branch") ?></h4>
                    </div>
               </div>
                <div class="modal-body" id="modal-content-group" style="padding: 30px;">
                    <div hidden="">
                        <?php echo yii\bootstrap\Html::input('text', 'pop_invoice_id', 0, ['id'=>'pop_invoice_id']) ?>
                    </div>
                    <div><?php echo Yii::t('app', 'Choose new branch'); ?></div>
                    <div>
                        <?php
                           $table = new app\modules\pos\models\Branch();
                            $dropdow_table =$table->getDataDropdownBranch(); 
                            echo '<select id = "branch_name">';
                            foreach($dropdow_table as $key=>$model){ 
                                    echo '<option value = '.$key.'>'; 
                                echo $model;
                                echo '</option>';
                            }
                            echo '</select>';
                             
                        ?>
                    </div>
                    <div style="display: none;" id ="show_info_order">
                    </div>
                    <button class="btn btn-success" style="margin-top: 14px; margin-left: 140px;" onclick="save_change_branch();return false;"><?php echo Yii::t('app', 'Create');?></button>
                </div>
            </div>
        </div>
    </div>
<!-- end department -->
<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/bootstrap.js" type="text/javascript" ></script>
<script>
    var dropdow_table_order =<?php echo json_encode($dropdow_table_order); ?>;
    $(document).ready(function(){
        $("#pop_guest_pay").blur(function() {
          if ($(this).attr("data-selected-all")) {
          //Remove atribute to allow select all again on focus        
          $(this).removeAttr("data-selected-all");
          }
        })
        $("#pop_guest_pay").click(function() {
         if (!$(this).attr("data-selected-all")) {
           try {
             $(this).selectionStart = 0;
             $(this).selectionEnd = $(this).value.length + 1;
             //add atribute allowing normal selecting post focus
             $(this).attr("data-selected-all", true);
           } catch (err) {
             $(this).select();
             //add atribute allowing normal selecting post focus
             $(this).attr("data-selected-all", true);
           }
         }
       });
    });

    function order(table_id,invoice_id){
        mybranch = '<?php echo $mybranch;?>';
         
        $('#bs-model-group .modal-content').css({'min-height':'670px'});
        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/table-oder'); ?>',{table_id:table_id},
            function(data){
                if(mybranch =='' && invoice_id ==0 )
                    $('#bs-model-change-branch').modal('show');
                $('#bs-model-group').modal('show');
                var table_name = $('#table_'+table_id+' h3').html();
                if(table_name==undefined)
                    $('#bs-model-group .modal-title').html('<?php echo Yii::t('app', 'New Order'); ?>');
                else
                    $('#bs-model-group .modal-title').html(table_name);
                $('#bs-model-list-order').modal('hide');
                if(invoice_id!=0)
                    $('#tab-invoice-'+invoice_id).click();
            });
    }
    
    function search_product()
    {
        var category =$('#category_product').val();
        
        var key_search = $('#search_product').val();
        
        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/index_sell'); ?>',{category:category,key_search:key_search});
    
    }
    
    function myFunction(){
        var table_id = $('#table_name').val();
        var pop_invoice_id = $('#pop_invoice_id').val();
        if(dropdow_table_order.hasOwnProperty(table_id)){
            $( "#show_info_order" ).show();
            $("#show_info_order").load('<?php echo Yii::$app->urlManager->createUrl('pos/default/load_order'); ?>',{table_id:table_id,invoice_id:pop_invoice_id});
        }else{
            $( "#show_info_order" ).hide();
        }
    }
    
    function join_order(invoice_id){
        var invoice_id_old = $('#order .tab-pane.active #invoice_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/join_order') ?>',
            data:{invoice_id:invoice_id,invoice_id_old:invoice_id_old},
            success:function(){
                $('#bs-model-change-table').modal('hide');
				$("#w0-container").load(location.href+" #w0-container>*","");
            }
        });
    }
    
    function listOrder(){
        $('#modal-list-order').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/order'); ?>',function(){
            $('#bs-model-list-order').modal('show');
        });
    }
	
	function reloadTables(){
		$("#w0-container").load(location.href+" #w0-container>*","");
	}
    
</script>