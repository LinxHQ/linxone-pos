<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\members\models\Members;
use kartik\date\DatePicker;
use app\models\User;
use app\models\ListSetup;
use app\modules\membership_type\models\MembershipType;
use app\modules\membership_type\models\MembershipPrice;
use app\modules\members\models\Membership;
use app\modules\invoice\models\Payment;

/* @var $this yii\web\View */
/* @var $model app\modules\invoice\models\invoice */
/* @var $form yii\widgets\ActiveForm */
$member_id=false;
$Member=new Members();
$ListSetup = new ListSetup();
$InfomembershipType = false;
$MemberShip = new Membership();
$payment = new Payment();
$createYear = date('Y');
$user = new app\models\User();

$payment_now=$payment->get_numerics($payment->getPaymentLast());

if(isset($_GET['member_id']))
    $Member = $Member->getMember($_GET['member_id']);
$MembershipPrice = new MembershipPrice();
$InfoPrice=array();
$Infomembership=array();

if(isset($_GET['membership_type']))
{
    $InfomembershipType= MembershipType::findOne($_GET['membership_type']);
    $InfoPrice= $MembershipPrice->getPriceByMembershipType($_GET['membership_type'],date('Y-m-d'));
    $Infomembership = $MemberShip->getMemberShip($_GET['member_id']);
}

$confirmation_code = "";
if(isset($_GET['book_id'])){
    $book_id = $_GET['book_id'];
    $book = \app\modules\booking\models\Booking::findOne($book_id);
    if($book)
        $confirmation_code = $book->confirmation_code;
}

$price=0;$membership_start_date="";$membership_end_date="";

if(count($InfoPrice) > 0)
{
    $price = $InfoPrice[0]['membership_price'];
}

if(count($Infomembership)>0)
{
    $membership_start_date = $Infomembership[0]['membership_startdate'];
    $membership_end_date = $Infomembership[0]['membership_enddate'];
}
$membership_start_date=($membership_start_date!="")?date('d/m/Y',strtotime($membership_start_date)):"";
$membership_end_date=($membership_end_date!="")?date('d/m/Y',strtotime($membership_end_date)):"";

$Method = $ListSetup->getSelectOptionList("Method");
$payment_note = $ListSetup->getSelectOptionList("payment_note");
if($InfomembershipType)
    $description = $InfomembershipType->membership_name.'<br/>'.$membership_start_date.'-'.$membership_end_date.'<br/>';
$amount = $price;
$quantity = 1;
$invoice_item_id = "";
$subTotal = $price;
$paid =0;
//echo '<pre>';
//print_r($invoiceItem);
$outstanding = $price;
if(isset($invoiceItem)){
    $invoice_item_id = $invoiceItem->invoice_item_id;
    $price = $invoiceItem->invoice_item_price;
    $quantity = $invoiceItem->invoice_item_quantity;
    $amount = $invoiceItem->invoice_item_amount;
    $description = $invoiceItem->invoice_item_description;
    $subTotal = $model->getSubtotalInvocie($model->invoice_id);
    $outstanding = $model->getInvoiceOustanding($model->invoice_id);
    $paid = $payment->getAmountByInvoice($model->invoice_id);
}
$curentcy = 0;
if($model->invoice_currency)
    $curentcy = $model->invoice_currency;
$discount_amount=0;
$tax_amount=0;

if($invoice->invoice_discount)
{
    $discount_arr=ListSetup::getItemByList('Discount');
    $discount_value=$invoice->invoice_discount;
    
    $discount_amount = ($amount*$discount_value)/100;
}
if($invoice->invoice_gst)
{
    $tax_arr=ListSetup::getItemByList('Tax');
    $tax_value=$tax_arr[$invoice->invoice_gst];
    $tax_amount = (($amount-$discount_amount)*$tax_value)/100;
}
    
?>

