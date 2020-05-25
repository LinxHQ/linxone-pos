<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\Deposit;
use app\modules\pos\models\DepositSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepositController implements the CRUD actions for Deposit model.
 */
class DepositController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * Lists all Deposit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepositSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Deposit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new \app\modules\invoice\models\Payment();
        $searchModel->deposit_id = $id;
        $dataProvider = $searchModel->search([]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider
        ]);
    }

    /**
     * Creates a new Deposit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Deposit();
        $next_id = new \app\models\NextIds();
        $model->deposit_no = $next_id->getDisplayDepositCode();
        $model->deposit_date = date('Y-m-d H:i:s');
        $model->deposit_status = 1;
        

        if ($model->load(Yii::$app->request->post())) {
            $model->deposit_balance = $model->deposit_amount;
            if($model->member_id>0)
                $model->deposit_name = 'member';
            //Upload image member
            if(isset($_FILES['deposit_images']) && $_FILES['deposit_images']['name']!="")
            {
              $type = pathinfo($_FILES['deposit_images']['name'], PATHINFO_EXTENSION);
              $data = file_get_contents($_FILES['deposit_images']['tmp_name']);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
              $model->deposit_images=$base64;
            }
            if($model->save()){
                $next_id->setNextId('next_deposit_id');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Deposit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			$model->deposit_balance = $model->deposit_balance + $_POST['deposit_recharge'];
			$model->deposit_amount = $model->deposit_amount + $_POST['deposit_recharge'];
            //Upload image member
            if(isset($_FILES['deposit_images']) && $_FILES['deposit_images']['name']!="")
            {
              $type = pathinfo($_FILES['deposit_images']['name'], PATHINFO_EXTENSION);
              $data = file_get_contents($_FILES['deposit_images']['tmp_name']);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
              $model->deposit_images=$base64;
            }
            if($model->save())
                return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Deposit model.
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
     * Finds the Deposit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deposit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deposit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionLoadMember(){
        if(isset($_POST['member_id'])){
            $member= \app\modules\members\models\Members::findOne($_POST['member_id']);
            return $this->renderAjax('_member_info', [
                'member' => $member,
            ]);
        }
    }
}
