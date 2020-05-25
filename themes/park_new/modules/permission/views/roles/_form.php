<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\permission\models\Roles */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
        <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
               <div class="parkclub-invoice">
                    <div class="parkclub-newm-left-title">
                        <div class="parkclub-header-left">
                            <i class="glyphicon glyphicon-circle-arrow-left"></i>
                            <?php echo Yii::t('app', 'Create Role');?>
                        </div>
                    </div>
                    <div class="parkclub-newm ">
                        <fieldset>
                            <?= $form->field($model, 'role_name')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'role_description')->textInput(['maxlength' => true]) ?>
                        </fieldset>
                   </div>
                   <br>
                   <div class="parkclub-footer" style="text-align: center">
                       <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
                   </div>
               </div>
            </div>
        </div>
<?php ActiveForm::end(); ?>
