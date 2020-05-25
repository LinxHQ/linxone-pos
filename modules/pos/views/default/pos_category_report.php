
<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use app\models\ListSetup;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'POS category report');
$selected_start_date = (isset($_GET['start_date'])) ? $_GET['start_date'] : $start_date;
$selected_end_date = (isset($_GET['end_date'])) ? $_GET['end_date'] : $end_date;
?>
<html>

<body>

<div class="container">                                                              
  <div class="table-responsive"> 
  <?php 
    echo '<table style ="margin-top: 3%;">';
    echo '<tr>';
    

    echo '<td>';
    echo '<label class="control-label" >'.Yii::t('app', 'From').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'start_date1',
        'value' => $selected_start_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';
    
    echo '<td >';
    echo '<label class="control-label">'.Yii::t('app', 'To').': </label></td><td>';
    echo DatePicker::widget([
        'name' => 'dp_3',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'id'=>'end_date1',
        'value' => $selected_end_date,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd/mm/yyyy'
        ]
    ]);
    echo '</td>';   
    echo '<td>';  
    echo '<button style="margin-left:4px; margin-bottom: 10px;background-color: rgb(50, 205, 139);" onclick="search_pos_category();return false;" class="btn btn-success">'.Yii::t('app', 'Search').'</button>';
    echo '</td>';

    echo '</tr>';
    echo '</table>';

?>
  <table class="table">
    <thead>
        <tr>
        <th style="font-weight: 500;"><?php echo Yii::t('app', 'Name'); ?></th>
        <th style="font-weight: 500;"><?php echo Yii::t('app', 'Quantity'); ?></th>
        <th style="font-weight: 500;"><?php echo Yii::t('app', 'Price'); ?></th>
        <th style="font-weight: 500;"><?php echo Yii::t('app', 'Total'); ?></th>
      </tr>
    </thead>
    <tbody>
		<?php 
			$list_set_up = new ListSetup();
			foreach($category as $data) { 
				switch($data['type']){
					case 0:
						$padding = '';
						$bold = 'font-weight: 500;';
						break;
					case 1:
						$padding = 'padding-left:20px;';
						$bold = 'font-weight: 500;';
						break;
					case 2:
						$padding = 'padding-left:40px;';
						$bold = '';
						break;
				}
		?>
        <tr>
			<td style="<?php echo $bold;echo $padding ?>"><?= $data['name']?></td>
			<td style="<?php echo $bold ?>"><?= $data['qty']?></td>
			<td style="<?php echo $bold ?>"><?php if($data['price'] > 0) echo $list_set_up->getDisplayPrice(floatval($data['price']),2)?></td>
			<td style="<?php echo $bold ?>"><?= $list_set_up->getDisplayPrice(floatval($data['total']),2)?></td>
		</tr>
		<?php } ?>
    </tbody>
  </table>
     <?php echo '<a style = "padding-left: 45%;" href='.yii\helpers\Url::toRoute(['/pos/default/pos-report-category-pdf', 'start_date'=>$selected_start_date, 'end_date'=>$selected_end_date]).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Print PDF').'</button> </a>'; 
	 echo '<a href='.yii\helpers\Url::toRoute(['/pos/default/pos-report-category-excel', 'start_date'=>$selected_start_date, 'end_date'=>$selected_end_date]).' ><button class="btn btn-success" style = "background-color: rgb(50, 205, 139);">'.Yii::t('app', 'Export Excel').'</button> </a>';
	 ?>
  </div>
</div>

</body>
</html>
<script>
    function search_pos_category(){
        var start_date = $('#start_date1').val();
        var end_date = $('#end_date1').val();
//        window.location.href='pos_report?start_date='+start_date+'&end_date='+end_date;
        window.location.href =  '<?php echo yii\helpers\Url::toRoute(['/pos/default/pos_report'] )?>' + "&start_date=" +start_date+'&end_date='+end_date;

    }
</script>