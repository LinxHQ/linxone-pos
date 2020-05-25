<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use app\modules\pos\models\Product;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$total_amount = 0;
$total_qty = 0;
foreach ($dataProvider->models  as $item) {
        $total_amount += $item->invoice_item_amount;
        $total_qty += $item->invoice_item_quantity;
    }
?>




            <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}\n{pager}",
        'showFooter'=>TRUE,
        'summary' => false,
        'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
            
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Invoice no'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice)
                                    return $invoice->invoice_no;
                                return "";
                            },
                            'footer'=>'<b>'.Yii::t('app', 'Total').'</b>'
                        ], 
                                    
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Product name'),
                            'value'=>function($model){
                                return $model->invoice_item_description;
                            },
                        ],
                                    
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Table name'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    $invoice_type_id = $invoice->invoice_type_id;
                                    $table = app\modules\pos\models\Tables::findOne($invoice_type_id);
                                    if($table)
                                        return $table->table_name;
                                    return "";
                                }
                                return "";
                            }
                        ],
                                
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice_item_price, 2);
                            },
                        ],
                                
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Quantity'),
                            'value'=>function($model){
                                return $model->invoice_item_quantity;
                            },
                            'footer'=>'<b>'.$total_qty.'</b>'
                        ],
                               
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Amount'),
                            'value'=>function($model){
                                if($model->invoice_item_tax ==0){
                                    return app\models\ListSetup::getDisplayPrice($model->invoice_item_amount, 2);
                                }else{
                                    return app\models\ListSetup::getDisplayPrice($model->invoice_item_amount, 2)."(Trừ ".$model->invoice_item_tax."% discount)";
                                }
                            },
                            'footer'=>'<b>'. app\models\ListSetup::getDisplayPrice($total_amount, 2).'</b>'
                        ],
                                   
                        
        ],
    
    ]); ?>
<?php Pjax::end(); ?>
            <div style="margin-top: 2%; text-align: center;">
             <?php echo '<a href="#" ><button class="btn btn-success" style = "margin-bottom: 10px;" onclick = print_receipt('.$id.'); >'.Yii::t('app', 'Print').'</button> </a>'; ?>
            </div>
<div id="order_print" style="display: none"></div>
<script>
    function print_receipt(invoice_id){
        $('#order_print').load('<?php echo Yii::$app->urlManager->createUrl('pos/default/print-receipt') ?>',{invoice_id:invoice_id},function(){
           // loadOrderItem();
            $.print('#order_print');
            $(this).html("");
        });
    }
</script>
