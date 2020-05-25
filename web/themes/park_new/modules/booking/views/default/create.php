<?php

use yii\helpers\Html;
use app\models\NextIds;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = Yii::t('app', 'Create Booking');

?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    <?php echo Yii::t('app', 'New Booking');?>
                </div>
            </div>
           <div class="parkclub-newm ">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
           </div>
       </div>
    </div>
</div>
