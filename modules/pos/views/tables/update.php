<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Tables */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tables',
]) . $model->table_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->table_id, 'url' => ['view', 'id' => $model->table_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tables-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
