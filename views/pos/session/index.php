<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate">
		<div class="parkclub-iconbg">
			<img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png" alt="">
		</div> 
		<h3><?php echo Yii::t('app','Sessions') ?></h3>
	</div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
			<?= GridView::widget([
					'dataProvider' => $dataProvider,
					// 'layout'=>"{items}\n{pager}",
					'showFooter'=>FALSE,
					'footerRowOptions'=>['style'=>'font-weight:bold;',],
					'tableOptions' =>['id'=>'pos-session-report'],
					'columns' => [
						['class' => 'yii\grid\SerialColumn', 'header'=>Yii::t('app', 'No.')],
						[
								'attribute' =>'user_id',
								'label' => Yii::t('app','Username')." <span class='glyphicon glyphicon-chevron-down'>",
								'encodeLabel' => false,
								'format' => 'html',
								'value' => 'user.username'
						],
						[
								'attribute' => 'sesstion_start_date',
								'label' => Yii::t('app','Start Time')." <span class='glyphicon glyphicon-chevron-down'>",
								'encodeLabel' => false,
								'format' => 'html',
								'value' => function($model) {
										return $model->sesstion_start_date;
								}
						],
						[
								'attribute' => 'sesstion_end_date',
								'label' => Yii::t('app','End Time')." <span class='glyphicon glyphicon-chevron-down'>",
								'encodeLabel' => false,
								'format' => 'html',
								'value' => function($model) {
											return $model->sesstion_end_date;
								}
						],
						[
								'attribute' => 'sesstion_status',
								'header' => Yii::t('app','Status'),
								'format' => 'html',
								'value' => function($model) {
										if($model->sesstion_status ==0)
											return Yii::t('app',\app\modules\pos\models\Sesstion::STATUS_SESSION_OPENED);  
										else
											return Yii::t('app',\app\modules\pos\models\Sesstion::STATUS_SESSION_CLOSED); 
								}
						],
						['class' => 'yii\grid\ActionColumn',
							'template'=>'{view}',
							'buttons' => [
								'view' => function ($url, $model) {
									$url = Url::to(['session/view', 'id' => $model->sesstion_id]);
								   return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => 'View']);
								},
							]
						],
					]
				])
			;?>
		</div>
	</div>
</div>	