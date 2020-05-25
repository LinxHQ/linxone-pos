<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\checkin_entity\models\MemberRegister */

$this->title = $model->register_entity_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Member Registers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-register-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->register_entity_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->register_entity_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'register_entity_id',
            'entity_id',
            'entity_type',
            'member_code',
            'member_name',
            'member_email:email',
            'member_phone',
            'member_date',
            'member_status',
            'member_note',
            'member_id',
        ],
    ]) ?>

</div>
