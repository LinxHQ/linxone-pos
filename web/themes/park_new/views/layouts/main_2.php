<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);

$Modules = new app\modules\permission\models\Modules();

//Check permission 

$BasicPermission = new \app\modules\permission\models\BasicPermission();
$DefinePermission = new \app\modules\permission\models\DefinePermission();

$m = 'report';
$canAccountReceivable = $DefinePermission->checkFunction($m, 'Account Receivable');
$canPaymentReport = $DefinePermission->checkFunction($m, 'Payment report');
$canMembershipExpiryReport = $DefinePermission->checkFunction($m, 'Membership Expiry Report');
$canHistoryBooking = $DefinePermission->checkFunction($m, 'History Booking');
$canPtCheckin = $DefinePermission->checkFunction($m, 'PT checkin/out Report');
$canMemberCheckin = $DefinePermission->checkFunction($m, 'Member Checkin/out Report');
$canBirthdayReport = $DefinePermission->checkFunction($m, 'Birthday Report');
$canMembersReport = $DefinePermission->checkFunction($m, 'Members Report');
$canPtSessionServiceReport = $DefinePermission->checkFunction($m, 'Pt Session Service Report');
$canPtTrainingReport = $DefinePermission->checkFunction($m, 'Pt Training Report');
$canPosReport = $DefinePermission->checkFunction($m, 'Pos Report');
//End check permission

$user = new app\models\User();
$roleName ="";

