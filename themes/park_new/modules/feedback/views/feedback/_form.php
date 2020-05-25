<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\members\models\Members;
use app\modules\comment\models\Comment;
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


        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
					<input type="hidden" id="feedback_id" name="feedback_id" value="<?php echo $model->feedback_id;?>"/>
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/feedback/feedback/index']); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app', 'Feedback'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm ">
                       <fieldset>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo Yii::t('app', 'Member'); ?></label>
                            <select id="member_id" class="form-control">
                                <option value=""><?php echo Yii::t('app','Select member') ?></option>
                                  <?php foreach ($dropdow_member as $key=>$item) {?>
                                <option value = <?php echo $key; ?> <?php if($model->member_id == $key) echo 'selected' ?>><?php echo $item ?></option>
                                  <?php } ?>
                            </select>
							<div hidden="" class="error-form" style="color:#a94442" id="error_member_id"><?php echo Yii::t('app','Member can not be blank'); ?></div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo Yii::t('app', 'Service'); ?></label>
                            <select id="service_type" class="form-control">
                                  <?php foreach ($dropdow_service as $item) {?>
                                <option value = "<?php echo $item; ?>" <?php if($model->service_type == $item) echo 'selected' ?>><?php echo $item ?></option>
                                  <?php } ?>
                            </select>
                        </div>
                           
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo Yii::t('app', 'Feedback'); ?></label>
                            <textarea class="form-control" id="content_feedback" rows="3"><?php 
								if($model->feedback_id) {
									$comment = new Comment();
									$comment = $comment::find()->where(['comment_entity_id'=>$model->feedback_id,'comment_parent'=>0])->one();
									echo $comment->comment_content;
								}
							?></textarea>
                        </div>
                       </fieldset>
                   </div>
                    
                   <div class="parkclub-footer" style="text-align: center">
				    <?php 
						if(!$model->feedback_id) { $btn = 'Create';} else {$btn = 'Update';}
				    ?>
                       <button type="button" class="btn btn-primary" onclick="saveFeedback();return false;"><?php echo Yii::t('app',$btn); ?></button>
                   </div>
               </div>
            </div>
        </div>

</div>

<script type="text/javascript">

function saveFeedback(){
    var member_id = $('#member_id').val();
    var service_type = $('#service_type').val();
    var content = $('#content_feedback').val();
	var feedback_id = $('#feedback_id').val();
	if(member_id == '')
		$('#error_member_id').show();
	else {
		$('#error_member_id').hide();
		$.ajax({
			type:'POST',
			url:<?php if(!$model->feedback_id) {echo "'create'";} else {echo "'store'";}?>,
			data:{member_id:member_id,service_type:service_type,content:content,feedback_id},
			success:function(data){
			}
		});
	}	
}
</script>