<h3 style="font-size: 18px; text-align: center">MEMBERSHIP AGREEMENT</h3> 
<div style="width: 100%;height: 20px; background-color: black;font-size: 14px;color: white;text-align: center;font-weight: bold;" >PERSONAL DETAIL</div>
    <table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
        <tr>
            <td style="width: 25%"><span >Full name:</span></td><td> <?php echo $Member->member_name;?></td>
            <td style="width: 25%"><span >Identity Card/Passport No:</span></td><td style="text-align: right"> <?php echo $Member->id_card;?></td>
            
        </tr>
        <tr>
            <td style="width: 25%;"><span >Registered Address:</span></td><td> <?php echo $Member->member_address;?></td>
            <td ><p>Email Address:</p></td>
            <td style="text-align: right"><?php echo $Member->member_email;?></td>
        </tr>
        <tr>
            <td ><p style="">Date of Birth:</p></td>
            <td  style="">

                <?php
                $model->invoice_date = ($model->invoice_date) ? $model->invoice_date : date('Y-m-d');
                echo date('d/m/Y',strtotime($model->invoice_date));
                ?>
            </td>
            <td>Home Phone No:</td>
            
        </tr>
        <tr>
            
        </tr>
        <tr>
            <td style="width: 25%;"><span>Mobile No:</span></td><td> <?php echo $Member->member_mobile;?></td>
            <td style=""><p></p></td>
            <td style="text-align: right"><?php echo $Member->member_phone;?></td>
        </tr>
        <tr>
            <td colspan="3"><span>VAT Invoice (if applicable): Company Name, Address&Tax code : </span><?php echo $Member->VAT_request;?></td>
            
        </tr>
<!--        <tr>
            <td style="width: 25%;font-weight: bold"><span >Emergency contact</span></td><td> </td>
            
        </tr>
        <tr>
            <td colspan="4">
            <table style="float:left; width: 100%;font-size: 14px;line-height: 18px">
                <tr>
                    <td style="width: 25%;"><span>Name:</span></td><td> </td>
                    <td style=""><p>Telephone:</p></td>
                    <td style="text-align: right"><?php echo $Member->member_phone;?></td>
                </tr>
                
            </table>
            </td>
        </tr>-->
        
    </table>

<table style="float:left; width: 100%;">
<!--    <tr>
        <td style="font-size: 18px;width: 40%;height: 30px; background-color: black;color: white;text-align: center;font-weight: bold;" >ACCOUNTING </td>
        <td style="font-size: 18px;width: 60%;height: 30px; background-color: black;color: white;text-align: center;font-weight: bold;" >MEMBERSHIP DETAILS</td>
    </tr>-->
    <tr>
        <td style="width:20%">
            <table style="float:left; width: 100%;font-size: 1px;line-height: 17px">
                <tr><td style="font-size: 1px">Gender:</td></tr>
            </table>
        </td>
        <td style="font-size: 14px;width: 60%;height: 20px; background-color: black;color: white;text-align: center;font-weight: bold;" >MEMBERSHIP DETAILS</td>
    </tr>
    <tr>
        <td>
            <table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
                <tr>
                    <td style="width: 25%;font-weight: bold"><span>Emergency contact</span></td>
           
                </tr>
                <tr>
                    <td ><p>Name:</p></td>
                   
                </tr>
                <tr>
                    <td ><p>Telephone:</p></td>
                   
                </tr>
                
                
            </table>
        </td>
        <td >
            <table style="width: 100%;font-size: 12px;border-left:1px solid #ddd">
                <tr>
                    <td style="width: 20%;">Kind:</td>
                    <td style="width: 60%">
                        <table style="line-height: 17px">
                            <tr>
                                <td><input type="checkbox">Resident</td>
                                <td><input type="checkbox">Non Resident</td>
                                <td><input type="checkbox">Individual</td>
                            </tr>
                            <tr>
                                
                                <td><input type="checkbox">Family Master </td>
                            
                                <td><input type="checkbox">Family Add On</td>

                            </tr>

                        </table>
                        
                    </td>

                </tr>
            </table>
            
        </td>
    </tr>
    <tr>
        <td style="font-size: 14px;width: 40%;height: 20px; background-color: black;color: white;text-align: center;font-weight: bold;" >ACCOUNTING </td>
        <td style="font-size: 12px" >
            <table style="border-left:1px solid #ddd;">
                <tr>
                    <td style="font-size: 12px" >
                        Membership Type:
                    </td>
                        <td style="text-align: left"> <?php echo ($modelMembershipType?$modelMembershipType->membership_name:"");?>
                    </td>


                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 50%;font-size: 12px">
            <table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
                <tr>
                    <td style="width: 60%"><span >Membership Fee</span></td>
                    <td> <?php echo number_format($amount,2);?> </td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Discount</span></td>
                    <td> <?php echo number_format($discount_amount,2);?></td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Tax</span></td>
                    <td> <?php echo number_format($tax_amount,2);?></td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Total amount</span></td>
                    <td > <?php echo number_format($subTotal,2);?> </td>
                    <td>VND</td>

                </tr>

            </table>
                
        </td>
        <td style="width: 60%;font-size: 16px;border-left:1px solid #ddd">
            <table style="width: 100%;font-size: 12px;">

                <tr>
                    <td  >
                        Related Membership:
                    </td>
                        <td style="text-align: left"> 
                    </td>


                </tr>
                <tr>
                    <td >
                        
                        <span >Original Membership:</span>
                    </td>
                        <td style="text-align: left"> 
                    </td>


                </tr>
                <tr>
                    <td colspan="2">
                        <table style="">
                            <tr>
                                <td style="width: 25%"><input type="checkbox"><span >PREPAID Membership</span></td>
                                <td style="width: 20%"> Begin</td>
                                <td style="width: 10%"><span >D</span></td>
                                <td style="width: 10%" >M</td>
                                <td style="width: 10%" >Y</td>
                                <td style="width: 20%" >Prepaid Period Months</td>

                            </tr>


                        </table>
                    </td>
                </tr>
                <br/>
                
                <tr>
       
                    <td colspan="2" style="font-size: 12px">A Prepaid membership is non-cancelable and the prepaid monthly  dues are non-refundable</td>

                </tr>
                <tr><td colspan="2" style="text-align: right">Member's Initial</td> </tr>
                
            </table>
                
        </td>
        
    </tr>
    
