<?php
$ListSetup = new \app\models\ListSetup();
?>
<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <?php echo Yii::t('app','Price');?>
                </div>
            </div>
            <div class="parkclub-rectangle-content" id="table_price">
            <table style="margin-bottom: 0px;" >
                <tr>
                    <th>#</th>
                    <th width="20%"><?php echo Yii::t('app','Membership Type');?></th>
                    <th width="10%"><?php echo Yii::t('app','Name');?></th>
                    <th width="10%"><?php echo Yii::t('app','Tax');?></th>
                    <th width="10%"><?php echo Yii::t('app','Price Before Tax');?></th>
                    <th width="10%"><?php echo Yii::t('app','Price After Tax');?></th>
                    <th width="15%"><?php echo Yii::t('app','Start date');?></th>
                    <th width="15%"><?php echo Yii::t('app','End date');?></th>
                    <th><?php echo Yii::t('app','Actions');?></th>
                </tr>
                <?php 
                $stt_price = 0;
                foreach ($facility_price as $facility_price_item) { 
                $stt_price++;    
                ?>
                <tr id='price_<?php echo $stt_price; ?>'>
                    <td><?php echo $stt_price; ?></td>
                    <td><?php echo (($facility_price_item->membershipType) ? $facility_price_item->membershipType->membership_name : Yii::t('app',"All")); ?></td>
                    <td><?php echo $facility_price_item->facility_price_name; ?></td>
                    <td><?php 
                        $tax_arr = \app\models\ListSetup::getItemByList('Tax');
                        echo ((isset($tax_arr[$facility_price_item->facility_price_tax])) ? $tax_arr[$facility_price_item->facility_price_tax] : '0'); ?>%
                    </td>
                    <td><?php echo $facility_price_item->facility_price; ?></td>
                    <td><?php echo $facility_price_item->facility_price_after_tax; ?></td>
                    <td><?php echo $ListSetup->getDisplayDate($facility_price_item->facility_startdate); ?></td>
                    <td><?php echo $ListSetup->getDisplayDate($facility_price_item->faclity_enddate); ?></td>
                    <td>
                        <a href="<?php echo yii::$app->urlManager->createUrl('/facility/default/update_price?price_id='.$facility_price_item->history_facility_id) ?>" ><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;
                        <a onclick="return confirm('<?php echo Yii::t('app','Are you sure you want to delete this item?');?>');" href="<?php echo yii::$app->urlManager->createUrl('/facility/default/delete_price?price_id='.$facility_price_item->history_facility_id) ?>" ><span class="glyphicon glyphicon-remove"></span></a>&nbsp;
                    </td>
                </tr>
                <?php } ?>
            </table>
           </div>
       </div>
        <div class="parkclub-footer">
            <button onclick="addPrice();return false;" id="btn-add-price" type="button" class="btn btn-primary" ><?php echo Yii::t('app','Add Price');?></button>
        </div>
    </div>
</div>