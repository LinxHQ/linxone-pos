<?php

/**
 * @author Thongnv 
 */
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\permission\models\ModulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Modules Active');
?>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo $this->title ?>
                </div>
            </div>
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
        //        'filterModel' => $searchModel,
                'summary'=>false,
                'columns' => [
        //            ['class' => 'yii\grid\SerialColumn'],
                    [
                        'header'=>'#',
                        'attribute'=>'module_order',
                        'format'=>'raw',
                    ],
                    [
                        'attribute'=>'module_name',
                        'format'=>'raw',
                        'value'=>function($model){
                            return Yii::t('app', $model->module_name);
                        }
                    ],
                    [
                        'attribute'=>'module_text',
                        'format'=>'raw',
                        'value'=>function($model){
                            return Yii::t('app', $model->module_text);
                        }
                    ],
                    'modules_description',
                    [
                        'header'=>Yii::t('app','Menu Status'),
                        'attribute'=>'module_hidden',
                        'format'=>'raw',
                        'value'=>function($model){
                            if($model->module_hidden==1) 
                                return "<a href='#' onclick='ajaxUpdateStatusModule(".$model->lb_record_primary_key.",0); return false;'><i class='glyphicon glyphicon-ok-circle'></i> ".Yii::t('app', 'Visible')."</a>";
                            else
                                return "<a href='#' onclick='ajaxUpdateStatusModule(".$model->lb_record_primary_key.",1); return false;'><i class='glyphicon glyphicon-remove-sign'></i>".Yii::t('app', 'Hidden')."</a>";
                            }
                    ],
                    [
                        'header'=>Yii::t('app','Actions'),
                        'format'=>'raw',
                        'value'=>function($model){
                                return '<a href="#" onclick="ajaxDeleteModule('.$model->lb_record_primary_key.')" id="member_delete" data-original-title="Xóa" rel="tooltip" title><i class="icon-trash"></i> '.Yii::t('app','Remove').'</a>';
                        }
                    ],

        //            ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
<br />
<?php
$module = new \app\modules\permission\models\Modules();
// get the modules actually installed on the file system
$modFiles = $module->readDirs('modules');
//print_r($modFiles);
$modelMod = $module->find()->all();
foreach ($modelMod as $modelModItem) {
    if($modFiles[$modelModItem['module_directory']] == $modelModItem['module_directory'])
        $modFiles[$modelModItem['module_directory']]="";
}
?>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Modules Deactivate'); ?>
                </div>
            </div>
        <div class="parkclub-rectangle-content">
            <table class="items table table-striped">
                <thead>
                    <tr>
                        <td><b><?php echo Yii::t('app', 'Module name'); ?></b></td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($modFiles as $modItem) {
                        $ok = @include_once( Yii::$app->basePath.'/modules/'.$modItem.'/setup.php' );
                        if($modItem!="" && $ok)
                        {

                    ?>
                        <tr>
                            <td><?php echo $modItem; ?></td>
                            <td><a href="<?php echo Yii::$app->urlManager->createUrl('permission/default/install_module?mod_name='.$modItem); ?>"><?php echo Yii::t('app', 'Install')?></a></td>
                        </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function ajaxDeleteModule(module_id)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('permission/default/delete_module'); ?>',
            beforeSend: function(){
                if(confirm('Bạn có chắc muốn xóa module này không?'))
                    return true;
                return false;
            },
            data:{module_id:module_id},
            success: function(data){
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    window.location.assign('<?php echo Yii::$app->urlManager->createUrl('permission/default/view_modules'); ?>');
                else
                    alert("error");
                    
            },
        });
    }
    
    function ajaxUpdateStatusModule(module_id,status)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('permission/default/update_status_module'); ?>',
            beforeSend: function(){
                //code
            },
            data:{modules_id:module_id,status:status},
            success: function(data){
                location.reload();
            }
        })
    }
</script>