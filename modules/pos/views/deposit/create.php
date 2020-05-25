<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Deposit */

$this->title = Yii::t('app', 'Create Deposit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deposits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
