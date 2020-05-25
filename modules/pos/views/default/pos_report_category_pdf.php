<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\ListSetup;

$this->title = Yii::t('app', 'POS Category Report');
?>
<link href='<?php echo Yii::$app->urlManager->baseUrl;?>/css/pdf.css' rel='stylesheet' type='text/css'>
<div class="park-header">
    <div class="pdf_head_sogan"><?php echo YII::$app->params['sogan_report'] ?></div>
    <br>
    <div class="pdf_head_title"><?php echo Yii::t('app','POS Category Report'); ?></div> 
    <div class="pdf_head_date"><?php echo Yii::t('app','From')?>: <?php  echo $view_start_date; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo Yii::t('app','To')?>: <?php echo $view_end_date; ?></div>
<br>
</div>
<div class="table-responsive"> 
    <table class="table" style="width: 100%;">
        <thead>
            <tr>
                <th style="font-weight: bold;"><?php echo Yii::t('app', 'Name'); ?></th>
                <th style="font-weight: bold;"><?php echo Yii::t('app', 'Quantity'); ?></th>
                <th style="font-weight: bold;"><?php echo Yii::t('app', 'Price'); ?></th>
                <th style="font-weight: bold;"><?php echo Yii::t('app', 'Total'); ?></th>
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
</div>
