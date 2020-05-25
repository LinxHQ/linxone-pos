<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\course\models\ClassMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Class Members');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="class-member-index">
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'class_member_code',
            'class_member_name',
            'class_member_email:email',
            // 'class_member_phone',
            // 'class_member_date',
            // 'class_member_status',
            // 'class_member_note',
            // 'member_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
