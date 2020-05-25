<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\course\models\Classc */

$this->title = $model->event_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classc-view">
    
    <div id="members-index">
        
    </div>
    
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                     <div class="parkclub-header-left">
                         <?php echo Yii::t('app', 'Member'); ?>
                     </div>
                     <div class="parkclub-header-right"></div>
                 </div>
                <div class="parkclub-newm ">
                    <div id="view-member">

                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>


</div>
<script type="text/javascript">

    $( document ).ready(function() {
        $('#view-member').load('<?php echo Yii::$app->urlManager->createUrl('/checkin_entity/default/index') ?>',{entity_id:'<?php echo $model->event_id; ?>',entity_type:'event'});
        $('#members-index').load('<?php echo Yii::$app->urlManager->createUrl('/event/default/view-event?id=') ?>'+<?php echo $model->event_id; ?>);
    })
    
</script>