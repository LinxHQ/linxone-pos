<?php
/**
 * author thongnv 
 */

?>

<div class="parkclub-wrapper parkclub-wrapper-search">
    <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app', 'Permission');?>
                </div>
            </div>
        <br>
        <div class="parkclub-rectangle-content" style="padding: 20px;">
            <p><a href="<?php echo YII::$app->urlManager->createUrl('permission/default/view_modules'); ?>"><i class="glyphicon glyphicon-inbox"></i> <?php echo Yii::t('app', 'View modules');?></a></p>
            <p><a href="<?php echo YII::$app->urlManager->createUrl('permission/roles'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('app', 'User roles');?></a></p>
        </div>
    </div>
</div>