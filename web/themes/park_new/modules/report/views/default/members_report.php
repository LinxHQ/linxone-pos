<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use app\modules\membership_type;
use app\modules\members\models\Membership;
use app\models\ListSetup;
use app\modules\members\models\Members;
use app\modules\membership_type\models\MembershipType;
use kartik\select2\Select2;
use app\modules\checkin\models\MembersCheckin;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\members\models\memberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// echo "<pre>";
// print_r($dataProvider);
$membership_type = new MembershipType();
$dropdow_membership_type= array(""=>Yii::t('app','All'))+$membership_type->getDropDown();

$membership = new Membership();
$dropdow_membership= array(""=>Yii::t('app','All'))+$membership->getArrayStatus()+array("Expired"=>Yii::t('app','Expired'));

$membership_type_selected = isset($_GET['membership_type_name'])?$_GET['membership_type_name']:'';
$membership_selected = isset($_GET['membership_status'])?$_GET['membership_status']:'';

$year_now = date('Y');
$month_now = date('m');
$day_now = date('d');
$house_now = 0;
$mitu_now = 0;
$second_now = 0;
$start_date= false;
$end_date= false;
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


?>

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Members Report');?>
                        </div>
                    </div>
                    
<?php
   echo '<table>';
    echo '<tr>';

    echo '<td><label class="control-label">'.Yii::t('app','Type of member').':</label></td>';
    echo '<td style = "width:200px;">';
     echo Select2::widget([
        'name' => 'id223',
        'data' => $dropdow_membership_type,
        'value' => $membership_type_selected,
        'options' => [
//            'placeholder' => 'Select Member/PT....',
            'width'=>'100px',
            'id'=>'membership_type_name'
  //        'multiple' => true
        ],
    ]);
    echo '</td>';

    echo '<td style="padding:20px;"><label class="control-label">'.Yii::t('app','Status').':</label></td>';
    echo '<td style = "width:200px;">';
    echo Select2::widget([
        'name' => 'id133',
        'data' => $dropdow_membership,
        'value' => $membership_selected,
        'options' => [
//            'placeholder' => 'Select Facility ...',
            'width'=>'100px',
            'id'=>'membership_status'
  //        'multiple' => true
        ],
    ]);
    echo '</td>';

    echo '<td>';
    echo '<button style="margin-left:4px" onclick="search_member();return false;" class="btn btn-success">'.Yii::t('app','Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';
?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'layout'=>"{items}\n{pager}",
        'showFooter'=>FALSE,
        'footerRowOptions'=>['style'=>'font-weight:bold;',],
        'tableOptions' =>['class'=>'scroll-report', 'id'=>'member-report'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app', 'No.'),
                'headerOptions'=>['style'=>'min-width:50px'],
                'contentOptions'=>['style'=>'min-width:50px']
            ],
        	[
		            'attribute' =>'member_barcode',
                            'label' => Yii::t('app','Barcode')." <span class='glyphicon glyphicon-chevron-down'>",
                            'encodeLabel' => false,
		            'format' => 'html',
		            'value' => function($model) {
                                if($model->member_barcode == ""){
                                    return $model->guest_code;
                                }
                                return $model->member_barcode;
		            }
		    ],
		    [
		            'attribute' => 'member_name',
                            'label' => Yii::t('app','Name')." <span class='glyphicon glyphicon-chevron-down'>",
                            'encodeLabel' => false,
		            'format' => 'html',
		            'value' => function($model) {
	                        return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$model->surname." ".$model->first_name."</a>";
		            }
		    ],
		    [
		            'attribute' => 'Address',
                            'header' => Yii::t('app','Address'),
		            'format' => 'html',
		            'value' => function($model) {
                                $members = new Members();
                                return $members->getMemberFullAddress($model->member_id);
		            }
		    ],
		    [
		            'attribute' => 'Date of birth',
                            'header' => Yii::t('app','Date of birth'),
		            'format' => 'html',
		            'value' => function($model) {
                                return $model->member_birthday;  
		            }
		    ],
		    [
		            'attribute' => 'Phone number',
                            'header' => Yii::t('app','Phone number'),
		            'format' => 'html',
		            'value' => function($model) {
	                        return $model->member_mobile;
		            }
		    ],
		    [
		            'attribute' => 'Email',
                            'header' => Yii::t('app','Email'),
		            'format' => 'html',
		            'value' => function($model) {
	                        return $model->member_email;
		            }
		    ],
		    [
		            'attribute' => 'Membership Type',
                            'header' => Yii::t('app','Membership type'),
		            'format' => 'html',
		            'value' => function($model) {
                        $membership_type_name = "";
                        $i=0;
                        foreach ($model->memberShip as $data) {
                            $i++;
                            $membership_type_name .= ($data->membershipType) ? $i.". ".$data->membershipType->membership_name.'<br>' : "";
                        }
                        return $membership_type_name;
                    },
                    'headerOptions'=>['style'=>'min-width:250px'],
                    'contentOptions'=>['style'=>'min-width:250px']
		    ],
                    [
		            'attribute' => 'Membership_status',
                            'header' => Yii::t('app','Membership status'),
		            'format' => 'html',
		            'value' => function($model) {
                        $membership_status = "";
                        foreach ($model->memberShip as $data) {
                            $membership_status .= Yii::t('app',$data->getStatus()).'<br>';
                        }
                        return $membership_status;
                    },
                    'headerOptions'=>['style'=>'min-width:140px'],
                    'contentOptions'=>['style'=>'min-width:140px']
                    
		    ],        
		    [
		            'attribute' => 'Joining date',
                            'header' => Yii::t('app','Joining date'),
		            'format' => 'html',
		            'value' => function($model) {
                        $membership_startdate = "";
                        foreach ($model->memberShip as $data) {
                            $membership_startdate .= ListSetup::getDisplayDate($data->membership_startdate).'<br>';
                        }
                        return $membership_startdate;
                    },
                    'headerOptions'=>['style'=>'min-width:130px'],
                    'contentOptions'=>['style'=>'min-width:130px']
		    ],
		    [
		            'attribute' => 'Expiry',
                            'header' => Yii::t('app','Expiry'),
		            'format' => 'html',
		            'value' => function($model) {
                        $membership_enddate = "";
                        foreach ($model->memberShip as $data) {
                            $membership_enddate .= ListSetup::getDisplayDate($data->membership_enddate).'<br>';
                        }
                        return $membership_enddate;
                    },
                    'headerOptions'=>['style'=>'min-width:130px'],
                    'contentOptions'=>['style'=>'min-width:130px']
		    ],
                    [
		            'attribute' => 'Served session',
                            'header' => Yii::t('app','Served session'),
		            'format' => 'html',
		            'value' => function($model) {
                        $served_session = "";
                        foreach ($model->memberShip as $data) {
                            if($data->membership_enddate){
                                $checkin = new MembersCheckin();
                                $served_session .= $checkin->getTotalMemberCheckin($data->membership_startdate,$data->membership_enddate, $model->member_id).'<br>';
                            }
                        }
                        return $served_session;
                }
		    ],
        ]
    ])
;?>

<?php 
        echo '<div class="parkclub-footer" style="text-align: center">';
        echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/membersreportpdf?membership_type_name='.$membership_type_selected.'&membership_status='.$membership_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print Pdf').'</button> </a>';
        echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/member_report_excel?membership_type_name='.$membership_type_selected.'&membership_status='.$membership_selected).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export excel').'</button> </a>';
        echo '</div>';
        ?>
                </div>
            </div>
</div>
<script>
    $( document ).ready(function() {
        var a = $('#check').val();
        if(a==1){
            search_member();
        }
    })
    function search_member()
    {
        var membership_type_name=$('#membership_type_name').val();
        var membership_status = $('#membership_status').val();
     
        
      window.location.href='membersreport?membership_type_name='+membership_type_name+'&membership_status='+membership_status;
    }
    $('#member-report').on('scroll', function () {
        $("#member-report > *").width($("#member-report").width() + $("#member-report").scrollLeft());
        });
</script>
