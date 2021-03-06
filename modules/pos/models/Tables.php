<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "pos_tables".
 *
 * @property integer $table_id
 * @property integer $category_table_id
 * @property string $table_name
 * @property integer $table_order
 * @property integer $table_status
 * @property integer $table_created_by
 * @property string $table_created_date
 * @property string $table_description
 */
class Tables extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_tables';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_table_id', 'table_name'], 'required'],
            [['category_table_id', 'table_order', 'table_status', 'table_created_by'], 'integer'],
            [['table_created_date'], 'safe'],
            [['table_name'], 'string', 'max' => 100],
            [['table_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'table_id' => Yii::t('app', 'Table ID'),
            'category_table_id' => Yii::t('app', 'Group'),
            'table_name' => Yii::t('app', 'Name'),
            'table_order' => Yii::t('app', 'Order'),
            'table_status' => Yii::t('app', 'Status'),
            'table_created_by' => Yii::t('app', 'Table Created By'),
            'table_created_date' => Yii::t('app', 'Table Created Date'),
            'table_description' => Yii::t('app', 'Description'),
        ];
    }
    
    public function getGroupName(){
        $category_table = CategoryTable::findOne($this->category_table_id);
        if($category_table)
            return $category_table->category_table_name;
        return "";
    }

    public function check_status_table($table_id){
        $invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        $invoice = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>$table_id,'invoice_status'=>$invoice_status])->one();  
        if($invoice){
            return FALSE;
        }else{
            return true;
        }
                
    }

    public function getDataDropdownTableName(){
        $model = Tables::find()->where(['table_status'=>1])->all();
        return \yii\helpers\ArrayHelper::map($model, 'table_id', 'table_name');
    }

    public function getDataDropdownTableNameOrder(){
        $invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        $model = Tables::find()->where(['table_status'=>1])->All();
        $array = array();
        foreach ($model as $items){
            $invoice = \app\modules\invoice\models\invoice::find()->where(['invoice_type'=>'pos','invoice_type_id'=>$items->table_id,'date(invoice_date)'=>date('Y-m-d'),'invoice_status'=>$invoice_status])->one(); 
            if($invoice){
                $array[$items->table_id]=  $items->table_name;
            }
        }
        return $array;
    }
    
}
