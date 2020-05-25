<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "list_setup".
 *
 * @property integer $list_id
 * @property string $list_name
 * @property integer $list_parent
 * @property string $list_value
 * @property string $list_description
 */
class ListSetup extends \yii\db\ActiveRecord
{
    const THEME_PARK = 'old';
    const THEME_PARK_NEW = 'new';
    const FOMAT_DATE_VIEW = 'd/m/Y';
    const FOMAT_DATE_SQL = 'Y-m-d';
    const FOMAT_DATETIME_VIEW = 'd/m/Y H:i:s';
    const FOMAT_DATETIME_SQL = 'Y-m-d H:i:s';
    const FOMAT_TIME_VIEW = 'H:i';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'list_setup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['list_name', 'list_parent','list_value'], 'required'],
            [['list_parent'], 'integer'],
            [['list_name', 'list_value'], 'string', 'max' => 100],
            [['list_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'list_id' => Yii::t('app', 'List ID'),
            'list_name' => Yii::t('app', 'Name'),
            'list_parent' => Yii::t('app', 'List Parent'),
            'list_value' => Yii::t('app', 'Value'),
            'list_description' => Yii::t('app', 'Description'),
        ];
    }
    
    static public function getItemByList($parent_list_name){
        $array = array();
        $parent = ListSetup::find()->where(['list_name'=>$parent_list_name,'list_value'=>'parent'])->one();
        if($parent){
            $items = ListSetup::find()->where(['list_parent'=>$parent->list_id])->all();
            foreach ($items as $item) {
                $array[$item->list_value] = Yii::t('app',$item->list_name);
            }
        }
        return $array;
    }



    public function getSelectOptionList($list_name=false,$list_arr=false,$name=false,$event="",$selected=false,$id_name=false,$class='form-control')
    {
        $id= "";
        if(!$list_arr)
            $list_arr = $this->getItemByList($list_name);
        if(!$name)
            $name = $list_name."[]";
        if($list_name)
            $id = $list_name;
        if($id_name)
            $id = $id_name;
        $select ="<select class='".$class."' id=".$id."  name=".$name." ".$event." >";
        foreach ($list_arr as $key=>$value)
        {
            if($selected && $key==$selected )
                $select.="<option value=".$key." selected >".$value."</option>";
            else
                $select.="<option value=".$key." >".$value."</option>";
        }
        $select.="</select>";
        return $select;
    }
    
    public function getItemName($list_name,$id)
    {
        $arr_list = $this->$list_name;
        return $arr_list[$id];
    }
    
