<?php

namespace app\modules\pos\models;

use Yii;
use app\modules\pos\models\Product;
use app\modules\invoice\models\InvoiceItem;
use app\models\ListSetup;

/**
 * This is the model class for table "pos_category_product".
 *
 * @property integer $category_product_id
 * @property string $category_product_name
 * @property string $category_product_description
 * @property integer $category_product_created_by
 * @property integer $category_product_parent
 * @property integer $category_product_status
 */
class CategoryProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_category_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_product_name'], 'required'],
            [['category_product_created_by', 'category_product_parent', 'category_product_status'], 'integer'],
            [['category_product_name'], 'string', 'max' => 100],
            [['category_product_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_product_id' => Yii::t('app', 'Category Product ID'),
            'category_product_name' => Yii::t('app', 'Name'),
            'category_product_description' => Yii::t('app', 'Description'),
            'category_product_created_by' => Yii::t('app', 'Created By'),
            'category_product_parent' => Yii::t('app', 'Parent'),
            'category_product_status' => Yii::t('app', 'Status'),
        ];
    }
    
/**
     * MENU đệ quy lấy theo select
     * @param type $menus
     * @param type $parrent
     */
    public function menuSelectPage($parrent = 0,$space="",$selected_id="",$event="",$option_default=false) 
    {
        $pages= CategoryProduct::find()->all();
        $option = "";
        if($option_default)
            $option = '<option value="0">'.Yii::t('app', 'Select parent').'</option>';
        $select='<select '.$event.' id="category_product_parent" name="CategoryProduct[category_product_parent]" >';
            $select.=$option;
            $select.=$this->SelectOptions($pages,$parrent,$space,$selected_id);
        $select.='</select>';
        return $select;
    }
    
    public function SelectOptions($array,$parrent = 0,$space="",$selected_id="") 
    {
        $option="";
        foreach ($array as $item) 
        {
            if ($item->category_product_parent == $parrent) 
            {
                $data_parent = "";
                if($parrent!=0)
                    $data_parent = 'data-parent='.$parrent;
				$selected="";
                if($selected_id == $item->category_product_id)
                    $selected = "selected";
                $pages= CategoryProduct::find()->where('category_product_parent='.intval($item->category_product_id))->all();
                $option.='<option value="'.$item->category_product_id.'" '.$selected.' '.$data_parent.'>';
                        $option.=$space.' '.$item->category_product_name;
                $option.='</option>';
                $option.=$this->SelectOptions($pages, $item->category_product_id,$space.'--',$selected_id);
            }
            
        }
        return $option;
    }
    
    function getParentName(){
        $model = CategoryProduct::findOne($this->category_product_parent);
        if($model)
            return $model->category_product_name;
        return "";
    }
    
    public function getDataArray(){
        $category = new CategoryProductSearch();
        $category->category_product_parent = 0;
        $data = $category->search([]);
        return \yii\helpers\ArrayHelper::map($data->models, 'category_product_id', 'category_product_name');
    }
    
    public function getArrayCategory(){
        $array = CategoryProduct::find()->all();
        return $array;
    }


    public function getIdItemByParrent($array,$parrent = 0){
        $array_id = array();
        $array_id[] = $parrent;
        foreach ($array as $item) 
        {
            if ($item->category_product_parent == $parrent) 
            {
                    
                    $arrays= CategoryProduct::find()->where('category_product_parent='.intval($item->category_product_id))->all();
                    $array_id[] = $item->category_product_id;
                    if($arrays){
                        $array_id[] = $this->getIdItemByParrent($arrays, $item->category_product_id);
                    }   
            }
        }
        return $array_id;
    }
    
    public function tableCategoryProduct($parrent = 0,$space="",$start_date=false,$end_date=false) 
    {
        
        $pages= CategoryProduct::find()->all();
            $table=$this->SelectTrTable($pages,$parrent,$space,$start_date,$end_date);

        return $table;
    }
    
    public function SelectTrTable($array,$parrent = 0,$space="",$start_date=false,$end_date=false) 
    {
        $option="";
        $product = new \app\modules\pos\models\Product();
        $invoice_item = new \app\modules\invoice\models\InvoiceItem();
        $listsetup = new \app\models\ListSetup();
        foreach ($array as $item) 
        {
            if ($item->category_product_parent == $parrent) 
            {
                $pages= CategoryProduct::find()->where('category_product_parent='.intval($item->category_product_id))->all();
                if(count($product->getIdProductByArrayCategoryId($pages,$item->category_product_id)) == 0){
                    $option.='<tr><td style = "font-weight: 500;">'.$space.' '.$item->category_product_name.'</td><td style = "font-weight: 500;">0</td><td></td><td style = "font-weight: 500;">0</td></tr>';
                }else{
                    $option.='<tr><td style = "font-weight: 500;">'.$space.' '.$item->category_product_name.'</td><td style = "font-weight: 500;">'.$invoice_item->getItemByProductId($product->getIdProductByArrayCategoryId($pages,$item->category_product_id),$start_date,$end_date)["total"].'</td><td></td><td style = "font-weight: 500;">'.$listsetup->getDisplayPrice($invoice_item->getItemByProductId($product->getIdProductByArrayCategoryId($pages,$item->category_product_id),$start_date,$end_date)["amount"], 2).'</td></tr>';
                }
                $array_item = $product->getItemByCategoryId($item->category_product_id);
                if($array_item){
                    foreach ($array_item as $key=>$value) {
                        $qty = $invoice_item->getItemByProductId($key,$start_date,$end_date)['total'];
                        if($qty != 0)
                            $option.='<tr><td>'.$space.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$value.'</td><td>'.$qty.'</td><td>'.$listsetup->getDisplayPrice($product->getPriceByProductId($key), 2).'</td><td>'.$listsetup->getDisplayPrice($invoice_item->getItemByProductId($key,$start_date,$end_date)['amount'], 2).'</td></tr>';
                    }
                }
                $option.=$this->SelectTrTable($pages, $item->category_product_id,$space.'&nbsp;&nbsp;&nbsp;&nbsp;',$start_date,$end_date);
            }
            
        }
        return $option;
    }
	public function getPosCategoryReport($start_date,$end_date){
		$category = CategoryProduct::find()->where(['category_product_parent'=>0])->orderBy(['category_product_id'=>SORT_DESC])->all();
		$data = [];
		$row = 0;
		if(count($category)) {
			//category
			foreach($category as $cat){	
				$cat_qty = 0;
				$cat_total = 0;
				
				//products in Sub_category
				$sub_category = CategoryProduct::find()->where(['category_product_parent'=>$cat->category_product_id])->orderBy(['category_product_id'=>SORT_DESC])->all();
				if(count($sub_category)) {
					//sub_category
					foreach($sub_category as $sub_cat) {
						$sub_cat_qty = 0;
						$sub_cat_total = 0;
						$product = new Product();
						$product = $product->getProductByCategoryId($sub_cat->category_product_id);
						if(count($product)) {
							foreach($product as $prod) {
								$invoice_item = new InvoiceItem();	
								$qty = $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['total']; 
								if($qty != 0) {
									$data[$row]['name'] = $prod->product_name;
									$data[$row]['qty'] = $qty;
									$data[$row]['price'] = $prod->product_selling;
									$data[$row]['total'] = $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['amount'];
									$data[$row]['type'] = 2;
									$sub_cat_qty += $qty;
									$cat_qty += $qty;
									$sub_cat_total += $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['amount'];
									$cat_total += $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['amount'];
									$row++;
								}
							}
						}
						$data[$row]['name'] = $sub_cat->category_product_name;
						$data[$row]['qty'] = $sub_cat_qty;
						$data[$row]['price'] = '';
						$data[$row]['total'] = $sub_cat_total;
						$data[$row]['type'] = 1;
						$row++;
					}
				}
				
				//products in Category
				$products = new Product();
				$products = $products->getProductByCategoryId($cat->category_product_id);
				if(count($products)) {
					foreach($products as $prod) {
						$invoice_item = new InvoiceItem();	
						$qty = $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['total']; 
						if($qty != 0) {
							$data[$row]['name'] = $prod->product_name;
							$data[$row]['qty'] = $qty;
							$data[$row]['price'] = $prod->product_selling;
							$data[$row]['total'] = $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['amount'];
							$data[$row]['type'] = 2;
							$cat_qty += $qty;
							$cat_total += $invoice_item->getItemByProductId($prod->product_id,$start_date,$end_date)['amount'];
							$row++;
						}
					}
				}
				
				$data[$row]['name'] = $cat->category_product_name;
				$data[$row]['qty'] = $cat_qty;
				$data[$row]['price'] = '';
				$data[$row]['total'] = $cat_total;
				$data[$row]['type'] = 0;
				$row++;
			}
		}
		return array_reverse($data);
	}
}
