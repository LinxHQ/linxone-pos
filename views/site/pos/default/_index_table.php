<?php
use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\tabs\TabsX;

//Check permission 
$m = 'pos';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$key;
//End check permission
?>

<div class="row">
<?php
foreach ($dataProvider->models as $value) {
    $table_name = $value->table_name;
    if($value->check_status_table($value->table_id)==false){
        $img = "<img width='100' src='".Yii::$app->urlManager->baseUrl."/image/park_new/table_order.png' />";
    }else{
        $img = "<img width='100' src='".Yii::$app->urlManager->baseUrl."/image/park_new/table.png' />";
    }
        echo '<div class="col-sm-6 col-md-3" id="table_'.$value->table_id.'" onclick="order('.$value->table_id.',0);return false;">';
          echo '<a href="#" class="thumbnail">';
            echo $img;
            echo '<div>';
              echo '<h3 style = "text-align:center;">'.$table_name.'</h3>';
            echo '</div>';
          echo '</a>';
        echo '</div>';
}
?>
</div>
