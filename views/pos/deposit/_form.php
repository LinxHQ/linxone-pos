<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$next_id = new app\models\NextIds();
$member = new app\modules\members\models\Members();

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Deposit */
/* @var $form yii\widgets\ActiveForm */
$member_arr = Array(""=>Yii::t('app',"--Select Member--")) + $member->getDataDropdown(false,false,false,true);
?>

<div class="deposit-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>"multipart/form-data"]]); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/deposit/index') ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                            <?php echo Yii::t('app', 'Deposit'); ?>
                        </div>
                    </div>
                   <div class="parkclub-newm">
                       <?php if(!isset($model->deposit_id)){ ?>
                        <div style="padding:20px 40px 10px 40px; border-bottom: 1px solid #ddd;">
                            <div class="alert alert-info" role="alert"><?php echo Yii::t('app', 'Nếu đã là hội viên trong hệ thống thì tạo deposit cho hội viên này. Nếu chưa thì tạo Deposit mới cho khách') ?></div>
                            <input type="radio" name="type-payment" id="type-payment" value="0" onclick="depositMember(1);" checked=""/>
                                <?php echo Yii::t('app','Member');?> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="type-payment" id="type-payment" value="1" onclick="depositMember(0);"/>
                                <?php echo Yii::t('app','Others');?>
                        </div>
                       <?php } ?>
                        <fieldset>
                            <label for="" class="left_input"><?php echo Yii::t('app', 'Deposit no'); ?>:</label>
                             <b><?= ($model) ? $model->deposit_no : $next_id->getDisplayDepositCode(); ?></b><br><br>
                            <?php if(isset($model->deposit_id) && $model->deposit_id > 0){ ?>
                                <label for="" class="left_input"><?php echo Yii::t('app', 'Amount'); ?>:</label>
                                 <b><?= app\models\ListSetup::getDisplayPrice($model->deposit_amount); ?></b><br><br>
                                <label for="" class="left_input"><?php echo Yii::t('app', 'Balance'); ?>:</label>
                                <b><?= app\models\ListSetup::getDisplayPrice($model->deposit_balance);?></b><br><br>
                            <?php } ?>

                             <div id="deposit-member" <?php if(isset($model->deposit_id) && ($model->member_id <= 0 || $model->member_id == NULL)) echo  'style="display: none"'; ?> >
                                 <label for=""><?php echo Yii::t('app', 'Name'); ?></label>
                                <div style="width: 90%; margin-left: 5%;">
                                <?php
                                    echo $form->field($model, 'member_id')->widget(Select2::classname(), [
                                        'data' => $member_arr,
                                        'options' => ['placeholder' => Yii::t('app','Select member'),'onchange'=>'loadMember(this.value)'],
                                        'pluginOptions' => [
                                            'allowClear' => true,

                                        ],
                                    ])->label(false);
                                ?>
                                </div>
                                <div id="deposit-member-detail" style="width: 90%; margin-left: 5%;">
                                    
                                </div>
                             </div>
                             <div id="deposit-no-member" <?php if((isset($model->deposit_id) && $model->member_id > 0) || (!isset($model->deposit_id))) echo 'style="display: none"'; ?>>
                                <label for="" class="required"><?php echo Yii::t('app', 'Name'); ?></label>
                                <?= $form->field($model, 'deposit_name')->textInput(['maxlength' => true])->label(false); ?>
                                
                                <label for=""><?php echo Yii::t('app', 'Picture'); ?></label>
                                <?php echo yii\bootstrap\Html::fileInput('deposit_images'); ?>

                                <label for=""><?php echo Yii::t('app', 'Phone'); ?></label>
                                <?= $form->field($model, 'deposit_phone')->textInput(['maxlength' => true])->label(false); ?>

                                <label for=""><?php echo Yii::t('app', 'Email'); ?></label>
                                <?= $form->field($model, 'deposit_email')->textInput(['maxlength' => true])->label(false); ?>

                                <label for=""><?php echo Yii::t('app', 'Address'); ?></label>
                                <?= $form->field($model, 'deposit_address')->textarea(['rows' => 4])->label(false); ?>

                             </div>
                            <?php if(!isset($model->deposit_id)){ ?>
                                <label for="" class="required"><?php echo Yii::t('app', 'Amount'); ?></label>
                                    <?= $form->field($model, 'deposit_amount')->textInput(['maxlength' => true])->label(false); ?>		
                            <?php }?>
                            <?php if(isset($model->deposit_id) && $model->deposit_id > 0){ ?>
                                <label for=""><?php echo Yii::t('app', 'Recharge'); ?></label>
                                <?= yii\bootstrap\Html::input('text', 'deposit_recharge', '0') ?>
                            <?php } ?>
                            <label for=""><?php echo Yii::t('app', 'Note'); ?></label>
                                <?= $form->field($model, 'deposit_note')->textarea(['rows' => 4])->label(false); ?>

                        </fieldset>
                   </div>
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','id'=>'submit-facility']) ?>
                   </div>
               </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    var is_update = '<?php echo $model->deposit_id; ?>';
    $('#deposit-deposit_name').val("name");
    function depositMember(type){
        if(type==0){
            $('#deposit-no-member').show();
            $('#deposit-member').hide();
            $('#deposit-deposit_name').val("");
        }else{
            $('#deposit-no-member').hide();
            $('#deposit-member').show();
            $('#deposit-deposit_name').val("name");
        };
        if(is_update==""){
            $('#deposit-deposit_name').val("");
            $('#deposit-deposit_phone').val("");
            $('#deposit-deposit_email').val("");
            $('#deposit-deposit_address').val("");
            $('#deposit-member_id').val("").trigger("change");
            $('#deposit-member-detail').html("");
        }
    }
    
    function loadMember(member_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('pos/deposit/load-member'); ?>',
            'data':{member_id:member_id},
            success:function(data){
                $('#deposit-member-detail').html(data);
            }
        })
    }
</script>