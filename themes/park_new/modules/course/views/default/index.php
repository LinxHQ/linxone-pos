<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Courses');

$keyserch = "";
$add = "<div class='parkclub-rectangle-header-right' ><button id='btn-add-facility' onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/course/default/create')."\"'>".Yii::t('app', 'NEW COURSE')."</button></div>";
?>
<div class="course-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/education.png" width="24" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
        <div class="parkclub-search">
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="">
                <?php
                    $tab=0;
                    $active_calendar=false;
                    $active_list_courser=false;
                    if(isset($_GET['tab']))
                        $tab = $_GET['tab'];
                    if($tab==1)
                        $active_list_courser=true;
                    else
                        $active_calendar=true;
                  $items = [
                        [
                            'label'=>'<i class="glyphicon glyphicon-home"></i> '.Yii::t('app', 'Session'),
                            'content'=>$this->render('_index_calendar'),
                            'active'=>$active_calendar,
                            'linkOptions' => array('onclick'=>'active_session();')
                //            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/booking/default/bookingcalendar?test=1'])]
                        ],
                        [
                            'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'List Course'),
                            'content'=>$this->render('_index_list',['dataProvider'=>$dataProvider]),
                            'active'=>$active_list_courser,
//                            'linkOptions' => array('onclick'=>'active_list();')
                //            'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/booking/default/bookingcalendar?facility_id=0&date=2016-12-08'])]
                        ],
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
    function active_session()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/course/default/index?tab=0');?>';
        window.location.href=url;
    }
    function active_list()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/course/default/index?tab=1');?>';
        window.location.href=url;
    }
</script>