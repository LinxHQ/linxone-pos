<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\Tables;
use app\modules\pos\models\TablesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TablesController implements the CRUD actions for Tables model.
 */
class TablesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        //Check permission 
        $m = 'pos';
//        $DefinePermission = new \app\modules\permission\models\DefinePermission();
//        $canManagerTable = $DefinePermission->checkFunction($m, 'Manager table');
//        $canManagerMenu = $DefinePermission->checkFunction($m, 'Manager menu');
//        if(!$canManagerTable){
//            throw new NotFoundHttpException(Yii::t('app',"You don't have permission with this action."));
//            exit();
//        } 
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
			'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Tables models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new TablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tables model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tables();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Tables::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tables::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateStatus(){
        if(isset($_POST['id']) && isset($_POST['status'])){
            $model = $this->findModel($_POST['id']);
            $model->table_status = $_POST['status'];
            if($model->save())
                echo '{"status":"success"}';
            else
                echo '{"status":"fail"}';
        }
    }
    
    public function actionChange_table(){
        if(isset($_POST['invoice_id']) && isset($_POST['table_name'])){
            $invoice_id = $_POST['invoice_id'];
            $table_name = $_POST['table_name'];
	        $invoice = \app\modules\invoice\models\invoice::findOne($invoice_id);
	        if($invoice){
	            $invoice->invoice_type_id = $table_name;
	            if($invoice->save())
					echo '{"status":"success"}';
				else
					echo '{"status":"fail"}';
	        }
		}else{
			echo '{"status":"fail"}';
		}
   }
}
