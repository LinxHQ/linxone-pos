<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\SesstionOrder;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?php $sesstion = new Sesstion(); $m = $sesstion->getSesstionIdNow(); ?>
<?php
    $total_outstanding = 0;
    $total_paid = 0;
    foreach ($dataProvider->models  as $item) {
        $total_outstanding += $item->invoice->invoice_total_last_tax;
    }
    foreach ($dataProviderPaid->models  as $item) {
        $total_paid += $item->invoice->invoice_total_last_tax;
    }
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png" alt=""></div> <h3><?php echo Yii::t('app','Session Report') ?></h3><a style="margin-left: 40%;" href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/product-item'); ?>" class="btn btn-primary"><?php echo Yii::t('app', 'Sold items') ?></a><a style="margin-left: 1%;" onclick="end_sesstion(); return false;" class="btn btn-primary"><?php echo Yii::t('app', 'End session') ?></a></div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'showFooter'=>TRUE,
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'><h3>".Yii::t('app', 'Outstanding order')."</h3> ".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> {totalCount, plural,=0{order} one{order} other{orders}}")."</div>
                     </div>",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Invoice no'),
                            'value'=>function($model){
                                return $model->invoice->invoice_no;
                            },
                            'footer'=>'<b>'.Yii::t('app', 'Total').'</b>'
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Date time'),
                            'value'=>function($model){
                                return $model->invoice->invoice_date;
                            },
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Table name'),
                            'value'=>function($model){
                                $table = app\modules\pos\models\Tables::findOne($model->invoice->invoice_type_id);
                                if($table)
                                    return $table->table_name;
                                return "";
                            }
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice->invoice_total_last_tax, 2);
                            },
                            'footer'=>'<b>'. app\models\ListSetup::getDisplayPrice($total_outstanding, 2).'</b>'
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Tax'),
                            'value'=>function($model){
                                return $model->invoice->invoice_gst_value;
                            },
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Discount'),
                            'value'=>function($model){
                                return $model->invoice->invoice_discount;
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{delete}',
                            'buttons'=>[ 
                                'delete'=> function ($url,$model){
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>','',
                                            [
                                                'onclick'=>'delete_order('.$model->invoice_id.'); return false;'
                                            ]);
                                },            
                            ],
                        ],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
            <div>
                <p class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo Yii::t('app', 'Outstanding invoice will be transferred to the following session unless you delete them.'); ?></p>
            </div>
        </div>
        
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProviderPaid,
                    'showFooter'=>TRUE,
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'><h3>".Yii::t('app', 'Paid order')."</h3> ".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> {totalCount, plural,=0{order} one{order} other{orders}}")."</div>
                     </div>",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Invoice no'),
                            'value'=>function($model){
                                return $model->invoice->invoice_no;
                            },
                            'footer'=>'<b>'.Yii::t('app', 'Total').'</b>'
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Date time'),
                            'value'=>function($model){
                                return $model->invoice->invoice_date;
                            },
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Table name'),
                            'value'=>function($model){
                                $table = app\modules\pos\models\Tables::findOne($model->invoice->invoice_type_id);
                                if($table)
                                    return $table->table_name;
                                return "";
                            }
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice->invoice_total_last_tax, 2);
                            },
                            'footer'=>'<b>'.app\models\ListSetup::getDisplayPrice($total_paid,2).'</b>'
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Tax'),
                            'value'=>function($model){
                                return $model->invoice->invoice_gst_value;
                            },
                        ],
                        [
                            'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Discount'),
                            'value'=>function($model){
                                return $model->invoice->invoice_discount;
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{delete}',
                            'buttons'=>[ 
                                'delete'=> function ($url,$model){
                                        return Html::a('<span class = "btn btn-danger">'.Yii::t('app', 'Void invoice').'</span>','',
                                            [
                                                'onclick'=>'void_invoice('.$model->invoice_id.'); return false;'
                                            ]);
                                },            
                            ],
                        ],
                    ],
                ]);         
            ?>
            <?php Pjax::end(); ?>
                <div class="parkclub-footer" style="text-align: center">
                <a class="btn btn-success" href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/sesstion-pdf'); ?>"><?php echo Yii::t('app', 'Print Pdf'); ?> </a>'
                <a class="btn btn-success" href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/sesstion-excel'); ?>" ><?php echo Yii::t('app', 'Export excel'); ?></a>'
                </div>
        </div>
    </div>
</div>
<script>
    function end_sesstion(){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/end-sesstion') ?>',
            data:{},
            success:function(){
                window.location.href = '<?php echo Yii::$app->urlManager->createUrl('/pos/default/start-sesstion') ?>';
            }
        });
    }
    
    function delete_order(id){
            var result = confirm("Are you sure you want to delete this item?");
            if(result){
                $.ajax({
                    type:'post',
                    url:'<?php echo Yii::$app->urlManager->createUrl('pos/default/delete-order'); ?>',
                    data:{'invoice_id':id},
                    success:function(data){
                        console.log(data);
                         window.location.href = '<?php echo Yii::$app->urlManager->createUrl('/pos/default/report-sesstion') ?>';
                    }
                });
            }
        }
    function void_invoice(id){
        var result = confirm("Are you sure you want to void this invoice?");
        if(result){
                $.ajax({
                    type:'post',
                    url:'<?php echo Yii::$app->urlManager->createUrl('pos/default/void-invoice'); ?>',
                    data:{'invoice_id':id},
                    success:function(data){
                        location.reload();  
                    }
                });
            }
    }
    
</script>