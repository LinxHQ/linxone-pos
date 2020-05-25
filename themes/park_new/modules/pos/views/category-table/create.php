<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\CategoryTable */

$this->title = Yii::t('app', 'Create Category Table');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Category Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-table-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
