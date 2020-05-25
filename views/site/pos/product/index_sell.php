<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$document = new \app\models\Documents();
$totalPage = $totalProduct%12;
$page = ((isset($_POST['page']) && $_POST['page']>0) ? $_POST['page'] : 0);
$previous = ($page<=0) ? 0 : $page-1;
$next = ($page<0) ? 0 : $page+1;
//print_r($count);
?>
<div class="row">
    <div style="float: left; width: 4%;">
        <?php if($page>0){ ?>
            <a href="#" class="btn btn-info btn-page" onclick="searchProduct(<?php echo $previous; ?>);return false;"><i class="glyphicon glyphicon-menu-left"></i></a>
        <?php } else {echo "&nbsp;";} ?>
    </div>
    <div style="width: 90%; overflow: hidden;float: left;">
        <?php
        foreach ($dataProvider->models as $value) {
            $product_id = $value->product_id;
            $product_name = $value->product_name;
            $product_amount = $value->product_selling;
        //    $img = $document->getImagesEntity($product_id, 'product');
            $amount = number_format($product_amount,0,",",".");

                echo '<div class="col-sm-6 col-md-3" style="text-align:center;" onclick="addProduct('.$product_id.',\''.$product_name.'\','.$product_amount.');return false;">';
                    echo $img = $document->getImagesEntity($product_id, 'product',true,'100px','100%');
                      echo '<h6>'.$product_name.'<br>'.$amount.'</h6>';
                echo '</div>';
        }
        ?>
    </div>
    <div style="float: left;idth: 4%;">
        <?php if($next<$totalPage && $totalProduct>12){ ?>
            <a href="#" class="btn btn-info btn-page" onclick="searchProduct(<?php echo $next; ?>);return false;"><i class="glyphicon glyphicon-menu-right"></i></a>
        <?php } ?>
    </div>
</div>