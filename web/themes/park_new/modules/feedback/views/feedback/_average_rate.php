<?php

$feedback = new app\modules\feedback\models\Feedback();


?>
<div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/feedback.png" width="22" alt=""></div> <h3><?php echo Yii::t('app', 'Average rate') ?></h3></div>
</div>
<div class="parkclub-wrapper parkclub-wrapper-search">
        <div class="parkclub-rectangle parkclub-shadow">
            <div class="parkclub-rectangle-content">
                <table> 
                    <tr>
                            <th><?php echo Yii::t('app', 'Service type');?></th>
                            <th><?php echo Yii::t('app','Average rate');?></th>
                            
                    </tr>
                        <?php 
                       
                            foreach ($model as $data)
                            {
                                
                                ?>
                                    <tr>
                                        <td><?php echo $data ;?></td>
                                        <td><input id="input-4" name="input-4" class="rating rating-loading" data-show-clear="false" data-show-caption="false" value="<?php echo $feedback->AverageRateByService($data);?>" data-disabled="true"></td>
                                   

                                    </tr>

                                <?php 

                            }
                         
                        ?>
                        </table>
            </div> 
        </div>
</div>
