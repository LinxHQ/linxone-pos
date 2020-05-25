<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menu');
$button = "<div class='parkclub-rectangle-header-right' >"
            . "<button id='btn-add-facility' onclick='popAddTable();return false;'>".Yii::t('app', 'NEW ITEM')."</button>"
            . "<button id='btn-add-facility' onclick='popCategory();return false;'>".Yii::t('app', 'CATEGORY')."</button>"
        . "</div>";
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/facilities.png" alt=""></div> <h3><?php echo $this->title; ?></h3></div>
    <div class="col-lg-6" style="text-align: right; margin-top: 30px;">
        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'POINT OF SALE') ?></a>
        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/tables/index') ?>" class="btn btn-primary"><?php echo Yii::t('app', 'TABLE') ?></a>
    </div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items")."</div>"
                        . $button
                    . "</div>",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'product_no',
                        'product_name',
                        [
                            'attribute'=>'category_product_id',
                            'value'=>function($model){
                                return $model->getCategoryName();
                            }
                        ],
                        'product_selling',
                        // 'product_qty_out_of_stock',
                        // 'product_qty_notify',
                        // 'product_description',
                        // 'product_status',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons'=>[ 
                                'update'=> function ($url,$model){
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>','',
                                            [
                                                'onclick'=>'popUpdateTable('.$model->product_id.'); return false;'
                                            ]);
                                         },
                                'view'=> function ($url,$model){
                                         return Html::a('<span class="glyphicon glyphicon-eye-open"></span>','',
                                                 [
                                                     'onclick'=>'popViewTable('.$model->product_id.'); return false;'
                                                 ]);
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
        <div class="modal-dialog modal-lg" role="document" style="width: 850px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "LIST CATEGORY") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-content-group">
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL LIST GROUP -->

<!-- MODAL ADD TABLE -->
    <div id="bs-model-table" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 1000px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff;">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "New Product") ?></h4>
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
    function popCategory(){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-product/index'); ?>',
            function(data){
                $('#bs-model-group').modal('show'); 
            });
    }
    
    function popAddTable(){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-table').load('<?php echo Yii::$app->urlManager->createUrl('pos/product/create'); ?>',
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app', 'New product') ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    
    function popUpdateTable(id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-table').load('<?php echo Yii::$app->urlManager->createUrl('pos/product/update'); ?>?id='+id,
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app', 'Update product') ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    
    function popViewTable(id){
        $('.modal-content').css({'min-height':'200px'});
        $('#modal-content-table').load('<?php echo Yii::$app->urlManager->createUrl('pos/product/view'); ?>?id='+id,
            function(data){
                $('#bs-model-table .modal-title').html("<?php echo Yii::t('app', 'View product') ?>");
                $('#bs-model-table').modal('show'); 
            });
    }
    
    function updateStatus(id,status){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('pos/product/update-status'); ?>',
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