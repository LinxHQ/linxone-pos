<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Classc */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Classc',
]) . $model->class_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classcs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->class_id, 'url' => ['view', 'id' => $model->class_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$label_note= '<div class="alert alert-info" role="alert" style="margin: 0px 5% 20px 5%;">'.Yii::t("app","Nếu có thay đổi về số buổi học, ngày học, lịch học hệ thống sẽ hiển thị thông báo xác nhận cập nhật lịch học.").'</div>';
?>
<div class="classc-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelTrainer'=>$modelTrainer,
        'label_note'=>$label_note
    ]) ?>

</div>
