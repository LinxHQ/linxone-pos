<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property integer $config_id
 * @property integer $data_demo
 * @property integer $tour
 * @property integer $delete_data
 * @property integer $tour_step
 * @property string $subdomain
 * @property string $domain_status
 * @property string $register_date
 * @property integer $cancel_booking_overdue
 * @property string $currency
 * @property string $thousandSeparator
 * @property string $decimalSeparator
 * @property string $format_date
 * @property string $agreement_vi
 * @property string $agreement_en
 * @property integer $decimal
 * @property string $format_time
 * @property integer $default_tax
 * @property integer $date_expiry_membership
 */
class Config extends \yii\db\ActiveRecord
{
    const TOUR_MEMBERSHIP_TYPE = 1;
    const TOUR_MEMBER = 2;
    const TOUR_CHECKIN = 3;
    const TOUR_FACILITY = 4;
    const TOUR_BOOKING = 5;
    const TOUR_TRAINER = 6;
    
    const STATUS_TRIAL = 'trial';
    const STATUS_EXPIRED_TRAIL = 'expired_trial';
    const STATUS_BOUGHT = 'Bought';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data_demo', 'tour'], 'required'],
            [['data_demo', 'tour','delete_data','cancel_booking_overdue','default_tax'], 'integer'],
            [['domain_status'], 'string','max' => 50],
            [['decimal','date_expiry_membership'],'integer'],
            [['currency','decimalSeparator','thousandSeparator','format_date','format_time'], 'string','max' => 10],
            [['register_date','agreement_vi','agreement_en','customisable_membership_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_id' => Yii::t('app', 'Config ID'),
            'data_demo' => Yii::t('app', 'Data Demo'),
            'tour' => Yii::t('app', 'Tour'),
        ];
    }

    public function addDefault(){
        $model = new Config();
        $data = $model->find()->one();
        if(!$data) {
            $model->data_demo = 0;
            $model->tour = 0;
            $model->delete_data = 0;
            $model->tour_step = 0;
            $model->domain_status = self::STATUS_TRIAL;
            $model->register_date = date('Y-m-d H:i:s');
            $model->currency = '0';
            $model->decimalSeparator = ',';
            $model->thousandSeparator = ".";
            $model->format_date = 'd/m/Y';
            $model->agreement_vi = "";
            $model->agreement_en = "";
            $model->decimal = 0;
            $model->format_time = 'H:i';
            $model->default_tax = 3;
            $model->date_expiry_membership = 14;
        }else{
            $model->data_demo = 1;
        } 
        $model->save();
    }
    
    /**
     * 
     * @param type $day_trail // Số ngày cho dùng thử
     * @return boolean
     */
    public function isExpiredTrail($day_trail=30){
        if($config->domain_status == self::STATUS_TRIAL && $this->dayTriedDomain()>$day_trail)
            return true;
        else
            return false;
    }
    
    public function dayTriedDomain(){
        $config = Config::find()->one();
        if(!$config){
            $this->addDefault();
            $config = Config::find()->one();
        }
        $day_trial = ListSetup::diffDays($config->register_date, date('Y-m-d'));
        return $day_trial;
    }
    
    public function getShowImgProduct(){
        $config = Config::find()->one();
        return $config->show_img_product;
    }
    
//    public function getFormatTime(){
//        if($this->format_time==0)
//            return 'h:i a';
//        else
//            return 'H:i';
//    }
//    
//    public function getFormatDate(){
//        if($this->format_time==0)
//            return 'h:i a';
//        else
//            return 'H:i';
//    }
    
}
