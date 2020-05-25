<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use app\modules\invoice\models\InvoiceItem;
use app\modules\invoice\models\invoice;
use app\modules\members\models\Members;
use app\modules\members\models\Membership;
use app\modules\invoice\models\Payment;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$member = new Members();
$invoice = new invoice();
$ListSetup = new \app\models\ListSetup();
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
            <div class="parkclub-rectangle parkclub-shadow">
                <div class="parkclub-rectangle-content">
                   <div class="parkclub-newm-left-title" >
                        <div class="parkclub-header-left" style = "color: rgb(50, 205, 139);">
                            <?php echo Yii::t('app','Account receivable');?>
                        </div>
                    </div>


<?php 
$selected_member = (isset($_GET['member_id'])) ? $_GET['member_id'] : "";
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;
$selected_invoice_status = (isset($_GET['invoice_status'])) ? $_GET['invoice_status'] : "";
// $drop_member = array(''=>Yii::t('app', 'All'))+$member->getDataDropdown();

echo '<table>';
    echo '<tr>';

    echo '<td style="padding:10px;" >';
    echo '<label class="control-label">'.Yii::t('app', 'From').': </label></td><td style="width: 200px;">';
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
    
    echo '<td style="padding:10px;">';
    echo '<label class="control-label">'.Yii::t('app', 'To').': </label></td><td style="width: 200px;">';
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
    echo '</tr>';
    echo '<tr>';
        echo '<td><label class="control-label">'.Yii::t('app', 'Member').': </label></td>';
    echo '<td style="padding:10px;">';
    // echo Select2::widget([
        // 'name' => 'member_id',
        // 'data' => $drop_member,
        // 'value' => $selected_member,
        // 'options' => [
            // 'width'=>'400px',
            // 'id'=>'member_id'
// //            'multiple' => true
        // ],
    // ]);
	echo '<input type="text" name="member_id" id="member_id" placeholder="'.Yii::t('app','Name').'" value="'.$selected_member.'" />';
    echo '</td>';
    echo '<td style="padding:10px;"><label class="control-label">'.Yii::t('app', 'Status invoice').':</label></td>';
    echo '<td>';
    $arr_invoice_status = array(''=>Yii::t('app','All')) + $invoice->getDropdownStatus();
    echo $ListSetup->getSelectOptionList("invoice_status",$arr_invoice_status,"invoice_status","",$selected_invoice_status);
    echo '</td>';

    echo '<td style="padding:10px;">';
    echo '<button style="margin-left:4px; background-color: rgb(50, 205, 139);" onclick="search_payment();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';
    

$payment_Manage = new \app\modules\invoice\models\Payment();
$invoice = new \app\modules\invoice\models\invoice();
$ListSetup  = new app\models\ListSetup();
//$dataProvider->pagination->pageSize=0;


