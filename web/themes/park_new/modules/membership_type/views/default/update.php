<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\membership_type\models\MembershipType */

$m = 'membership_type';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$canList = $BasicPermission->checkModules($m, 'list');
$canEdit = $BasicPermission->checkModules($m, 'edit');
$canView = $BasicPermission->checkModules($m, 'view');
$canDelete = $BasicPermission->checkModules($m, 'delete');

if(!$canEdit){
    echo Yii::t('app','You do not have permission with this action.');
    return ;
}

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Membership Type',
]) . $model->membership_type_id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Membership Types'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->membership_type_id, 'url' => ['view', 'id' => $model->membership_type_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?= $this->render('_form', [
    'model' => $model,
    'label'=>Yii::t('app', 'Update MemberShip Type')
]) ?>


        
