<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\members\models\Members;
use app\modules\members\models\Membership;
use app\modules\membership_type\models\MembershipType;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use app\models\ListSetup;
use app\modules\invoice\models\invoice;
use app\modules\training\models\MemberTrainingsSearch;
use app\modules\training\models\MemberTrainers;
use app\modules\training\models\TrainerCheckin;
use kartik\editable\Editable;
use timurmelnikov\widgets\WebcamShoot;
use kartik\select2\Select2;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\modules\members\models\Members */

$member_id = $model->member_id;

$member = new Members();
$MemberShip = new Membership();
$membershipInfo = $MemberShip->getMemberShip($member_id,1);
$ListSetup = new ListSetup();
$arr_member=$member->getSubAccount($member_id);
$memberTrainer = new MemberTrainers();
$modelTraining = new MemberTrainingsSearch();
$modelTraining->member_id=$model->member_id;
$modelTraining->training_renew_id=0;
$training = $modelTraining->search(Yii::$app->request->queryParams);

$invoice = new invoice();
$book = new app\modules\booking\models\Booking();
$trainer_checkin = new TrainerCheckin();

//echo WebcamShoot::widget([
//    'targetInputID' => 'textimg',
//    'targetImgID' => 'textphoto',
//]);        
//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$canView = $BasicPermission->checkModules($m, 'view');
$canUpdate = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');

$DefinePermission = new app\modules\permission\models\DefinePermission();
$canAddnotes = $DefinePermission->checkFunction($m, 'Add notes');
$canCheckin_out = $DefinePermission->checkFunction($m, 'Check in/check out');
$canVoidMembershipAgreement = $DefinePermission->checkFunction($m, 'Void membership agreement');
$canAddMembership = $DefinePermission->checkFunction($m, 'Add membership');
$canAddTraining = $DefinePermission->checkFunction($m, 'Add training');
$canAddNotes = $DefinePermission->checkFunction($m, 'Add notes');
        
?>
<!--<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3>Members</h3></div>
    <div class="parkclub-search">
        <input id="search_member" type="text" placeholder="By Name or Mobile">
        <button class="parkclub-searchbtn parkclub-searchbtn-2" type="submit" onclick="search_member();return false;"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/search.png"></button>
    </div>
