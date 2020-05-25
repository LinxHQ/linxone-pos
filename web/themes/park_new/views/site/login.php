<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$user = new app\models\User();
if(isset(YII::$app->user->id)){
    Yii::$app->language = $user->findOne(YII::$app->user->id)->language_name;
}
$config = new app\models\Config();
$config_data = $config->find()->one();
$sub_demo = "";
if($config_data)
    $sub_demo=$config_data->subdomain;
?>
    <div class="parkclub-login-bg">
        <h1><?php echo Yii::t('app', Yii::$app->params['sogan_login']);?></h1>
		<div class="alert alert-danger fade in alert-dismissable" id="error_message" style="width: 98%;display:none">
			<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
			<?php echo Yii::t('app','Your browser has disabled cookies. Make sure cookies are enabled and try again.') ?>
		</div>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'parkclub-login-form'
             ]
                ]); ?>
       
            <input class="parkclub-login-user" name="LoginForm[username]" type="text" placeholder="<?php echo Yii::t('app', 'Username');?>">
            <input class="parkclub-login-password" type="password" name="LoginForm[password]" placeholder="<?php echo Yii::t('app', 'Password');?>">
            <?= $form->errorSummary($model,['value'=>Yii::t('app','Incorrect username or password.'),'header'=>false]); ?>
            <input class="parkclub-login-submit" type="submit" value="<?php echo Yii::t('app','Login');?>" name="login-button">
            <?php if($sub_demo!='demo'){?>
                <a href="<?php echo Yii::$app->urlManager->createUrl('/site/request-password-reset') ?>"><?php echo Yii::t('app', 'Forgot password? <b>Reset</b>');?></a>
            <?php }else { ?>
                <a href="#">Demo account: <b>admin / admin123</b></a>
            <?php } ?>
        <?php ActiveForm::end(); ?>
    </div>
<script type="text/javascript">
$( document ).ready(function() {
	var cookie_enabled = are_cookies_enabled();
	if(!cookie_enabled)
		document.getElementById("error_message").style.display = "block";
		
});
function are_cookies_enabled()
{
	var cookieEnabled = (navigator.cookieEnabled) ? true : false;

	if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
	{ 
		document.cookie="testcookie";
		cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
	}
	return (cookieEnabled);
}
</script>