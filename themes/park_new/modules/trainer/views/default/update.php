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
use app\modules\training\models\MemberTrainingsSearch;
use app\modules\training\models\MemberTrainers;
use kartik\editable\Editable;
use kartik\select2\Select2;

$revenue = new \app\modules\revenue_type\models\RevenueItem();
$package_arr = $revenue->getRevenueItemByEntry(2,'array','index');

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

$ListSetup = new ListSetup();
$user = new app\models\User();
$book = new app\modules\booking\models\Booking();
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div style="text-align: center;">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['trainer/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $model->getMemberFullName(); ?>
                </div>
                <div class="parkclub-header-right">
                    <button class="right-button" type="button" onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('/trainer/default/create'); ?>'"><?php echo Yii::t('app', 'ADD TRAINER');?></button>
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
                            echo '<img id="images_member" style="margin: 10px 0 10px 0;" width="200px" src="'.$model->member_picture.'">';
                        }
                        else
                        {
                        ?>
                        <img id="images_member" style="margin: 10px 0 10px 0;" width="200px" src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/unknown.png">
                        <?php } ?>
                        <?php if($canUpdate){ ?>
                            <?php //$form->field($model, 'member_picture')->fileInput(['style'=>'margin-left: 455px;background-image: url("/parkcity/image/image-hv.png");'])->label(false); ?>
                        <h4><button class="parkclub-button" type="button" onclick="popScanWebcome(<?php echo $model->member_id; ?>);"><?php echo Yii::t('app', 'Take a picture / Upload file photo');?></button></h4>
                    <?php } ?>
                </div>
                <!-- END MODAL WELCOME --> 
        </div>
        
        <table class="table parkclub-table" style="text-align: left;margin-bottom: 0;">
            <tr>
                <td><?php echo Yii::t('app','Trainer code'); ?>:</td>
                <td><?php echo $model->trainer_code; ?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app','First name'); ?>:</td>
                <td><?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label(false); ?></td>
                <td><?php echo Yii::t('app','Surname'); ?>:</td>
                <td><?= $form->field($model, 'surname')->textInput(['maxlength' => true])->label(false); ?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app','Given name'); ?>:</td>
                <td><?= $form->field($model, 'given_name')->textInput(['maxlength' => true])->label(false); ?></td>
                <td><?php echo Yii::t('app','Address'); ?>:</td>
                <td><?= $form->field($model, 'member_address')->textarea(['rows' => 2])->label(false); ?></td>
            </tr>

            <tr>
                <td><?php echo Yii::t('app','Mobile'); ?>:</td>
                <td><?= $form->field($model, 'member_mobile')->textInput()->label(false); ?></td>
                <td><?php echo Yii::t('app','Phone'); ?>:</td>
                <td><?= $form->field($model, 'member_phone')->textInput()->label(false); ?>
                </td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app','Email'); ?>:</td>
                <td><?= $form->field($model, 'member_email')->textInput(['maxlength' => true])->label(false); ?>
                </td>
                <td><?php echo Yii::t('app','Note'); ?>:</td>
                <td><?= $form->field($model, 'member_note')->textarea()->label(false); ?>
                </td>
            </tr>
            <tr>
                 <td><?php echo Yii::t('app','Position'); ?>:</td>
                 <td>
                 <?= $form->field($model, 'position')->dropDownList(ListSetup::getItemByList('position'))->label(false); ?>
                 </td>
                 <td></td>
                 <td></td>
            </tr>
        </table>
        
        <div class="parkclub-footer" style="text-align: center">
            <button class="btn btn-success"><?php echo Yii::t('app','Save'); ?></button>
<!--            <button class="btn btn-primary" style="margin-left: 10px"><?php echo Yii::t('app','Print PDF'); ?></button>-->
        </div>
    </div>
</div>

