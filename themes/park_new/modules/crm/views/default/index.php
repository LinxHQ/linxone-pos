<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;

//Call permisstion
$BasicPermission = new app\modules\permission\models\BasicPermission();
$m = 'members';
$canAdd = $BasicPermission->checkModules($m, 'add');
$add_guest = "";
if($canAdd){
    $add_guest = "<div class='parkclub-rectangle-header-right'><button onclick='window.location.href=\"".Yii::$app->urlManager->createUrl('/members/default/create')."\"'>".Yii::t('app', 'ADD GUEST')."</button></div>";
}
?>
<div class="parkclub-subtop-bar">
    <div class="parkclub-nameplate"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/crm.png" alt="" width="26"></div> <h3><?php echo Yii::t('app', 'CRM GUEST');?></h3></div>
    <div class="parkclub-search">
        
    </div>
</div>

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
        <div class="parkclub-rectangle-content" style="padding-top: 10px;">
            <?php
                    $tab=0;
                    $active_member=false;
                    $active_guest=false;
                    if(isset($_GET['tab']))
                        $tab = $_GET['tab'];
                    if($tab==1)
                        $active_guest=true;
                    else
                        $active_member=true;
                $items = [
                            [
                                'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','CRM Member').'',
                                // 'content'=>'<div id="content-member"></div>',
								'content'=>$this->render('_crm_member',['searchModel'=>$searchModel,'dataProvider'=>$dataProviderMembers]),
                                'active'=>$active_member,
                                'linkOptions' => array('onclick'=>'active_members();')
                            ],
                            [
                                'label'=>'<i class="glyphicon glyphicon-user"></i> '.Yii::t('app','CRM Guest').'',
                                // 'content'=>'<div id="content-guest"></div>',
								'content'=>$this->render('_crm_gest',['searchModel'=>$searchModel,'dataProvider'=>$dataProviderGuests]),
                                'active'=>$active_guest,
								'linkOptions' => array('onclick'=>'active_guests();')
                            ],
                    ];
                echo TabsX::widget([
                    'items'=>$items,
                    'position'=>TabsX::POS_ABOVE,
                    'encodeLabels'=>false
                ]);
            ?>
        </div>
    </div>
</div>
<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/bootstrap.js" type="text/javascript" ></script>
<script type="text/javascript">
    // $(document).ready(function(){
        // loadCrmGuest();
        // loadCrmMember();
    // });
    // function loadCrmMember(){
        // $.blockUI();
        // $('#content-member').load('<?php echo Yii::$app->urlManager->createUrl('/crm/default/load-member'); ?>',function(){
            // $.unblockUI();
        // });
    // }
    // function loadCrmGuest(){
        // $.blockUI();
        // $('#content-guest').load('<?php echo Yii::$app->urlManager->createUrl('/crm/default/load-guest'); ?>',function(){
            // $.unblockUI();
        // });
    // }
	function active_members()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/crm/default/index?tab=0');?>';
        window.location.href=url;
    }
	function active_guests()
    {
        var url='<?php echo Yii::$app->urlManager->createUrl('/crm/default/index?tab=1');?>';
        window.location.href=url;
    }
</script>