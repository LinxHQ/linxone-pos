<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ListSetup */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'List Setups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->list_id, 'url' => ['view', 'id' => $model->list_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$duplicated = 0;
if(isset($_GET['duplicated']))
	$duplicated = $_GET['duplicated'];
?>
<div class="list-setup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'duplicated' => $duplicated
    ]) ?>

</div>
