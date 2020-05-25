<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('user/index') ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'Create User'); ?>
                </div>
            </div>
                <?= $this->render('_form', ['user' => $user]) ?>
        </div>
    </div>
</div>


