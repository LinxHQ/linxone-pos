<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Product */

$this->title = $model->product_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$document = new \app\models\Documents();
?>
<div class="product-view">
    <div>
        <?php echo $document->getImagesEntity($model->product_id, 'product')  ?>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product_name',
            [
                'attribute'=>'category_product_id',
                'value'=>$model->getCategoryName()
            ],
            'product_no',
            'product_description',
            'product_original',
            'product_selling',
            'product_qty_out_of_stock',
            'product_qty_notify',
            [
                'attribute'=>'product_status',
                'value'=>$model->getStatus()
            ],
        ],
    ]) ?>

</div>
