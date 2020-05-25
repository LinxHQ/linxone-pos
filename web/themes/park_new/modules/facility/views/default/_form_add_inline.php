<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\modules\membership_type;
use kartik\date\DatePicker;
$ListSetup = new app\models\ListSetup();
$drop_tax = $ListSetup->getSelectOptionList('Tax',false,false,"onchange='updatePrice(".$_POST['count'].")'",false,'price_tax','form-control select-none');

$MembershipType = new app\modules\membership_type\models\MembershipType();
$membershipTypeDropdow = $MembershipType->getDropDown(false,false,'All');
$dropdow='<select class="form-control select-none" name="membership_type_id" id="membership_type_id">';
foreach ($membershipTypeDropdow as $key=>$value)
{
    $dropdow.='<option value='.$key.'>'.$value.'</option>';
}
$dropdow.='</select>';
?>
<table style="text-align: left; margin-top: 0;" id='price_<?php echo $_POST['count']; ?>'>
<tr >
        <td><?php echo $_POST['count']; ?></td>
        <td style="width:18%"><?php echo $dropdow; ?></td>
        <td style='width:10%'><input style='width:100%' type='text' value='' id='facility_price_name' ></td>
        <td style='width:10%' ><?php echo $drop_tax; ?></td>
        <td style='width:10%' ><input id='facility_price'  style='width:100%'  type='text' value='0' onkeyup='calculatePriceAfter(<?php echo $_POST['count']; ?>,this.value);' ></td>
        <td style='width:10%' ><input id='facility_price_after' style='width:100%'  type='text' value='0' onkeyup='calculatePrice(<?php echo $_POST['count']; ?>,this.value);' ></td>
        <td style="width:16%">
        <?php 
            echo DatePicker::widget([
                'name' => 'facility_startdate[]',
                'id' => 'facility_startdate_'.$_POST['count'],
                'value' => date('Y-m-d'),
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'options' => ['placeholder' => 'End date ...'],
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',

                ]
            ]);
        ?>
        </td>
        <td style="width:16%">
        <?php 
            echo DatePicker::widget([
                'name' => 'faclity_enddate[]',
                'id' => 'faclity_enddate_'.$_POST['count'],
                'options' => ['placeholder' => 'End date ...'],
    //            'value' => '',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
        ?>
        </td>
        <td style="width:15%">
            <button type="button" id="bnt-save-price" onclick="savePrice(<?php echo $_POST['count']; ?>);return false;" class="btn btn-success btn-small"><?php echo Yii::t('app', 'Save');?></button>
            <span style="cursor: pointer; font-size:18px; color:#a94442" onclick="removePrice(<?php echo $_POST['count']; ?>);" class="glyphicon glyphicon-trash"></span>
        </td>
    </tr>
    <tr style="background: #fff;"><td colspan="9" style="padding-top: 0;padding-bottom: 0;"> <span class="error" id='limit_error_<?php echo $_POST['count']; ?>'></span></td></tr>
</table>