</table>

<br/>

<div style="width: 100%;height: 20px; background-color: black;font-size: 14px;color: white;text-align: center;font-weight: bold;" >CONSIGNER</div> 
<table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
    <tr>
        <td style="width:40%"><input type="checkbox"  value="asasas" />
            Parent/Guardian: On behalf of my minor child, I acknowledge and consent to be bound by the relevant terms and conditions of the Agreement and be responsible for any financial obligation including the payment of membership fee that my minor child does not fulfill for any reason.
        </td>
        <td style="width:9%;"></td>
        <td style="width:40%;"><input type="checkbox"  value="asasas" />
            Consigner: I acknowledge and consent to be bound by the relevant terms and conditions of this Agreement and be responsible for any financial obligation including the payment of membership fee that the undersigning member does not fulfill for any reason
        </td>
       
    </tr>
    <tr>
        <td colspan="4">As a parent/guardian or cosigner. I understand that my obligations under this Agreement would end only when the undersigning member terminates in accordance with the terms of this Agreement.</td>
    </tr>
    
</table>
<table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
    
    <tr>
        <td style="width:40%">Signature:</td>
        <td style="width:30%;">Relationship:</td>
        <td style="width:30%;">Date:
        </td>
       
    </tr>
    <tr>
        <td style="width:40%">Name:</td>
        <td colspan="2">Identity Card #/Passport#:</td>
       
    </tr>
    <tr>
        <td style="width:40%">Residential Address:</td>
        <td colspan="2">Email:</td>
       
    </tr>
    <tr>
        <td style="width:40%">Mobile Phone No:</td>
        <td colspan="2">Home Phone No:</td>
       
    </tr>
</table>


