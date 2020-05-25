<div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px">
    <p><?php echo Yii::t('app', 'Phone'); ?>: <?php echo $member->member_mobile; ?></p>
    <p><?php echo Yii::t('app', 'Email'); ?>: <?php echo $member->member_email; ?></p>
    <p><?php echo Yii::t('app', 'Address'); ?>: <?php echo $member->getMemberFullAddress($member->member_id); ?></p>
</div>

