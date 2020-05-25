<div id="html_webcome" style="text-align: center;">
                <script src="<?php echo Yii::$app->request->baseUrl; ?>/js/jquery.min.js" type="text/javascript" ></script>
                <script src="<?php echo Yii::$app->request->baseUrl; ?>/plugin/qrscan/swfobject.js" type="text/javascript" ></script>
                <script src="<?php echo Yii::$app->request->baseUrl; ?>/plugin/qrscan/scriptcam.js" type="text/javascript" ></script>
                
		<script language="JavaScript"> 
			$(document).ready(function() {
				$("#webcam").scriptcam({
					showMicrophoneErrors:false,
					onError:onError,
                                        width:320,
					cornerRadius:20,
					cornerColor:'e3e5e2',
					onWebcamReady:onWebcamReady,
					uploadImage:'<?php echo Yii::$app->request->baseUrl; ?>/plugin/qrscan/upload.gif',
					onPictureAsBase64:base64_tofield_and_image,
                                        pathc:'<?php echo Yii::$app->request->baseUrl; ?>/plugin/qrscan/'
				});
                                
			});
			function base64_tofield() {
				$('#formfield').val($.scriptcam.getFrameAsBase64());
			};
			function base64_toimage() {
   
                                var member_id = $('#member_id').val();
                                var element_id = $('#element_id').val();
                                var image_basic64 = "data:image/png;base64,"+$.scriptcam.getFrameAsBase64();
                                $('#images_member').attr("src",image_basic64);
                                if(member_id==0){
                                        $('#take_picture'+element_id).hide();
                                        $('#view_picture'+element_id).show(); 
                                        $('#view_picture_after'+element_id).show();
                                        $('#member_picture'+element_id).val(image_basic64);
                                        $('#member_img'+element_id).attr("src",image_basic64);
                                }
                                else{
                                    $.ajax({
                                        type:'POST',
                                        url:'<?php echo Yii::$app->urlManager->createUrl('/members/default/updatewebcome'); ?>',
                                        data:{member_id:member_id,image_basic64:image_basic64},
                                        success:function(data){
                                            $('#view_picture img').attr("src",image_basic64);
                                            $('#take_picture').hide();
                                            $('#view_picture').show(); 
                                        }
                                    });
                                }
			};
			function base64_tofield_and_image(b64) {
//				$('#formfield').val(b64);
//				$('#image').attr("src","data:image/png;base64,"+b64);
                                $('#images_member').attr("src","data:image/png;base64,"+b64);
                                var member_id = $('#member_id').val();
                                var element_id = $('#element_id').val();
                                var image_basic64 = "data:image/png;base64,"+b64;
                                if(member_id==0){
                                        $('#take_picture'+element_id).hide();
                                        $('#view_picture'+element_id).show(); 
                                        $('#view_picture_after'+element_id).show();
                                        $('#member_picture'+element_id).val(image_basic64);
                                        $('#member_img'+element_id).attr("src",image_basic64);
                                }
                                else{
                                    $.ajax({
                                        type:'POST',
                                        url:'<?php echo Yii::$app->urlManager->createUrl('/members/default/updatewebcome'); ?>',
                                        data:{member_id:member_id,image_basic64:image_basic64},
                                        success:function(data){
                                            $('#take_picture').hide();
                                            $('#view_picture').show(); 
                                        }
                                    });
                                }
			};
			function changeCamera() {
				$.scriptcam.changeCamera($('#cameraNames').val());
			}
			function onError(errorId,errorMsg) {
				$( "#btn1" ).attr( "disabled", true );
				$( "#btn2" ).attr( "disabled", true );
				alert(errorMsg);
			}			
			function onWebcamReady(cameraNames,camera,microphoneNames,microphone,volume) {
				$.each(cameraNames, function(index, text) {
					$('#cameraNames').append( $('<option></option>').val(index).html(text) )
				}); 
				$('#cameraNames').val(camera);
			}
                        function cancel_picture(element_id){
                            var member_id = $('#member_id').val();
                            if(element_id == undefined){
                                $('#html_webcome').remove();
                                $('#take_picture').hide();
                                $('#view_picture').show(); 
                                $('#view_picture_after').show();
                            }
                            if(member_id==0){
                                $('#html_webcome').remove();
                                $('#take_picture'+element_id).hide();
                                $('#view_picture'+element_id).show(); 
                                $('#view_picture_after'+element_id).show();
                            }
                        };
		</script> 
            <input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id; ?>" />
            <input type="hidden" id="element_id" name="element_id" value="<?php echo $element_id; ?>" />
		<div style="width:100%;">
			<div id="webcam">
			</div>
			<div style="margin:5px;">
<!--				<img src="<?php echo Yii::$app->request->baseUrl; ?>/plugin/qrscan/webcamlogo.png" style="vertical-align:text-top"/>-->
<!--				<select id="cameraNames" size="1" onChange="changeCamera()" style="width:245px;font-size:10px;height:25px;">
				</select>-->
			</div>
		</div>
		<div style="width:100%;">
			<a class="btn btn-success" id="btn2" onclick="base64_toimage()"><?php echo Yii::t('app', 'Save');?></a>
                        <a class="btn btn-success" id="btn2" onclick="cancel_picture(<?php echo $element_id; ?>)"><?php echo Yii::t('app', 'Cancel');?></a>
		</div>
</div>