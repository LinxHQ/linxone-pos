<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Product */
/* @var $form yii\widgets\ActiveForm */
$category_product = new \app\modules\pos\models\CategoryProduct();
$document = new \app\models\Documents();
?>

<div class="product-form" style="overflow: hidden">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>"multipart/form-data",'onsubmit'=>'return checkDataSubmit();']]); ?>
    <div class="col-lg-6">
        <?= $form->field($model, 'product_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>
        <label class="control-label"><?php echo Yii::t('app', 'Category') ?></label>
        <?= $category_product->menuSelectPage(0, "", $model->category_product_id,'style="margin-left:0px;width:100%;" name="Product[category_product_id]"') ?>
        
        <?= \yii\bootstrap\Html::input('file', 'file_product[]',"",['multiple'=>'multiple']) ?>

        <?php if(isset($model->product_id)){ ?>
            <div id="product-view-images"></div>
        <?php } ?>

    </div>
    
    <div class="col-lg-6">
        <?= $form->field($model, 'product_original')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'product_selling')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'product_description')->textarea(['style'=>'height:60px']) ?>

        <?= $form->field($model, 'product_status')->dropDownList(\app\models\ListSetup::getItemByList('status')) ?>
    </div>
    <div class="form-group col-lg-12" style="margin-top: 15px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function(){
        LoadImages();
    })
    function LoadImages(){
        $('#product-view-images').load('<?php echo Yii::$app->urlManager->createUrl('/site/update-images'); ?>',
            {entity_id:'<?php echo $model->product_id ?>',entity_type:'product'});
    }
    
    function removeImages(id){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('site/delete-images'); ?>',
            'data':{id:id},
            'success':function(data){
                data = jQuery.parseJSON(data);
                if(data.status=='success'){
                    $.notify("<?php echo Yii::t('app', 'successfully saved') ?>", "success");
                    $('#product-view-images').load('<?php echo Yii::$app->urlManager->createUrl('/site/update-images'); ?>',
                        {entity_id:'<?php echo $model->product_id ?>',entity_type:'product'}); 
                }else{
                    alert(data.status);
                }
            }
        });

    }
    
    function checkDataSubmit(){
        var product_no = $('#product-product_no').val();
        var product_selling = $('#product-product_selling').val();
        var product_name = $('#product-product_name').val();
        var check = true;
        if(product_name == ""){
            $('.field-product-product_name .help-block').html('<?php echo Yii::t('app', 'Name cannot be blank.'); ?>');
            check = false;
        }
        if(product_selling == "" || parseInt(product_selling) == 0){
            $('.field-product-product_selling .help-block').html('<?php echo Yii::t('app', 'Selling Price cannot be blank and greater than 0.'); ?>');
            check = false;
        }
        if(product_no == ""){
            $('.field-product-product_no .help-block').html('<?php echo Yii::t('app', 'Product code cannot be blank.'); ?>');
            check = false;
        }
        return check;
    }
</script>
