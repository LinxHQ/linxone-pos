<?php
use app\rbac\models\AuthItem;
use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ListSetup;
use app\models\Config;
use yii\widgets\DetailView;
use app\helpers\CssHelper;

$ListSetup = new ListSetup();
/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
$config = new Config();
$config_data = $config->find()->one();
$sub_demo = "";
if($config_data)
    $sub_demo=$config_data->subdomain;
?>
<!--<div class="user-form">-->

    <?php $form = ActiveForm::begin(['id' => 'form-user']); ?>
       <div class="parkclub-newm ">
            <fieldset>
                <?php if($sub_demo!='demo'){ ?>
                    <?= $form->field($user, 'username')->textInput(
                            ['placeholder' => Yii::t('app', 'Enter username'), 'autofocus' => true]) ?>
                    <?= $form->field($user, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter e-mail')]) ?>

                    <?php if ($user->scenario === 'create'): ?>

                        <?= $form->field($user, 'password')->widget(PasswordInput::classname(), 
                            ['options' => ['placeholder' => Yii::t('app', 'Enter password')]]) ?>

                    <?php else: ?>

                        <?= $form->field($user, 'password')->widget(PasswordInput::classname(),
                                 ['options' => ['placeholder' => Yii::t('app', 'Change password ( if you want )')]]) ?> 

                    <?php endif ?>



                    <div class="row">
                    <div>

                        <?= $form->field($user, 'status')->dropDownList($user->statusList) ?>

                        <?php foreach (AuthItem::getRoles() as $item_name): ?>
                            <?php $roles[$item_name->name] = $item_name->name ?>
                        <?php endforeach ?>
                        <?= $form->field($user, 'item_name')->dropDownList($roles) ?>

                    </div>
                    </div>
                <?php } else if($user->id>0) { ?>
                <div class="bs-example bs-example-bg-classes park_new_margin" data-example-id="contextual-backgrounds-helpers">
                    <p class="bg-danger"><?php echo Yii::t('app','Demo system not allowed to change some users of information.');?></p>
                </div>
                        <?= DetailView::widget([
                            'model' => $user,
                            'id'=>'update_view',
                            'attributes' => [
                                'username',
                                'email:email',
                                //'password_hash',
                                [
                                    'attribute'=>'status',
                                    'value' => '<span class="'.CssHelper::userStatusCss($user->status).'">
                                                    '.$user->getStatusName($user->status).'
                                                </span>',
                                    'format' => 'raw'
                                ],
                                [
                                    'attribute'=>'item_name',
                                    'value' => '<span class="'.CssHelper::roleCss($user->getRoleName()).'">
                                                    '.$user->getRoleName().'
                                                </span>',
                                    'format' => 'raw'
                                ],
                            ],
                        ]) ?>   
                <?php } ?>
                <div class="row">
                <div>
                    <?= $form->field($user, 'language_name')->dropDownList(ListSetup::getItemByList('language')) ?>
                </div>
                </div>
            </fieldset>
       </div>
       <div class="parkclub-footer" style="text-align: center">
            <?= Html::submitButton($user->isNewRecord ? Yii::t('app', 'Create') 
                : Yii::t('app', 'Update'), ['class' => $user->isNewRecord 
                ? 'btn btn-success' : 'btn btn-success']) ?>

            <?= Html::a(Yii::t('app', 'Cancel'), ['user/index'], ['class' => 'btn btn-default']) ?>
       </div>
    <?php ActiveForm::end(); ?>
<!-- 
</div>-->
