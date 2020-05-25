<?php
use yii\helpers\Html;
use app\modules\permission\models\Roles;

/* @var $this yii\web\View */
/* @var $user app\models\User */
$ruserRoles = new Roles();
$is_admin = $ruserRoles->getIsRoles(Yii::$app->user->id,"admin");
$is_manage = $ruserRoles->getIsRoles(Yii::$app->user->id,"Manager");

$this->title = Yii::t('app', 'Update User') . ': ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('user/index') ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'Update User'); ?>
                </div>
            </div>
                <?= $this->render('_form', ['user' => $user]) ?>
        </div>
    </div>
</div>
