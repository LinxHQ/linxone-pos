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
// $dropdow_member = array(''=>Yii::t('app','All'))+$member->getDataDropdown();
$member_selected = isset($_GET['member'])?$_GET['member']:'';

$year_now = date('Y');
$month_now = date('m');
$day_now = date('d');
$house_now = 0;
$mitu_now = 0;
$second_now = 0;
if(isset($_GET['month']) && isset($_GET['year'])){
    $month = $_GET['month']+1;
    $year = $_GET['year'];
    $date_chart = mktime($house_now, $mitu_now, $second_now, $month,01, $year);
        $start_date = date('d/m/Y', $date_chart); 
     
        $end_date = date('t/m/Y', $date_chart); 
        
}
$a = false;
if(isset($_GET['a'])){
    $a=$_GET['a'];
    echo "<input id='check' value =".$a." style= 'display:none;' </input>";
}
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;
$ListSetup  = new app\models\ListSetup();
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app','Checkin/out Report');?>
                        </div>
                    </div>

    <?php 
    
    echo '<table>';
    echo '<tr><td style="padding:20px;">';
echo '<label class="control-label">'.Yii::t('app','Members').': </label></td><td style="width:200px;">';
    
    // echo Select2::widget([
        // 'name' => 'id',
        // 'data' => $dropdow_member,
        // 'value' => $member_selected,
        // 'options' => [
// //            'placeholder' => 'Select trainers ...',
            // 'width'=>'400px',
            // 'id'=>'member'
// //            'multiple' => true
        // ],
    // ]);
    echo '<input type="text" name="member" id="member" placeholder="'.Yii::t('app','Name').'" value="'.$member_selected.'" />';        
    echo '</td>';
    echo '<td style="padding:20px;">';
    echo '<label class="control-label">'.Yii::t('app','From').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'start_date',
        'value' => $selected_start_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    echo '<td style="padding:20px;">';
    echo '<label class="control-label">'.Yii::t('app','To').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'end_date',
        'value' => $selected_end_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    echo '<td>';
    echo '<button style="margin-left:4px;background-color: rgb(50, 205, 139);margin-bottom: 10px;" onclick="search_checkin_report();return false;" class="btn btn-success">'.Yii::t('app','Search').'</button></td>';
    echo '</tr>';
    echo '</table>';
    
    
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'layout'=>"{items}",
//        'showFooter'=>FALSE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
            
            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app','Member'),
                'value' => function($model) {
                    $member = new Members();
                  
                    return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$member->getMemberFullName($model->member_id)."</a>";
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app','Member Barcode'),
                'value' => function($model) {
                    $member = \app\modules\members\models\Members::findOne($model->member_id);
                    return $member['member_barcode'];
                }
            ],
            [
                'attribute' => 'membership_type_id',
                'format' => 'html',
                'header' => Yii::t('app','Membership type'),
                'value' => function($model) {
                    if($model->membership_type_id > 0)
                        $membershiType = app\modules\membership_type\models\MembershipType::findOne($model->membership_type_id);
                    if(isset($membershiType))
                        return $membershiType['membership_name'];
                    return "";
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app','Checkin time'),
                'value' => function($model) {
                    $checkin_time = ($model->checkin_time != "0000-00-00 00:00:00")?date('d/m/Y H:i:s',  strtotime($model->checkin_time)):"";
                    return $checkin_time;
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app','Checkout time'),
                'value' => function($model) {
                    $checkcout_time = ($model->checkcout_time != "0000-00-00 00:00:00")?date('d/m/Y H:i:s',  strtotime($model->checkcout_time)):"";
                    return $checkcout_time;
                }
            ],

                    ],
    ]); ?>
<?php 
echo '<div class="parkclub-footer" style="text-align: center">';
echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/member_checkin_pdf?start_date='.$start_date.'&end_date='.$end_date.'&member='.urlencode($member_selected)).' ><button class="btn btn-success"style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print Pdf').'</button> </a>';
echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/excel_checkin?start_date='.$start_date.'&end_date='.$end_date.'&member='.urlencode($member_selected)).' ><button class="btn btn-success"style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export Excel').'</button> </a>';
echo '</div>';
?>
                    </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Checkin-out Chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Type');?><?php echo yii\bootstrap\Html::dropDownList('change-type',"",$ListSetup->getTypetime(),['onchange'=>'load_chart_checkin();return false','style'=>'width:30%','id'=>'type-checkin']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),array(''=>Yii::t('app','All'))+$ListSetup->year(),['onchange'=>'load_chart_checkin();return false;','style'=>'width:30%','id'=>'year-checkin']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="checkin-chart" style="min-height: 500px;">
                    </div>
                </div>
            </div>
</div>
<script>
    $( document ).ready(function() {
        load_chart_checkin();
        var a = $('#check').val();
        if(a==1){
            search_checkin_report();
        }
    })
    
    function search_checkin_report()
    {
        var member=$('#member').val();
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        window.location.href = "<?php echo Yii::$app->urlManager->createUrl('/report/default/chekin');?>?member="+member+"&start_date="+start_date+"&end_date="+end_date;

    }
    function load_chart_checkin(){
        $.blockUI();
        var type = $('#type-checkin').val();
        var year = $('#year-checkin').val();
        $('#checkin-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-checkin') ?>',{type:type,year:year},function(){
            $.unblockUI();
        });
    }
</script>