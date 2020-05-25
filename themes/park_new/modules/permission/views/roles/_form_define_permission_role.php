<?php
/**
 * author Thongnv
 * @var $model Roles;
 * @var $this RolesControler
 */
$modelModule = new app\modules\permission\models\Modules();
$DefinePermission = new \app\modules\permission\models\DefinePermission();
$RolesDefinePermission = new app\modules\permission\models\RolesDefinePermission();
$module = $modelModule->getModules();
?>

<table class="items table table-bordered table-condensed">
    <thead>
        <tr class="grid-header">
            <td><b><?php echo Yii::t('app', 'Modules');?></b></td>
            <td width="10%" style="text-align: center"><b><?php echo Yii::t('app', 'Allow');?></b></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if(count($module)>0)
        {
            foreach ($module as $moduleItem) {
                $definePerModule = $DefinePermission->getDefinePerModule($moduleItem->lb_record_primary_key);
                if(count($definePerModule)>0)
                {
            ?>
                    <tr>
                        <td colspan="2" style="background: #f3f3f3;"><?php echo Yii::t('app',$moduleItem->module_text); ?></td>
                    </tr>
                    <?php foreach ($definePerModule as $definePerModuleItem) { 
                        $checkstatus = $RolesDefinePermission->CheckDefinePerRole($model->lb_record_primary_key, $definePerModuleItem->define_permission_id);
                        if($checkstatus)
                            $status=1;
                        else
                            $status=0;
                    ?>
                        <tr>
                            <td style="padding-left: 30px;"><?php echo Yii::t('app',$definePerModuleItem->define_permission_name); ?></td>
                            <td style="text-align: center;"><?php echo \yii\helpers\Html::checkBox('permission', $status, ['value'=>$definePerModuleItem->define_permission_id,'onclick'=>'updateDefinePerRole(this.value,'.$moduleItem->lb_record_primary_key.','.$status.');']); ?></td>
                        </tr>
                    <?php } ?>
        <?php }}} else { ?>
            <tr>
                <td colspan="2"><?php echo Yii::t('app', 'No result');?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script type="text/javascript">
    function updateDefinePerRole(define_per_id,module_id,status)
    {
        var role_id = <?php echo $model->lb_record_primary_key; ?>;
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('permission/roles/update_define_per_role'); ?>',
            data:{role_id:role_id,define_per_id:define_per_id,status:status,module_id:module_id},
            beforeSend: function(){
                $("#contentai-role-define").block();
            },
            success: function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    $("#contentai-role-define").load('<?php echo Yii::$app->urlManager->createUrl('permission/roles/reload_roles_define_permission'); ?>',{role_id:role_id});
                else
                    alert('Error');
            },
            done:function(data){$("#contentai-role-define").unblock();},
        });
    }
</script>