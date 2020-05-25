<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\SesstionOrder;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$total_outstanding = 0;
    foreach ($dataProvider->models  as $item) {
        $total_outstanding += $item->invoice->invoice_total_last_tax;
    }
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png" alt=""></div> <h3><?php echo Yii::t('app','Session report') ?></h3><a style="margin-left: 40%;" onclick="create_sesstion(); return false;" class="btn btn-primary"><?php echo Yii::t('app', 'Start session') ?></a></div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <div class='parkclub-rectangle-header'>
                <div class='parkclub-rectangle-header-left'>
                    <h3><?php echo Yii::t('app', 'Previous session report'); ?></h3>
                </div>
            </div>
            <div style="margin-left: 20px; margin-top: 15px; font-size: 16px;">
                <p><b><?php echo Yii::t('app', 'Cashier'); ?></b> : <?php $sesstion = Sesstion::find()->orderBy('sesstion_start_date DESC')->one();
                                                                    $user_id = false;
                                                                    if($sesstion){$user_id = $sesstion->user_id;} 
                                                                    $user = new User();
                                                                    echo $user->getFullName($user_id);
                                                                    ?></p>
                <p><b><?php echo Yii::t('app', 'Collected amount'); ?></b> : <?php $sesstion_order = new SesstionOrder(); echo number_format($sesstion_order->getTotalInvoicePaid(),0,",","."); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search ">
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
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
<script>
    function create_sesstion(){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/default/create-sesstion') ?>',
            data:{},
            success:function(){
                window.location.href = '<?php echo Yii::$app->urlManager->createUrl('/pos/default/index') ?>';
            }
        });
    }
</script>