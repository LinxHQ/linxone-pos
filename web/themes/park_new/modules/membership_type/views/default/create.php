<?php

use yii\helpers\Html;

$m = 'membership_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$canList = $BasicPermission->checkModules($m, 'list');
$canEdit = $BasicPermission->checkModules($m, 'edit');
$canView = $BasicPermission->checkModules($m, 'view');
$canDelete = $BasicPermission->checkModules($m, 'delete');

if(!$canAdd){
    echo Yii::t('app','You do not have permission with this action.');
    return ;
}

/* @var $this yii\web\View */
/* @var $model app\modules\membership_type\models\MembershipType */

$this->title = Yii::t('app', 'Create Membership Type');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Membership Types'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
   
    <?= $this->render('_form', [
        'model' => $model,
        'label'=>Yii::t('app', 'Add MemberShip Type')
    ]) ?>

