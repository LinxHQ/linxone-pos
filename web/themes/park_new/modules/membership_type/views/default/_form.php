<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ListSetup;
use app\modules\membership_type\models\MembershipPrice;

/* @var $this yii\web\View */
/* @var $model app\modules\membership_type\models\MembershipType */
/* @var $form yii\widgets\ActiveForm */

$ListSetup = new ListSetup();
$arr_membershipType= ListSetup::getItemByList('membership_type');
$membershipPrice = new app\modules\membership_type\models\MembershipPrice();
$info_price_date =$membershipPrice->getPriceByMembershipType($model->membership_type_id);
$membership_type_id = $model->membership_type_id;

?>

<?php $form = ActiveForm::begin(); ?>
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo Yii::$app->urlManager->createUrl('membership_type/default/index'); ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo $label; ?>
                    </div>
                </div>
               <div class="parkclub-newm " id="form_membership_type">
                    <fieldset >
                        <label for=""><?php echo Yii::t('app', 'Full name'); ?></label>
                        <?= $form->field($model, 'membership_name')->textInput(['maxlength' => true])->label(false); ?>

                        <label for=""><?php echo Yii::t('app', 'Description'); ?></label>
                        <?= $form->field($model, 'membership_description')->textarea(['rows' => 6])->label(false); ?>
                        
                        <label for=""><?php echo Yii::t('app', 'Months'); ?></label>
                        <?= $form->field($model, 'membership_type_month')->dropDownList(ListSetup::getItemByList('memberShipType_status'))->label(false) ?>
                        
                        <label for=""><?php echo Yii::t('app', 'Limit the number of sessions'); ?></label>
                        <?= $form->field($model, 'membership_type_limit_number_of_session')->textInput(['maxlength' => true])->label(false); ?>

                        <label for=""><?php echo Yii::t('app', 'Active'); ?></label>
                        <?= $form->field($model, 'membership_status')->dropDownList($model->getStatusDropdow())->label(false) ?>
                        
                    </fieldset>
               </div>

               <div class="parkclub-footer" style="text-align: center">
                   <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success','id'=>'tour-create-membership-type']) ?>
               </div>
           </div>
        </div>
    </div>
<?php if($model->membership_type_id)
{
   ?>
    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <?php echo Yii::t('app', 'Price'); ?>
                    </div>
                </div>
               <div class="parkclub-rectangle-content">
                        <table>
                            <tr>
                                <th>#</th>
                                <th><?php echo Yii::t('app','Price'); ?></th>
                                <th><?php echo Yii::t('app','Start date'); ?></th>
                                <th><?php echo Yii::t('app','End date'); ?></th>
                                <th></th>
                                <th></th>
                            </tr>
                            
                                <?php 
                                $i=1;
                                if($info_price_date)
                                {
                                    foreach ( $info_price_date as $info_price)
                                    {
                                    ?>
                                    <tr id="price<?php echo $info_price['membership_price_id']; ?>">
                                                <td><?php echo $i;?></td>
                                                <td><?php echo $info_price['membership_price'];?></td>
                                                <td><?php echo date('d/m/Y ',  strtotime($info_price['price_start_date']));?></td>
                                                <td><?php echo  ($info_price['price_end_date'] !="0000-00-00")?date('d/m/Y ',  strtotime($info_price['price_end_date'])):"";?></td>

                                                <td><a href="<?php echo Yii::$app->urlManager->createUrl("/membership_type/default/updateprice?id=".$info_price['membership_price_id']); ?>" ><i class="glyphicon glyphicon-pencil" style="width: 20px"></i></a></td>
                                                <td>
                                                    <span style="cursor: pointer" class="glyphicon glyphicon-trash" onclick="delete_price(<?php echo $info_price['membership_price_id'];?>)"></span>
                                                </td>
                                        </tr>
                                    <?php 
                                    $i++;
                                    }
                                } ?>
                                

                            </table>
               </div>

               <div class="parkclub-footer">
                   <?= Html::a(Yii::t('app', 'Add Price'),Yii::$app->urlManager->createUrl('membership_type/default/addprice/?id='.$model->membership_type_id), ['class' =>'btn btn-primary']) ?>
               </div>
           </div>
        </div>
    </div>
<?php } ?>
    <?php ActiveForm::end(); ?>

<script>
    $( document ).ready(function() {
        var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
        var tour = '<?php echo Yii::$app->session['tour']; ?>';
        var tour_step = '<?php echo Yii::$app->session['tour-step']; ?>';
        var membership_id = '<?php echo $model->membership_type_id; ?>';
        if(intall_data==2 && tour==1){
            $('#membershiptype-membership_name').val('Invition');
            $('#membershiptype-membership_type_month').val('1');
            tour_no_demo.restart();
            tour_no_demo.start();
            if(membership_id=="")
                tour_no_demo.goTo(2);
            else
                tour_no_demo.goTo(6);
        }
        if(tour_step=='<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>')
        {
            $('#membershiptype-membership_name').val('Invition');
            $('#membershiptype-membership_type_month').val('1');
            tour_membership_type.restart();
            tour_membership_type.start();
            tour_membership_type.goTo(3);
            if(membership_id!=""){
                tour_membership_type.end();
                $('#bs-model-checkin-endtour').modal('show');
            }
        }
    });
    function delete_price(price_id)
    {
        var membership_type_id = $('#membership_type_id').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/membership_type/default/deleteprice');  ?>',
            'data':{id:price_id,membership_type_id:membership_type_id},
            success:function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status == "Fail")
                {
                    alert(responseJSON.message);
                }
                else
                {
                    $('#price'+price_id).remove();
                }
            }
        });
    }
</script>
