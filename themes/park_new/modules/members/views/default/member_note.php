<?php

use yii\helpers\Html;

use app\modules\members\models\MembersNote;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\modules\members\models\Members */
$user = new app\models\User();
$uer_arr = $user->getUser();

$DefinePermission = new app\modules\permission\models\DefinePermission();
$canDeletenotes = $DefinePermission->checkFunction('members', 'Delete notes');
     
?>
<table id="table-note" style="width:100%;"> 
    <tr>
        <th style="padding-left: 10px;"><?php echo Yii::t('app', 'Date');?></th>
        <th style="padding-left: 40px; width: 50%"><?php echo Yii::t('app', 'Note');?></th>
        <th style="padding-left: 10px;"><?php echo Yii::t('app', 'Add note by');?></th>
        <th style="padding-left: 10px; text-align: center;"><?php echo Yii::t('app','Show checkin');?></th>
        <th style="padding-left: 10px;"></th>
    </tr>
<?php 
$modelNote = MembersNote::findAll(['member_id'=>$member_id,'is_guest_crm'=>0]);

foreach($modelNote as $dataNote){?>
<tr id="note_member<?php echo $dataNote->member_note_id;?>">
    <td>
        <span><?php echo ($dataNote->note_date && $dataNote->note_date !="0000-00-00")?date('d/m/Y H:i:s',  strtotime($dataNote->note_date)):"";  ?></span>
    </td>
    <td>
		<textarea id="note-<?php echo $dataNote->member_note_id;?>" onchange="editNote(<?php echo $dataNote->member_note_id;?>)"><?php echo $dataNote->note; ?></textarea>
	</td>
    <td>
       <?php 
       if($dataNote->created_by > 0)
            echo $uer_arr[$dataNote->created_by]; 
       $check = ($dataNote->show_checkin==1) ? 0 : 1;
       ?>
    </td>
    <td style="text-align: center">
        <div class="checkbox">
            <label style="font-size: 1.5em">
                <?php echo yii\bootstrap\Html::checkbox('show_checkin',$dataNote->show_checkin , ['id'=>'show_checkin','onclick'=>'updateNote('. $dataNote->member_note_id.','.$check.'); return false;']); ?>
                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
            </label>
        </div>
    </td>
	<?php if($canDeletenotes) { ?>
	<td>
	<a href="#" onclick="delete_note(<?php echo $dataNote->member_note_id;?>);return false;"><i class="glyphicon glyphicon-trash"></i></a>
	</td>
	<?php } ?>
</tr>
<?php } ?>

</table>
