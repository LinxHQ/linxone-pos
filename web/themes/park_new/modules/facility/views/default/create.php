<?php

use yii\helpers\Html;

//Check permission 
$m = 'facility';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canAdd){
    echo "You don't have permission with this action.";
    return false;
}
//End check permission

/* @var $this yii\web\View */
/* @var $model app\models\Facility */

$this->title = Yii::t('app', 'New Facilities');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Facilities'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="facility-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
