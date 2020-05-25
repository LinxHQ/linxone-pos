<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ListSetup */

$this->title = Yii::t('app', 'Create List Setup');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'List Setups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-setup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
