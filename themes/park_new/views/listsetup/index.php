<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ListsetupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'List Setups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="list-setup-index">

<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png" alt=""></div> <h3><?php echo Yii::t('app','List setup');?></h3></div>
    <div class="parkclub-search">
        
    </div>
</div>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content">
            <div class="user-index">
<?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'id'=>'list-setup-gridview',
        'filterModel' => $searchModel,
        'summary' => "<div class='parkclub-rectangle-header'>"
                        . "<div class='parkclub-rectangle-header-left'>Showing <b>{begin} - {end}</b> of <b>{totalCount}</b> items</div>"
                    . "</div>",
        'tableOptions' => ['class' => 'parkclub-check-table'],
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'list_name',
                'format'=>'raw',
                'value'=>function($data){
                    return '<a href="'.Yii::$app->urlManager->createUrl('listsetup/item?parent_id='.$data->list_id).'">'.Yii::t('app',$data->list_name).'</a>';
                },
                'footer' =>'<input type="text" value="" name="ListSetup[list_name]" id="list_name" class="form-control" />'
                        . '<input type="hidden" value="0" name="ListSetup[list_parent]" id="list_parent" class="form-control" />'
                        . '<input type="hidden" value="parent" name="ListSetup[list_value]" id="list_value" class="form-control" />',
            ],
            [
                'attribute'=>'list_description',
                'footer' =>'<textarea name="ListSetup[list_description]" id="list_description" class="form-control" rows="1"></textarea>',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
				'template' => '',
                'footer' =>'<button onclick="add_listsetup();">'.Yii::t('app', 'Add').'</button>',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        function add_listsetup(){
            var list_name = $('#list_name').val();
            var list_value = $('#list_value').val();
			list_value = list_value.trim();
			if(list_value =='')
				alert('<?php echo Yii::t('app','List value can not be blanked') ?>');
			else {
				var list_description = $('#list_description').val();
				var list_parent = $('#list_parent').val();
				$.ajax({
					type:'post',
					url:'<?php echo Yii::$app->urlManager->createUrl('listsetup/create'); ?>',
					data:{'ListSetup[list_value]':list_value,'ListSetup[list_parent]':list_parent,'ListSetup[list_description]':list_description,
							'ListSetup[list_name]':list_name},
					success:function(data){
						if(data=='duplicated')
							alert('<?php echo Yii::t('app','Duplicated value')?>');
						$("#list-setup-gridview").yiiGridView("applyFilter");
					}
					
				});
			}	
        }
    </script>