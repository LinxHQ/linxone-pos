

<?php

use yii\helpers\Html;

//Check permission 
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$m = 'sale';
$canAdd = $BasicPermission->checkModules($m, 'add');
if(!$canAdd){
    echo Yii::t('app',"You don't have permission with this action.");
    exit();
}

//END PERMISSION

/* @var $this yii\web\View */
/* @var $model app\modules\members\models\Members */

//$this->title = Yii::t('app', 'Create Members');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Members'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
