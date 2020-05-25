<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\membership_type\models\MembershipPrice */

//$this->title = Yii::t('app', 'Update {modelClass}: ', [
//    'modelClass' => 'Membership Price',
//]) . $model->membership_price_id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Membership Prices'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->membership_price_id, 'url' => ['view', 'id' => $model->membership_price_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
    <?= $this->render('_form_price', [
        'model' => $model,
        'modelMembership'=>$modelMembership,
        'label'=>Yii::t('app','Edit memberships')
    ]) ?>

