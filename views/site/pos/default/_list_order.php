<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?php Pjax::begin(['id'=>'pajax-group-table']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}\n{pager}",  
//        'filterModel' => $searchModel,
        'id'=>'gridview-group-table',
        'showFooter' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>Yii::t('app','No.')],
            [
                'attribute'=>'invoice_no',
                'format' => 'raw',
                'header' => Yii::t('app','Invoice no'),
                'value' => function($model) {
                    return '<a href="#" onclick="order('.$model->invoice_type_id.','.$model->invoice_id.');return false;">'.$model->invoice_no.'</a>';
                }
            ],
            [
                'attribute'=>'invoice_type_id',
                'format' => 'html',
                'header' => Yii::t('app','Table name'),
                'value' => function($model) {
                    $table = \app\modules\pos\models\Tables::findOne($model->invoice_type_id);
                    if($table)
                        return $table->table_name;
                    return "";
                }
            ],
            [
                'attribute'=>'invoice_date',
                'format' => 'html',
                'header' => Yii::t('app','Date time'),
                'value' => function($model) {
                    return \app\models\ListSetup::getDisplayDateTime($model->invoice_date);
                }
            ],
            [
                'attribute'=>'invoice_id',
                'format' => 'html',
                'header' => Yii::t('app','Quantity'),
                'value' => function($model) {
                    $total_qty = 0;
                    $invoice_item_data = \app\modules\invoice\models\InvoiceItem::find()->where(['invoice_id'=>$model->invoice_id,'payment_id'=>null])->all();
                        foreach ($invoice_item_data as $items) { 
                            $total_qty += $items->invoice_item_quantity;
                        }
                        return $total_qty;
                }
            ],

            [
                'attribute'=>'invoice_total_last_tax',
                'format' => 'html',
                'header' => Yii::t('app','Total'),
                'value' => function($model) {
                    return $model->invoice_total_last_paid;
                }
            ],
            
        ],
    ]); ?>
<?php Pjax::end(); ?>
