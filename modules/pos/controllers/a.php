<?php

namespace app\modules\pos\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DepartmentController extends \yii\web\Controller
{
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
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionChange_department(){
        
        if( isset($_POST['department_name'])){
           
            $table_name = $_POST['department_name'];
            setcookie("mydepartment", $table_name,  time() - (86400 * 15)); 
	    setcookie("mydepartment", $table_name,  time() + (86400 * 15));     
            if(isset($_COOKIE['mydepartment'])) 
                echo '{"status":"success"}';
            else
                echo '{"status":"fail"}'; 
        }
	       
   }
}
