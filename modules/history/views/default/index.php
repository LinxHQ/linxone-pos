<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\history\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'History');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'history_item',
            [
                'attribute'=>'username',
                'value'=>'user.username'
                
            ],
            [
                'attribute'=>'history_action',
                'filter' => $searchModel->actionList,
            ],
            'history_date',
             'history_table',
             'history_module',
             'history_description',
            // 'history_content:ntext',

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
