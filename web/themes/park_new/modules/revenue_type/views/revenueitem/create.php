<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\RevenueItem */


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Revenue Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="revenue-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
