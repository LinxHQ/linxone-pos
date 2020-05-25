<?php
use app\helpers\CssHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\permission\models\Roles;

/* @var $this yii\web\View */
/* @var $user app\models\User */
$ruserRoles = new Roles();
$is_admin = $ruserRoles->getIsRoles(Yii::$app->user->id,"admin");
$is_manage = $ruserRoles->getIsRoles(Yii::$app->user->id,"Manager");

$this->title = $model->username;
$ListSetup = new app\models\ListSetup();
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl('user/index'); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $this->title; ?>
                </div>
                <div class="parkclub-header-right">
                    <?php
                    if($is_admin || $is_manage || Yii::$app->user->id == $model->id)
                    {
                      echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ;
                    }
                    ?>
                </div>
            </div>
            <div >
            
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'email:email',
                    //'password_hash',
                    [
                        'attribute'=>'status',
                        'value' => '<span class="'.CssHelper::userStatusCss($model->status).'">
                                        '.Yii::t('app',$model->getStatusName($model->status)).'
                                    </span>',
                        'format' => 'raw'
                    ],
                    [
                        'attribute'=>'item_name',
                        'value' => '<span class="'.CssHelper::roleCss($model->getRoleName()).'">
                                        '.$model->getRoleName().'
                                    </span>',
                        'format' => 'raw'
                    ],
                    //'auth_key',
                    //'password_reset_token',
                    //'account_activation_token',
                    'created_at:date',
                    'updated_at:date',
                    [
                        'attribute'=>'language_name',
                        'value'=> (array_key_exists($model->language_name,app\models\ListSetup::getItemByList('language'))) ? app\models\ListSetup::getItemByList('language')[$model->language_name] : '',
                        'format' => 'raw'
                    ]
                ],
            ]) ?>   
    <div id="view-account-permission">
    </div>
            </div>
       </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#view-account-permission').load('<?php echo Yii::$app->urlManager->createUrl("permission/default/view_permission?account_id=".$model->id); ?>');

    });
</script>