<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ListSetup */
/* @var $form yii\widgets\ActiveForm */

if($model && $model->list_parent)
	$url = YII::$app->urlManager->createUrl(['listsetup/item?parent_id='.$model->list_parent]);
else 
	$url = YII::$app->urlManager->createUrl(['listsetup/index']);

?>

<div class="list-setup-form">

    <?php $form = ActiveForm::begin(); ?>
	<div id="listsetup" class="parkclub-wrapper parkclub-wrapper-search ">
		<div class="parkclub-rectangle parkclub-shadow">
			<div class="parkclub-invoice">
				<div class="parkclub-newm-left-title">
					<div class="parkclub-header-left">
						<a href="<?php echo $url; ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
						<?php echo Yii::t('app', 'List Setup'); ?>
					</div>
				</div>
				<?php if(isset($duplicated) and $duplicated==1) { ?>
				<div class="alert alert-danger fade in alert-dismissable" style="width: 100%;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
					<?php echo Yii::t('app','Duplicated value');?>
				</div>
				<?php } ?>
				<div class="parkclub-newm ">
					<fieldset>
					<?= $form->field($model, 'list_name')->textInput(['maxlength' => true]) ?>

					<?= $form->field($model, 'list_parent')->textInput() ?>

					<?= $form->field($model, 'list_value')->textInput(['maxlength' => true]) ?>

					<?= $form->field($model, 'list_description')->textInput(['maxlength' => true]) ?>
					</fieldset>	
				</div>
				<div class="parkclub-footer" style="text-align: center">
					<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
				</div>
			</div>
		</div>
	</div>
    <?php ActiveForm::end(); ?>

</div>
