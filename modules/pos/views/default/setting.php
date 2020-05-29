<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//Check permission 
$m = 'pos';
//$DefinePermission = new \app\modules\permission\models\DefinePermission();
//$canManagerTable = $DefinePermission->checkFunction($m, 'Manager table');
//$canManagerMenu = $DefinePermission->checkFunction($m, 'Manager menu');
//$canManagerDeposit = $DefinePermission->checkFunction($m, 'Manager deposit');
//$canManageSessions = $DefinePermission->checkFunction($m, 'Manage session');
$canManagerTable=true;
$canManagerDeposit=true; 
$canManagerMenu=true;
$canManageSessions=true;
$this->title = Yii::t('app', 'Setting');
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('/pos'); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $this->title; ?>
                </div>
            </div>
           <div class="parkclub-newm " style="padding: 40px;">
                <?php if($canManagerMenu){ ?>
                <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/product/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'MENU') ?></a>
                <?php } ?>
                <?php if($canManagerTable){ ?>
                <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/tables/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'TABLE') ?></a>
                <?php } ?>
                <?php if($canManagerDeposit){ ?>
                <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/deposit/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'DEPOSIT') ?></a>
                <?php } ?>
				<?php if($canManageSessions){ ?>
                <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/session/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'SESSIONS') ?></a>
                <?php } ?>
           </div>
           <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','name'=>'w0[]']]);  ?>
            <div class="parkclub-newm " style="padding: 20px;">
                <label for=""><i class="glyphicon glyphicon-play-circle" style="font-size: 18px;"></i> <?php echo Yii::t('app', 'Show image product'); ?></label>
                    <?php echo Html::checkbox('show_img_product',$model->show_img_product,['style'=>'width:10%;height:20px;top: 2px;position: relative;']); ?>
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' =>'btn btn-success','name'=>'submit']) ?>
           </div>
           <?php ActiveForm::end(); ?>
       </div>
    </div>
</div>