if(isset(YII::$app->user->id)){
    $roleName = $user->findOne(YII::$app->user->id)->getRoleName();
    Yii::$app->language = $user->findOne(YII::$app->user->id)->language_name;
}
$config =new \app\models\Config();
$dete_data = false;
$config_data = $config->find()->one();
if($config_data && $config_data->delete_data==1)
    $dete_data = true;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
    
    <script src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.min.js" type="text/javascript" ></script>
	<script src="<?php echo Yii::$app->request->baseUrl; ?>/js/bootstrap.js" type="text/javascript" ></script>
        
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?php 
     
     $module_name="moverview";
       
    ?>
    <?php if (!Yii::$app->user->isGuest)
        {
        $module = $this->context->module;
        if($module->module)
            $module_name = $module->module->controller->module->id;
        else 
			$module_name = $this->context->action->id;
        if($module_name == "report"){
            $module_name=$this->context->action->id;
            if($module_name == 'chekin')
                $module_name = 'rchekin';
        }
        
    ?>
    <div class="container">
        <div class="parkclub-top-bar">
            <div class="parkclub-logo">
                <div style = "background-color:rgb(50, 205, 139);" class="navbar navbar-inverse navbar-twitch <?php if(Yii::$app->user->identity->menu == 0){echo "open";} ?>" role="navigation">
                    <div class ="container" style="margin-top: 0px;">
                        <nav>
                            <ul class="nav navbar-nav">
                                <li class="active">
                                    <span class="small-nav" data-toggle="tooltip" data-placement="right" ><h1>P</h1></span>
                                    <span class="full-nav"><h1><?php echo Yii::t('app', Yii::$app->name); ?></h1></span>
                                    
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="parkclub-nav">
                <div id="help-tour-content">
                        <i class="glyphicon glyphicon-list"></i> 
                        <a href="#" id="help-tour">
                            <?php echo Yii::t('app', 'Tour'); ?>
                        </a>
                </div>
                <?php if (Yii::$app->user->can('admin')){ ?>
                <div><a href="<?php echo Yii::$app->urlManager->createUrl('/user/index'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('app','Users');?></a></div>
                
                <?php } ?>
                
                <div class="dropdown pull-right">
            <a id="dLabel" data-toggle="dropdown" href="#" >
                <?php echo Yii::$app->user->identity->username; ?> <span class="caret"></span>
            </a>
    		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu" >
                    
              <li class="dropdown-submenu pull-left hienham">
                <a tabindex="-1" href="#"><?php echo Yii::t('app', 'Language'); ?></a>
                <ul class="dropdown-menu pull-left">
                  <li><a  tabindex="-1" href="<?php echo Yii::$app->urlManager->createUrl('/user/changelanguage?id='.YII::$app->user->id.'&language=en'); ?>"><?php echo "English"; ?></a></li>
                  <li><a  tabindex="-1" href="<?php echo Yii::$app->urlManager->createUrl('/user/changelanguage?id='.YII::$app->user->id.'&language=vi'); ?>"><?php echo "Việt Nam"; ?></a></li>
                </ul>
              </li>
              <?php if (!Yii::$app->user->isGuest) { ?>
                    <li><a href="<?php echo Yii::$app->urlManager->createUrl('/site/logout'); ?>"  data-method="post"><?php echo Yii::t('app', 'Logout') ?></a></li>
                    <?php } else { ?>
                    <li><a href="<?php Yii::$app->urlManager->createUrl('/site/login'); ?>"><?php echo Yii::t('app', 'login'); ?></a></li>
                    <?php } ?>
            </ul>
        </div>
                
                <!--            <div class="parkclub-user-photo"></div>-->
            </div>
        </div>
        
            <div class="navbar navbar-inverse navbar-twitch parkclub-menu <?php if(Yii::$app->user->identity->menu == 0){echo "open";} ?>" role="navigation">
                <div class="container">
                   
                        <nav>
                             <ul class="nav navbar-nav">

								<li class="dashboard active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/dashboard');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Management Dashboard');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/management.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/management.png"> <?php echo Yii::t('app','Management Dashboard');?></span></a></li>
                                <li class="moverview parckclub-active active"><a href="<?php echo Yii::$app->urlManager->createUrl('index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Overview');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/overview.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/overview.png"><?php echo Yii::t('app','Overview');?></span> </a></li>
                                <?php if($Modules->checkHiddenModule('checkin')){ ?>
                                <li class="checkin active"><a href="<?php echo Yii::$app->urlManager->createUrl('/checkin/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Check in/out');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png"> <?php echo Yii::t('app','Check in/out');?></span></a></li>
                                <?php } ?>
								<?php if($Modules->checkHiddenModule('event')){ ?>
                                <li class="event active"><a href="<?php echo Yii::$app->urlManager->createUrl('/event/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Events');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/event.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/event.png"> <?php echo Yii::t('app','Events');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('members')){ ?>
                                <li class="members active"><a href="<?php echo Yii::$app->urlManager->createUrl('/members/default/index?m=members');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Members');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members.png"> <?php echo Yii::t('app','Members');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('trainer')){ ?>
                                <li class="trainer active"><a href="<?php echo Yii::$app->urlManager->createUrl('/trainer/default/index?m=trainers');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Trainers');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/trainers.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/trainers.png"> <?php echo Yii::t('app','Trainers');?></span></a></li>
                                <?php } ?>
								<?php if($Modules->checkHiddenModule('sale')){ ?>
                                <li class="sale active"><a href="<?php echo Yii::$app->urlManager->createUrl('/sale/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Sales');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/salesperson.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/salesperson.png"> <?php echo Yii::t('app','Sale');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('crm')){ ?>
                                <li class="crm active"><a href="<?php echo Yii::$app->urlManager->createUrl('/crm/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','CRM Guest');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/crm.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/crm.png"><?php echo Yii::t('app','CRM Guest');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('booking')){ ?>    
                                <li class="booking active"><a href="<?php echo Yii::$app->urlManager->createUrl('/booking/default/index?m=members');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Booking');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/bookmark.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/bookmark.png"><?php echo Yii::t('app','Booking');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('facility')){ ?>
                                <li class="facility active"><a href="<?php echo Yii::$app->urlManager->createUrl('/facility/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Facilities');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/facilities.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/facilities.png"><?php echo Yii::t('app','Facilities');?></span></a></li>
                                <?php } ?>
                                <?php if($Modules->checkHiddenModule('pos')){ ?>
                                <li class="pos active"><a href="<?php echo Yii::$app->urlManager->createUrl('/pos/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','POS');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos.png"><?php echo Yii::t('app','POS');?></span></a></li>
                                <?php }  ?>
                                <?php if($Modules->checkHiddenModule('course')){ ?>
                                <li class="course active"><a href="<?php echo Yii::$app->urlManager->createUrl('/course/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Course');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/education.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/education.png"><?php echo Yii::t('app','Courses');?></span></a></li>
								<?php } ?>
                                <?php if($Modules->checkHiddenModule('feedback')){ ?>
                                <li class="feedback active"><a href="<?php echo Yii::$app->urlManager->createUrl('/feedback/feedback/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Feedback');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/feedback.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/feedback.png"> <?php echo Yii::t('app','Feedback');?></span></a></li>
                                <?php } ?>
                                <br>
                                
								<li class="report active"><a id="report-list" style="cursor:pointer"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check-report.png">&nbsp;</span><span class="full-nav "><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check-report.png"><?php echo Yii::t('app','Report');?>   <i class="fa fa-angle-down pull-right"></i></span></a></li>
								
								<div id="report-items" style="display:none;" >
									<?php if($canAccountReceivable){ ?>
									<li class="receivable active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/receivable');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Account Receivable');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/member.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/member.png"><?php echo Yii::t('app','Account Receivable');?></span></a></li>
									<?php } ?>
									<?php if($canPaymentReport){ ?>
									<li class="paymentreport active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/paymentreport');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Payment Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/payment.png"><?php echo Yii::t('app','Payment Report');?></span></a></li>
									<?php } ?>
									<?php if($canMembershipExpiryReport){ ?>
									<li class="membershipreport active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/membershipreport');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Membership Expiry');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/expiry.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/expiry.png"><?php echo Yii::t('app','Membership Expiry');?></span></a></li>
									<?php } ?>
									<?php if($canMemberCheckin){ ?>
									<li class="rchekin active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/chekin');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Checkin/out Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check.png"><?php echo Yii::t('app','Checkin/out Report');?></span></a></li>
									<?php } ?>
									<?php if($canMembersReport){ ?>
									<li class="membersreport active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/membersreport');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Members Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members-report.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/members-report.png"><?php echo Yii::t('app','Members Report');?></span></a></li>
									<?php } ?>
									<?php if($canHistoryBooking){ ?>
									<li class="history_booking active"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/history_booking');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Booking Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/bookmark.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/bookmark.png"><?php echo Yii::t('app','Booking Report');?></span></a></li>
									<?php } ?>
									<?php if($canPtSessionServiceReport){ ?>
									<li class="pt_session_service_report active"role="presentation" id = "chekin" ><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/pt_session_service_report');?>" ><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Pt Session Service Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check-report.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/check-report.png"><?php echo Yii::t('app','Pt Session Service Report');?></span></a></li>
									<?php } ?>
									<?php if($canPtTrainingReport){ ?>
									<li class="pt_training_report active"role="presentation" id = "chekin" ><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/pt_training_report');?>" ><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Pt Training Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/training.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/training.png"><?php echo Yii::t('app','Pt Training Report');?></span></a></li>
									<?php } ?>
								   <?php if($canPosReport){ ?>
									<li class="pos_report active"role="presentation"><a href="<?php echo Yii::$app->urlManager->createUrl('/report/default/pos_report');?>" ><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Pos Report');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos_report.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos_report.png"><?php echo Yii::t('app','Pos Report');?></span></a></li>
									<?php } ?>
								</div>	
                                <br>
                                <?php if(Yii::$app->user->can('admin')){ ?>
                                <li class="configuration active"><a href="<?php echo Yii::$app->urlManager->createUrl('site/configuration');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Configuration');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/types.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/types.png"><?php echo Yii::t('app','Configuration');?></span></a></li>
                                <?php } ?> 
                                <?php if($Modules->checkHiddenModule('history')) { ?>
                                <li class="history active"><a href="<?php echo Yii::$app->urlManager->createUrl('/history/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','History');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/history.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/history.png"><?php echo Yii::t('app','History');?></span></a></li>
                                <?php } ?>
                                <?php if($roleName=='admin') { ?>
                                <li class="permission active"><a href="<?php echo Yii::$app->urlManager->createUrl('/permission/default/index');?>"><span class="small-nav" data-toggle="tooltip" data-placement="right" title="<?php echo Yii::t('app','Permission');?>"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/documents.png">&nbsp;</span><span class="full-nav"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/documents.png"><?php echo Yii::t('app','Permission');?></span></a></li>
                                <?php } ?>
                            </ul>
                        </nav>
                </div>  
            </div>
            <button id="menu-open" type="button" class="btn btn-default btn-xs navbar-twitch-toggle" onclick="close123(<?php echo YII::$app->user->id; ?>,1);">	
                <span class="nav-close" style="width:20px; margin-left: -6px; margin-right: -6px;"><span id = "2" class="glyphicon glyphicon-chevron-left" ></span></span>
            </button>
            <button id="menu-close" type="button" class="btn btn-default btn-xs navbar-twitch-toggle nav-open" onclick="open123(<?php echo YII::$app->user->id; ?>,0); return false;">
                <span class="nav-open" style="width:20px;    margin-left: -6px;    margin-right: -6px;"><span id = "1" class="glyphicon glyphicon-chevron-right"  ></span></span>	
            </button>
        
                <?php } ?>

        <?= $content ?>

