<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>
<?php Pjax::begin(['id'=>'pajax-group-table']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}\n{pager}",  
//        'filterModel' => $searchModel,
        'id'=>'gridview-group-table',
        'showFooter' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn','header'=>Yii::t('app','No.')],

            [
                'attribute'=>'invoice_total_last_tax',
                'format' => 'html',
                'header' => Yii::t('app','Total'),
                'value' => function($model) {
                    return $model->invoice_total_last_tax;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons'=>[ 
                    'view'=> function ($url,$model){
                        return Html::a('<button class="btn btn-success" >'.Yii::t('app', 'Join').'</button>','',
                            [
                                'onclick'=>'join_order('.$model->invoice_id.'); return false;'
                            ]);
                         },
                    'update'=>function(){
                             return false;
                            },
                    'delete'=>function(){
                                return false;
                    }
                            
                    ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
