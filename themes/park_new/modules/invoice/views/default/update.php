<?php

use yii\helpers\Html;

//Check permission 
$m = 'invoice';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canEdit){
    echo "You don't have permission with this action.";
    return false;
}
//End check permission

/* @var $this yii\web\View */
/* @var $model app\modules\invoice\models\invoice */

//$this->title = Yii::t('app', 'Update {modelClass}: ', [
//    'modelClass' => 'Invoice',
//]) . $model->invoice_id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->invoice_id, 'url' => ['view', 'id' => $model->invoice_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="invoice-update">

<!--    <h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->renderAjax('_form', [
            'model' => $model,
            'booking' => $booking,
            'invoiceItem'=>$invoiceItem,
            'booking'=>$booking,
            'invoicePayment'=>$invoicePayment
    ]) ?>

</div>
