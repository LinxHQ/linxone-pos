<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\ListSetup;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueSearch */
/* @var $form yii\widgets\ActiveForm */
$m = 'revenue_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$add = "";
if($canAdd || Yii::$app->user->can('admin')){
    $add = "<div class='parkclub-rectangle-header-right'><button onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/revenue_type/default/create')."\"'>".Yii::t('app','ADD REVENUE TYPE')."</button></div>";
}
?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'layout'=>"{items}\n{pager}",
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $add
                    . "</div>",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
            [
	            'attribute' => 'revenue_name',
	            'format' => 'html',
	            'value' => function($model) {
                        return "<a href='".Yii::$app->urlManager->createUrl('revenue_type/default/update?id='.$model->revenue_id)."'>".$model->revenue_name."</a>";
	            }
	    ],
            'revenue_description:ntext',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                     'delete' => function ($model, $key, $index) {
                        $revenue = new app\modules\revenue_type\models\Revenue();
                        if($revenue->isCanDelete($index)){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $model, [
                                'class' => '',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                        return false;
                    },
                ],
            ],
            
        ],
    
    ]); ?>
<?php Pjax::end(); ?>