<footer class="footer" >
    <div class="container">
        <p class="pull-left"></p>
        <p class="pull-right" hidden=""><?= Yii::powered() ?></p>
    </div>
</footer>

<div style="display: none" id="popup-tour">
    <ul class="list-group">
        <li class="list-group-item"><a href="#" onclick="startTour(<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>);"><?php echo Yii::t('app','MemberShip Type');?></a></li>
        <li class="list-group-item"><a href="#" onclick="startTour(<?php echo app\models\Config::TOUR_MEMBER; ?>);"><?php echo Yii::t('app','Members');?></a></li>
        <li class="list-group-item"><a href="#" onclick="startTour(<?php echo app\models\Config::TOUR_CHECKIN; ?>);"><?php echo Yii::t('app','Checkin/out');?></a></li>
        <li class="list-group-item"><a href="#" onclick="startTour(<?php echo app\models\Config::TOUR_FACILITY; ?>);"><?php echo Yii::t('app','Facility');?></a></li>
        <li class="list-group-item"><a href="#" onclick="startTour(<?php echo app\models\Config::TOUR_TRAINER; ?>);"><?php echo Yii::t('app','Trainner');?></a></li>
<!--        <li class="list-group-item"><a href="#" onclick="startTour(<?php //echo app\models\Config::TOUR_BOOKING; ?>);"><?php //echo Yii::t('app','Booking');?></a></li>-->
        <li class="list-group-item"><a href="#" onclick="contact();"><i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('app','Contact');?></a></li>
        <?php if(!$dete_data){ ?>
        <li>
            <i class="glyphicon glyphicon-trash"></i> 
            <a href="#" onclick="deletedatademo();">
                <?php echo Yii::t('app', 'remote demo data'); ?>
            </a>
        </li>
        <?php } ?>
    </ul>
