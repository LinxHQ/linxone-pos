<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\members\models\Members;
use app\modules\invoice\models\Payment;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Membership Expiry Report');
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app', 'Membership Expiry Report');?>
                        </div>
                    </div>
                   

               
<?php 
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;

echo '<table>';
    echo '<tr>';
    

    echo '<td>';
    echo '<label class="control-label" >'.Yii::t('app', 'From').': </label></td><td>';
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
    
    echo '<td >';
    echo '<label class="control-label">'.Yii::t('app', 'To').': </label></td><td>';
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
    echo '<button style="margin-left:4px; margin-bottom: 10px;background-color: rgb(50, 205, 139);" onclick="search_payment();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';


$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();
?>

<?=GridView::widget([
        'dataProvider' => $dataProvider,
        //'layout'=>"{items}\n{pager}",
        'showFooter'=>FALSE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
            
            ],
        'tableOptions' =>['class'=>'scroll-report'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],

            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Member name'),
	            'value' => function($model) {
                        $member = new Members();
                        return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$member->getMemberFullName($model->member_id)."</a>";
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Member email'),
	            'value' => function($model) {
                        $member = Members::findOne($model->member_id);
                        return $member->member_email;
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Member mobile'),
	            'value' => function($model) {
                        $member = Members::findOne($model->member_id);
                        return $member->member_mobile;
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Member Barcode'),
	            'value' => function($model) {
                        $member = Members::findOne($model->member_id);
                        return $member->member_barcode;
	            },
                   
	    ],
            [
	            'attribute' => 'member_id',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Membership type'),
	            'value' => function($model) {
                        $memberShipType = new \app\modules\membership_type\models\MembershipType();
                        $memberShipType_arr = $memberShipType->getDropDown();
                        return $memberShipType_arr[$model->membership_type_id];
	            },
                    
	    ],
            [
	            'attribute' => 'membership_startdate',
	            'format' => 'html',
	            'header' => Yii::t('app', 'Start date'),
	            'value' => function($model) {
                        if($model->membership_startdate!="0000-00-00")
							return date('d/m/Y',  strtotime($model->membership_startdate));
						else return '';
				},
                    
	    ],
            [
	            'attribute' => 'membership_enddate',
	            'format' => 'html',
	            'value' => function($model) {
                    if($model->membership_enddate != "0000-00-00")    
                        return date('d/m/Y',  strtotime($model->membership_enddate));
					else return '';
				},
                    
	    ],
            [
	            'attribute' => 'expiry',
	            'format' => 'html',
	            'value' => function($model) {
                     
                        $listSetup = new \app\models\ListSetup();
						if($model->membership_enddate!="0000-00-00") {
							$array = $listSetup->getdate($model->membership_enddate);
							if($array['diff']<0){
								 return "<span class='label label-danger'>".Yii::t('app', 'Overdue for')." &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;<br/>".$array['day_return']." </span>";
							 }else{
								 return "<span class='label label-warning'>".$array['day_return']."</span>";
							 }
                        } else return '';
	            },
                    
	    ],
            
                    

        ],
    ]);  
     
echo '<div class="parkclub-footer" style="text-align: center">';
if(isset($_GET['m']) && $_GET['m'] =="expiry"){
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pdf_membership?start_date='.$start_date.'&end_date='.$end_date.'&m='.$_GET['m']).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>';   
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/excel_membership?start_date='.$start_date.'&end_date='.$end_date.'&m='.$_GET['m']).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);" >'.Yii::t('app', 'Export Excel').'</button> </a>'; 
} else {
    
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/pdf_membership?start_date='.$start_date.'&end_date='.$end_date).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>';                    
    echo '<a href='.Yii::$app->urlManager->createUrl('/report/default/excel_membership?start_date='.$start_date.'&end_date='.$end_date).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);" >'.Yii::t('app', 'Export Excel').'</button> </a>'; 
}                   
echo '</div';

?>
                </div>
            </div>
</div>
<script>
    function search_payment()
    {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        window.location.href='membershipreport?start_date='+start_date+'&end_date='+end_date;
    }
    </script>