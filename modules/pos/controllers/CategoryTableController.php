<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\CategoryTable;
use app\modules\pos\models\CategoryTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoryTableController implements the CRUD actions for CategoryTable model.
 */
class CategoryTableController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
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
     * Lists all CategoryTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoryTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort=false;
        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CategoryTable model.
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
     * Creates a new CategoryTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CategoryTable();
        $model->category_table_create_by = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo "{'status':'success'}";
        } else {
            echo "{'status':'fail'}";
//            return $this->render('create', [
//                'model' => $model,
//            ]);
        }
    }

    /**
     * Updates an existing CategoryTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->category_table_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CategoryTable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $this->findModel($id)->delete();
            echo "success";
        }
    }

    /**
     * Finds the CategoryTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoryTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoryTable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionUpdateStatus(){
        if(isset($_POST['id']) && isset($_POST['status'])){
            $model = $this->findModel($_POST['id']);
            $model->category_table_status = $_POST['status'];
            if($model->save())
                echo '{"status":"success"}';
            else
                echo '{"status":"save fail"}';
        }
        else
            echo '{"status":"error param"}';
    }
}
