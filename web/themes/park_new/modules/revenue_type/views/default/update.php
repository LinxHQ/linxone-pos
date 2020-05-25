<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\revenue_type\models\Revenue */


$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Revenues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->revenue_id, 'url' => ['view', 'id' => $model->revenue_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="revenue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
