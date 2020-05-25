<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

//$this->title = Yii::t('app', 'Update {modelClass}: ', [
//    'modelClass' => 'Booking',
//]) . $model->book_id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bookings'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->book_id, 'url' => ['view', 'id' => $model->book_id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    <?php echo Yii::t('app', 'Update Booking'); ?>
                </div>
            </div>
           <div>
                <?= $this->render('_form', [
                    'model' => $model,
                    'action' =>true
                ]) ?>
           </div>
       </div>
    </div>
</div>
