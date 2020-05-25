<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

//$this->title = 'Install';
//$this->params['breadcrumbs'][] = $this->title;
if(isset($_GET['lang']))
    Yii::$app->language = $_GET['lang'];
?>

<h2 class="title"><?php Yii::t('app', 'Create your Parklife account');?></h2>
<div class="site-install">
        <div class="row">
            <div class="parkclub-newm-right" style="width: 100%; min-height:100px; margin-top: 0; text-align: center">
                <fieldset style="margin-top: 0;">
                <div><?php echo YII::t('app', 'Your registration was successful, would you like to run the trial data?');?></div>
                <br>
            <div class="form-group">
                <?= Html::button(Yii::t('app', 'Install'), ['class' => 'btn btn-primary', 'name' => 'install-button',
                    'onclick'=>'intallDataDemo();']) ?>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-default" onclick="skipDataDemo()" type="button" data-dismiss="modal" aria-hidden="true"><?php echo Yii::t('app','Skip'); ?></button>
            </div>
            </fieldset>
            </div>
        </div>

</div>

<script type="text/javascript">
    function intallDataDemo(){
        $.ajax({
            'type': 'POST',
            'url': '<?php echo Yii::$app->urlManager->createUrl("/installdata");?>',
            'beforeSend': function () {
                $('#bs-model-checkin-installdata').modal('hide');
                $.blockUI();
            },
            'data': {install:'data_demo'},
            'success': function (data) {
                if(data != 'success') {
                    alert("Error intall data demo");
                }
                else{
                    $('#btn-letstart').attr('href','<?php echo Yii::$app->urlManager->createUrl('/checkin/default/index') ?>');
                    popWelcomeTour();
                }
                $.unblockUI();
            }
        })
    }
    
    function skipDataDemo(){
        $.ajax({
            'type': 'POST',
            'url': '<?php echo Yii::$app->urlManager->createUrl("/skipdata");?>',
            'data': {install:'data_demo'},
            'success': function (data) {
                $('#btn-letstart').attr('href','<?php echo Yii::$app->urlManager->createUrl('/overview') ?>');
                popWelcomeTour();
            }
        })
    }
    
    function popWelcomeTour(){
        $('#bs-model-checkin-welcometour').modal('show'); 
    }  
</script>
