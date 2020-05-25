<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\course\models\ClassSession */
$this->title = Yii::t('app', 'Create Session', [
    'modelClass' => 'Class Session',
]);
$model->class_id = $_GET['class_id'];
$class = app\modules\course\models\Classc::findOne($model->class_id);
?>
<div class="class-session-create">

    <div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
        <div class="parkclub-rectangle parkclub-shadow">
           <div class="parkclub-invoice">
                <div class="parkclub-newm-left-title">
                    <div class="parkclub-header-left">
                        <a href="<?php echo Yii::$app->urlManager->createUrl('/course/classc/view?id='.$model->class_id); ?>"<i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                        <?php echo Yii::t('app', 'Add session to class '.$class->class_name); ?>
                    </div>
                </div>
               
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
           </div>
        </div>
    </div>

</div>
