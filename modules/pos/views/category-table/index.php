<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\CategoryTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="category-table-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'id'=>'gridview-table',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'category_table_name',
            'category_table_description',
            'category_table_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
