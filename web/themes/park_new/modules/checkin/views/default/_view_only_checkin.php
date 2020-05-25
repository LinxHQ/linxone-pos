<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\checkin\models\MembersCheckin;
$memberCheckin = new MembersCheckin();
/* @var $this yii\web\View */
/* @var $searchModel app\models\MembersCheckinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$add = "<div class='parkclub-rectangle-header-right' id='btn-add-facility' style='margin: 20px 20px;'>"
        . "<a class='label label-info label-font-normal' onclick='popup_list_checkin(1);'>".Yii::t('app','Total daily checkin')."&nbsp;&nbsp; <span class='badge'>".$memberCheckin->getTotalDailyCheckin()."</span></a> "
        . "<a class='label label-success label-font-normal' onclick='popup_list_checkin(2);'>".Yii::t('app','Total daily checkout')."&nbsp;&nbsp; <span class='badge'>".$memberCheckin->getTotalDailyCheckout()."</span></a>"
        ."<button id='checkout_all' style='padding: 8px 10px;' onclick='checkoutSelected();'>".Yii::t('app','Check out')."</button>"
        . "</div>";
?>
    
<div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'id'=>'grid-checkin',
            //        'filterModel' => $searchModel,
                    'summary' => "<div class='parkclub-rectangle-header'>"
                                    . "<div class='parkclub-rectangle-header-left'>".Yii::t('app',"Showing <b>{begin}-{end, number}</b> of <b>{totalCount, number}</b> {totalCount, plural, =0{member} one{member} other{members}}")."</div>"
                                    . $add
                                . "</div>",
                    'tableOptions' => ['class' => 'parkclub-check-table'],
                    'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],
                        [
                            'header' => Yii::t('app', 'Card No'),
                            'attribute' => 'membership_code',
                            'format' => 'html',
                            'value' => function($model) {
                                return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$model->membership_code;
                            }
                        ],
                        [
                            'header' => Yii::t('app', 'Member Picture'),
                            'attribute' => 'member_picture',
                            'format' => 'raw',
                            'value' => function($model) {
                                $img = app\modules\members\models\Members::getMemberImages($model->member_id);
                                return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'><img class='img-circle' width='80' src= ".$img."></a>";
                            },
                            'contentOptions'=>['style'=>'margin-top:5px'],
                        ],
                        [
                            'attribute' => 'member_name',
                            'format' => 'html',
                            'value' => function($model) {
                                $member = new \app\modules\members\models\Members();
                                return "<a href='".Yii::$app->urlManager->createUrl('members/default/update?id='.$model->member_id)."'>".$member->getMemberFullName($model->member_id);
                            }
                        ],
                        [
                            'attribute' => 'member_barcode',
                            'header' => Yii::t('app', 'Member Barcode'),
                            'format' => 'html',
                            'value' => function($model) {
                                if($model->members)
                                    return $model->members->member_barcode;
                                return "";
                            }
                        ],
                        [   
                            'header' => Yii::t('app',"Check in"),
                            'format' => 'raw',
                            'value' => function($model) {
                                if(!($model->checkin_time != "0000-00-00 00:00:00" and ($model->checkcout_time == "0000-00-00 00:00:00" or $model->checkcout_time == null) and date('d',strtotime($model->checkin_time)) == date('d'))) {
                                    return '<button  type="button" onclick="popcheckin_line(\''.$model->membership_code.'\');" id="button_checkin" class="btn btn-primary buttonCheckin">'.Yii::t ('app','Check in').'</button>';
                                } else {
									if($model->checkin_time)
										return date(YII::$app->params['defaultDateTime'],  strtotime($model->checkin_time));
								}
							}
                        ],
                        [
                            'header' => Yii::t('app',"Check out"),
                            'format' => 'raw',
                            'value' => function($model) {
                                
                                if($model->checkin_time != "0000-00-00 00:00:00" and ($model->checkcout_time == "0000-00-00 00:00:00" or $model->checkcout_time == null) and date('d',strtotime($model->checkin_time)) == date('d')) {
                                    return '<button type="button" onclick="popcheckin_line(\''.$model->membership_code.'\');" id="button_checkin" class="btn btn-primary buttonCheckin">'.Yii::t ('app','Check out').'</button>';
                                } else{ 
									{
										if($model->checkcout_time != "0000-00-00 00:00:00" and $model->checkcout_time!=null)
											return date(YII::$app->params['defaultDateTime'],  strtotime($model->checkcout_time));
									}
								}
                                return "";
                            }
                        ],
                        [
                            'header' => Yii::t('app',"Expiry"),
                            'format' => 'html',
                            'value' => function($model) {
                                $listSetup = new \app\models\ListSetup();
                                return $listSetup->getDisplayDate($model->membership_enddate);
                            }
                        ],
                        [
                            'header' => Yii::t('app',"Membership Type"),
                            'format' => 'html',
                            'value' => function($model) {
                                if($model->membershipType)
                                    return $model->membershipType->membership_name;
                                return '';
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{history}',
                            'buttons' => [
                                    'history' => function ($url, $model) {
                                        return Html::a('<i class="glyphicon glyphicon-time"></i>', $url, [
                                                    'title' => Yii::t('app', 'View'),                            
                                        ]);
                                    },
                            ],
                            'urlCreator' => function ($action, $model, $key, $index) {
                                  if ($action === 'history') {
                                      $url =YII::$app->urlManager->createUrl('/checkin/default/history?membership_id='.$model->membership_id);
                                      return $url;
                              }
                            }
                        ],
                        [

                            'class' => 'yii\grid\CheckboxColumn',
                            
                            'checkboxOptions' =>
                        
                                function($model) {
                                    if ($model->checkin_time != "0000-00-00 00:00:00" and ($model->checkcout_time == "0000-00-00 00:00:00" or $model->checkcout_time == null) and date('d', strtotime($model->checkin_time)) == date('d')) {
                                        return ['value' => $model->checkin_id, 'class' => 'checkbox-row', 'id' => 'checkbox'];
                                    }else
                                        return ['style' => ['display' => 'none']];
                                    
                        
                                }
                        
                        ]
                                ],
                ]); ?>
            </div>
</div>
<!-- MODAL LIST CHECKIN -->
    <div id="bs-model-checkin-book-list" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 1080px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               
               <div>
                   <h4 class="modal-title"id = "header"></h4>
               </div>
               </div>
               <div class="modal-body" id="modal-content-checkin-list"></div>
            </div>
        </div>
    </div>
<!-- END MODAL LIST CHECKIN -->

<script>
    function popup_list_checkin(type){
        if(type == 1)
            $('#header').html('<?php echo yii::t('app','Daily checkin');?>');
        if(type == 2)
            $('#header').html('<?php echo yii::t('app','Daily checkout');?>');
        var date = "<?php echo date('Y-m-d') ; ?>";
        $('#modal-content-checkin-list').load('<?php echo Yii::$app->urlManager->createUrl('/checkin/default/popuplistcheckin'); ?>',{"date":date,"type":type},
            function(data){
                $('#bs-model-checkin-book-list').modal('show'); 
            });
    }
</script>