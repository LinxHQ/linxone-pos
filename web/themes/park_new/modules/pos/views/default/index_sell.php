<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\ListSetup;
use app\models\Config;

$Listsetup = new ListSetup();
/* @var $this yii\web\View */
/* @var $searchModel app\modules\pos\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$document = new \app\models\Documents();
$totalPage = $totalProduct/24;
$page = ((isset($_POST['page']) && $_POST['page']>0) ? $_POST['page'] : 0);
$previous = ($page<=0) ? 0 : $page-1;
$next = ($page<0) ? 0 : $page+1;
//print_r($count);
?>
<div class="row">
    <div style="float: left; width: 4%;">
        <?php if($page>0){ ?>
            <a href="#" class="btn btn-info btn-page" onclick="searchProduct(<?php echo $previous; ?>, <?php echo $category; ?>);return false;"><i class="glyphicon glyphicon-menu-left"></i></a>
        <?php } else {echo "&nbsp;";} ?>
    </div>
    <div style="width: 90%; overflow: hidden;float: left;" >
        <?php
        $config = new Config();
        if($config->getShowImgProduct() == 1){
            $totalPage = $totalProduct/12;
            foreach ($dataProvider->models as $value) {
                $product_id = $value->product_id;
                $product_name = $value->product_name;
                $product_amount = $value->product_selling;
            //    $img = $document->getImagesEntity($product_id, 'product');
                $amount = number_format($product_amount,0,",",".");

                    echo '<div class="col-sm-6 col-md-3 menu-product" style="text-align:center;" onclick="addProduct('.$product_id.',\''.$product_name.'\','.$product_amount.');return false;">';
                        echo $img = $document->getImagesEntity($product_id, 'product',true,'100px','100%');
                          echo '<a href = "#" style = "color: black;" data-toggle="tooltip" data-placement="top" title="'.$product_name.'">'.$Listsetup->getNameMenuPos($product_name).'<a><br><a href = "#" style = "color: black;" >'.$amount.'</a>';
                    echo '</div>';
            }
        }else{
        foreach ($dataProvider->models as $value) {
            $product_id = $value->product_id;
            $product_name = $value->product_name;
            $product_amount = $value->product_selling;
        //    $img = $document->getImagesEntity($product_id, 'product');
            $amount = number_format($product_amount,0,",",".");

                echo '<div class = "col-xs-3" style="text-align:center;height:88px;font-size: 12px;border: 1px solid;border-radius: 10px;margin:1px;" onclick="addProduct('.$product_id.',\''.$product_name.'\','.$product_amount.');return false;">';
                echo '<a href = "#" style = "color: black;" >'.$product_name.'<a><br><a href = "#" style = "color: black;" >'.$amount.'</a>';
                echo '</div>';
        }
        }
        ?>
    </div>
    <div style="float: left;width: 4%;">
        <?php if($next<$totalPage && $totalProduct>12){ ?>
            <a href="#" class="btn btn-info btn-page" onclick="searchProduct(<?php echo $next; ?>, <?php echo $category; ?>);return false;"><i class="glyphicon glyphicon-menu-right"></i></a>
        <?php } ?>
    </div>
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>