    public static $TViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
    "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
    ,"ế","ệ","ể","ễ",
    "ì","í","ị","ỉ","ĩ",
    "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
    ,"ờ","ớ","ợ","ở","ỡ",
    "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
    "ỳ","ý","ỵ","ỷ","ỹ",
    "đ",
    "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
    ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
    "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
    "Ì","Í","Ị","Ỉ","Ĩ",
    "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
    ,"Ờ","Ớ","Ợ","Ở","Ỡ",
    "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
    "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
    "Đ");
    
    public static $koDau=array("a","a","a","a","a","a","a","a","a","a","a"
     ,"a","a","a","a","a","a",
     "e","e","e","e","e","e","e","e","e","e","e",
     "i","i","i","i","i",
     "o","o","o","o","o","o","o","o","o","o","o","o"
     ,"o","o","o","o","o",
     "u","u","u","u","u","u","u","u","u","u","u",
     "y","y","y","y","y",
     "d",
     "A","A","A","A","A","A","A","A","A","A","A","A"
     ,"A","A","A","A","A",
     "E","E","E","E","E","E","E","E","E","E","E",
     "I","I","I","I","I",
     "O","O","O","O","O","O","O","O","O","O","O","O"
     ,"O","O","O","O","O",
     "U","U","U","U","U","U","U","U","U","U","U",
     "Y","Y","Y","Y","Y",
     "D");
    
 public static function replace_vietnameses($string_input)
    {

        return str_replace(ListSetup::$TViet,  ListSetup::$koDau,$string_input);
    }
    
    public static function getDisplayDate($date,$format=false){
        if($date=='0000-00-00' || $date=='0000-00-00 00:00:00' || $date=='1970-01-01 00:00:00' || $date=='1970-01-01' || $date==NULL)
            return "";
        $config = Config::find()->one();
        if(!$format)
            $format = $config->format_date; 
        
        return date($format, strtotime($date));
    }
    
    public static function getDisplayDateTime($date,$format=false){
        if($date=='0000-00-00' || $date=='0000-00-00 00:00:00' || $date=='1970-01-01 00:00:00' || $date=='1970-01-01')
            return "";
        $config = Config::find()->one();
		if(!$format)
            $format = $config->format_date.' '.$config->format_time; 
        return date($format, strtotime($date));
    }
    
    public static function getDisplayDateSql($date,$format=false){
        if($date=='0000-00-00' || $date=='0000-00-00 00:00:00' || $date=='1970-01-01 00:00:00' || $date=='1970-01-01')
            return "";
        if($format)
            return date($format, strtotime($date));
        else
            return date(ListSetup::FOMAT_DATE_SQL,  strtotime($date));
    }
    
    public static function getDisplayDateTimeSql($date,$format=false){
        if($date=='0000-00-00' || $date=='0000-00-00 00:00:00' || $date=='1970-01-01 00:00:00' || $date=='1970-01-01')
            return "";
        if($format)
            return date($format, strtotime($date));
        else
            return date(ListSetup::FOMAT_DATETIME_SQL,  strtotime($date));
    }
    
    public static function getDisplayPrice($price,$decimal=0){
        $config = Config::find()->one();
        $decimal = ($decimal==0) ? 0 : $config->decimal;
        return number_format($price,$decimal,$config->decimalSeparator,$config->thousandSeparator);
    }
    
    public static function getDisplayPriceSql($price){

        return number_format($price,0);
    }
    
    public static function getDisplayTime($date,$format=false){
        $config = Config::find()->one();
        if(!$format)
            $format = $config->format_time; 
        
        return date($format, strtotime($date));
    }
    
    public function delete() {
        $result = parent::delete();
        if($result)
            ListSetup::deleteAll ('list_parent='.$this->list_id);
        return $result;
    }
    
    public static function diffDays($dateFrom,$dateTo){
        $datetime1 = new DateTime($dateFrom);
        $datetime2 = new DateTime($dateTo);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        return $days;
    }
    
    public function year(){
        $year = array();$start = 2016;$end = date('Y');
        for($i=$start;$i<=$end;$i++){
            $year[$start] = $start;
            $start++;
        }
        return $year;
    }
    
    public function getMonth($i=0){
        $month = array();
        $month[$i] = Yii::t('app', 'Jan');
        $month[] = Yii::t('app', 'Feb');
        $month[] = Yii::t('app', 'Mar');
        $month[] = Yii::t('app', 'Apr');
        $month[] = Yii::t('app', 'May');
        $month[] = Yii::t('app', 'Jun');
        $month[] = Yii::t('app', 'Jul');
        $month[] = Yii::t('app', 'Aug');
        $month[] = Yii::t('app', 'Sep');
        $month[] = Yii::t('app', 'Oct');
        $month[] = Yii::t('app', 'Nov');
        $month[] = Yii::t('app', 'Dec');
        return $month;
    }
    
    
    
    public function getRevenue(){
        $revenue = new \app\modules\revenue_type\models\Revenue();
        return array('Membership'=>'Membership','booking'=>'Facilities') 
            + \app\models\ListSetup::getItemByList('revenue_type')+$revenue->getrevenueName() + array('other'=>'Other','pos'=>'Pos');
    }
    
    public function getTypetime($i=0){
        $type = array();
        $type[$i] = Yii::t('app', 'Monthly');
        $type[] = Yii::t('app', 'Weekly');
        $type[] = Yii::t('app', 'Daily');
        $type[] = Yii::t('app', 'Hourly');
        return $type;
    }
    public function getDay($i=0){
        $day = array();
        $day[$i] = Yii::t('app', '1st');
        $day[] = Yii::t('app', '2nd');
        $day[] = Yii::t('app', '3rd');
        $day[] = Yii::t('app', '4th');
        $day[] = Yii::t('app', '5th');
        $day[] = Yii::t('app', '6th');
        $day[] = Yii::t('app', '7th');
        $day[] = Yii::t('app', '8th');
        $day[] = Yii::t('app', '9th');
        $day[] = Yii::t('app', '10th');
        $day[] = Yii::t('app', '11st');
        $day[] = Yii::t('app', '12nd');
        $day[] = Yii::t('app', '13rd');
        $day[] = Yii::t('app', '14th');
        $day[] = Yii::t('app', '15th');
        $day[] = Yii::t('app', '16th');
        $day[] = Yii::t('app', '17th');
        $day[] = Yii::t('app', '18th');
        $day[] = Yii::t('app', '19th');
        $day[] = Yii::t('app', '20th');
        $day[] = Yii::t('app', '21st');
        $day[] = Yii::t('app', '22nd');
        $day[] = Yii::t('app', '23rd');
        $day[] = Yii::t('app', '24th');
        $day[] = Yii::t('app', '25th');
        $day[] = Yii::t('app', '26th');
        $day[] = Yii::t('app', '27th');
        $day[] = Yii::t('app', '28th');
        $day[] = Yii::t('app', '29th');
        $day[] = Yii::t('app', '30th');
        $day[] = Yii::t('app', '31st');
        return $day;
    }
    public function getHour($i=0){
        $hour = array();
        $hour[$i] = Yii::t('app', '00');
        $hour[] = Yii::t('app', '01');
        $hour[] = Yii::t('app', '02');
        $hour[] = Yii::t('app', '03');
        $hour[] = Yii::t('app', '04');
        $hour[] = Yii::t('app', '05');
        $hour[] = Yii::t('app', '06');
        $hour[] = Yii::t('app', '07');
        $hour[] = Yii::t('app', '08');
        $hour[] = Yii::t('app', '09');
        $hour[] = Yii::t('app', '10');
        $hour[] = Yii::t('app', '11');
        $hour[] = Yii::t('app', '12');
        $hour[] = Yii::t('app', '13');
        $hour[] = Yii::t('app', '14');
        $hour[] = Yii::t('app', '15');
        $hour[] = Yii::t('app', '16');
        $hour[] = Yii::t('app', '17');
        $hour[] = Yii::t('app', '18');
        $hour[] = Yii::t('app', '19');
        $hour[] = Yii::t('app', '20');
        $hour[] = Yii::t('app', '21');
        $hour[] = Yii::t('app', '22');
        $hour[] = Yii::t('app', '23');
        return $hour;
    }
    static public function getWeek(){
        $week = array();
        $week['Sunday'] = Yii::t('app', 'Sunday');
        $week['Monday'] = Yii::t('app', 'Monday');
        $week['Tuesday'] = Yii::t('app', 'Tuesday');
        $week['Wednesday'] = Yii::t('app', 'Wednesday');
        $week['Thursday'] = Yii::t('app', 'Thursday');
        $week['Friday'] = Yii::t('app', 'Friday');
        $week['Saturday'] = Yii::t('app', 'Saturday');
        
        return $week;
    }
    
    public function getNumericSymbols(){
        $result = array();
        $result[] = ' '.Yii::t('app', 'thousands');
        $result[] = ' '.Yii::t('app', 'millions');
        $result[] = ' '.Yii::t('app', 'billion');
        return $result;
    }
	public function getDate($date)
    {
        $result = array();
        $date_now = date('Y-m-d');
        $real_diff = strtotime($date) - strtotime($date_now);
		$diff = abs($real_diff);
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        if($years>0)
            $day_return = $years . " ".Yii::t('app','years').", " . $months . " ".Yii::t('app','months').", " . $days . " ".Yii::t('app','days')."";
        else if($months>0)
            $day_return=$months . " ".Yii::t('app','months').", " . $days . " ".Yii::t('app','days')."";
        else {
            $day_return=$days . " ".Yii::t('app','days')."";
        }
        $result['diff'] = $real_diff;
        $result['day_return']= $day_return;
        
        return $result;
    }
    
    public function getNameMenuPos($string=false){
        $string = strip_tags($string);

        if (strlen($string) > 17) {
            $stringCut = substr($string, 0, 17);
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
        }
        return $string;
    }
    
    public static function convertDayNumber(){
        $result['Monday']='Thứ 2';
        $result['Tuesday']='Thứ 3';
        $result['Wednesday']='Thứ 4';
        $result['Thursday']='Thứ 5';
        $result['Friday']='Thứ 6';
        $result['Saturday']='Thứ 7';
        $result['Sunday']='Chủ nhật';
        return $result;
    }
    
    public static function getCurrency(){
        $config = Config::find()->one();
        $currency = 'vnd';
        if($config)
            $currency = $config->currency;
        $list_currency = ListSetup::getItemByList('Currency');
        return isset($list_currency[$currency]) ? ' ('.$list_currency[$currency].')' : '';
    }
}