?>
<div >
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"<div class='wrapper1'><div class='div1'></div></div><div class='wrapper2'><div class='div2'>{items}</div></div>\n{pager}",     
        'showFooter'=>TRUE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
        ],
        'tableOptions' =>['id' => 'receivable'],
        'columns' => [
//            'author.name:text:Author Name',
                  ['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app','No.')],
                [
                    'attribute' => 'invoice_date',
                    'header'=>Yii::t('app', 'VAT Invoice Date'),
                    'format' => 'html',
                    'value' => function($model) {
                        $ListSetup = new \app\models\ListSetup(); 
                        return $ListSetup->getDisplayDate($model->invoice_date);

                    },
                    'footer'=> Yii::t('app', 'Total'),
                ],
            [
              'attribute' => 'invoice_no',
              'format' => 'html',
                    'header'=>Yii::t('app', 'Invoice number'),
              'value' => function($model) {
                $invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$model->invoice_id);
                return '<a href="'.$invoice_url.'">'.$model->invoice_no.'</a>'; 
                 
              },
            ],       
                  [
                    'attribute' => 'payment_date',
                    'format' => 'html',
                          'header'=>Yii::t('app', 'Receipt number'),
                    'value' => function($model) {
                            $payment = Payment::find()->where(["invoice_id"=>$model->invoice_id,"payment_void"=>0])->all();
                            $result = "";
                            foreach ($payment as $data) {
                                $result .= $data->payment_no.'<br>';
                            }
                             return $result;
                    },
            ],
                [
                    'attribute' => 'fullname',
                    'header' => Yii::t('app', 'Fullname'),
                    'format' => 'html',
                    'value' => function($model) {
                        $member = new Members();
                        $memberfullname = $member->getMemberFullName($model->member_id);
                            // return $memberInfo['member_name'];
                        // Neu Invoice Oustanding >= 23 ngay thi boi do
                        if($model->invoice_date){
                            $date_now = date('Y-m-d');
                            $diff = abs(strtotime($date_now) - strtotime($model->invoice_date));
                            $days = floor(($diff)/ (60*60*24));
                            if($days >= 23){ 
                                return "<div class='border'><a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$memberfullname."</a></div>";
                                //return '<div class="border">'.$memberfullname.'</div>';
                            } else {
                                return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$memberfullname."</a>";
                            }
                        }
                },
            ], 

            [
                'attribute' => 'Address',
                'header' => Yii::t('app', 'Address'),
                'format' => 'html',
                'value' => function($model) {
                    $member = new Members();
                    $memberaddress = $member->getMemberFullAddress($model->member_id);
                    return $memberaddress; 
                },
            ], 

            [
                    'attribute' => 'member_barcode',
                    'header' => Yii::t('app', 'Member Barcode'),
                    'format' => 'html',
                    'value' => function($model) {
                        $member = new Members();
                        $memberInfo = $member->getMember($model->member_id);
                        if($memberInfo)
                            return $memberInfo['member_barcode'];
                        return '';
                    },

            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Agreement No.'),
                    'value' => function($model) {
                            $membership = $model->invoice_type_id;
                            $membership_barcode='';
                            $membershiptype_name ="";
                            if($membership>0 && $model->invoice_type == "membership")
                            {
                                $membershipInfo = \app\modules\members\models\Membership::findOne($membership);
                                
                                if($membershipInfo)
                                {
                                    $membership_barcode = $membershipInfo['membership_barcode'];
                                   
                                }
                            }

                            return $membership_barcode;
                            
                    },

            ],
