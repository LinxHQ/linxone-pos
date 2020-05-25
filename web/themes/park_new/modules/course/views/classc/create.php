<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Classc */

$this->title = Yii::t('app', 'Create Classc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classcs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$label_note= '<div class="alert alert-info" role="alert" style="margin: 0px 5% 20px 5%;">'.Yii::t("app","Nếu chọn ngày bắt đầu học hệ thống sẽ tự động lên lịch học cho lớp dựa theo lịch học và ngày học.").'</div>';
?>
<div class="classc-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelTrainer'=>$modelTrainer,
        'label_note'=>$label_note
    ]) ?>

</div>