<!--<div style="width: 100%;height: 20px; background-color: black;font-size: 14px;color: white;text-align: center;font-weight: bold;" >IMPORTANT PRE-SIGNING NOTICES TO THE MEMBER</div> 
<table style="float:left; width: 100%;font-size: 12px;line-height: 17px">
    <tr>
        <td style="width:100%">
            1. You are given sufficient time to review this Membership Agreement (hereinafter refferred to as the "Agreement") and request for clarifications from the Club's staff. Thus, please do not sign this Agreement until you have read and understood all the terms and conditions of this Agreement and your membership. By signing this Agreement, you understand that your Membership shall commence as from the effective date of the Agreement and you have entered into a legally binding relationship between yourself and Vietnam International Township Development JSC (hereafter called "VIDC"). All rights and obligations set out in this Agreement will be in force from the effective date of this Agreement.
        </td>
    </tr>
    <tr>
        <td style="width:100%">
            2. The rights and obligation of the member are set forth in this Agreement, the Club Etiquette and in other internal regulations implemented by VIDC from time to time (collectively referred to as "Terms and Conditions"). By signing this Agreement, the member acknowledges his or her understanding and acceptance of the rights and obligations under Terms and Condtions, such Terms and Conditions are available at VIDC. VIDC reserve rights to amend the Term and Conditions from time to time and where appropriate. The member shall ask a front desk staff for clarification of any question or concerns prior to signing this Agreement.
        </td>
    </tr>
    
    
</table>

<table style="float:left; width: 100%;font-size: 12px;line-height: 20px">
    <tr>
        <td style="width:100%;text-align: right">
            For Member/ (For VIDC)
        </td>
    </tr>
    
</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<div style="width: 100%;height: 16px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >TERM AND CONDITION OF THE MEMBERSHIP OF VIDC</div> 
<div style="font-size: 12px">   
<span>1. The member must have the full civil capacity according to the Vienamese law to enter into this Agreement (for example, the member must be 18 years old or older). If the member's civil capacity is limited (for example, the member is between the ages of 12 and 18), he/she represents that he/she has obtained the consent of his/her legal representatvie prior to entering into this Agreement.</span>
<br/><span>2. The nature of the membership of VIDC under this Agreement is exclusive. The rights and privileges associated to the membership of VIDC shall be enjoyed only by the member and such member may only transfer one time his/her membership of VIDC to his/her family's relative. Transfer fee is 1,000,000VND.</span>
<br/><span>3. All monies paid, including but not limited to the membership fees, may not be refunded to the member in any case unless otherwise provided here in.</span>    
<br/><span>4. The member may suspend his/her membership for a period from one month up to one year of the memeber encounters a medical disability as substaintiated by a medical report or indication issued by a Public Hospital which is duly certified or verified by the competent authority. The member may also suspend his/her membership up to one year as a result of studying or business trip overseas as substantiated by a document evidencing; in this case, the member shall pay an administration fee of 200,000 VND per month during the suspension period. The membership suspension shall only begin upon VIDC receiving the required document from the member, no back dating will be allowed. The regulations on suspesion of the memebership are not applicable to any guest pass or short term memeberships from one to five months.</span>
<br/><span>5. Finess Orientation does not mean and shall not be deemed as Personal Training. Personal Training is available at an additional fee.Member is only allowed to contract VIDC's Personal Trainers, No others Personal Trainers may be contracted at The ParkCity Club Hanoi.</span>
<br/><span>6. If by reason of death or permanent disability which in capacitates the member or prevents him/her from using a significant part of the club's facilities, the member may terminate his/her membership and shall be relieved from the obligation of making payment for the membership and the service other than those service the member already used prior to the onset of the disability or time of death. If the services not yet used were prepaid, the pro-rated memebership fee will be refunded to the member or his/her successor within thirty (30) business days since the date of receiving a copy of the death certificate or medical proof issued by the competent authority or a duly licensed medical organization.</span>
<br/><span>7. For the safety and consideration of other members of the Club,VIDC Hanoi reserves the right to unilatrally terminate this Agreement and the membership of the member if the memeber commits any breach of this Agreement, the Club Etiquette, or any term of the Terms and Conditions. In such case, the member shall not be entitled to any refund of fees.</span>
<br/><span>8. To enter the club, the member must present his/her membership card. A photo of the memeber for the purpose of member identification will be taken by VIDC's staff. In the event the memebership card is lost or stolen, the member should contact VIDC regarding to the re-issuance of a replacement card. The member shall bear the cost for re-issuance of the replacement card, which is VND 300,000.</span>
<br/><span>9. In the event this is a Pre paid term membership as marked in the "Membership section, the members shall have continuous use of the facilities of the Club for the prepaid period. The usage for Tennis & Badminton court must be booked in advance upon the availability, the booking will be oppened from 9am the preceding Saturday for the following week's schedule (Monday to Sunday).</span>                                                                                                                                                                                                                                                    
<br/><span>10. VIDC reserves the rights to amend the type of aerobic/yoga/personal training programs and/or aerobic/yoga/personal training instructors assigned for any class in the club without prior notice. The member understands and accepts that access to classses may not be available from time to time especially during peak hours.</span>                                                                                                                                                                               
<br/><span>11. During each workout, the member is entitled to use a day locker to store his/her items. The day lockers are large lockers and must be cleared  when the member has finished his/her workout. The day lockers shall be cleared every night by VIDC's staff and contents removed due to hygiene reasons. </span>
<br/><span>12. This Agreement including the first part at the overleaf, the Terms and Condidtions of membership, and the disclaimer of liability constitutes the entire Agreement between you and VIDC with respect to the membership purchase and supersedes all prior discussion, negotiations and agreements between you and VIDC.</span>
<br/><span>13.The provisions of this Agreement are severable. The invalidity or unenforced ability of any provision of the Agreement shall not affect the validity or enforce ability of any other provision of the Agreement. 
<br/><span>14. Any amendment or supplementation to this Agreement must be made in writing and duly signed by memeber and the authorized representative of VIDC. Handwritten and/or verbal changes are invalid. the Term and Conditions are subject to modification from time to time at the sole discretion of VIDC and are not intended to be all inclusive or restrictive to the Club in the operation of its business. The updated editions of the Term and Condition are available at the club's reception counter. All previous editions are invalid.</span>
<br/><span>15. The Agreement shall be goveverned by and interpreted in accordance with the laws of Vietnam</span>  
<br/><span>16. The member acknowledges that he/she understands that he/she has the option of receiving this Agreement in English or Vietnamese, and the version signed is the language the member chooses.</span>
<br/><span>17. The Agreement is executed in two (2) copies, each of Member and VIDC shall keep one (1) copy as an original Agreement. This Agreement shall be effectived as from the signing date hereof until the expiry date of the membership or until being terminated in accordance with Terms and Conditions.</span>
</div>    