//        [
//            'attribute' => 'invoice_date',
//            'header' => 'Agreement Date.',
//            'format' => 'html',
//            'value' => function($model) {
//
//                    return date('d/m/Y',strtotime($model->invoice_date));
//            },
////            'footer'=> 'Total',
//        ],
        [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Description'),
                    'value' => function($model) {
                            $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$model->invoice_id])->one();
                          
                            if($invoiceItem)
                                return $invoiceItem->invoice_item_description;
                            return "";
                    },

            ],
            [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Period'),
                    'value' => function($model) {
                        $invoiceItemManager = new InvoiceItem();
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoiceItemManager->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        $date_apply = "";
                        if($invoiceItem && !in_array($invoiceItem->invoice_item_description, $invoiceItemManager->getArrayItemInvoice()))
                        {
                            $membership = $model->invoice_type_id;
                            if($membership>0 && $model->invoice_type == "membership")
                            {
                                $membershipInfo = \app\modules\members\models\Membership::findOne($membership);
                                if($membershipInfo)
                                    $date_apply = $MembershipPrice->getDateMemberTypeApplyPrice($membershipInfo->membership_type_id, $model->invoice_date);
                            }
                        }
                        return $date_apply;
                                
                    }
            ],
            [
                    'attribute' => 'invoice_type_id',
                    'format' => 'html',
                    'header' => Yii::t('app', 'Months'),
                    'value' => function($model) {
                        $listsetup = new app\models\ListSetup();
                        $invoiceItemManager = new InvoiceItem();
                        $MembershipPrice = new app\modules\membership_type\models\MembershipPrice();
                        $invoiceItem = $invoiceItemManager->find()->where(["invoice_id"=>$model->invoice_id])->one();
                        $Months = "";
                        if($invoiceItem && !in_array($invoiceItem->invoice_item_description, $invoiceItemManager->getArrayItemInvoice()))
                        {
                            $membership = $model->invoice_type_id;
                            if($membership>0 && $model->invoice_type == "membership")
                            {
                                $membershipInfo = \app\modules\members\models\Membership::findOne($membership);
                                if($membershipInfo){
                                    $membershipType = app\modules\membership_type\models\MembershipType::findOne($membershipInfo->membership_type_id);
                                    if($membershipType)
                                        if($membershipType->membership_type_month==0)
                                            $Months = "";
                                        else
                                            $Months = \app\models\ListSetup::getItemByList ('memberShipType_status')[$membershipType->membership_type_month];
                                }
                            }
                        }
                        return $Months;
                    },

            ],

            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Price'),
                    'value' => function($model) {
                            $amount=0;
                            $invoiceItem = InvoiceItem::find()->where(["invoice_id"=>$model->invoice_id])->one();
                            if(isset($invoiceItem)){
                                $amount = $invoiceItem->invoice_item_amount;
                            }
                            return \app\models\ListSetup::getDisplayPrice($amount);
                            
                    },
                    'footer'=> \app\models\ListSetup::getDisplayPrice($invoice->getAllPrice("Oustanding",$dataProvider)),
            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Incentives/Discount amt.'),
                    'value' => function($model) {
                        return \app\models\ListSetup::getDisplayPrice($model->getDiscountRecord());
                    },
                    'footer'=> \app\models\ListSetup::getDisplayPrice($invoice->getAllAmountDiscount("Oustanding",$dataProvider),0,",","."),
            ],

            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Payables'),
                    'value' => function($model) {
                            return \app\models\ListSetup::getDisplayPrice($model->invoice_total_last_tax);
                    },
                       'footer'=> number_format($invoice->getAllAmountInvoice("Oustanding",$dataProvider),0,",","."),
            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Paid amt.'),
                    'value' => function($model) {
                            $payment = new Payment();
                            return \app\models\ListSetup::getDisplayPrice($payment->getAmountByInvoice($model->invoice_id));
                    },
                       'footer'=> \app\models\ListSetup::getDisplayPrice($invoice->getAmountPaymentInvoice("Oustanding",false,false,$dataProvider)),         
            ],
                [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Outstanding'),
                    'value' => function($model) {
                        $payment = new Payment();
                            // return $model->getInvoiceOustanding($model->invoice_id);
                        // Neu Invoice Oustanding >= 23 ngay thi boi do
                        if($model->invoice_date){
                            $date_now = date('Y-m-d');
                            $diff = abs(strtotime($date_now) - strtotime($model->invoice_date));
                            $days = floor(($diff)/ (60*60*24));
                            if($days >= 23){
                                return '<div class="border">'. \app\models\ListSetup::getDisplayPrice($model->invoice_total_last_paid).'</div>';
                            } else {
                                return \app\models\ListSetup::getDisplayPrice($model->invoice_total_last_paid);
                            }
                        }
                    },
                              'footer'=> \app\models\ListSetup::getDisplayPrice($invoice->getAmountOustandingInvoice("Oustanding",$dataProvider)), 
                        
                ],
                [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Payment due date'),
                    'value' => function($model) {
                            return $model->getDuaDate();
                    },
                              

                ],
                [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'Invoice Age'),
                    'value' => function($model) {
                        // return $model->getAgeInvoice($model->invoice_id);
                    // Neu Invoice Oustanding >= 23 ngay thi boi do 
                        if($model->invoice_date){
                            $listSetup = new \app\models\ListSetup();
                            $array = $listSetup->getdate($model->invoice_date);
                            if($array['diff'] >= 23){
                                return '<div class="border">'.$model->getAgeInvoice($model->invoice_id) .'</div>';
                            } else {
                                return $model->getAgeInvoice($model->invoice_id) ;
                            }
                        }
                    },
            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'VAT Invoice No.'),
                    'value' => function($model) {
                            return $model->invoice_vat_no;
                    },
            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                     'header'=>Yii::t('app', 'VAT Invoice Date'),
                    'value' => function($model) {
                        $ListSetup = new \app\models\ListSetup();
                            return $ListSetup->getDisplayDate($model->invoice_vat_date);
                    },
            ],
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                     'header'=>Yii::t('app', 'VAT Invoice Amount'),
                    'value' => function($model) {
                            return number_format($model->invoice_vat_amount,0,',','.');
                    },
            ],
            [
                    'attribute' => 'invoice_vat_status',
                    'format' => 'html',
                    'header'=>Yii::t('app', 'VAT Status'),
                    'value' => function($model) {
                         $ListSetup = new \app\models\ListSetup();
                         $arr_invoiceStatus= \app\models\ListSetup::getItemByList('vat_status');
                         return (isset($arr_invoiceStatus[$model->invoice_vat_status]) ? $arr_invoiceStatus[$model->invoice_vat_status] : "");
                    },
            ],            
            [
                    'attribute' => 'member_id',
                    'format' => 'html',
                     'header'=>Yii::t('app', 'Remark'),
                    'value' => function($model) {
                     //   $status = $model->getStatusInvoice();
                        return Yii::t('app', $model->invoice_status);
                    },
            ],
            [
                'attribute' => 'Joining Date',
                'header' => Yii::t('app', 'Joining Date'),
                'format' => 'html',
                'value' => function($model) { 
                    $membership = $model->invoice_type_id; 
                    if($membership>0 && $model->invoice_type == "membership")
                    {
                        $membershipInfo = \app\modules\members\models\Membership::findOne($membership); 
                        $start_date = $membershipInfo['membership_startdate'];  
                        if($start_date != "")    
                            return  date('d/m/Y',  strtotime($start_date));
                         else return '';
                    } ;
                },
            ],
            [
                'attribute' => 'Expiry Date',
                'header' => Yii::t('app', 'Expiry Date'),
                'format' => 'html',
                'value' => function($model) {
                    $membership = $model->invoice_type_id; 
                    if($membership>0 && $model->invoice_type == "membership")
                    {
                        $membershipInfo = \app\modules\members\models\Membership::findOne($membership);
                        $end_date = $membershipInfo['membership_enddate'];  
                        if($end_date != "")    
                        return  date('d/m/Y',  strtotime($end_date));
                         else return '';
                    } ;
    
                },
            ],
            
        ],
    ]); 
               
