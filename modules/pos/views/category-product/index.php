<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\CategoryProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$category_product = new \app\modules\pos\models\CategoryProduct();

$this->title = Yii::t('app', 'Category Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-product-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
    'id'=>'gridview-category',
    'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute'=>'category_product_name',
                'footer' =>'<input type="text" value="" name="CategoryProduct[category_product_name]" id="category_product_name" class="form-control" />',
                ],
            [
                'attribute'=>'category_product_parent',
                'value'=>function($model){
                    return $model->getParentName();
                },
                'footer' => $category_product->menuSelectPage(0,"","","class='form-control select-none'",true),
                ],
            [
                'attribute'=>'category_product_description',
                'footer' =>'<input type="text" value="" name="CategoryProduct[category_product_description]" id="category_product_description" class="form-control" />',
                ],
            [
                'attribute'=>'category_product_status',
                'format'=>'raw',
                'value' => function($model) {
                    if($model->category_product_status == 1)
                        return '<a href="#" onclick="updateStatus('.$model->category_product_id.',0);return false;"><i class="glyphicon glyphicon-ok"></i></a>';
                    else
                        return '<a href="#" onclick="updateStatus('.$model->category_product_id.',1);return false;"><i class="glyphicon glyphicon-remove"></i></a>';
                },
                'footer' =>'<input type="checkbox" value="" name="CategoryProduct[category_product_status]" id="category_product_status"  />',
                ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
                'buttons'=>[ 
                    'delete'=> function ($url,$model){
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>','',
                            [
                                'onclick'=>'delete_group('.$model->category_product_id.'); return false;'
                            ]);
                         },
                    ],
                'footer' =>'<button onclick="add_group();" class="btn btn-primary">'.Yii::t('app', 'Add').'</button>',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<script type="text/javascript">
        function add_group(){
            var category_product_name = $('#category_product_name').val();
            var category_product_description = $('#category_product_description').val();
            var category_product_status = $('#category_product_status').val();
            var category_product_parent = $('#category_product_parent').val();
            $.ajax({
                type:'post',
                url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-product/create'); ?>',
                data:{'CategoryProduct[category_product_name]':category_product_name,'LCategoryProduct[category_product_description]':category_product_description,
                    'CategoryProduct[category_product_status]':category_product_status,'CategoryProduct[category_product_parent]':category_product_parent},
                success:function(data){
                     $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-product/index'); ?>');
                }
            });
        }
        
        function delete_group(id){
            $.ajax({
                type:'post',
                url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-product/delete'); ?>',
                data:{'id':id},
                success:function(data){
                     $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-product/index'); ?>');
                }
            });
        }
        
        function updateStatus(id,status){
            $.ajax({
                type:'POST',
                url:'<?php echo Yii::$app->urlManager->createUrl('pos/category-product/update-status'); ?>',
                data:{id:id,status:status},
                success:function(data){
                    data = jQuery.parseJSON(data);
                    if(data.status=='success'){
                        $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                        $('#modal-content-group').load('<?php echo Yii::$app->urlManager->createUrl('pos/category-product/index'); ?>');
                    }
                }
            });
        }
</script>