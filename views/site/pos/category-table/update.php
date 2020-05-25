<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryTable */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Category Table',
]) . $model->category_table_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_table_id, 'url' => ['view', 'id' => $model->category_table_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-table-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
