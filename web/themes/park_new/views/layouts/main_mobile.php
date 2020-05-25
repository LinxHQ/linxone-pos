<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <link href='<?php echo Yii::$app->request->baseUrl; ?>/css/jquery.mobile-1.4.5.min.css' rel='stylesheet' type='text/css'>
    <link href='<?php echo Yii::$app->request->baseUrl; ?>/css/style_mobile.css' rel='stylesheet' type='text/css'>
    <link href='<?php echo Yii::$app->request->baseUrl; ?>/css/jquery.imageview.css' rel='stylesheet' type='text/css'>
    <script src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.min.js" type="text/javascript" ></script>
<!--    <link href='<?php echo Yii::$app->request->baseUrl; ?>/css/jquery.mobile-1.4.5.css' rel='stylesheet' type='text/css'>-->
    
    <script src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.mobile-1.4.5.js" type="text/javascript" ></script>
    <script src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.imageview.js"></script>

</head>
<html>
    <?= $content ?>
</html>


