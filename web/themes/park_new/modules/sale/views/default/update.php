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
<style>
.div{
    padding:5px 10px;
    background:#32cd8b;
    border: 0 none;
    position:relative;
    color:#fff;
    border-radius:2px;
    text-align:center;
    float:left;
    cursor:pointer;
    margin-left: 370px;
    font-size:14px;
    font-weight: 400;
    padding: 10px 30px;
}
.hide_file {
    position: absolute;
    z-index: 1000;
    opacity: 0;
    cursor: pointer;
    right: 0;
    top: 0;
    height: 100%;
    font-size: 24px;
    width: 100%;
    border-radius: 8px;
}
</style>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div style="text-align: center;">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo YII::$app->urlManager->createUrl(['sale/default/index']); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo $model->getMemberFullName(); ?>
                </div>
                <div class="parkclub-header-right">
                    <button class="right-button" type="button" onclick="location.href='<?php echo Yii::$app->urlManager->createUrl('/sale/default/create'); ?>'"><?php echo Yii::t('app', 'ADD SALES');?></button>
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
                        <h4><button style="border-radius: 2px; margin-right: 350px;"class="parkclub-button" type="button" onclick="popScanWebcome(<?php echo $model->member_id; ?>);"><?php echo Yii::t('app', 'Take a picture');?></button>
                            <div class="div">
                                <?php echo Yii::t('app','Choose a file'); ?>
                                <input type="file" class="hide_file" name = "avatar">
                            </div>
                        </h4>
                    <?php } ?>
                </div>
                <!-- END MODAL WELCOME --> 
        </div>
        
        <table class="table parkclub-table" style="text-align: left;margin-bottom: 0;">
            <tr>
                <td><?php echo Yii::t('app','Sales code'); ?>:</td>
                <td><?php echo $model->sale_code; ?></td>
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
            
        </table>
        
        <div class="parkclub-footer" style="text-align: center">
            <button class="btn btn-success"><?php echo Yii::t('app','Save'); ?></button>
<!--            <button class="btn btn-primary" style="margin-left: 10px"><?php echo Yii::t('app','Print PDF'); ?></button>-->
        </div>
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