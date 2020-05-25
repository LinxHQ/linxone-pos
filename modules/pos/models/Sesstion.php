<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "pos_sesstion".
 *
 * @property integer $sesstion_id
 * @property integer $user_id
 * @property string $sesstion_start_date
 * @property string $sesstion_end_date
 */
class Sesstion extends \yii\db\ActiveRecord
{
    const STATUS_SESSION_CLOSED="Closed";
    const STATUS_SESSION_OPENED="Opened";
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_sesstion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sesstion_start_date', 'sesstion_status'], 'required'],
            [['user_id','sesstion_status'], 'integer'],
            [['sesstion_start_date', 'sesstion_end_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sesstion_id' => 'Sesstion ID',
            'user_id' => 'User ID',
            'sesstion_start_date' => 'Sesstion Start Date',
            'sesstion_end_date' => 'Sesstion End Date',
            'sesstion_status' => 'Sesstion Status'
        ];
    }
	
	public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }
    
    public function getSesstionIdNow(){
        $sesstion = Sesstion::find()->orderBy('sesstion_start_date DESC')->one();
        if($sesstion)
            return $sesstion->sesstion_id;
         
    }
    
    public function getSesstionIdOld(){
        $sesstion = Sesstion::find()->orderBy('sesstion_end_date DESC')->one();
        if($sesstion)
            return $sesstion->sesstion_id;
    }
    
    
}
