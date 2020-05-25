<?php
    use yii\helpers\Html;
    use yii\grid\GridView;
    use yii\widgets\Pjax;
    use app\modules\members\models\Members;
    use app\modules\invoice\models\Payment;
    use kartik\date\DatePicker;
    use kartik\select2\Select2;
    use app\models\ListSetup;
    use kartik\datetime\DateTimePicker;
    use app\modules\revenue_type\models\Revenue;
    use app\modules\facility\models\Facility;
    
    $ListSetup = new ListSetup();
    $facility = new Facility();
    $dropdow_facility = $facility->getDataDropdown();
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg" style="padding:12px;"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/management.png"style="width:30px;height:30px;" alt=""></div> <h3><?php echo Yii::t('app','Management dashboard'); ?></h3></div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);width:43%;">
                            <?php echo Yii::t('app', 'Payment chart');?>
                        </div>
                       <div class="parkclub-header-right" style="width:57%;">
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_year(this.value)','style'=>'width:30%']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="live-chart">

                    </div>
                </div>
            </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Revenue chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Month');?><?php echo yii\bootstrap\Html::dropDownList('change-month',"",array(''=>Yii::t('app','All'))+$ListSetup->getMonth(1),['onchange'=>'load_chart_revenue();return false','style'=>'width:30%','id'=>'month-revenue']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_revenue();return false;','style'=>'width:30%','id'=>'year-revenue']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="revenue-chart" style="min-height: 400px;">
                    </div>
                </div>
            </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);width:43%;">
                            <?php echo Yii::t('app', 'Checkin-out chart');?>
                        </div>
                       <div class="parkclub-header-right" style="width:57%;">
                           <?php echo Yii::t('app', 'Type');?><?php echo yii\bootstrap\Html::dropDownList('change-type',"",$ListSetup->getTypetime(),['onchange'=>'load_chart_checkin();return false','style'=>'width:40%','id'=>'type-checkin']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_checkin();return false;','style'=>'width:30%','id'=>'year-checkin']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="checkin-chart" style="min-height: 400px;">
                    </div>
                </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);width:34%;">
                            <?php echo Yii::t('app', 'Booking chart');?>
                        </div>
                       <div class="parkclub-header-right" style="width:66%;">
                           <?php echo Yii::t('app', 'Facility');?><?php echo yii\bootstrap\Html::dropDownList('change-facility',"",array('0'=>Yii::t('app','All'))+$dropdow_facility,['onchange'=>'load_chart_booking();return false','style'=>'width:43%','id'=>'facility-booking']); ?>
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_booking();return false;','style'=>'width:30%','id'=>'year-booking']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="live-booking-chart">

                    </div>
                </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);width:58%;">
                            <?php echo Yii::t('app', 'Facility booking chart');?>
                        </div>
                       <div class="parkclub-header-right" style="width:42%;">
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_facility(this.value)','style'=>'width:40%']); ?>
                       </div>
                    </div>
                    <br>
                    <div id="live-facility-chart">

                    </div>
                </div>
            </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-small parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content" >
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Members chart');?>
                        </div>
                       <div class="parkclub-header-right">
                           <?php echo Yii::t('app', 'Year');?><?php echo yii\bootstrap\Html::dropDownList('change-year',date('Y'),$ListSetup->year(),['onchange'=>'load_chart_members(); return false;','style'=>'width:30%','id'=>'year-member']); ?>
                     
                       </div>
                    </div>
                    <br>
                    <div id="member-chart" style="min-height: 400px;">
                    </div>
                </div>
            </div>
</div>

<script>

    $( document ).ready(function() {
        load_chart_year('<?php echo date('Y'); ?>');
        load_chart_revenue();
        load_chart_checkin();
        load_chart_booking();
        load_chart_facility('<?php echo date('Y'); ?>');
        load_chart_members();
    })
    function load_chart_year(year){
        $.blockUI();
        $('#live-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-payment') ?>',{year:year,small:1},function(){
            $.unblockUI();
        });
    }
    
    function load_chart_revenue(){
        $.blockUI();
        var month = $('#month-revenue').val();
        var year = $('#year-revenue').val();
        $('#revenue-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-revenue') ?>',{month:month,year:year},function(){
            $.unblockUI();
        });
    }
    function load_chart_checkin(){
        $.blockUI();
        var type = $('#type-checkin').val();
        var year = $('#year-checkin').val();
        $('#checkin-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-checkin') ?>',{type:type,year:year},function(){
            $.unblockUI();
        });
    }
    function load_chart_booking(){
        $.blockUI();
        var facility= $('#facility-booking').val();
        var year = $('#year-booking').val();
        $('#live-booking-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-booking') ?>',{facility:facility,year:year},function(){
            $.unblockUI();
        });
    }
    function load_chart_facility(year){
        $.blockUI();
        $('#live-facility-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-facility') ?>',{year:year},function(){
            $.unblockUI();
        });
    }
    function load_chart_members(){
        $.blockUI();
        var year = $('#year-member').val();
        $('#member-chart').load('<?php echo Yii::$app->urlManager->createUrl('report/default/chart-member') ?>',{year:year},function(){
            $.unblockUI();
        });
    }
</script>
