<?php
    use app\modules\members\models\Members;
    use app\modules\feedback\models\Feedback;
    use app\models\User;
?>
<div class="media-body">
  <div class="well well-lg">
      <h4 ><?php 
      $member = new Members();
          $user = new User();   
          $name = "";
          $feedback = new Feedback();
          $model = $feedback->findOne($comment->comment_entity_id);
          if($model->member_id > 0){
              $name = $member->getMemberFullName($model->member_id);
          }else{
              $name = $model->guest_name;
          }
          echo $name;
      
       ?> </h4>
      <ul class="media-date text-uppercase reviews list-inline">
                    <li><?php echo date("H:i:s d/m/Y", strtotime($comment->comment_create_date)); ?></li>
                  </ul>
      <p class="media-comment">
        <?php echo $comment->comment_content; ?>
      </p>

  </div>              
</div>
<div id = "comment-detail">
    <ul class="media-list">
        <?php foreach($comment_reply as $item){
            echo '<li class="media media-replied">';
            echo '<div class="media-body">';
            echo '<div class="well well-lg well-ms">';
            if(isset($item->comment_create_by) && $item->comment_create_by>0){
                echo '<h4 ><span >admin</span></h4>';
            }else{
                echo '<h4 ><span>'.$name.'</span></h4>';
            }
            
            echo '<ul class="media-date text-uppercase reviews list-inline">';
            echo '<li>'.date("H:i:s d/m/Y", strtotime($item->comment_create_date)).'</li>';
            echo '</ul>';
            echo '<p class="media-comment">'.$item->comment_content.'</p>';
            echo '<a onclick = "delete_comment('.$item->comment_id.');return false;" href ="#">Xóa</a>';
            echo '</div> </div> </li>';
        }  
        ?>
    </ul>  
</div>
<div class="media-body">
  <div class="well well-lg well-ms" id = "comment-reply">
      <input type='text' id='comment-reply-content'>
      <div style="float:right;">
        <button type="button" id="save-reply" onclick="saveReply();return false;" class="btn btn-success btn-small"><?php echo Yii::t('app', 'Save'); ?></button>
      </div>
  </div>                                                                                                                                                                                                                                                                                                                                n 
</div>

<script type="text/javascript">
    
    function saveReply(){
        var comment_content = $('#comment-reply-content').val();
        var comment_parent = '<?php echo $comment->comment_id; ?>';
        var feedback_id = '<?php echo $model->feedback_id; ?>';

        $.ajax({
            'type':'POST',
            'url':'<?php echo YII::$app->urlManager->createUrl('/comment/comment/create'); ?>',
            'data':{comment_content:comment_content,comment_parent:comment_parent, feedback_id:feedback_id},
            'success':function(data){
                if(data=='success'){
                    location.reload();
                }else{
                    alert('<?php echo Yii::t('app','Không lưu được comment.'); ?>');
                }
            }
        });
    }
    
    function delete_comment(id){
    var result = confirm("Are you sure you want to delete this item?");
        if(result){
            $.ajax({
                'type':'POST',
                'url':'<?php echo YII::$app->urlManager->createUrl('/comment/comment/delete-comment');  ?>',
                'data':{id:id},
                'success':function(data){
                    if(data = "Success"){
                        $("#comment-detail").load(location.href + " #comment-detail");
                    }
                }
            });
        }
    }
</script>