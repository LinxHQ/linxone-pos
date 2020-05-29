<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\SesstionOrder;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sesstion = new Sesstion();
$total_outstanding = 0;
$total_paid = 0;
$total_void = 0;
foreach ($dataProvider->models  as $item) {
	$total_outstanding += $item->invoice->invoice_total_last_tax;
}
foreach ($dataProviderPaid->models  as $item) {
	$total_paid += $item->invoice->invoice_total_last_tax;
}
foreach ($dataProviderVoid->models  as $item) {
	$total_void += $item->invoice->invoice_total_last_tax;
}
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate">
		<div class="parkclub-iconbg">
			<img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png" alt="">
		</div> 
		<h3><?php echo Yii::t('app','Sessions') ?></h3>
	</div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'showFooter'=>TRUE,
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'summary' => "<div class='parkclub-header-left' style='width:auto'><a href='".Yii::$app->urlManager->createUrl('pos/session/index')."'><i class='glyphicon glyphicon-circle-arrow-left'></i></a>"
                    ."</div>"
						."<div class='parkclub-rectangle-header'>"
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
                    ],
                ]); ?>
			<div class="parkclub-footer" style="text-align: center"></div>	
        </div>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProviderPaid,
					'filterModel' => $searchModel,
                    'showFooter'=>TRUE,
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'><h3>".Yii::t('app', 'Paid order')."</h3> ".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> {totalCount, plural,=0{order} one{order} other{orders}}")."</div>
                     </div>",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'invoice_no',
                            'header' => Yii::t('app', 'Invoice no'),
                            'value'=>function($model){
                                return $model->invoice->invoice_no;
                            },
                            'footer'=>'<b>'.Yii::t('app', 'Total').'</b>'
                        ],
                        [
                            'attribute'=>'invoice_date',
							'filterInputOptions' => [
								'class'       => 'form-control',
								'placeholder' => 'Y-m-d H:i:s'
							],
                            'header' => Yii::t('app', 'Date time'),
                            'value'=>function($model){
                                return $model->invoice->invoice_date;
                            },
                        ],
                        [
                            // 'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Table name'),
                            'value'=>function($model){
                                $table = app\modules\pos\models\Tables::findOne($model->invoice->invoice_type_id);
                                if($table)
                                    return $table->table_name;
                                return "";
                            }
                        ],
                        [
                            // 'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Price'),
                            'value'=>function($model){
                                return app\models\ListSetup::getDisplayPrice($model->invoice->invoice_total_last_tax, 2);
                            },
                            'footer'=>'<b>'.app\models\ListSetup::getDisplayPrice($total_paid,2).'</b>'
                        ],
                        [
                            // 'attribute'=>'invoice_id',
                            'header' => Yii::t('app', 'Tax'),
                            'value'=>function($model){
                                return $model->invoice->invoice_gst_value;
                            },
                        ],
                        [
                            // 'attribute'=>'invoice_discount',
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
			<div class="parkclub-footer" style="text-align: center"></div>
        </div>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProviderVoid,
                    'showFooter'=>TRUE,
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'><h3>".Yii::t('app', 'Void order')."</h3> ".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> {totalCount, plural,=0{order} one{order} other{orders}}")."</div>
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
                            'footer'=>'<b>'.app\models\ListSetup::getDisplayPrice($total_void,2).'</b>'
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
                    ],
                ]);         
            ?>
            <?php Pjax::end(); ?>
			<div class="parkclub-footer" style="text-align: center"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    function void_invoice(id){
        var result = confirm('<?php echo Yii::t('app',"Are you sure you want to void this invoice?") ?>');
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