</div>
        
<!-- MODAL END TOUR -->
<div id="bs-model-checkin-endtour" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 500px;">
        <div  class="modal-content">
           <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
           <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
           <div>
               <h4 class="modal-title"><?php echo YII::t('app', "Finish") ?></h4>
           </div>
           </div>
            <div class="modal-body" id="modal-conten-welcometour" style="text-align: center">
                <br>
                <?php echo Yii::t('app','Our tour has ended here. Thank you.');?><br><br>
                <button class="btn btn-default" onclick="endTour()" type="button" data-dismiss="modal" aria-hidden="true"><?php echo Yii::t('app','Close'); ?></button>
                <br>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL END TOUR -->

<!-- MODAL WELCOME TOUR -->
    <div id="bs-model-checkin-welcometour" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 500px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "Let's start") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-conten-welcometour" style="text-align: center">
                    <br>
                    <?php echo Yii::t('app','Welcome to Parklife, We would like to give you a tour.'); ?>
                    <br><br>
                    <button class="btn btn-default" id="btn-letstart" href="" onclick="letStart();" type="button" data-dismiss="modal" aria-hidden="true"><?php echo Yii::t('app',"Let's start"); ?></button>
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL WELCOME TOUR -->

<!-- MODAL CONTACT -->
    <div id="bs-model-checkin-contact" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg popupCheckin" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 500px;">
            <div  class="modal-content">
                <div class="modal-header" style="background-color: rgb(50, 205, 139); color:#fff; text-align:center">
                    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
               <div class="fa fa-2x fa-calendar-plus-o" style="float:left;margin-right:10px"></div>
               <div>
                   <h4 class="modal-title"><?php echo YII::t('app', "Contact") ?></h4>
               </div>
               </div>
                <div class="modal-body" id="modal-conten-contact" style="text-align: left; padding: 20px;">
                    <div class="alert alert-info">
                      <?php echo Yii::t('app', 'Please feel free to get in touch, we value your feedback.'); ?>
                    </div>
                    <form id="form-contact">
                        <div class="form-group">
                            <label for="support_name"><?php echo Yii::t('app', 'Name'); ?> *</label>
                            <input type="text" class="form-control" name="support_name" id="support_name">
                        </div>
                        <div class="form-group">
                          <label for="support_email"><?php echo Yii::t('app', 'Email'); ?> *</label>
                          <input type="email" class="form-control" name="support_email" id="support_email">
                        </div>
                        <div class="form-group">
                          <label for="support_subject"><?php echo Yii::t('app', 'Subject'); ?> *</label>
                          <input type="text" class="form-control" name="support_subject" id="support_subject">
                        </div>
                        <div class="form-group">
                          <label for="support_massage"><?php echo Yii::t('app', 'Message'); ?> *</label>
                          <textarea class="form-control" rows="5" name="support_massage" id="support_massage"></textarea>
                        </div>
                        <button type="button" onclick="sendContact();return false;" class="btn btn-success"><?php echo Yii::t('app','Send');?></button>
                   </form> 
                </div>
            </div>
        </div>
    </div>
    <input id = "isset" type="hidden" <?php if(isset(Yii::$app->user->identity->menu)){ ?>value =<?php echo Yii::$app->user->identity->menu;} ?>>
</div>
<!-- END MODAL CONTACT -->

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>

