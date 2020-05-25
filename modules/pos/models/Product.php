<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "pos_product".
 *
 * @property integer $product_id
 * @property integer $category_product_id
 * @property string $product_no
 * @property string $product_name
 * @property string $product_original
 * @property string $product_selling
 * @property integer $product_qty_out_of_stock
 * @property integer $product_qty_notify
 * @property string $product_description
 * @property integer $product_status
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_product_id', 'product_no', 'product_name', 'product_selling', 'product_status'], 'required'],
            [['category_product_id', 'product_qty_out_of_stock', 'product_qty_notify', 'product_status'], 'integer'],
            [['product_original', 'product_selling'], 'number'],
            [['product_no'], 'string', 'max' => 50],
            [['product_no'], 'unique'],
            [['product_name'], 'string', 'max' => 100],
            [['product_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product'),
            'category_product_id' => Yii::t('app', 'Category'),
            'product_no' => Yii::t('app', 'Product code'),
            'product_name' => Yii::t('app', 'Name'),
            'product_original' => Yii::t('app', 'Original Price'),
            'product_selling' => Yii::t('app', 'Selling Price'),
            'product_qty_out_of_stock' => Yii::t('app', 'Quantity Out Of Stock'),
            'product_qty_notify' => Yii::t('app', 'Quantity Notify'),
            'product_description' => Yii::t('app', 'Description'),
            'product_status' => Yii::t('app', 'Status'),
        ];
    }
    
    public function getCategoryName(){
        $category = CategoryProduct::findOne($this->category_product_id);
        if($category)
            return $category->category_product_name;
        return "";
    }
    
    public function getStatus(){
        $listSetup= \app\models\ListSetup::getItemByList('status');
        if($listSetup)
            return (isset($listSetup[$this->product_status])) ? $listSetup[$this->product_status] : "";
        return "";
    }
    
    public function getImages(){
        $document = \app\models\Documents::find()->where(['entity_id'=> $this->product_id,['entity_type'=>'product']])->one();
        if($document){
            return \yii\bootstrap\Html::img(Yii::$app->request->baseUrl . $document->document_url, ['width'=>120]);
        }
        return \yii\bootstrap\Html::img(Yii::$app->request->baseUrl . '/image/image-hv.png', ['width'=>120]);
    }
    public function getDataDropdownProductName(){
        $model = Product::find()->where(['product_status'=>1])->all();
        return \yii\helpers\ArrayHelper::map($model, 'product_name', 'product_name');
    }
    public function getItemByCategoryId($category_id) {
        $ModelSearch = new ProductSearch();
        if($category_id){
            $ModelSearch->category_product_id = $category_id;
            $data = $ModelSearch->search([]);
        }
        return \yii\helpers\ArrayHelper::map($data->models, 'product_id', 'product_name');
    }
    
    public function getPriceByProductId($product_id){
        $model = Product::findOne($product_id);
        return $model->product_selling;
    }
    
    public function getIdProductByArrayCategoryId($array,$parrent = 0){
        $model = new CategoryProduct();
        
        $array_category_id = $model->getIdItemByParrent($array,$parrent);
        
        $array_product_id = array();
        if($array_category_id){
            $ModelSearch = new ProductSearch();
            $dataProvider = $ModelSearch->search(Yii::$app->request->queryParams,false,false,$array_category_id);
            foreach ($dataProvider->models as $item){
                $array_product_id[] = $item->product_id;
            }
        }
        
        return $array_product_id;
    }
	
	public function getProductByCategoryId($category_id) {
		$products = Product::find()->where(['category_product_id'=>$category_id])->orderBy(['product_id'=>SORT_DESC])->all();
		return $products;
    }
}
