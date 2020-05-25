<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Tables */

$this->title = Yii::t('app', 'Create Tables');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tables-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
