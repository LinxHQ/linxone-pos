<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\members\models\Members;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersCheckinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$member = new Members();
$dropdow_member = $member->getDataDropdown();
$member_selected = isset($_GET['member'])?$_GET['member']:0;
$end_date_default = date('d/m/Y');
$year_now = date('Y');
$month_now = date('m');
$day_now = date('d');

$dateint = mktime(0, 0, 0, $month_now,$day_now-1, $year_now);
$start_date_default = date('d/m/Y', $dateint); // 02/12/2016

$start_date = (isset($_GET['start_date']) && $_GET['start_date'] !="")?$_GET['start_date']:$start_date_default;
$end_date = (isset($_GET['end_date']) && $_GET['end_date'] !="")?$_GET['end_date']:$end_date_default;

?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'PT Checkin/out Report');?>
                        </div>
                    </div>

    <?php 
    
    echo '<table>';
    echo '<tr><td>';
    echo '<label class="control-label">'.Yii::t('app', 'Members').': </label></td><td>';
    
    echo Select2::widget([
        'name' => 'id',
        'data' => $dropdow_member,
        'value' => $member_selected,
        'options' => [
            'placeholder' => Yii::t('app', 'Select trainers ...'),
            'width'=>'400px',
            'id'=>'member'
//            'multiple' => true
        ],
    ]);
            
    echo '</td>';
    echo '<td style="padding:20px; >';
    echo '<label class="control-label">'.Yii::t('app', 'From').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'start_date',
        'value' => $start_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    echo '<td style="padding:20px;">';
    echo '<label class="control-label">'.Yii::t('app', 'To').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'end_date',
        'value' => $end_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    echo '<td>';
    echo '<button style="margin-left:4px; background-color: rgb(50, 205, 139);" onclick="search_checkin_report();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button></td>';
    echo '</tr>';
    echo '</table>';
    
    
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{items}",
        'showFooter'=>TRUE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app', 'No.')],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => 'Name',
                'value' => function($model) {
                    $member = \app\modules\members\models\Members::findOne($model->member_id);
                  
                    return $member-> getMemberFullName();
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app', 'PT Barcode'),
                'value' => function($model) {
                    $member = \app\modules\members\models\Members::findOne($model->member_id);
                    return $member['trainer_code'];
                }
            ],
            
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app', 'Checkin time'),
                'value' => function($model) {
                    $checkin_time = ($model->checkin_time != "0000-00-00 00:00:00")?date('d/m/Y h:i:s',  strtotime($model->checkin_time)):"";
                    return $checkin_time;
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app', 'Checkout time'),
                'value' => function($model) {
                    $checkcout_time = ($model->checkcout_time != "0000-00-00 00:00:00")?date('d/m/Y h:i:s',  strtotime($model->checkcout_time)):"";
                    return $checkcout_time;
                }
            ],

                    ],
    ]); ?>
<?php 
    echo '<div class="parkclub-footer" style="text-align: center">';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_checkin_pdf?start_date='.$start_date.'&end_date='.$end_date.'&member='.$member_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print Pdf').'</button> </a>';
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pt_checkin_excel?start_date='.$start_date.'&end_date='.$end_date.'&member='.$member_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export Excel').'</button> </a>';
    echo '</div>';
    ?>
                </div>
            </div>
</div>
<script>
    function search_checkin_report()
    {
        var member=$('#member').val();
        
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        window.location.href = "<?php echo Yii::$app->urlManager->createUrl('/report/default/ptcheckin');?>?member="+member+"&start_date="+start_date+"&end_date="+end_date;

    }
</script>