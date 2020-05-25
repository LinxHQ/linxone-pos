<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "pos_category_table".
 *
 * @property integer $category_table_id
 * @property string $category_table_name
 * @property string $category_table_description
 * @property integer $category_table_create_by
 * @property integer $category_table_parent
 * @property integer $category_table_status
 */
class CategoryTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_category_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_table_name'], 'required'],
            [['category_table_create_by', 'category_table_parent', 'category_table_status'], 'integer'],
            [['category_table_name'], 'string', 'max' => 100],
            [['category_table_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_table_id' => Yii::t('app', 'Category Table ID'),
            'category_table_name' => Yii::t('app', 'Group Name'),
            'category_table_description' => Yii::t('app', 'Description'),
            'category_table_create_by' => Yii::t('app', 'Category Table Create By'),
            'category_table_parent' => Yii::t('app', 'Category Table Parent'),
            'category_table_status' => Yii::t('app', 'Status'),
        ];
    }
    
    public function getDataArray($status=false){
        $table = new CategoryTableSearch();
        if($status)
            $table->category_table_status = $status;
        $data = $table->search([]);
        return \yii\helpers\ArrayHelper::map($data->models, 'category_table_id', 'category_table_name');
    }
}
