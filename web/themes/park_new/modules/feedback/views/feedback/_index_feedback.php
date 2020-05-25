<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\members\models\Members;
use app\modules\comment\models\Comment;
use kartik\rating\StarRating;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\comment\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Feedbacks');
//$this->params['breadcrumbs'][] = $this->title;
//Check permission
$m = 'feedback';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canUpdate = $BasicPermission->checkModules($m, 'update');
//End check permission

?>
<div class="parkclub-rectangle-content">
<?php Pjax::begin(); ?> 
<?= GridView::widget([
                'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
                //'summary'=>true,
                'tableOptions' => ['class' => 'parkclub-check-table'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'feedback_id',
			'header' => Yii::t('app','Content'),
                        'format'=>'raw',
                        'value'=>function($model){
                            $comment = new Comment();
                            $models = $comment->find();
                            $models->andFilterWhere(['=', 'comment_entity_id', $model->feedback_id]);
                            $models->andFilterWhere(['=', 'comment_parent', 0]);
                            $models=$models->one();
                            if($models){
								//Check permission
								$m = 'feedback';
								$BasicPermission = new \app\modules\permission\models\BasicPermission();
								$canView = $BasicPermission->checkModules($m, 'view');
								//End check permission
								if($canView)
									return '<a href ="'.YII::$app->urlManager->createUrl("comment/comment/comment-detail?comment_id=".$models->comment_id).'">'.$models->comment_content.'</a>';
								else 
									return $models->comment_content;
							} else {
                                return "";
                            }
                        }
                    ],
                    
                    [
                        'attribute' => 'rating',
			'header' => Yii::t('app','Rate'),
                        'format'=>'raw',
                        'value'=>function($model){
                            $comment = new Comment();
                            $models = $comment->find();
                            $models->andFilterWhere(['=', 'comment_entity_id', $model->feedback_id]);
                            $models->andFilterWhere(['=', 'comment_parent', 0]);
                            $models=$models->one();
                            if($models){
                                return '<input id="input-4" name="input-4" class="rating rating-loading" data-show-clear="false" data-show-caption="false" value="'.$models->rating.'" data-disabled="true">';
                            }
                            
                        }
                    ],

                    [
                        'attribute' => 'guest_name',
			'header' => Yii::t('app','Customer Name'),
                        'format'=>'raw',
                        'value'=>function($model){
                            if($model->member_id != 0){
                                $member = new Members();
                  
                                return $member->getMemberFullName($model->member_id);
                            }else{
                                return $model->guest_name;
                            }
                            
                        }
                    ],
                    [
                        'attribute' => 'guest_phone',
			'header' => Yii::t('app','Mobile'),
                        'format'=>'raw',
                        'value'=>function($model){
                            if($model->member_id != 0){
                                $member = new Members();
                                
                                $models = $member->findOne($model->member_id);
                  
                                return $models->member_mobile;
                            }else{
                                return $model->guest_phone;
                            }
                        }
                    ],
                    [
                        'attribute' => 'service_type',
			'header' => Yii::t('app','Service type'),
                        'format'=>'raw',
                        'value'=>function($model){
                            return $model->service_type;
                        }
                    ],
                    [
                        'attribute' => 'feedback_id',
			'header' => Yii::t('app','Sent date'),
                        'format'=>'raw',
                        'value'=>function($model){
                            $comment = new Comment();
                            $models = $comment->find();
                            $models->andFilterWhere(['=', 'comment_entity_id', $model->feedback_id]);
                            $models->andFilterWhere(['=', 'comment_parent', 0]);
                            $models=$models->one();
                            if($models){
                                return date("d/m/Y", strtotime($models->comment_create_date));
                            } else {
                                return "";
                            }
                        }
                    ],
                    [
                        'attribute'=>'feedback_status',
                        'header' => Yii::t('app','Feedback status'),
                        'format'=>'raw',
                        'value' => function($model) use ($canUpdate) {
							if(!$canUpdate) {
								if($model->feedback_status == 1)
									return '<a href="#" onclick="alert(&quot;You do not have permission with this action.&quot;);"><i class="glyphicon glyphicon-ok"></i></a>';
								else
									return '<a href="#" onclick="alert(&quot;You do not have permission with this action.&quot;);"><i class="glyphicon glyphicon-remove"></i></a>';
							} else {
								if($model->feedback_status == 1)
									return '<a href="#" onclick="updateStatus('.$model->feedback_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
								else
									return '<a href="#" onclick="updateStatus('.$model->feedback_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
							}
						},

                    ],
                    ['class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}'],
                ],
            ]); ?> 
    <?php Pjax::end(); ?>
</div>
<script type="text/javascript">
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('feedback/feedback/update-status'); ?>',
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    location.reload();      
                }
            }
        });
    }
</script>