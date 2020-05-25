<?php

use yii\helpers\Html;

?>

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['/booking/default/index?tab=2']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app','Update Booking');?>
                </div>
            </div>
           <div style="width: 90%">
                <?= $this->render('_form', [
                    'model' => $model,
                    'action' =>true
                ]) ?>
           </div>
       </div>
    </div>
</div>