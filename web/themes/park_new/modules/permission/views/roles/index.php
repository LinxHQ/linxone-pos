<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\permission\models\RolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Roles');
$add_roles = "<div class='parkclub-rectangle-header-right'><button onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('permission/roles/create')."\"'>".Yii::t('app','CREATE ROLE')."</button></div>";
?>
<div class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'summary' => "<div class='parkclub-rectangle-header'>"
                            . "<div class='parkclub-rectangle-header-left'><h3>".$this->title."</h3></div>"
                            . $add_roles
                        . "</div>",
                        'tableOptions' => ['class' => 'parkclub-check-table'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute'=>'role_name',
                    ],
                    'role_description',
                    [
                        'header'=>Yii::t('app','Actions'),
                        'format'=>'raw',
                        'value'=>function($model){
                                return '<a href="#" onclick="ajaxDeleteRole('.$model->lb_record_primary_key.')" id="member_delete" data-original-title="'.Yii::t('app','Delete').'" rel="tooltip" title><i class="glyphicon glyphicon-trash"></i></a>
                            <a href="roles/view?id='.$model->lb_record_primary_key.'" id="member_delete" data-original-title="View Role" rel="tooltip" title><i class="glyphicon glyphicon-lock"></i></a>';
                        }
                    ],

        //            ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
        <div class="parkclub-footer"></div>
    </div>
</div>

<script type="text/javascript">
    function AjaxAddLineRole()
    {
        var role_name = $('#role_name').val();
        var role_description = $('#role_description').val();
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('AjaxAddLineRole'); ?>',
            data:{role_name:role_name,role_description:role_description},
            beforeSend: function(data)
            {
                $('#lb-role-grid').block();
            },
            success:function(data)
            {
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    $.fn.yiiGridView.update('lb-role-grid');
                else
                    alert('Error');
            },
            error: function(data){
                //code...
            },
            done:function(){
                $('#lb-role-grid').unblock();
            },
        });
    }
    function ajaxDeleteRole(role_id)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('/permission/roles/delete_role'); ?>',
            beforeSend: function(data)
            {
                $('#lb-role-grid').block();
                if(confirm('Bạn có chắc muốn xóa module này không?'))
                    return true;
                return false;
            },
            data:{role_id:role_id},
            success:function(data)
            {
                var responseJSON = jQuery.parseJSON(data);
                if(responseJSON.status=="success")
                    location.reload();
                else
                    alert('Error'); 
            },
            done:function(){
                $('#lb-role-grid').unblock();
            },
        
        });
    }
</script>