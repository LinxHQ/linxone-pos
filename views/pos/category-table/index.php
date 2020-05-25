<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\CategoryTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="category-table-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php Pjax::begin(['id'=>'pajax-group-table']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'id'=>'gridview-group-table',
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'category_table_name',
                'footer' =>'<input type="text" value="" name="CategoryTable[category_table_name]" id="category_table_name" class="form-control" />',
                ],
            [
                'attribute'=>'category_table_description',
                'footer' =>'<input type="text" value="" name="CategoryTable[category_table_description]" id="category_table_description" class="form-control" />',
                ],
            [
                'attribute'=>'category_table_status',
                'format'=>'raw',
                'value' => function($model) {
                    if($model->category_table_status == 1)
                        return '<a href="#" onclick="updateStatus('.$model->category_table_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                    else
                        return '<a href="#" onclick="updateStatus('.$model->category_table_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
                },
                'footer' =>'<input type="checkbox" value="" name="CategoryTable[category_table_status]" id="category_table_status"  />',
                ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[ 
                    'delete'=> function ($url,$model){
                        $category_table = \app\modules\pos\models\Tables::find()->where(['category_table_id'=> $model->category_table_id])->all();
                        $result = count($category_table);
                        if($result==0){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>','',
                                [
                                    'onclick'=>'delete_group('.$model->category_table_id.'); return false;'
                                ]);
                        }else {
                            return "";
                        }
                    },
                    ],
                'footer' =>'<button onclick="add_group();" class="btn btn-primary">'.Yii::t('app', 'Add').'</button>',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
    <script type="text/javascript">
        function add_group(){
            var category_table_name = $('#category_table_name').val();
            var category_table_description = $('#category_table_description').val();
            var category_table_status = $('#category_table_status').val();
            $.ajax({
                type:'post',
                url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-table/create'); ?>',
                data:{'CategoryTable[category_table_name]':category_table_name,'LCategoryTable[category_table_description]':category_table_description,
                    'CategoryTable[category_table_status]':category_table_status},
                success:function(data){
                     $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-table/index'); ?>');
                }
            });
        }
        
        function delete_group(id){
            var result = confirm("Are you sure you want to delete this item?");
            if(result){
                $.ajax({
                    type:'post',
                    url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-table/delete'); ?>',
                    data:{'id':id},
                    success:function(data){
                         $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-table/index'); ?>');
                    }
                });
            }
        }
        
        function updateStatus(id,status){
            $.ajax({
                type:'POST',
                url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-table/update-status'); ?>',
                data:{id:id,status:status},
                success:function(data){
                    data = jQuery.parseJSON(data);
                    if(data.status=='success'){
                        $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-table/index'); ?>');
                    }
                }
            });
        }
    </script>