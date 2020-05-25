<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use app\modules\pos\models\Product;
use app\modules\invoice\models\invoice;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$product = new Product();
$dropdow_product = array(''=>Yii::t('app','All'))+$product->getDataDropdownProductName();
$product_selected = isset($_GET['product'])?$_GET['product']:0;
$total_amount = 0;
$total_qty = 0;
$invoi = new invoice();
foreach ($dataProvider->models  as $item) {
        $invoice = app\modules\invoice\models\invoice::findOne($item->invoice_id);
        $total_qty += $item->invoice_item_quantity;
        $total_amount += $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$item->invoice_item_amount,$invoice->invoice_discount);
    }
?>


<div  id="members-index" class="parkclub-wrapper parkclub-wrapper-search members-index">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app','Sold products');?>
                        </div>
                    </div>
             <?php 
    
    echo '<table>';
    echo '<tr><td style="padding:20px; width:40px;">';
    echo '<label class="control-label">'.Yii::t('app','Products').': </label></td><td style="width:250px;">';
    
    echo Select2::widget([
        'name' => 'id',
        'data' => $dropdow_product,
        'value' => $product_selected,
        'options' => [
            'id'=>'product',
            'onchange' => 'search_product_sold(); return false;',
            'width' =>'30%',
        ],
    ]);
            
    echo '</td>';
    echo '<td> </td>';
    echo '</tr>';
    echo '</table>';
    
    
    ?>
            <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}\n{pager}",
        'showFooter'=>TRUE,
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                    . "</div>",
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Date'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice)
                                    return date('d/m/Y',  strtotime($invoice->invoice_date));
                                return "";
                            }
                        ],
            
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
                                
//                        [
//                            'attribute'=>'invoice_item_id',
//                            'header' => Yii::t('app', 'Date time'),
//                            'value'=>function($model){
//                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
//                                if($invoice)
//                                    return $invoice->invoice_date;
//                                return "";
//                            },
//                        ],
                                    
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Product name'),
                            'value'=>function($model){
                                return $model->invoice_item_description;
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
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice_item_price, 2);
                            },
                        ],
                                
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Discount'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    return $invoice->invoice_discount." %";
                                }
                            },
                        ],
                               
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Amount'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    $invoi = new app\modules\invoice\models\invoice();
                                    $amount = $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$model->invoice_item_amount,$invoice->invoice_discount);
                                    return app\models\ListSetup::getDisplayPrice($amount, 2);
                                }
                                return "";
                            },
                            'footer'=>'<b>'. app\models\ListSetup::getDisplayPrice($total_amount, 2).'</b>'
                        ],
                        [
                            'attribute'=>'invoice_item_id',
                            'header' => Yii::t('app', 'Status'),
                            'value'=>function($model){
                                $invoice = app\modules\invoice\models\invoice::findOne($model->invoice_id);
                                if($invoice){
                                    return Yii::t('app',$invoice->invoice_status);
                                }
                            },
                        ],
                                   
                        
        ],
    
    ]); ?>
<?php Pjax::end(); ?>
            <div style="margin-top: 2%; text-align: center;">
             <?php echo '<a href='.Yii::$app->urlManager->createUrl('/pos/default/pos_report_product_sold?product='.$product_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);" >'.Yii::t('app', 'Export Excel').'</button> </a>'; ?>
            </div>
        </div>
    </div>
</div>
<script>
    function search_product_sold()
    {
        var product=$('#product').val();
        window.location.href = "<?php echo Yii::$app->urlManager->createUrl('/pos/default/product-item');?>?product="+product;

    }
</script>