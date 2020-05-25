<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\training\models\MemberTrainings */

//Check permission 
$m = 'training';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canUpdate){
    echo Yii::t('app','You do not have permission with this action.');
    return false;
}
//End check permission

$this->title = Yii::t('app', 'Update Member Training') ;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member Trainings'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->member_training_id, 'url' => ['view', 'id' => $model->member_training_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="member-trainings-update">
    <?= $this->render('_form', [
        'model' => $model,
        'ModelMemberTrainings'=>$ModelMemberTrainings,
        'ModelMemberTrainer'=>$ModelMemberTrainer,
        'modelTrainer'=>$modelTrainer,
        'label'=>$this->title
    ]) ?>

</div>
