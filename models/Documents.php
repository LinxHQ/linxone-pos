<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property integer $document_id
 * @property string $document_name
 * @property string $document_url
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $document_created_by
 * @property string $document_created_date
 */
class Documents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_name', 'document_url', 'entity_id', 'entity_type', 'document_created_by', 'document_created_date'], 'required'],
            [['entity_id', 'document_created_by'], 'integer'],
            [['document_created_date'], 'safe'],
            [['document_name', 'document_url'], 'string', 'max' => 255],
            [['entity_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => Yii::t('app', 'Document ID'),
            'document_name' => Yii::t('app', 'Document Name'),
            'document_url' => Yii::t('app', 'Document Url'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'document_created_by' => Yii::t('app', 'Document Created By'),
            'document_created_date' => Yii::t('app', 'Document Created Date'),
        ];
    }
    
    function addDocument($entity_id,$entity_type,$document_name,$document_url){
        $document = new Documents();
        $document->entity_id = $entity_id;
        $document->entity_type = $entity_type;
        $document->document_name = $document_name;
        $document->document_url = $document_url;
        $document->document_created_date = date('Y-m-d H:i:s');
        $document->document_created_by = Yii::$app->user->id;
        return $document->save();
    }
    
    public function getImagesEntity($entity_id,$entity_type,$view_one=false,$height="80px",$width="auto"){
        $model = Documents::find()->where(['entity_id'=>$entity_id,'entity_type'=>$entity_type])->all();
        if($model){
            echo '<div class="row">';
            foreach ($model as $items) {
                    echo '<div style="float:left;">
                            <a href="#" class="thumbnail" style="margin-bottom:0px;">';
                        if(file_exists(Yii::$app->basePath.'/../uploads/'.$items->document_url)){    
                              echo '<img style="height:'.$height.'; width:'.$width.'" src="'.Yii::$app->request->baseUrl.'/uploads/'.$items->document_url.'" alt="'.$items->document_name.'">';
                        }else{
                            echo '<img style="height:'.$height.'; width:'.$width.'" src="'.Yii::$app->request->baseUrl.'/uploads/image-hv.png'.'" alt="img">';
                        }
                    echo '   </a>
                      </div>';
                    if($view_one)
                        break;
            }
            echo '</div>';
        }
        else{
            echo '<div class="row"><div style="float:left;">
                    <a href="#" class="thumbnail" style="margin-bottom:0px;">
                      <img style="height:'.$height.'; width:'.$width.'" src="'.Yii::$app->request->baseUrl.'/uploads/image-hv.png'.'" alt="img">
                    </a>
              </div></div>';  
        }
    }
        public function getImagesBase64($entity_id,$entity_type){
        $src = array();
        $model = Documents::find()->where(['entity_id'=>$entity_id,'entity_type'=>$entity_type])->all();
        if($model){
            foreach ($model as $items) {
                        if(file_exists(Yii::$app->basePath.'/../uploads/'.$items->document_url)){
                            $path = Yii::$app->basePath.'/../uploads/'.$items->document_url;
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $src[] = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        }
            }
        }
        return $src;
    }
}
