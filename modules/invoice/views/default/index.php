<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\invoice\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Check permission 
$m = 'invoice';
$BasicPermission = new \app\modules\permission\models\BasicPermission();
$canList = $BasicPermission->checkModules($m, 'list');
$canAdd = $BasicPermission->checkModules($m, 'add');
$canEdit = $BasicPermission->checkModules($m, 'update');
$canDelete = $BasicPermission->checkModules($m, 'delete');
$canView = $BasicPermission->checkModules($m, 'view');

if(!$canList){
    echo Yii::t('app','You do not have permission with this action.');
    return false;
}
//End check permission
$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Invoice'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'invoice_id',
            'invoice_no',
            'invoice_date',
            'invoice_type_id',
            'invoice_note:ntext',
            // 'invoice_type',
            // 'member_id',
            // 'invoice_discount',
            // 'invoice_gst',
            // 'invoice_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
