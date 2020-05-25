<?php
/**
 * author Thongnv
 * $model Roles;
 * $this RolesControler
 */
$RolesBasicPermission = new app\modules\permission\models\RolesBasicPermission();
$moduleRoles = $RolesBasicPermission->getModuleByRoles($model->lb_record_primary_key);
$permissionBase = new app\modules\permission\models\BasicPermission();
$listPermistionBasic = $permissionBase->getBasicPermission();
$permissionRoles = array();
?>
<table id="table-center" class="items table table-striped table-bordered table-condensed">
          <thead>
              <tr class="grid-header">
                <td style="text-align: left"><?php echo Yii::t('app', 'Module Name');?></td>
                <?php foreach ($listPermistionBasic as $item) { ?>
                    <td width="12%" style="text-align: center;"><?php echo Yii::t('app',$item['basic_permission_name']); ?></td>
                <?php } ?>
                <td width="5%">&nbsp;</td>
            </tr>
          </thead>
          <tbody>
          
          <?php 
          foreach ($moduleRoles as $moduleRolesItem) { 
              $permissionRoles = $RolesBasicPermission->getPermissionByRoles($model->lb_record_primary_key, $moduleRolesItem->module_id);
              $module = app\modules\permission\models\Modules::findOne($moduleRolesItem->module_id);
          ?>
            <tr>
                <td style="text-align: left"><?php echo Yii::t('app',$module->module_text); ?></td>
                <?php foreach ($permissionRoles as $permissionRolesItem) {

                ?>
                <td style="text-align: center;"><?php
                    $checked = false;
                    $status = 1;
                    if($permissionRolesItem->basic_permission_status==1)
                    {
                        $checked= true;
                        $status=0;
                    }
                    echo yii\helpers\Html::checkBox('permission', $checked, array('value'=>$permissionRolesItem->role_basic_permission_id,'onclick'=>'updatePermissionRole(this.value,'.$status.');')); ?>
                </td>
              <?php } ?>
                <td><a href="#" onclick="deleteModuleRole(<?php echo $model->lb_record_primary_key; ?>,<?php echo $moduleRolesItem->module_id;?>); return false;"><i class="glyphicon glyphicon-trash"></i></a></td>
            </tr>
          <?php }   ?>
          <?php if(count($permissionRoles)<=0){ ?>
            <tr><td colspan="11" style="text-align: left;"><?php echo Yii::t('app', 'No result');?></td></tr>
          <?php } ?>
          </tbody>
</table>

<script type="text/javascript">
    function deleteModuleRole(role_id,module_id)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('permission/roles/delete_module_role'); ?>',
            beforeSend: function(){
                $('#contentai-role-basic').block();
                if(confirm('<?php echo Yii::t('app', 'Are you sure you want to delete this item?');?>'))
                    return true;
                return false;
            },
            data:{role_id:role_id,module_id:module_id},
            success: function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    $('#contentai-role-basic').load('<?php echo Yii::$app->urlManager->createUrl('permission/roles/reload_roles_basic_permission'); ?>',{role_id:role_id});
                else
                    alert('Error');
            },
            done: function(){
                $('#contentai-role-basic').unblock();
            }
        });
    }
    
</script>
