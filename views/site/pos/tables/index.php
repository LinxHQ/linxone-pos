<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\TablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tables');
$this->params['breadcrumbs'][] = $this->title;
$keyserch = "";

$button = "<div class='parkclub-rectangle-header-right' >"
            . "<button id='btn-add-facility' onclick='popAddTable();return false;'>".Yii::t('app', 'NEW TABLE')."</button>"
            . "<button id='btn-add-facility' onclick='popGroup();return false;'>".Yii::t('app', 'LIST GROUP')."</button>"
        . "</div>";
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/facilities.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="col-lg-6" style="text-align: right; margin-top: 30px;">
        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'POINT OF SALE') ?></a>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/product/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'MENU') ?></a>
    </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id'=>'gridview-tables',
//                    'filterModel' => $searchModel,
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $button
                    . "</div>",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'table_name',
                            'format'=>'raw',
                            'value' => function($model) {
                                return '<a href="#" onclick="popUpdateTable('.$model->table_id.'); return false;">'.$model->table_name.'</a>';
                            } 
                        ],
                        'table_description',
                        [
                            'attribute'=>'category_table_id',
                            'value'=>function($model){
                                return $model->getGroupName();
                            }
                        ],
                        [
                            'attribute'=>'table_status',
                            'format'=>'raw',
                            'value' => function($model) {
                                if($model->table_status == 1)
                                    return '<a href="#" onclick="updateStatus('.$model->table_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                                else
                                    return '<a href="#" onclick="updateStatus('.$model->table_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
                            } 
                        ],
                        'table_order',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template'=>'{update}{delete}',
                            'buttons'=>[ 
                                'update'=> function ($url,$model){
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>','',
                                            [
                                                'onclick'=>'popUpdateTable('.$model->table_id.'); return false;'
                                            ]);
                                         },
                                'delete' => function ($url, $model) {
                                    $table_order = \app\modules\invoice\models\invoice::find()->where(['invoice_type_id'=> $model->table_id])->all();
                                    $result = count($table_order);
                                    if($result == 0){
                                        $url = YII::$app->urlManager->createUrl('/pos/tables/delete?id='.$model->table_id);
                                        return Html::a('<span class="glyphicon glyphicon-trash" ></span>', $url, [
                                            'data' => [
                                                'confirm' => Yii::t('app','Are you sure you want to delete this item?'),
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }
                                    return "";
                                },
                            ],
                        ],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
<!-- MODAL LIST GROUP -->
    <div id="bs-model-group" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 800px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "LIST GROUP") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-content-group" style="text-align: center">
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL LIST GROUP -->

<!-- MODAL ADD TABLE -->
    <div id="bs-model-table" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 600px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "New table") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-content-table" style="padding: 25px;">
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL ADD TABLE -->
<script type="text/javascript">
    function popGroup(){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-table/index'); ?>',
            function(data){
                $('#bs-model-group').modal('show'); 
            });
    }
    
    function popAddTable(){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-table').load('<?php echo Yii::$app->urlManager->createUrl('pos/tables/create'); ?>',
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app', 'New table') ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    
    function popUpdateTable(id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-table').load('<?php echo Yii::$app->urlManager->createUrl('pos/tables/update'); ?>?id='+id,
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app', 'Update table') ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('pos/tables/update-status'); ?>',
            data:{id:id,status:status},
            success:function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#gridview-tables').yiiGridView("applyFilter");
                }
            }
        });
    }
</script>