<script type="text/javascript">
   
    var class_active = '<?php echo $module_name; ?>';
	$('.parkclub-menu li').removeClass("parckclub-active");
	$('.'+class_active).addClass("parckclub-active");
	var class_active_parent_id = $('.'+class_active).parent().attr('id');
	if(class_active_parent_id == 'report-items')
		$( "#report-items" ).slideToggle({
		});
 
    function deletedatademo(){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/deletedatademo'); ?>',
            'data':{},
            'beforeSend':function(){
                if(confirm('<?php echo Yii::t("app","Are you sure you want to delete this item?") ?>')){
                    $.blockUI({ message: '' });
                    return true;
                }else{
                    return false;
                }
            },
            'success':function(data){
                $.unblockUI();
                location.reload();
            }
        });
    }
    
    function startTour(tour_step){
        $.ajax({
            'type':'POST',
            'url':'<?php echo Yii::$app->urlManager->createUrl('/starttour'); ?>',
            'data':{tour_step:tour_step},
            'success':function(data){
                if(data=='<?php echo app\models\Config::TOUR_MEMBERSHIP_TYPE; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('configuration'); ?>';
                }
                if(data=='<?php echo app\models\Config::TOUR_MEMBER; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('members/default/index?m=members'); ?>';
                }
                if(data=='<?php echo app\models\Config::TOUR_CHECKIN; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('checkin/default/index'); ?>';
                }
                if(data=='<?php echo app\models\Config::TOUR_FACILITY; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('facility/default/index'); ?>';
                }
                if(data=='<?php echo app\models\Config::TOUR_TRAINER; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('trainer/default/index?m=trainers'); ?>';
                }
                if(data=='<?php echo app\models\Config::TOUR_BOOKING; ?>'){
                    location.href='<?php echo Yii::$app->urlManager->createUrl('booking/default/index?tab=0'); ?>';
                }
            }
        });
    }
    
    function endTour(tour_step){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    }
    

    $("#help-tour").popover({
            content: '', 
            html: true,
            placement: 'bottom', 
            title: '<?php echo YII::t("app","Activities"); ?>',
            template: '<div class="popover help-tour-popover-medium" style="width: 500px"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div></div>'

        });
    $('#help-tour').on('shown.bs.popover', function () {
        var data = $('#popup-tour').html();
        $("#help-tour-content .popover-content").html(data);
    })
    
    function letStart(){
        $('#bs-model-checkin-welcometour').modal('hide');
        location.href=$('#btn-letstart').attr('href');
    }
    
