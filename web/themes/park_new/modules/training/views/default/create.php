<?php

use yii\helpers\Html;


//Check permission 
$m = 'training';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canAdd){
    echo Yii::t('app','You do not have permission with this action.');
    return false;
}
//End check permission

/* @var $this yii\web\View */
/* @var $model app\modules\training\models\MemberTrainings */

$this->title = Yii::t('app', 'New Member Training');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member Trainings'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

    <?= $this->render('_form', [
        'model' => $model,
         'modelTrainer'=>$modelTrainer,
        'ModelMemberTrainer'=>$ModelMemberTrainer,
        'label'=>$this->title
    ]) ?>
