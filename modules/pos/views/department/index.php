<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title; 
 
?> 
<div class="pos-default-index">
    <div class="parkclub-subtop-bar">
        <div class="parkclub-nameplate col-lg-5"><div class="parkclub-iconbg"><a href="<?php echo yii\helpers\Url::toRoute(['/pos/default/index']); ?>" ><img src="<?php echo Yii::$app->urlManager->baseUrl; ?>/image/park_new/pos.png" width="22" alt=""></div> <h3><?php echo Yii::t('app', 'Department') ?></h3></a></div>
        <div class="col-lg-6" style="text-align: right; margin-top: 30px;">
        <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/department/index') ?>" class="btn btn-primary"  ><?php echo Yii::t('app', 'DEPARTMENT') ?> <span class="badge" style="color: #ce1515" id="new_order_count"></span></a>
         </div>
    </div>
     

<div  class="parkclub-wrapper parkclub-wrapper-search" id="view_only_checkin">
   <div class="parkclub-rectangle parkclub-shadow">
        <div class="department-view">
         <p  >
             <br><br>
             <?= Html::a('Create Department', ['create'], ['class' => 'btn btn-success']) ?>
         </p>

         <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

         <?= GridView::widget([
             'dataProvider' => $dataProvider,
             'filterModel' => $searchModel,
             'columns' => [
                 ['class' => 'yii\grid\SerialColumn'],

                 'department_id',
                 'department_name',
                 'department_place',

                 ['class' => 'yii\grid\ActionColumn'],
             ],
         ]); ?>
            </div>
       </div>
    </div>
</div>