var data_demo = new Tour({
    backdrop:true,
    backdropContainer: 'body',
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('/endtour');?>',
            data:{},
            success:function(data){
                
            }
        });
    },
    steps: [
         {
            element: ".parkclub-search",
            title: "Title of my step",
            content: '<?php echo Yii::t('app','<p>In Checkin/Checkout tab, members can check in by their member cards</p>In case, they forgot their cards, receptionist can help them check in/out by their <b>ID number</b>, <b>mobile phone</b> or <b>member barcode</b>.');?>',
            placement:"left",
            onShow: function(){
                $('#txt_card_id').val('MS20171');
            }
        },
        {
           element: ".parkclub-checkbtn",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Press "Checkin/out" button or "Enter" to show up member detailed information.');?>',
           placement:"left",
           backdrop:false,
            onNext: function(){
                popcheckin();
            },
         },
        {
           element: "#pop_checktour",
           backdrop:false,
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Press "Checkin/out" button or "Enter" to finish the process.');?>',
            onNext: function(){
                $('#pop_checktour').click();
            },
         },
        {
           element: ".receivable",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','In Account Receivable, tracking and checking your business become so easy.');?>',
            onNext: function(){
                document.location.href = '<?php echo Yii::$app->urlManager->createUrl('report/default/receivable'); ?>';
            },
         },
        {
           element: ".paymentreport",
           title: "Title of my step",
           content: '<?php echo Yii::t('app',"In Payement Report, you can have an overview about financial status of your business.");?>',
            onNext: function(){
                document.location.href = '<?php echo Yii::$app->urlManager->createUrl('report/default/paymentreport'); ?>';
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         },
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour
var tour_no_demo = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
                
            }
        });
    },
    steps: [
        {
            element: ".membership_type",
            title: "Title of my step",
            content: '<?php echo Yii::t("app","In order to add new membership, please click here. Press, Next to continue.");?>',
            onNext: function(){
                document.location.href = '<?php echo Yii::$app->urlManager->createUrl('membership_type/default/index'); ?>';
            }
        },
        {
           element: "#tour-add-membership-type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Please click here to create new membership type.');?>',
           placement:"left",
            onNext: function(){
                $('#tour-add-membership-type').click();
            },
         },
        {
           element: "#form_membership_type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Fill in membership information.');?>',
           placement:"left",
         },
        {
           element: "#tour-create-membership-type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Click here to save changes');?>',
            onNext: function(){
                $('#tour-create-membership-type').click();
            },
         },
        {
           element: "#form_membership_type_price",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'In order to insert membership price, please fill in  price and period field.');?>',
           placement:"left",
         },
        {
           element: "#create-mbshiptype-price",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'After filling in price and period, please click "Create" to save.');?>',
            onNext: function(){
                $('#create-mbshiptype-price').click();
            },
         },
        {
           element: ".members",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Press "Next to continue" and add a guest or a member.');?>',
            onNext: function(){
                document.location.href = '<?php echo Yii::$app->urlManager->createUrl('members/default/index?m=members'); ?>';
            },
         },
{
           element: "#add-guest",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Click on "Add guest" to create a guest or member account.');?>',
           placement:"left",
            onNext: function(){
                $('#add-guest').click();
            },
         },
        {
           element: "#form_membership",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Fill in required information for a guest or a member.');?>',
           placement:"left"
         },
        {
           element: "#membership_type_id",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'If you want the guest become member, select a membership here.<p>If not, just leave this field blank.</p>');?>',
           placement:"left"
         },
        {
           element: "#create-member",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'After filling in information, Click on "Add guest" to save.  If you choose memberships, the system will show up invoice for the payment');?>',
           placement:"left",
            onNext: function(){
                $('#create-member').click();
            },
         },  
        {
           element: "#new-payment",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Create new payment here.');?>',
            onNext: function(){
                $('#new-payment').click();
            },
         }, 
        {
           element: "#create-invoice",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Press Paid to confirm member payment and print receipt for your customers.');?>',
            onNext: function(){
                $('#create-invoice').click();
            },
         }, 
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour
var tour_membership_type = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
            element: ".revenue_type",
            title: "Title of my step",
            content: '<?php echo Yii::t("app","Nhấn vào đây để chỉnh sửa các thông tin cấu hình của hệ thống.");?>',
        },
        {
            element: "#cog-membership-type",
            title: "Title of my step",
            content: '<?php echo Yii::t("app","In order to add new membership, please click here. Press, Next to continue.");?>',
            onNext: function(){
                 document.location.href = '<?php echo Yii::$app->urlManager->createUrl('/membership_type/default/index'); ?>';
            },
        },
        {
           element: "#tour-add-membership-type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Please click here to create new membership type.');?>',
           placement:"left",
            onNext: function(){
                $('#tour-add-membership-type').click();
            },
         },
        {
           element: "#form_membership_type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Fill in membership information.');?>',
           placement:"left",
         },
        {
           element: "#tour-create-membership-type",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Click here to save changes');?>',
            onNext: function(){
                $('#tour-create-membership-type').click();
            },
         },
        {
           element: "#form_membership_type_price",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'In order to insert membership price, please fill in  price and period field.');?>',
           placement:"left",
         },
        {
           element: "#create-mbshiptype-price",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'After filling in price and period, please click "Create" to save.');?>',
            onNext: function(){
                $('#create-mbshiptype-price').click();
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});
// Instance the tour
var tour_member = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
           element: ".members",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Press "Next to continue" and add a guest or a member.');?>',
         },
        {
           element: "#add-guest",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Click on "Add guest" to create a guest or member account.');?>',
           placement:"left",
            onNext: function(){
                $('#add-guest').click();
            },
         },
        {
           element: "#form_membership",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Fill in required information for a guest or a member.');?>',
           placement:"left"
         },
        {
           element: "#membership_type_id",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'If you want the guest become member, select a membership here.<p>If not, just leave this field blank.</p>');?>',
           placement:"left"
         },
        {
           element: "#create-member",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'After filling in information, Click on "Add guest" to save.  If you choose memberships, the system will show up invoice for the payment');?>',
           placement:"left",
            onNext: function(){
                $('#create-member').click();
            },
         },  
        {
           element: "#new-payment",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Create new payment here.');?>',
            onNext: function(){
                $('#new-payment').click();
            },
         }, 
        {
           element: "#create-invoice",
           title: "Title of my step",
           content: '<?php echo Yii::t("app",'Press Paid to confirm member payment and print receipt for your customers.');?>',
            onNext: function(){
                $('#create-invoice').click();
            },
         }, 
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour
var tour_checkout = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
         {
            element: ".parkclub-search",
            title: "Title of my step",
            content: '<?php echo Yii::t('app','<p>In Checkin/Checkout tab, members can check in by their member cards</p>In case, they forgot their cards, receptionist can help them check in/out by their <b>ID number</b>, <b>mobile phone</b> or <b>member barcode</b>.');?>',
            placement:"left",
            onShow: function(){
                $('#txt_card_id').val('MS20171');
            }
        },
        {
           element: ".parkclub-checkbtn",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Press "Checkin/out" button or "Enter" to show up member detailed information.');?>',
           placement:"left",
            onNext: function(){
                popcheckin();
            },
         },
        {
           element: "#pop_checktour",
           backdrop:false,
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Press "Checkin/out" button or "Enter" to finish the process.');?>',
            onNext: function(){
                $('#pop_checktour').click();
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour
var tour_facility = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
         {
            element: ".facility",
            title: "Title of my step",
            content: '<?php echo Yii::t('app','Nhấn vào đây để vào quản lý các sân phòng của hệ thống.');?>',
            placement:"right",
        },
        {
           element: "#btn-add-trainer",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để tào phòng mới.');?>',
           placement:"left",
            onNext: function(){
                document.location.href = '<?php echo Yii::$app->urlManager->createUrl('/facility/default/create'); ?>';
            },
         },
        {
           element: "fieldset",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhập thông tin của phòng ở đây.');?>',
           placement:"top",
            onShow: function(){
                $('#facility-facility_name').val('Gym');
            }
         },
        {
           element: "#submit-facility",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để lưu phòng vào hệ thống.');?>',
            onNext: function(){
                $('#submit-facility').click();
            },
         },
        {
           element: "#bnt-add-limit",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để thêm giới hạn số lần đặt chỗ của mỗi thẻ hội viên. Giới hạn số lần đặt theo ngày, theo tuần và theo tháng.');?>',
            onNext: function(){
                $('#bnt-add-limit').click();
            },
                    placement:"left",
         },
        {
           element: "#limit_1",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Chọn thẻ hội viên và số lần giới hạn đặt phòng cho thẻ hội viên này.');?>',
           placement:"top",
         },
        {
           element: "#bnt-save-limit",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để lưu phòng vào hệ thống.');?>',
            onNext: function(){
                $('#bnt-save-limit').click();
            },
                    placement:"left",
         },
        {
           element: "#btn-add-price",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để thêm giá phòng.');?>',
            onNext: function(){
                $('#btn-add-price').click();
            },
                   placement:"left", 
         },
        {
           element: "#price_101",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhập thông tin giá phòng váo đây. Nếu chọn tất cả giá này sẽ áp dụng cho tất cả các thẻ hội viên.');?>',
           placement:"top",
         },
        {
           element: "#bnt-save-price",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để lưu giá phòng.');?>',
            onNext: function(){
                $('#bnt-save-price').click();
            },
                    placement:"left", 
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour trainer
var tour_trainer = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
         {
            element: ".trainer",
            title: "Title of my step",
            content: '<?php echo Yii::t('app','Nhấn vào đây để vào quản lý các huấn luận viên.');?>',
            placement:"right",
        },
        {
           element: "#btn-add-trainer",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn và đây để thêm mới huấn luận viên.');?>',
           placement:"left",
            onNext: function(){
                $('#btn-add-trainer').click();
            },
         },
        {
           element: ".parkclub-wrapper",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhập thông tin huấn luận viên ở đây.');?>',
           placement:"top",
            onShow: function(){
                $('#member_first_name').val('Lê');
                $('#member_surname').val('Hiếu');
                $('#member_address').val('28 Tran Hung Dao, Hai Ba Trung, Ha Noi');
                $('#member_mobile').val('0924234');
                $('#member_phone').val('0924234');
                checkMember('0924234','member_mobile');
            }
         },
        {
           element: "#btn-submit-trainer",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','Nhấn vào đây để lưu thông tin huấn luận viên vào hệ thống. ');?>',
           placement:"left",
            onNext: function(){
                $('#btn-submit-trainer').click();
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour booking
var tour_booking1 = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
        {
           element: ".modal-select",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','1');?>',
           placement:"left",
            onNext: function(){
                $('#submit').click();
            },
         },
        {
           element: "#submit",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','2');?>',
           placement:"left",
            onNext: function(){
                $('#submit').click();
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});

// Instance the tour trainer
var tour_booking = new Tour({
    backdrop:true,
    onEnd:function(tour){
        $.ajax({
            type:'POST',
            url:'<?php echo Yii::$app->urlManager->createUrl('endtour'); ?>',
            data:{},
            success:function(data){
            }
        });
    },
    steps: [
         {
            element: ".booking",
            title: "Title of my step",
            content: '<?php echo Yii::t('app','1');?>',
            placement:"right",
        },
        {
           element: "#calendar_test",
           title: "Title of my step",
           content: '<?php echo Yii::t('app','2');?>',
           placement:"top",
            onNext: function(){
                $('#modalContent').load('<?php echo Yii::$app->urlManager->createUrl('booking/default/create?my_start_time=18:00&my_end_time=18:30&start=2017-08-29&end=2017-08-29&faciliti_id=4'); ?>',function(){
                    $('#modal-select').modal('show');
                });
            },
         },
        {
           element: ".members1123",
           title: "Title of my step",
           content: "Content of my step",
         }, 
    ],
    template:'<div class="popover" role="tooltip"> <div class="arrow"></div> <div class="popover-content"></div><div class="arrow"></div> <div class="popover-navigation"> <div class="btn-group"> <button class="btn btn-sm btn-info" data-role="next"><?php echo Yii::t('app','Next');?> &raquo;</button> </div>&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-default" data-role="end"><?php echo Yii::t('app','End tour');?></button> </div> </div>'
});




var intall_data = '<?php echo Yii::$app->session['install-data']; ?>';
var tour = '<?php echo Yii::$app->session['tour']; ?>';
if(intall_data==2 && tour==1){
    tour_no_demo.start();
}

var isset_menu = $('#isset').val();
$(document).ready(function() {
    $('.navbar-nav [data-toggle="tooltip"]').tooltip();
    $('.navbar-twitch-toggle').on('click', function(event) {
        event.preventDefault();
        $('.navbar-twitch').toggleClass('open');
    });
    
    $('.nav-style-toggle').on('click', function(event) {
        event.preventDefault();
        var $current = $('.nav-style-toggle.disabled');
        $(this).addClass('disabled');
        $current.removeClass('disabled');
        $('.navbar-twitch').removeClass('navbar-'+$current.data('type'));
        $('.navbar-twitch').addClass('navbar-'+$(this).data('type'));
    });
  
    if(isset_menu ==0 ){
        $('.parkclub-subtop-bar').css({'width':'calc(100% - 250px)'});
        $('.parkclub-wrapper').css({'width':'calc(100% - 250px)'});
        $('.parkclub-new-member-wap').css({'width':'calc(100% - 250px)'});
        $('.parkclub-nav').css({'width':'calc(100% - 250px)'});
		$('.parkclub-wrapper-small').css({'width':'calc(50% - 125px)'});
    }
    if(isset_menu ==1 ){
        $('.parkclub-subtop-bar').css({'width':'calc(100% - 51px)'});
        $('.parkclub-wrapper').css({'width':'calc(100% - 51px)'});
        $('.parkclub-new-member-wap').css({'width':'calc(100% - 51px)'});
        $('.parkclub-nav').css({'width':'calc(100% - 51px)'});
		$('.parkclub-wrapper-small').css({'width':'calc(50% - 25px)'});
        }
		
	$("#report-list").click(function () {
		$("#report-list").addClass('parckclub-active');
		$( "#report-items" ).slideToggle({
		});
	});  

});
function open123(id,menu){
    $('.parkclub-subtop-bar').css({'width':'calc(100% - 250px)'});
        $('.parkclub-wrapper').css({'width':'calc(100% - 250px)'});
        $('.parkclub-wrapper-small').css({'width':'calc(50% - 125px)'});
        $('.parkclub-new-member-wap').css({'width':'calc(100% - 250px)'});
        $('.parkclub-nav').css({'width':'calc(100% - 250px)'});
    $.ajax({
            'url':'<?php echo Yii::$app->urlManager->createUrl('/user/changemenu'); ?>',
            type: 'post',
            data: {
                      id: id , 
                      menu: menu
                  },
            success: function (data) {
                load_chart_year();
                load_chart_revenue();
                load_chart_checkin();
                load_chart_booking();
                load_chart_facility('<?php echo date('Y'); ?>');
                load_chart_members();
            }
       });
    }
    function close123(id,menu){
        $('.parkclub-subtop-bar').css({'width':'calc(100% - 51px)'});
        $('.parkclub-wrapper').css({'width':'calc(100% - 51px)'});
        $('.parkclub-wrapper-small').css({'width':'calc(50% - 25px)'});
        $('.parkclub-new-member-wap').css({'width':'calc(100% - 51px)'});
        $('.parkclub-nav').css({'width':'calc(100% - 51px)'});
        $.ajax({
            'url':'<?php echo Yii::$app->urlManager->createUrl('/user/changemenu'); ?>',
            type: 'post',
            data: {
                      id: id , 
                      menu: menu
                  },
            success: function (data) {
                load_chart_year();
                load_chart_revenue();
                load_chart_checkin();
                load_chart_booking();
                load_chart_facility('<?php echo date('Y'); ?>');
                load_chart_members();
            }
       });
    }
    
    function contact(){
        $('#help-tour').click();
        $('#bs-model-checkin-contact').modal('show');
    }
    
    function sendContact(){
        var data_form = $('#form-contact').serialize();
        var check_validate = 0;
        if($('#support_email').val()==""){
            $('#support_email').addClass("error");
            check_validate = 1;
        }
        else if(validateEmail($('#support_email').val())==false){
            $('#support_email').addClass("error");
            check_validate = 1;
        }else{
            $('#support_email').removeClass("error");
        }
        if($('#support_name').val()==""){
            $('#support_name').addClass("error");
            check_validate = 1;
        }else{
            $('#support_name').removeClass("error");
        }
        if($('#support_subject').val()==""){
            $('#support_subject').addClass("error");
            check_validate = 1;
        }else{
            $('#support_subject').removeClass("error");
        }
        if($('#support_massage').val()==""){
            $('#support_massage').addClass("error");
            check_validate = 1;
        }else{
            $('#support_massage').removeClass("error");
        }
        if(check_validate==0){
            $('#bs-model-checkin-contact').modal('hide');
            $.blockUI();
            $.ajax({
                type:'POST',
                url:'<?php echo Yii::$app->urlManager->createUrl('/contact'); ?>',
                data:data_form,
                success:function(data){
                    data = JSON.parse(data);
                    if(data.status=='success'){
                        swal({
                          title: '<?php echo Yii::t("app","Successfully sent!"); ?>',
                          text: '<?php echo Yii::t("app","Thanks for contacting. We will revert to you as soon as possible."); ?>',
                          type: "success",
                          showConfirmButton: true,
                          confirmButtonText:'<?php echo Yii::t('app','Close')?>',
                        });
                    }
                    else
                        alert('Fail');
                    $.unblockUI();
                    
                }
            });
        }
    }
    
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

</script>

