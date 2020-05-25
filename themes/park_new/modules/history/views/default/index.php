<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\history\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'History');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/trainers.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="parkclub-search">
    </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'history_item',
                    [
                        'attribute'=>'username',
                        'header'=>Yii::t('app','Username'),
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
    </div>
</div>