</div
<div class="members-index">-->
<!--
</div>-->
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div style="text-align: center;">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo YII::$app->urlManager->createUrl(['members/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $model->getMemberFullName(); ?>
                    </div>
                    <div class="parkclub-header-right">
                        <?php if($canUpdate){ ?>
                        <button class="btn btn-success"><?php echo Yii::t('app', 'Save');?></button>
                        <?php }?>
                        <?php echo '<a href='.Yii::$app->urlManager->createUrl('members/default/profile_pdf?id='.$model->member_id).' <button class="btn btn-success">'.Yii::t('app', 'Print Pdf').'</button> </a>';?>
                        <?php if($canAdd){ ?>
                        <button class="right-button" type="button" onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('/members/default/create'); ?>'"><?php echo Yii::t('app', 'Add Member');?></button>
                        <?php } ?>
                    </div>
                </div>
                <!-- MODAL WELCOME -->
                <div id="take_picture" style="display: none;padding: 20px;">
                        <div id="modal-content-welcome">
                        </div>
                </div>
                <div id="view_picture" style="display: block;">
                    <?php if($model->member_picture)
                        {
                            echo '<img style="margin: 10px 0 10px 0;" width="350px" src="'.$model->member_picture.'">';
                        }
                        else
                        {
                        ?>
                        <img id="images_member" style="margin: 10px 0 10px 0;" width="200px" src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/unknown.png">
                        <?php } ?>
                        <?php if($canUpdate){ ?>
                            <?php //$form->field($model, 'member_picture')->fileInput(['style'=>'margin-left: 455px;background-image: url("/parkcity/image/image-hv.png");'])->label(false); ?>
                        <h4><button class="parkclub-button" type="button" onclick="popScanWebcome(<?php echo $model->member_id; ?>);"><?php echo Yii::t('app', 'Take a picture');?></button></h4>
                    <?php } ?>
                </div>
                <!-- END MODAL WELCOME --> 
            </div>
            
                    <table class="table parkclub-table" style="text-align: left;">
                        <?php if($model->parent_account > 0) { 
                            $parentAccountId = $model->parent_account ;
                            $master_info = Members::findOne(['parent_account'=>$model->parent_account]);
                        ?>
                        <tr>
                            <td ><?php echo Yii::t('app', 'Master Account');?></td>
                            <td colspan="4"><?php  echo "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->parent_account)."'>".$member->getMemberFullName($model->parent_account)."</a>" ; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="td-left"><?php echo Yii::t('app', 'Given name');?>:</td>
                            <td class="td-right"><?= $form->field($model, 'given_name')->textInput(['maxlength' => true])->label(false); ?></td>
                            <td></td>
                            <td class="td-left"><?php echo Yii::t('app', 'First name');?>:</td>
                            <td class="td-right">
                                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label(false); ?>
                            </td>
                        </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Surname');?>:</td>
                        <td>
                            <?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label(false); ?>
                        </td>
                        <td></td>
                    <td><?php echo Yii::t('app', 'Resident');?>:</td>
                        <td>
                            <?= $form->field($model, 'resident')->dropDownList(ListSetup::getItemByList('resident'),['onChange'=>"change_resident(this.value);" ])->label(false) ?>
                        </td>
                    </tr>
                    <?php echo ($model->resident > 0)?'<tr  class="member_Resident" >':'<tr hidden=""  class="member_Resident">'; ?>
                        <td><?php echo Yii::t('app', 'Full Address');?></td>
                        <td><?= $form->field($model, 'member_address')->textarea(['rows' => 3])->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Unit');?>:</td>
                        <td>
                            <?= $form->field($model, 'unit')->textarea(['rows' => 3])->label(false) ?>
                        </td>

                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'DOB');?>
                        </td>
                        <td>
                            <?php 
                            echo $form->field($model, 'member_birthday')->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => Yii::t('app','Enter date...')],
                                'value' => '',
                                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd',
                                ],
                                
                            ])->label(false);
                            ?>
                        </td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Identity card/ Passport');?> :</td>
                        <td><?= $form->field($model, 'id_card')->textInput()->label(false); ?></td> 
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app','Province/City');?></td>
                        <td>
                            <?php
                            $province_arr = ListSetup::getItemByList('city');
                            asort($province_arr);
                            echo $form->field($model, 'city')->widget(Select2::classname(), [
                                'data' => $province_arr,
                                'language' => 'de',
                                'options' => ['placeholder' => Yii::t('app','Choose Province/City'),'onchange'=>'loadDistrict(this.value); return false;'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label(false);;
                           
                            ?>
                        </td>
                        <td></td>
                       <td><?php echo Yii::t('app', 'District');?></td>
                        <td>
                            <?php
                            if($model->city) {
								$city = $province_arr[$model->city];
							} else $city = '';
                            echo $form->field($model, 'district')->widget(Select2::classname(), [
                                'data' => ListSetup::getItemByList($city),
                                'language' => 'de',
                                'options' => ['placeholder' => Yii::t('app','Select a state ...')],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label(false);;
                           
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Member barcode');?> :</td>
                        <td><?= $form->field($model, 'member_barcode')->textInput(['readonly'=>true])->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Company address');?></td>
                        <td><?= $form->field($model, 'company_address')->textInput()->label(false); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Email');?></td>
                        <td><?= $form->field($model, 'member_email')->textInput(['maxlength' => true])->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Mobile');?></td>
                        <td><?= $form->field($model, 'member_mobile')->textInput()->label(false); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Phone');?></td>
                        <td><?= $form->field($model, 'member_phone')->textInput()->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Source');?></td>
                        <td><?= $form->field($model, 'member_profession')->dropDownList(ListSetup::getItemByList('source'))->label(false) ?></td>
                    </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Company name');?></td>
                        <td><?= $form->field($model, 'company_name')->textInput()->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Company tax code');?></td>
                        <td><?= $form->field($model, 'company_tax_code')->textInput()->label(false); ?></td>

                    </tr>
                    <?php if(!$model->isMemberShip($model->member_id)){ ?>
                        <tr>
                            <td><?php echo Yii::t('app', 'Guest code');?> :</td>
                            <td><?= $form->field($model, 'guest_code')->textInput(['readonly'=>true])->label(false); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($modelUser){?>
                        <tr class="nav-title">
                            <td colspan="6"><div style="padding: 5px; font-size: 16px; font-weight: 500;"><?php echo Yii::t('app', 'Login Account'); ?></div></td>
                        </tr>
                    <tr>
                        <td><?php echo Yii::t('app', 'Username');?></td>
                        <td><?= $form->field($modelUser, 'username')->textInput()->label(false); ?></td>
                        <td></td>
                        <td><?php echo Yii::t('app', 'Password');?></td>
                        <td><?php
                        $modelUser->password = $modelUser->user_password;
                        echo $form->field($modelUser, 'password')->textInput()->label(false); ?></td>

                    </tr>
                    <?php } ?>
                </table>
            <div class="parkclub-footer" style="text-align: center">
                <?php if($canUpdate){ ?>
                    <button class="btn btn-success"><?php echo Yii::t('app', 'Save');?></button>
                <?php }?>
                <?php echo '<a href='.Yii::$app->urlManager->createUrl('members/default/profile_pdf?id='.$model->member_id).' <button class="btn btn-success">'.Yii::t('app', 'Print PDF').'</button> </a>';?>
            </div>
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Note');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
            <?php 
                echo $this->render('member_note',['member_id'=>$member_id,]); 
            ?>
            </div>
            <div class="parkclub-footer">
                <?php if($canAddNotes) { ?>
                <button onclick="addNote();" type="button" class="btn btn-primary" ><?php echo Yii::t('app', 'Add note');?></button>
                <button onclick="saveNote();" type="button" class="btn btn-primary" ><?php echo Yii::t('app', 'Save note');?></button>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Membership');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                <table>
                        <tr>
                            <th width="30%"><?php echo Yii::t('app', 'Type');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Code');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Since');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Expiry');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Status');?></th>
                            <th width="15%"><?php echo Yii::t('app', 'Invoice number');?></th>
                            <th><?php echo Yii::t('app', 'Payment');?></th>
                        </tr>
                    <?php 
                    $invoice = new invoice();
                    $count_memberShip = 0;
                    if($membershipInfo)
                    {
                        $count_memberShip = 1;
                        foreach ($membershipInfo as $data)
                        {
                           $membership_name="";

                           $InfomembershipType = MembershipType::findOne($data->membership_type_id);
                           if($InfomembershipType)
                           {
                               $membership_name=$InfomembershipType->membership_name;

                           }
                           $membership_status=$data->getStatus();
                           $invoice_info =$invoice->getInvoice($data->membership_id, "membership");

                           $status = "";
                           $invoice_no = "";
                           $invoice_url = "";
                           $all_invoice_value="";

                           if($invoice_info)
                           {
                               foreach ($invoice_info as $invoice_item)
                               {
                                    $invoice_id = $invoice_item['invoice_id'];
                                    $invoice_no = $invoice_item['invoice_no'];
                                   $void_class = "";
                                   if($invoice_item->invoice_status==invoice::INVOICE_STATUS_VOID_INVOICE){
                                       $void_class = "void";
                                   }
                                   else{
                                        $status=$invoice->getStatusInvoice($invoice_id);
                                        $status=$invoice->getDisplayInvoiceStatus($status);
                                   }

                                    $invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$invoice_id.'&membership_type='.$data->membership_type_id.'&member_id='.$data->member_id.'&membership_id='.$data->membership_id);
                                    $all_invoice_value.='<div style="width:100%;" class="'.$void_class.'"><a href="'.$invoice_url.'">'.$invoice_no.'</a></div>';
                               }
                           }
                           if($InfomembershipType)
                           {
                            ?>
                                <tr>
                                    <td><?php echo $membership_name;?></td>
                                    <td><a href="<?php echo Yii::$app->urlManager->createUrl('/members/default/addmembership?id='.$model->member_id.'&membership_id='.$data->membership_id)?>"><?php echo $data->membership_code;?></a></td>
                                    <td><?php echo $data->membership_startdate!="0000-00-00"?date('d/m/Y', strtotime($data->membership_startdate)):"";?></td>
                                    <td><?php echo $data->membership_enddate!="0000-00-00"?date('d/m/Y', strtotime($data->membership_enddate)):"";?></td>
                                    <td>
                                    <?php 
                                    echo Yii::t('app', $membership_status);
                                    ?></td>
                                    <td><?php echo $all_invoice_value;?></td>
                                    <td><?php echo Yii::t('app', $status);?></td>
                                </tr>

                            <?php 
                           }

                        }
                    }

                    $invoice_guest = $invoice->getInoviceByGuest($member_id);
                    $invoice_guest_no_all="";
                    if($invoice_guest) { 
                        $status = "";
                        foreach ($invoice_guest as $item_guest){
                            $invoice_id = $item_guest['invoice_id'];
                            $invoice_no = $item_guest['invoice_no'];
                            $void_class = "";
                            if($item_guest->invoice_status==invoice::INVOICE_STATUS_VOID_INVOICE){
                               $void_class = "void";
                            }
                            else{
                                $status=$invoice->getStatusInvoice($invoice_id);
                                $status=$invoice->getDisplayInvoiceStatus($status);
                            }

                            $invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$invoice_id.'&membership_type=&member_id='.$model->member_id.'&membership_id=');
                            $invoice_guest_no_all.='<div style="width:100%;" class="'.$void_class.'"><a href="'.$invoice_url.'">'.$invoice_no.'</a></div>';
                        }
                        ?>
                        <tr>
                            <td><?php echo Yii::t('app', 'Guest'); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $invoice_guest_no_all;?></td>
                            <td><?php echo Yii::t('app', $status);?></td>
                        </tr>
                    <?php } ?>
            </table>
            <div class="parkclub-footer">
                <button onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('/invoice/default/create?membership_type='.$membershipInfo[0]['membership_type_id'].'&membership_id='.$membershipInfo[0]['membership_id'].'&member_id='.$model->member_id);?>'" type="button" class="btn btn-primary" ><?php echo Yii::t('app', 'Add Invoice');?></button>
                    <?php  
                    if($canAddMembership)
                    { ?>
                        <!-- <a  href="<?php echo Yii::$app->urlManager->createUrl('members/default/addmembership?id='.$model->member_id);?>"><input type="button" class="btn btn-success" value="Add Membership" /></a> -->
                        <input type="hidden" name="members_id" id="members_id" value="<?php echo $model->member_id ?>">
                        <input type="hidden" name="addmembership" id="addmembership" value="<?php echo Yii::$app->urlManager->createUrl('members/default/addmembership?id='.$model->member_id);?>">
                        <button onclick="check_confirm_addMembership(<?php echo $count_memberShip; ?>)" type="button" class="btn btn-primary" ><?php echo Yii::t('app','Add Membership');?></button>
                <?php } ?>
            </div>
                
            <table> 
                    <tbody>
                        <tr>
                            <th width="20%"><?php echo Yii::t('app', 'Type');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Code');?></th>
                            <th width="20%"><?php echo Yii::t('app', 'Note');?></th>
                            <th width="12%"><?php echo Yii::t('app', 'Start Date');?></th>
                            <th width="12%"><?php echo Yii::t('app', 'End Date');?></th>
                            <th width="10%"><?php echo Yii::t('app', 'Status');?></th>
                            <th ><?php echo Yii::t('app', 'Invoice');?></th>
                        </tr>
                    
                        <?php 
                            $membershipInfo = $MemberShip->getMemberShip($member_id);
                            // echo "<pre>";
                            // print_r($membershipInfo);
                            // echo "</pre>";
                            if($membershipInfo > 0){
                                foreach($membershipInfo as $result){
                                    $membership_name="";
                                    $InfomembershipType = MembershipType::findOne($result->membership_type_id);
                                    if($InfomembershipType)
                                    {
                                        $membership_name=$InfomembershipType->membership_name;

                                    }
                                    $invoice_info = array();
                                    $invoice_info =$invoice->getInvoice($result->membership_id, "membership");   
                                    $all_invoice_value = "";
                                    if($invoice_info)
                                    {
                                        foreach ($invoice_info as $invoice_item)
                                        {
                                             $invoice_id = $invoice_item['invoice_id'];
                                             $invoice_no = $invoice_item['invoice_no'];
                                            $void_class = "";
                                            if($invoice_item->invoice_status==invoice::INVOICE_STATUS_VOID_INVOICE){
                                                $void_class = "void";
                                            }
                                            else{
                                                 $status=$invoice->getStatusInvoice($invoice_id);
                                                 $status=$invoice->getDisplayInvoiceStatus($status);
                                            }

                                             $invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$invoice_id.'&membership_type='.$result->membership_type_id.'&member_id='.$result->member_id.'&membership_id='.$result->membership_id);
                                             $all_invoice_value.='<div style="width:100%;" class="'.$void_class.'"><a href="'.$invoice_url.'">'.$invoice_no.'</a></div>';
                                        }
                                    }
                                    
                                    if($result->getStatus() != "Active"){
                                        $url = Yii::$app->urlManager->createUrl('/members/default/addmembership?id='.$model->member_id.'&membership_id='.$result->membership_id);
                                        $start_date = $result->membership_startdate!="0000-00-00"?date('d/m/Y', strtotime($result->membership_startdate)):"";
                                        $end_date = $result->membership_enddate!="0000-00-00"?date('d/m/Y', strtotime($result->membership_enddate)):"";
                                        echo "
                                            <tr>
                                                <td>".$membership_name."</td>
                                                <td ><a href='".$url."' target='_blank'>".$result->membership_code."</a></td>
                                                <td></td>
                                                <td>".$start_date."</td>
                                                <td>".$end_date."</td>
                                                <td>".$result->getStatus()."</td>
                                                <td>".$all_invoice_value."</td>
                                            </tr>
                                        ";
                                    }
                                     else if( $result->membership_status == "Active" &&  strtotime($result->membership_enddate) < strtotime(date('d/m/Y'))){
                                        echo $result->membership_status;
                                        $url = Yii::$app->urlManager->createUrl('/members/default/addmembership?id='.$model->member_id.'&membership_id='.$result->membership_id);
                                        $start_date = $result->membership_startdate!="0000-00-00"?date('d/m/Y', strtotime($result->membership_startdate)):"";
                                        $end_date = $result->membership_enddate!="0000-00-00"?date('d/m/Y', strtotime($result->membership_enddate)):"";
                                        echo "
                                            <tr>
                                                <td>".$membership_name."</td>
                                                <td ><a href='".$url."' target='_blank'>".$result->membership_code."</a></td>
                                                <td></td>
                                                <td>".$start_date."</td>
                                                <td>".$end_date."</td>
                                                <td>". $result->getStatus()."</td>
                                                <td>".$all_invoice_value."</td>
                                            </tr>
                                        ";
                                    }
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='7'>".Yii::t('app', 'No result')."</td>
                                    </tr>";
                            }
                         ?>
                    </tbody>
                </table>
                <div class="parkclub-footer"></div>
            </div>
        </div>
    </div>
    <?php if($model->member_barcode!="") { ?>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                <?php echo Yii::t('app', 'Training');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                <table> 
                    <tr>
                            <th style="padding-left: 5px;"><?php echo Yii::t('app', 'Package');?></th>
                            <th><?php echo Yii::t('app','Trainer');?></th>
                            <th style="padding-left: 5px;"><?php echo Yii::t('app', 'Start date');?></th>
                            <th><?php echo Yii::t('app','End date');?></th>
                            <th align="center"><?php echo Yii::t('app', 'Total SS');?></th>
                            <th align="center"><?php echo Yii::t('app', 'Used SS');?></th>
                            <th align="center"><?php echo Yii::t('app', 'Remaining SS');?></th>
                            <th><?php echo Yii::t('app', 'Status invoice');?></th>
                            <th><?php echo Yii::t('app', 'Status');?></th>
                            <th></th>
                            <th><?php echo Yii::t('app', 'Invoice');?></th>
                            <th></th>
                            <th></th>
                    </tr>
                        <?php 
                        if($training)
                        {
                            $revenue = new app\modules\revenue_type\models\RevenueItem();
                            $tranning_parckage = $revenue->getRevenueItemByEntry(2,'array','index');
                            foreach ($training->models as $data)
                            {
                                $arr_training=array();
                                $name = "";
                                $url='';
                                $re_ss = $data->getRemainingSession($data->member_training_id);
                                $user_ss = $data->training_total_sessions - $re_ss;
                                if($memberTrainer->getTrainer($data->member_training_id))
                                {
                                    
                                    $arr_training=$memberTrainer->getTrainer($data->member_training_id);
                                    foreach ($arr_training as $training_value)
                                    {
                                        $name.= Html::a($member->getMemberFullName($training_value->trainer_user_id),['/trainer/default/update','id'=>$training_value->trainer_user_id]).'<br/>';
//                                       $name.=$training_value->username.'<br/>';
                                    }
                                    $url=Yii::$app->urlManager->createUrl('/training/default/update?id='.$data->member_training_id.'&member_id='.$_GET['id'].'&training_id='.$data->member_training_id);

                                }
                                $invoice_tranning = $invoice->getInvoiceOneByEntry($data->member_training_id, 'Trainer');
                                $invoice_no = "";
                                if($invoice_tranning){
                                    $invoice_url = Yii::$app->urlManager->createUrl('/invoice/default/update?id='.$invoice_tranning->invoice_id.'&training_id='.$data->member_training_id);
                                    $invoice_no = '<a href="'.$invoice_url.'">'.$invoice_tranning->invoice_no.'</a>';
                                }
                                ?>
                                    <tr>
                                        <td><?php echo (isset($tranning_parckage[$data->package_id]) ? $tranning_parckage[$data->package_id] : "") ;?></td>
                                        <td><?php echo $name;?></td>
                                        <td><?php echo $ListSetup->getDisplayDate($data->training_start_date);?></td>
                                        <td><?php echo $ListSetup->getDisplayDate($data->training_end_date);?></td>
                                        <td align="center"><?php echo $data->training_total_sessions;?></td>
                                        <td align="center"><?php echo $user_ss;?></td>
                                        <td align="center"><?php echo $re_ss;?></td>
                                        <td style ="padding-left: 10px;"><?php echo Yii::t('app', $data->getStatus($data->member_training_id));?></td>
                                        <td style ="padding-left: 10px;"><?php echo Yii::t('app', $data->member_training_status);?></td>
                                        <td><a href="<?php echo $url;?>"><?php echo Yii::t('app', 'Edit'); ?></a></td>
                                        <td><?php echo $invoice_no; ?></td>
                                        <td>
                                            <?php if($data->getStatus($data->member_training_id)== Members::STATUS_EXPIRED_MEMBERS_TRAINING){ ?>
                                            <a href="#" onclick="renew(<?php echo $data->member_training_id; ?>); return false;">Renew</a>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($data->training_note!=""){ ?>
                                            <a href="#" onclick="return false;" title="<?php echo Yii::t('app', 'Change history trainer');?>" data-toggle="popover" data-placement="top" data-content='<?php echo $data->training_note; ?>'><i class="glyphicon glyphicon-time"></i></a>
                                            <?php } ?>
                                        </td>

                                    </tr>

                                <?php 

                            }
                        } 
                        ?>
                        </table>
            </div> 
            <div class="parkclub-footer">
                <?php if($canAddTraining){ ?>
                    <button onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('training/default/create?member_id='.$model->member_id);?>'" type="button" class="btn btn-primary" ><?php echo Yii::t('app', 'Add Training');?></button>
                <?php } ?> 
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($model->parent_account<=0 && $model->isMemberShip($model->member_id)){ ?>
    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Sub-account');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                <table> 
                    <tr>
                        <th><?php echo Yii::t('app', 'Name');?>    </th>
                        <th><?php echo Yii::t('app', 'Birthday');?></th>
                        <th><?php echo Yii::t('app', 'Mobile');?></th>
                    </tr>
                    <?php 
                    if($arr_member)
                    {
                        foreach ($arr_member as $data)
                        {
                            ?>
                                <tr>
                                    <td><?php echo "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$data->member_id)."'>".$member->getMemberFullName($data->member_id)."</a>" ; ?></td>
                                    <td><?php echo (($data->member_birthday!="") ? date('d/m/Y', strtotime($data->member_birthday)) : "");?></td>
                                    <td><?php echo $data->member_mobile;?></td>
                                </tr>
                            <?php 
                        }
                    } 
                    ?>
                </table>
            </div> 
            <div class="parkclub-footer">
                <?php if($canAdd){ ?>
                    <button onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('members/default/create?id='.$model->member_id);?>'" type="button" class="btn btn-primary" ><?php echo Yii::t('app', 'Add Sub-account');?></button>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                <?php echo Yii::t('app', 'Checkin - out');?>
                </div>
            </div>
<div class="parkclub-rectangle-content" >
                
                
                    <?php \yii\widgets\Pjax::begin(); ?>
                    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{summary}\n{items}\n{pager}",
        'showFooter'=>TRUE,
        'footerRowOptions'=>[
            'style'=>'font-weight:bold;',
            
            ],
        'columns' => [
            
            [
                'attribute' => 'membership_type_id',
                'format' => 'html',
                'header' => Yii::t('app','MemberShip'),
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
                    $checkin_time = ($model->checkin_time != "0000-00-00 00:00:00")?date('d/m/Y h:i:s',  strtotime($model->checkin_time)):"";
                    return $checkin_time;
                }
            ],
            [
                'attribute' => 'member_id',
                'format' => 'html',
                'header' => Yii::t('app','Checkout time'),
                'value' => function($model) {
                    $checkcout_time = ($model->checkcout_time != "0000-00-00 00:00:00")?date('d/m/Y h:i:s',  strtotime($model->checkcout_time)):"";
                    return $checkcout_time;
                }
            ],

                    ],
    ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>         


            </div> 
            <div class="parkclub-footer"></div>
        </div>
    </div>

    <div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                <?php echo Yii::t('app', 'Payment');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content">
                    <table> 
                        <tr>      
                            <th><?php echo Yii::t('app', 'Payment number');?></th>
                            <th><?php echo Yii::t('app', 'Invoice number');?></th>
                            <th><?php echo Yii::t('app', 'Payment date');?></th>
                            <th><?php echo Yii::t('app', 'Payment amount');?></th>
                        </tr>
                        <?php 
                        $payment_Manage = new \app\modules\invoice\models\Payment();
                        $payment_arr = $payment_Manage->getPayment($member_id,false,false,true);
                        $user_created= Yii::$app->user->identity->username;
                        if($payment_arr)
                        {
                            foreach ($payment_arr as $data)
                            {
                                $invoice = invoice::findOne($data->invoice_id);
                                $invoice_no = $invoice['invoice_no'];
                                $url = $invoice_url=Yii::$app->urlManager->createUrl('invoice/default/update?id='.$data->invoice_id);
                                $invoice_no_url='<div><a href="'.$url.'">'.$invoice_no.'</a></div>';
                                ?>
                                    <tr>
                                        <td><?php echo $data->payment_no; ?></td>
                                        <td><?php echo $invoice_no_url;?></td>
                                        <td><?php echo $data->payment_date;?></td>
                                        <td><?php echo $ListSetup->getDisplayPrice($data->payment_amount); ?></td>     
                                    </tr>

                        <?php }}  ?>
                    </table>
            </div> 
            <div class="parkclub-footer"></div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<script>

    $(document).ready(function(){
        
        var p = $('[data-toggle="popover"]').popover({
            html : true,
        });
        p.on("show.bs.popover", function(e){
            p.data("bs.popover").tip().css({"max-width": "600px","width":"500px"});
        }); 
        var is_member = '<?php echo $model->membership_code ;?>';

        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var istour = '<?php echo Yii::$app->session['tour']; ?>';
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        
        if(is_member=="" && (((intall_data==2 || intall_data==1) && istour==1) || tour_step=='<?php echo app\models\Config::TOUR_MEMBER; ?>')){
            $('#bs-model-checkin-endtour').modal('show');
        }
    });
    var count=0;
	var edited_note_ids = new Array();
	var edited_notes = new Array();
    var arr_note_date=new Array();
    var arr_note=new Array();
    $('select').removeClass('form-control');
    function search_member()
    {
        var search_member=$('#search_member').val();
        alert(search_member);
        $(".members-index").load("searchmember",{search_member:search_member});
    }
    function addNote()
    {
        var tr;
        tr+='<tr id="delete_note_tr'+count+'">';
        tr+='<td>';
        tr+='<?php echo date('Y-m-d H:i:s');?>';
        tr+='<input type="text" class="disable" readonly="true" value="<?php echo date('Y-m-d H:i:s');?>" id="note_date_'+count+'"/>';
        tr+='</td>';
        tr+='<td>';
        tr+='<textarea type="text" value="" id="note_'+count+'"/></textare>';
        tr+='</td>';
        tr+='<td><?php echo $user_created;?>';
        tr+='</td>';
        tr+='<td>';
        tr+='<span class="glyphicon glyphicon-trash" onclick="delete_note_tr('+count+')" style="cursor:pointer;"></span>';
        tr+='</td>';
        
        tr+='</tr>';
        $('#table-note').append(tr);
//        $('#note_date_'+count).datetimepicker();
        count++;
        
    }
    function delete_note_tr(i)
    {
        $('#delete_note_tr'+i).remove();
        
    }
    
    function saveNote()
    {
        var value ;
        var value_note ;
        for(var i=0; i<count;i++)
        {
            if($("#note_date_"+i).length)
            {
            value = $("#note_date_"+i).val();
            value_note = $("#note_"+i).val();
            arr_note_date.push(value);
            arr_note.push(value_note);
            }
        }
        
		if(count) {
			$.ajax({
					url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/addnote');?>",
					data: {arr_note_date:arr_note_date,arr_note:arr_note,member_id:<?php echo $member_id;?>},
					type:"POST",
					success: function (data) {
						$('#table-note').load("<?php echo Yii::$app->urlManager->createUrl('/members/default/listnote')?>",{member_id:'<?php echo $member_id;?>'});
						count=0;
						arr_note_date=new Array();
						arr_note=new Array();
					}
			});
		}
		if(edited_note_ids.length) {
			$.blockUI();
			$.ajax({
					url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/editnote');?>",
					data: {edited_note_ids:edited_note_ids,edited_notes:edited_notes},
					type:"POST",
					success: function (data) {
						$('#table-note').load("<?php echo Yii::$app->urlManager->createUrl('/members/default/listnote')?>",{member_id:'<?php echo $member_id;?>'});
						$.unblockUI();
						edited_note_ids=new Array();
						edited_notes=new Array();
					}
			});
		}
    }
    
    function delete_note(note_id)
    {
         $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/deletenote');?>",
                data: {note_id:note_id},
                type:"POST",
                success: function (data) {
                    $('#note_member'+note_id).remove();
                }
        });
    }
    function change_resident(resident)
    {
//        alert(resident);
        if(resident == 1)
        {
            $('.member_Resident').show();
        }
        else
        {
            $('#members-unit').val("");
            $('.member_Resident').hide();
        }
    }

    function popScanWebcome(member_id){
        
        $('.modal-content').css({'min-height':'400px'});
        $('#modal-content-welcome').load('<?php echo Yii::$app->urlManager->createUrl('/members/default/webcomescan'); ?>',{member_id:member_id},
            function(data){
                $('#take_picture').show();
                $('#view_picture').hide(); 
            });
    }
    function check_confirm_addMembership(count_membership){
        var members_id = $("#members_id").val();
        var addmembership = $("#addmembership").val();
        if(count_membership==1){
            if (confirm('<?php echo Yii::t('app','Since you create a new membership, the current membership will be deactivated and its information will be saved in the note . Are you sure ?') ?>')) {                window.location.href = addmembership;
            }
        }
        else{
            window.location.href = addmembership;
            return false;
        }
    }
    function renew(training_id){
         $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/trainer_booking/default/renew-training');?>",
                data: {training_id:training_id},
                type:"POST",
                success: function (data) {
                    alert('Training course is successfully extended.');
                    location.reload();
                }
        });
    }
    
    function updateNote(note_id,id_check){
//        var show_checkin = 0;
//        if($('#'+id_check).is(':checked'))
//            varshow_checkin = id_check;
         $.ajax({
                url: "<?php echo Yii::$app->urlManager->createUrl('/members/default/updatenote');?>",
                data: {note_id:note_id,show_checkin:id_check},
                beforeSend:function(){
                    $.blockUI();
                },
                type:"POST",
                success: function (data) {
                    $('#table-note').load("<?php echo Yii::$app->urlManager->createUrl('/members/default/listnote')?>",{member_id:'<?php echo $member_id;?>'});
                    $.unblockUI();
                }
        });
    }
    
    function loadDistrict(city){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl("/members/default/load-district")?>',
            data:{city:city},
            success:function(data){
                $('#members-district').html(data);
            }
        });
    }
	
	function editNote(id){
		var note = $.trim($("#note-"+id).val());
		edited_note_ids.push(id);
		edited_notes.push(note);
	}
</script>