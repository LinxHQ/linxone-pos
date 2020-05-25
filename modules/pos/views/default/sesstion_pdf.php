<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\pos\models\Sesstion;
use app\modules\pos\models\SesstionOrder;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$this->title = Yii::t('app', 'Report');
$total_paid = 0;
foreach ($dataProvider->models  as $item) {
        $total_paid += $item->invoice->invoice_total_last_tax;
    }
?>
 <link href='<?php echo Yii::$app->urlManager->baseUrl;?>/css/pdf.css' rel='stylesheet' type='text/css'>
<div class="park-header">
    <div class="pdf_head_sogan"><?php echo YII::$app->params['sogan_report'];?></div>
    <br>
    <div class="pdf_head_title"><?php echo Yii::t('app','Sesstion report'); ?></div> 
<br>
</div>
 <div style="width:100%;">
 <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}\n{pager}",
        'showFooter'=>TRUE,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Invoice no'),
                'value'=>function($model){
                    return $model->invoice->invoice_no;
                },
                'footer'=>'<b>'.Yii::t('app', 'Total').'</b>',
                'contentOptions'=>['style'=>'width: 15%;text-align: center;'],
                'footerOptions'=>['style'=>'text-align: center;'],
            ],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Date time'),
                'value'=>function($model){
                    return app\models\ListSetup::getDisplayDateTime($model->invoice->invoice_date,'d/m/Y');
                },
                'contentOptions'=>['style'=>'width: 28%;text-align: center;']
            ],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Table name'),
                'value'=>function($model){
                    $table = app\modules\pos\models\Tables::findOne($model->invoice->invoice_type_id);
                    if($table)
                        return $table->table_name;
                    return "";
                },
                'contentOptions'=>['style'=>'width: 20%;text-align: center;']
            ],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Tax'),
                'value'=>function($model){
                    return number_format($model->invoice->invoice_gst_value)."%";
                },
                'contentOptions'=>['style'=>'width: 10%;text-align: right;']
            ],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Discount'),
                'value'=>function($model){
                    return $model->invoice->invoice_discount."%";
                },
                'contentOptions'=>['style'=>'text-align: right;']
            ],
            [
                'attribute'=>'invoice_id',
                'header' => Yii::t('app', 'Amount'),
                'value'=>function($model){
                    return app\models\ListSetup::getDisplayPrice($model->invoice->invoice_total_last_tax, 2);
                },
                'footer'=>'<b>'.app\models\ListSetup::getDisplayPrice($total_paid,2).'</b>',
                'contentOptions'=>['style'=>'width: 15%;text-align: right;'],
                'footerOptions'=>['style'=>'text-align: right;'],
            ],
        ],
    ]);          
?>
<?php Pjax::end(); ?>
 </div>