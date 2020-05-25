<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\members\models\Members;
use app\modules\comment\models\Comment;
use kartik\rating\StarRating;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\comment\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Feedbacks');
//$this->params['breadcrumbs'][] = $this->title;
//Check permission
$m = 'feedback';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canAdd = $BasicPermission->checkModules($m, 'add');
$canView = $BasicPermission->checkModules($m, 'view');
//End check permission
?>
<div class="course-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/feedback.png" width="22" alt=""></div> <h3><?php echo Yii::t('app', 'Feedbacks') ?></h3></div>
        <div class="col-lg-6" style="text-align: right; margin-top: 30px;">
            <?php if($canView) { ?>
			<a href="<?php echo Yii::$app->urlManager->createUrl('/feedback/feedback/average-rate') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'AVERAGE RATE') ?></a>
            <?php }
			if($canAdd) { ?>
			<a href="<?php echo Yii::$app->urlManager->createUrl('/feedback/feedback/create') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'NEW FEEDBACK') ?></a>
			<?php } ?>
		</div>
    </div>
    
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="">
                <?php 
                    $tab=0;
                    $active_feedback=false;
                    $inactive_feedback=false;
                    if(isset($_GET['tab']))
                        $tab = $_GET['tab'];
                    if($tab==1)
                        $inactive_feedback=true;
                    else if($tab==0)
                        $active_feedback=true;

                    $items = [
                    [
                        'label'=>'<i class="glyphicon glyphicon-plus-sign"></i> '.Yii::t('app', 'Open'),
                        'content'=>$this->render('_index_feedback',['dataProvider'=>$dataProvider]),
                        'active'=>$active_feedback,
                        'linkOptions' => array('onclick'=>'active_feedback();')
                    ],
                    [
                        'label'=>'<i class="glyphicon glyphicon-minus-sign"></i> '.Yii::t('app', 'Close'),
                        'content'=>$this->render('_index_feedback',['dataProvider'=>$dataProvider]),
                        'active'=>$inactive_feedback,
                        'linkOptions' => array('onclick'=>'inactive_feedback();')
                    ]
                ];

                echo TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false
                ]);

                ?>
            </div>
            <br>
        </div>
    </div>
    
</div>
<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/bootstrap.js" type="text/javascript" ></script>
<script type="text/javascript">

    
    function active_feedback()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/feedback/feedback/index?tab=0');?>';
        window.location.href=url;
    }
    function inactive_feedback()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/feedback/feedback/index?tab=1');?>';
        window.location.href=url;
    }
    
</script>