?>
<?php
$start_date=false;
if(isset($_GET['start_date']))
    $start_date=$_GET['start_date'];
$end_date=false;
if(isset($_GET['end_date']))
    $end_date=$_GET['end_date'];
echo '<div class="parkclub-footer" style="text-align: center">';
echo '<a target="_blank"  href='.Yii::$app->urlManager->createUrl('/report/default/pdfreceivable?start_date='.$start_date.'&end_date='.$end_date.'&member_id='.urlencode($selected_member).'&invoice_status='.$selected_invoice_status).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>'      ;              
echo '<a target="_blank"  href='.Yii::$app->urlManager->createUrl('/report/default/excelreceivable?start_date='.$start_date.'&end_date='.$end_date.'&member_id='.urlencode($selected_member).'&invoice_status='.$selected_invoice_status).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export Excel').'</button> </a>'  ;                  
echo '</div>';
?>
</div>
                </div>
            </div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.doubleScroll.js"></script>
<script>
    $(function () {
        $('.wrapper1').on('scroll', function (e) {
            $('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
        }); 
        $('.wrapper2').on('scroll', function (e) {
            $('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
        });
    });
    $(window).on('load', function (e) {
        $('.div1').width($('#receivable').width());
        $('.div2').width($('#receivable').width());
    });
    
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        if((intall_data==2 || intall_data==1) && istour==1){
            data_demo.init();
            data_demo.restart();
            data_demo.start();
            data_demo.goTo(4);
        }
    });
    function search_payment()
    {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var member_id = $('#member_id').val();
        var invoice_status = $('#invoice_status').val();
        window.location.href='receivable?start_date='+start_date+'&end_date='+end_date+'&member_id='+member_id+'&invoice_status='+invoice_status;
    }
        $('#receivable').on('scroll', function () {
        $("#receivable > *").width($("#receivable").width() + $("#receivable").scrollLeft());
        });
</script>