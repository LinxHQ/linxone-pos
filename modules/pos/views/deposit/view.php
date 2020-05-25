<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pos\models\Deposit */

$this->title = $model->deposit_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deposits'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deposit-view">

<div id="members-index" class="parkclub-wrapper parkclub-wrapper-search ">
    <div class="parkclub-rectangle parkclub-shadow">
       <div class="parkclub-invoice">
            <div class="parkclub-newm-left-title">
                <div class="parkclub-header-left">
                    <a href="<?php echo Yii::$app->urlManager->createUrl('/pos/deposit/index') ?>"><i class="glyphicon glyphicon-circle-arrow-left"></i></a>
                    <?php echo Yii::t('app', 'Deposit'); ?>
                </div>
            </div>
           <div class="parkclub-newm">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'deposit_id',
                        'member_id',
                        'deposit_no',
                        'deposit_name',
                        'deposit_phone',
                        'deposit_email:email',
                        'deposit_address',
                        'deposit_note',
                        'deposit_status',
                    ],
                ]) ?>
           </div>
       </div>
    </div>
</div>
