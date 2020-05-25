<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "next_ids".
 *
 * @property integer $next_id
 * @property integer $next_book_number
 * @property integer $next_barcode_id 
 * @property integer $next_guest_code
 * @property integer $next_agreement_id
 * @property integer $next_invoice_id
 * @property integer $next_card_id
 * @property integer $next_deposit_id
 * @property integer $next_member_checkin
 */
class NextIds extends \yii\db\ActiveRecord
{
    const FREFIX_BOOK = 'BK';
    const FREFIX_INVOICE = 'IV';
    const FREFIX_MEMBERSHIP = 'MS';
    const FREFIX_BARCODE = 'PCH';
    const FREFIX_MEMBERSHIP_BARCODE = 'Agmt';
    const FREFIX_GUEST = 'Guest';
    const FREFIX_TRAINER_CODE = 'TN';
    const FREFIX_SALE_CODE = 'SC';
    const FREFIX_PRODUCT_CODE = 'SP';
    const FREFIX_DEPOSIT_CODE = 'DP';
    const FREFIX_MEMBER_CHECKIN = 'CI';
    const FREFIX_LENGTH=6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'next_ids';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['next_book_number','next_card_id','next_invoice_id','next_guest_code','next_barcode_id','next_agreement_id','next_deposit_id'
                ,'next_member_checkin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'next_id' => 'Next ID',
            'next_book_number' => 'Next Book Number',
            'next_card_id' => 'Next Card No',
            'next_barcode_id'=>'Next Member Barcode',
            'next_guest_code'=>'Next Guest code',
            'next_agreement_id'=>'Next Agreement No',
            'next_invoice_id'=>'Next Invoice Number',
            'next_product_id'=>'Next Product Number',
            'next_deposit_id'=>'Next Depost Number'
        ];
    }
    
    public function addDefaultNextNumber(){
        $nextId = new NextIds();
        $nextId->next_id='';
        $nextId->next_book_number=1;
        $nextId->next_invoice_id=1;
        $nextId->next_card_id=1;
        $nextId->next_guest_code = 1;
        $nextId->next_barcode_id =1;
        $nextId->next_agreement_id =1;
        $nextId->next_trainer_id =1;
        $nextId->next_sale_id =1;
        $nextId->next_product_id =1;
        $nextId->next_deposit_id=1;
        $nextId->next_member_checkin=1;
        $nextId->save();
    }


    public function getNextBookNumber(){
        $next_id = NextIds::find()->one();
        $next_number = 1;
        
        if($next_id){
            $next_id = NextIds::find()->one();
            $next_number = $next_id->next_book_number;
        }else{
            
            $this->addDefaultNextNumber();
            $next_id = NextIds::find()->one();
            if($next_id)
                $next_number = $next_id->next_book_number;
        }
        return NextIds::FREFIX_BOOK.date('Y').$next_number;
    }
    
    
    
    public function getNextInvoice()
    {
        
        $next_id = NextIds::find()->one();
        $next_number = 1;
        
        if($next_id){
            $next_id = NextIds::find()->one();
            $next_number = $next_id->next_invoice_id;
            $next_id->next_invoice_id++;
            $next_id->save();
        }else{
            
            $this->addDefaultNextNumber();
            $next_id = NextIds::find()->one();
            if($next_id)
                $next_number = $next_id->next_invoice_id;
        }
        return NextIds::FREFIX_INVOICE.date('Y').$next_number;
    }
    
    
    public function getDisplayCardNo($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId("next_card_id");
        return NextIds::FREFIX_MEMBERSHIP.date('Y').$next_number;
        
    }
    public function getDisplayGuest($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId("next_guest_code");
        
        $return_next=NextIds::FREFIX_GUEST;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        $return_next.=$next_number;
        return $return_next;
        
    }
    public function getDisplayMemberBarcode($next_number=false)
    {
        $list_next_id = ListSetup::getItemByList('next_ids');
        if(!$next_number)
            $next_number = $this->getNextId("next_barcode_id");
        $return_next=NextIds::FREFIX_BARCODE;
        $set_frefix = array_search('member_barcode', $list_next_id);
        $set_frefix_lenght = array_search('frefix_length', $list_next_id);
                
        if(in_array('member_barcode', $list_next_id))
            $return_next = $set_frefix;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number);
        if(in_array('frefix_length', $list_next_id))
            $caculator_number = $set_frefix_lenght - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    public function getDisplayTrainerCode($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_trainer_id');
        $return_next=NextIds::FREFIX_TRAINER_CODE;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    public function getDisplayAgreementNo($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_agreement_id');
        $return_next=NextIds::FREFIX_MEMBERSHIP_BARCODE;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;;
        
    }
    
    public function getDisplaySaleCode($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_sale_id');
        $return_next=NextIds::FREFIX_SALE_CODE;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    
    public function getDisplayProductCode($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_product_id');
        $return_next=NextIds::FREFIX_PRODUCT_CODE;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    
    
    public function getDisplayDepositCode($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_deposit_id');
        $return_next=NextIds::FREFIX_DEPOSIT_CODE;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    
    public function getDisplayMemberCheckin($next_number=false)
    {
        if(!$next_number)
            $next_number = $this->getNextId ('next_member_checkin');
        $return_next=NextIds::FREFIX_MEMBER_CHECKIN;
        $caculator_number = NextIds::FREFIX_LENGTH - strlen($next_number); 
        $i=0;
        while ($i<$caculator_number)
        {
            $return_next=$return_next."0";
            $i++;
        }
        
        $return_next.=$next_number;
        return $return_next;
        
    }
    
    
    
    public function getNextId($feild)
    {
        $next_id = NextIds::find()->one();
        if(!$next_id){
            $this->addDefaultNextNumber();
            $next_id = NextIds::find()->one();
        }
        
        if(!$next_id->$feild)
            return false;
        
        $next = $next_id->$feild;
        return $next;
    }
    
    public function setNextId($feild,$next=false)
    {
        $feild = trim($feild);
        $next_id = NextIds::find()->one();
        if(!$next_id->$feild)
            return false;
        
        if(!$next_id){
            $this->addDefaultNextNumber();
            $next_id = NextIds::find()->one();
        }
            
        if($next)
            $next_id->$feild = $next;
        else{
            $next_id->$feild ++;
        }
        $next_id->save();
        return $next;
    }
}