<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >DISCLAIMER OF LIABILITY</div> 
<div style="font-size: 12px">   
<span>1. The use of the facilities of the Club naturally involves the risk of injury to a member him/herself or other members or guests of VIDC, whether caused by the member or someone else. The member understands and voluntarily accepts risk. The member warrants and represents that she/he has consulted with his/her physician prior to beginning any exercise program. The member agrees that VIDC will not be liable for any injury including, without limitation, bodily or mental injury, economic loss, or any damage to the member, or his/her relatives resulting from the acts of anyone using the facilities or acts of VIDC's employees or agents. The member agrees to bear all responsibilites for all liablilities or damages arising from any injury, including without limitation, bodily or mental injury, economic loss, or any damage to another member caused by deliberate or negligent action of the member him/herself. If any claim is made by anyone baed on any injury, loss or damage described herein, which involves the member or his/her guest, the member agrees to defend VIDC against such claims and pay VIDC for all expenses, including legal fees relating to the claims and indemnity VIDC for alll liabilities to the member and his/her spouse, unborn child, relatives or anyone else resulting from such claims</span>
<br/><span>2. VIDC is not responsible for any items lost or stolen on its premises for any reason whatsoever. The member is solely responsible for keeping their personal belongings safe in the premises.</span>
</div>-->
<br>
<br>
<table style="width:100%;font-size: 14px">
    <tr>
        <td style="width:70%;height: 60px;">Member's Signature</td>
        <td style="text-align: left;">Manager's Signature</td>
    </tr>
</table>

<table style="width:100%;font-size: 12px">
    <tr>
        <td style="width:70%">Date: </td>
        <td style="text-align: left">Date: </td>
    </tr>
</table>

             
      


