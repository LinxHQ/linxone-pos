<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'POS report');

$keyserch = "";

?>
<div class="course-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos_report.png" width="24" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
        <div class="parkclub-search">
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="">
                <?php
                    $tab=0;
                    $active_invoice_report = false;
                    $active_catrgory_report = false;
                    if(isset($_GET['tab']))
                        $tab = $_GET['tab'];
                    if($tab==1)
                        $active_invoice_report = true;
                    else
                        $active_catrgory_report = true;
                  $items = [
                        [
                            'label'=>'<i class="glyphicon glyphicon-home"></i> '.Yii::t('app', 'POS report category'),
                            'content'=>$this->render('pos_category_report',['category'=>$category,'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date,'start_date'=>$start_date,'end_date'=>$end_date]),
                            'active'=>$active_catrgory_report,
                            'linkOptions' => array('onclick'=>'active_session();')
                        ],
                        [
                            'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'POS report item'),
                            'content'=>$this->render('pos_report',['dataProvider'=>$dataProvider, 'view_start_date'=>$view_start_date,'view_end_date'=>$view_end_date,'status'=>$status,'start_date'=>$start_date,'end_date'=>$end_date]),
                            'active'=>$active_invoice_report,
                            'linkOptions' => array('onclick'=>'active_report_invoice();')
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
        var url='<?php echo Yii::$app->urlManager->createUrl('/report/default/pos_report?tab=0');?>';
        window.location.href=url;
    }
    
    function active_report_invoice(){
        var url='<?php echo Yii::$app->urlManager->createUrl('/report/default/pos_report?tab=1');?>';
        window.location.href=url;
    }

</script>