<?php

namespace app\modules\pos\models;

use Yii;

/**
 * This is the model class for table "pos_deposit".
 *
 * @property integer $deposit_id
 * @property integer $member_id
 * @property string $deposit_no
 * @property string $deposit_name
 * @property string $deposit_phone
 * @property string $deposit_email
 * @property string $deposit_address
 * @property string $deposit_note
 * @property integer $deposit_status
 * @property string $deposit_images
 * @property string $deposit_amount
 * @property string $deposit_balance
 * @property string $deposit_date
 */
class Deposit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_deposit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deposit_no', 'deposit_status','deposit_amount','deposit_name'], 'required'],
            [['member_id', 'deposit_status'], 'integer'],
            [['deposit_no', 'deposit_phone'], 'string', 'max' => 20],
            [['deposit_name'], 'string', 'max' => 60],
            [['deposit_email'], 'string', 'max' => 100],
            [['deposit_address', 'deposit_note'], 'string', 'max' => 255],
            [['deposit_amount', 'deposit_balance'], 'number'],
            [['deposit_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deposit_id' => Yii::t('app', 'Deposit ID'),
            'member_id' => Yii::t('app', 'Member ID'),
            'deposit_no' => Yii::t('app', 'Deposit No'),
            'deposit_name' => Yii::t('app', 'Name'),
            'deposit_phone' => Yii::t('app', 'Phone'),
            'deposit_email' => Yii::t('app', 'Email'),
            'deposit_address' => Yii::t('app', 'Address'),
            'deposit_note' => Yii::t('app', 'Note'),
            'deposit_status' => Yii::t('app', 'Status'),
            'deposit_images'=> Yii::t('app', 'Images'),
            'deposit_amount'=> Yii::t('app', 'Amount'),
            'deposit_balance'=> Yii::t('app', 'Balance'),
        ];
    }
    
    public function getMemberImages(){
        if($this->member_id>0){
            $member = \app\modules\members\models\Members::findOne($this->member_id);
            return $member->getMemberImages($this->member_id);
        }
        if ($this->deposit_images!="")
                return $this->deposit_images;

        return Yii::$app->request->baseUrl . '/image/park_new/unknown.png';
    }
}
