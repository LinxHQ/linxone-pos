<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassMember */

$this->title = Yii::t('app', 'Create Class Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Class Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
