<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\SesstionOrder;
use app\modules\pos\models\Sesstion;

class SessionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //Check permission
        $m = 'pos';
//        $DefinePermission = new \app\modules\permission\models\DefinePermission();
//        $canDo = $DefinePermission->checkFunction($m, 'Manage session');
//        if(!$canDo){
//            echo "You don't have permission with this action.";
//            return ;
//        }
        //End check permission
		$searchModel = new \app\modules\pos\models\SessionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index',[
				'searchModel'=>$searchModel,
				'dataProvider'=>$dataProvider,
		]);
    }
	
	public function actionView($id){
		//Check permission
        $m = 'pos';
//        $DefinePermission = new \app\modules\permission\models\DefinePermission();
//        $canDo = $DefinePermission->checkFunction($m, 'Manage session');
//        if(!$canDo){
//            echo "You don't have permission with this action.";
//            return ;
//        }
        //End check permission
		$sesstionorder = new SesstionOrder();
        $status_outstanding = "Outstanding";
        
        $sesstion = $this->findModel($id);
        $sesstion_id = $sesstion->sesstion_id;
        $dataProvider = $sesstionorder->search(Yii::$app->request->queryParams, $status_outstanding, $sesstion_id);
        $dataProvider->pagination->pageParam  = 'outstanding-page';
        $status_paid = "Paid";
        $dataProviderPaid = $sesstionorder->search(Yii::$app->request->queryParams, $status_paid, $sesstion_id);
		$dataProviderPaid->pagination->pageParam  = 'paid-page';
		$status_void = "Void Invoice";
        $dataProviderVoid = $sesstionorder->search(Yii::$app->request->queryParams, $status_void, $sesstion_id);
		$dataProviderVoid->pagination->pageParam  = 'void-page';
		return $this->render('view', [
			'searchModel' => $sesstionorder,
            'dataProvider' => $dataProvider,
            'dataProviderPaid' => $dataProviderPaid,
            'dataProviderVoid' => $dataProviderVoid
        ]);
	}
	
	protected function findModel($id)
    {
        if (($model = Sesstion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
