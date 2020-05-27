<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $department_id
 * @property string $department_name
 * @property string $department_place
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_name', 'department_place'], 'required'],
            [['department_name', 'department_place'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => 'Department ID',
            'department_name' => 'Department Name',
            'department_place' => 'Department Place',
        ];
    }
    public function getDataDropdownDepartment(){
        $invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        $model = Department::find()->All();
        $array = array(); 
         foreach ($model as $items){
                $array[$items->department_id]=  $items->department_name;
         }
        return $array;
    }
}
