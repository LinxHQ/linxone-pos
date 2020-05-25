<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\members\models\Members;
use app\models\ListSetup;

/* @var $this yii\web\View */
/* @var $model app\modules\feedback\models\Feedback */
/* @var $form yii\widgets\ActiveForm */
?>
<?php 
    $member = new Members();
    $dropdow_member = $member->getDataDropdown();
    $listsetup = new ListSetup();
    $dropdow_service = $listsetup->getItemByList('service');
?>
<div class="feedback-form">
    <div class="ui-select">
        <div id="select-native-1-button" class="ui-btn ui-icon-carat-d ui-btn-icon-right ui-corner-all">
            <span><?php echo Yii::t('app', 'Select service'); ?></span>
            <select id="service_type" class="form-control">
                  <?php foreach ($dropdow_service as $item) {?>
                <option value = "<?php echo $item; ?>"><?php echo $item ?></option>
                  <?php } ?>
            </select>
        </div>
    </div>
    
    <textarea class="ui-input-text ui-body-inherit ui-corner-all ui-textinput-autogrow" placeholder="<?php echo Yii::t('app', 'Feedback'); ?>" id="content_feedback" rows="5"></textarea>
    <div class="wap">
         <div class="stars" id = "star-checked">
            <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
            <label class="star star-5" for="star-5"></label>
            <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
            <label class="star star-4" for="star-4"></label>
            <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
            <label class="star star-3" for="star-3"></label>
            <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
            <label class="star star-2" for="star-2"></label>
            <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
            <label class="star star-1" for="star-1"></label>
        </div>  
    </div>

    
    <button type="button" class="ui-btn ui-corner-all" onclick="saveFeedback();return false;"><?php echo Yii::t('app', 'Create'); ?></button>

</div>

<script type="text/javascript">

function saveFeedback(){
    var member_id = $('#member_id').val();
    var service_type = $('#service_type').val();
    var content = $('#content_feedback').val();
    $.ajax({
        type:'POST',
        url:'create',
        data:{member_id:member_id,service_type:service_type,content:content},
        success:function(data){
        }
    });
}
</script>