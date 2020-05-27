<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "branch".
 *
 * @property int $branch_id
 * @property string $branch_name
 * @property string $branch_place
 */
class Branch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['branch_name', 'branch_place'], 'required'],
            [['branch_name', 'branch_place'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'branch_id' => 'Branch ID',
            'branch_name' => 'Branch Name',
            'branch_place' => 'Branch Place',
        ];
    }
     public function getDataDropdownBranch(){
        $invoice_status = \app\modules\invoice\models\invoice::INVOICE_STATUS_OUSTANDING;
        $model = Branch::find()->All();
        $array = array(); 
         foreach ($model as $items){
                $array[$items->branch_id]=  $items->branch_name;
         }
        return $array;
    }
}
