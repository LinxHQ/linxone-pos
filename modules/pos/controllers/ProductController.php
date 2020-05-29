<?php

namespace app\modules\pos\controllers;

use Yii;
use app\modules\pos\models\Product;
use app\modules\pos\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $next_id = new \app\models\NextIds();
        $model->product_qty_out_of_stock = 0;
        $model->product_qty_notify = 0;
        $model->product_original = 0;
        $model->product_selling = 0;
        $model->product_no = $next_id->getDisplayProductCode();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $next_id->setNextId('next_product_id');
            $files = \yii\web\UploadedFile::getInstancesByName('file_product');
            foreach ($files as $item) {
                $document = new \app\models\Documents();
                if($item){
                    $file_name = $item->baseName.date('i') . '.' . $item->extension;
                    if($document->addDocument($model->product_id, 'product', $item->baseName, $file_name)){
                            $item->saveAs(Yii::getAlias('@webroot').'/uploads/' . $file_name);
                    }
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
         
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $files = \yii\web\UploadedFile::getInstancesByName('file_product');
            foreach ($files as $item) {
                $document = new \app\models\Documents();
                if($item){
                    $file_name = $item->baseName.date('i') . '.' . $item->extension;
                    if($document->addDocument($model->product_id, 'product', $item->baseName, $file_name)){
                            $item->saveAs(Yii::getAlias('@webroot').'/uploads/' . $file_name);
                    }
                }
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
