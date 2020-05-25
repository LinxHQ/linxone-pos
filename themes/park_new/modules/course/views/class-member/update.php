<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassMember */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Class Member',
]) . $model->class_member_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Class Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->class_member_id, 'url' => ['view', 'id' => $model->class_member_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="class-member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
