<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\permission\models\Roles */

$this->title = Yii::t('app', 'view roles');
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <i class="glyphicon glyphicon-circle-arrow-left"></i>
                    <?php echo $this->title; ?>
                </div>
            </div>
            <div >
                    <div class="roles-view">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                    //            'lb_record_primary_key',
                                'role_name',
                                'role_description',
                            ],
                        ]) ?>

                    </div>
                    <!-- =============== BASIC PERMISSION ROLE ============= -->
                    <fieldset style="text-align: left; margin-top: 30px;">
                        <legend><b><?php echo Yii::t('app','Basic permission'); ?></b></legend>
                        <div style="margin-top: 10px;">
                            Module: 
                            <?php 
                                $modelModule = new app\modules\permission\models\Modules();
                                $modelModule = $modelModule->getModules();
                                $lisData = \yii\helpers\ArrayHelper::map($modelModule, 'lb_record_primary_key','module_name');
                                echo Html::dropDownList('assign_module_roles', '',$lisData, array('id'=>'assign_module_roles','class'=>'form-control','style'=>'width:200px; display:inline;'));
                                echo Html::button(Yii::t('app','Add'),array(
                                    'onclick'=>'add_permission_role();return false;',
                                    'class'=>'btn btn-default',
                                    'style'=>'margin-left:10px;'
                                ));
                            ?>
                      </div>

                      <div id="contentai-role-basic">
                          <?= $this->render('_form_basic_permission_role', array(
                              'model'=>$model,
                          )) ?>
                      </div>  

                    </fieldset >
                      <!-- ------------- END BASIC PERMISSIONROLE -------------- -->

                        <!-- =============== DEFINE PERMISSION ROLE ============= -->
                    <fieldset style="text-align: left;margin-top: 20px;">
                        <legend><b><?php echo Yii::t('app','Define permission'); ?></b></legend>
                      <div id="contentai-role-define">
                          <?= $this->render('_form_define_permission_role', array(
                              'model'=>$model,
                          )) ?>
                      </div>

                    </fieldset>
                        <!-- ------------- END DEFINE PERMISSION ROLE -------------- -->
            </div>
       </div>
    </div>
</div>
<script type="text/javascript">
    function add_permission_role()
    {
        var role_id = <?php echo $model->lb_record_primary_key; ?>;
        var modules_id = $('#assign_module_roles').val();
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('permission/roles/assing_permission_roles'); ?>',
            data:{role_id:role_id,modules_id:modules_id},
            success:function(data)
            {
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    $('#contentai-role-basic').load('<?php echo Yii::$app->urlManager->createUrl('permission/roles/reload_roles_basic_permission'); ?>',{role_id:role_id});
                else if(responseJSON.status=="exist")
                    alert(responseJSON.msg);
                else
                    alert('Error');
            }
        });
    }
    
    function updatePermissionRole(permission_id,status)
    {
        var role_id = <?php echo $model->lb_record_primary_key; ?>;
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('permission/roles/update_basic_permission_roles'); ?>',
            data:{permission_id:permission_id,status:status},
            beforeSend: function(){
                $("#contentai-role-basic").block();
            },
            success:function(data)
            {
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    $('#contentai-role-basic').load('<?php echo Yii::$app->urlManager->createUrl('permission/roles/reload_roles_basic_permission'); ?>',{role_id:role_id});
                else
                    alert('Error');
            },
            done:function(data){$("#contentai-role-basic").unblock();},
        });
    }
</script>