<?php
use app\helpers\CssHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\permission\models\Roles;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
//$this->params['breadcrumbs'][] = $this->title;
$add_user = "<div class='parkclub-rectangle-header-right'><button onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/user/create')."\"'>".Yii::t('app','ADD USER')."</button></div>";
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3><?php echo Yii::t('app','USER');?></h3></div>
    <div class="parkclub-search">
        
    </div>
</div>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <div class="user-index">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "<div class='parkclub-rectangle-header'>"
                                    . "<div class='parkclub-rectangle-header-left'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items</div>"
                                    . $add_user
                                . "</div>",
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'username',
                        'email:email',
                        // status
                        [
                            'attribute'=>'status',
                            'filter' => $searchModel->statusList,
                            'value' => function ($data) {
                                return $data->getStatusName($data->status);
                            },
                            'contentOptions'=>function($model, $key, $index, $column) {
                                return ['class'=>CssHelper::userStatusCss($model->status)];
                            }
                        ],
                        // role
                        [
                            'attribute'=>'item_name',
                            'filter' => $searchModel->rolesList,
                            'value' => function ($data) {
                                return $data->roleName;
                            },
                            'contentOptions'=>function($model, $key, $index, $column) {
                                return ['class'=>CssHelper::roleCss($model->roleName)];
                            }
                        ],
                        // buttons
                        ['class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app',"Action"),
                        'template' => '{view} {update} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('', $url, ['title'=>'View user', 'class'=>'glyphicon glyphicon-eye-open']);
                                },
                                'update' => function ($url, $model, $key) {
                                    $ruserRoles = new Roles();
                                    $is_admin = $ruserRoles->getIsRoles(Yii::$app->user->id,"admin");
                                    $is_manage = $ruserRoles->getIsRoles(Yii::$app->user->id,"Manager");
                                    if($model->id == Yii::$app->user->id || $is_admin || $is_manage)
                                        return Html::a('', $url, ['title'=>'Manage user', 'class'=>'glyphicon glyphicon-user']);
                                },
                                'delete' => function ($url, $model, $key) {
                                    $ruserRoles = new Roles();
                                    $is_admin = $ruserRoles->getIsRoles(Yii::$app->user->id,"admin");
                                    $is_manage = $ruserRoles->getIsRoles(Yii::$app->user->id,"Manager");
                                    if(($is_admin || $is_manage) && $model->id!=1)
                                    {
                                        return Html::a('', $url, 
                                        ['title'=>'Delete user', 
                                            'class'=>'glyphicon glyphicon-trash',
                                            'data' => [
                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this user?'),
                                                'method' => 'post']
                                        ]);
                                    }
                                }
                            ]

                        ], // ActionColumn

                    ], // columns

                ]); ?>

            </div>
        </div>
    </div>
</div>