<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-newm-left-title">
            <div class="parkclub-header-left">
                <?php echo Yii::t('app', 'Member Infomation');?>
            </div>
        </div>
        <div class="parkclub-rectangle-content">
            <table > 
                <tr style="line-height: 2.0;">
                    <th style=""><?php echo Yii::t('app','Created Date'); ?></th>
                    <th style="padding-left: 5px;"><?php echo Yii::t('app','Member name'); ?></th>
                    <th style=""><?php echo Yii::t('app','Member code'); ?></th>
                    <th style=""><?php echo Yii::t('app','Package'); ?></th>
                    <th style=""><?php echo Yii::t('app','Start date'); ?></th>
                    <th style=""><?php echo Yii::t('app','End date'); ?></th>
                    <th style=""><?php echo Yii::t('app','Total SS'); ?></th>
                    <th style=""><?php echo Yii::t('app','Remaining SS'); ?></th>
                </tr>
                <?php foreach ($membertrainer as $item) { 
                    $member = Members::findOne($item->memberTrainings->member_id);
                    ?>
                    <tr>
                        <td><?php echo $item->memberTrainings->create_date ?></td>
                        <td><?php echo "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$member->member_id)."'>".$member->getMemberFullName($member->member_id)."</a>" ;?></td>
                        <td><?php echo $member->member_barcode; ?></td>
                        <td style=" padding-left: 10px;"><?php echo (isset($package_arr[$item->memberTrainings->package_id]) ? $package_arr[$item->memberTrainings->package_id] : ""); ?></td>
                        <td><?php echo $item->memberTrainings->training_start_date ?></td>
                        <td><?php echo $item->memberTrainings->training_end_date ?></td>
                        <td align="center"><?php echo $item->memberTrainings->training_total_sessions ?></td> 
                        <td align="center"><?php echo $book->getRemainingSession($item->memberTrainings->member_training_id) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="parkclub-footer"></div>
    </div>
</div>


<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-newm-left-title">
            <div class="parkclub-header-left">
                <?php echo Yii::t('app','PT Booking History'); ?>
            </div>
        </div>
        <div class="parkclub-rectangle-content">
            <table > 
                        <tr style="line-height: 2.0;">
                            <th style=""><?php echo Yii::t('app','Package'); ?></th>
                            <th style=""><?php echo Yii::t('app','Member'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Book date'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Book time'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Note'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Created by'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Trainer Checkin'); ?></th>
                            <th style="" align="center"><?php echo Yii::t('app','Member Checkin'); ?></th>
                            <th style=""><?php echo Yii::t('app','Witness'); ?></th>
                        </tr>
                        <?php foreach ($booking_trainer->models as $item) {
                            $tranier_package = app\modules\training\models\MemberTrainings::findOne($item->traning_id);
                            if($tranier_package){
                                $member = Members::findOne($tranier_package->member_id);
                        ?>
                            <tr>
                                <td><?php echo (isset($package_arr[$tranier_package->package_id]) ? $package_arr[$tranier_package->package_id] : ""); ?></td>
                                <td><?php echo $member->getMemberFullName(); ?></td>
                                <td align="center">
                                    <?php echo $ListSetup->getDisplayDate($item->book_date); ?>
                                </td>
                                <td align="center"><?php echo $item->book_startdate.' - '.$item->book_enddate; ?></td>
                                <td align="center"><?php echo $item->book_notes ?></td>
								<td align="center"><?php echo $user->getFullName($item->book_createby);?></td>
                                <td align="center" id="pt_checkin_<?php echo $item->book_id; ?>"><?php 
                                    if($item->book_trainer_checkin=="0000-00-00 00:00:00")
                                        echo Html::button('Checkin', ['onclick'=>'PTCheckin('.$item->book_id.');','class'=>'btn btn-primary']);
                                    else
                                        echo $ListSetup->getDisplayDateTime($item->book_trainer_checkin);
                                ?></td>
                                <td align="center" id="member_checkin_<?php echo $item->book_id; ?>"><?php 
                                    if($item->book_member_checkin=="0000-00-00 00:00:00")
                                        echo Html::button('Checkin', ['onclick'=>'memberCheckin('.$item->book_id.');','class'=>'btn btn-primary']);
                                    else
                                        echo $ListSetup->getDisplayDateTime($item->book_member_checkin);
                                ?></td>
                                <td id="witness_check_<?php echo $item->book_id; ?>"><?php 
                                    if($item->book_witness_check==0)
                                        echo Html::button(Yii::t ('app','Confirm'), ['onclick'=>'witnessCheckin('.$item->book_id.');','class'=>'btn btn-primary']);
                                    else
                                        echo $user->getFullName($item->book_witness_check);
                                ?></td>
                            </tr>     
                        <?php }} ?>
                    </table>
        </div>
        <div class="parkclub-footer"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    $( document ).ready(function() {
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        if(tour_step=='<?php echo app\models\Config::TOUR_TRAINER; ?>')
        {
            $('#bs-model-checkin-endtour').modal('show');
        }
    });
    function popScanWebcome(member_id){
        
        $('.modal-content').css({'min-height':'400px'});
        $('#modal-content-welcome').load('<?php echo Yii::$app->urlManager->createUrl('/members/default/webcomescan'); ?>',{member_id:member_id},
            function(data){
                $('#take_picture').show();
                $('#view_picture').hide(); 
            });
    }
    function PTCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/checkin');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success')
                    $('#pt_checkin_'+book_id).html(data.time);
                else
                    alert('Error checkin');
            }
        });
    }
    function witnessCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/witness-check');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    //$('#witness_check_'+book_id).html(data.name);
                    location.reload();
                }
                else
                    alert('Error checkin');
            }
        });
    }
    function memberCheckin(book_id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/trainer_booking/default/member-checkin');  ?>',
            'data':{book_id:book_id},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success')
                    location.reload();
                else
                    alert('Error checkin');
            }
        });
    }
    
</script>