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

<h3 style="font-size: 20px; text-align: center">HỢP ĐỒNG HỘI VIÊN</h3> 
<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >THÔNG TIN CÁ NHÂN</div>
    <table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
        <tr>
            <td style="width: 25%"><span >Họ tên:</span></td><td> <?php echo $Member->member_name;?></td>
            <td style="width: 25%"><span >Số CMND/Hộ chiếu:</span></td><td style="text-align: right"> <?php echo $Member->id_card;?></td>
            
        </tr>
        <tr>
            <td style="width: 25%;"><span >Địa chỉ thường trú:</span></td><td> <?php echo $Member->member_address;?></td>
            <td ><p>Địa chỉ Email:</p></td>
            <td style="text-align: right"><?php echo $Member->member_email;?></td>
        </tr>
        <tr>
            <td ><p style="">Ngày sinh:</p></td>
            <td  style="">

                <?php
                $model->invoice_date = ($model->invoice_date) ? $model->invoice_date : date('Y-m-d');
                echo date('d/m/Y',strtotime($model->invoice_date));
                ?>
            </td>
            <td>Số điện thoại nhà:</td>
            
        </tr>
        <tr>
            
        </tr>
        <tr>
            <td style="width: 25%;"><span>Số điện thoại:</span></td><td> <?php echo $Member->member_mobile;?></td>
            <td style=""><p></p></td>
            <td style="text-align: right"><?php echo $Member->member_phone;?></td>
        </tr>
        <tr>
            <td colspan="3"><span>Thông tin hóa đơn (nếu cần): Tên & địa chỉ Công ty, mã số thuế: </span><?php echo $Member->VAT_request;?></td>
            
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
            <table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
                <tr><td >Giới tính:</td></tr>
            </table>
        </td>
        <td style="font-size: 12px;width: 60%;height: 14px; background-color: black;color: white;text-align: center;font-weight: bold;" >THÔNG TIN THẺ HỘI VIÊN</td>
    </tr>
    <tr>
        <td>
            <table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
                <tr>
                    <td style="width: 25%;font-weight: bold"><span>Người liên hệ khẩn cấp</span></td>
           
                </tr>
                <tr>
                    <td ><p>Tên:</p></td>
                   
                </tr>
                <tr>
                    <td ><p>Số điện thoại:</p></td>
                   
                </tr>
                
                
            </table>
        </td>
        <td >
            <table style="width: 100%;font-size: 12px;border-left:12px solid #ddd">
                <tr>
                    <td style="width: 20%;">Loại:</td>
                    <td style="width: 60%">
                        <table style="line-height: 18px">
                            <tr>
                                <td><input type="checkbox">Cư dân</td>
                                <td><input type="checkbox">Không phải cư dân</td>
                                <td><input type="checkbox">Gia hạn</td>
                            </tr>
                            <tr>
                                
                                <td><input type="checkbox">Cá nhân </td>
                            
                                <td><input type="checkbox">Gia đình</td>

                            </tr>

                        </table>
                        
                    </td>

                </tr>
            </table>
            
        </td>
    </tr>
    <tr>
        <td style="font-size: 12px;width: 40%;height: 14px; background-color: black;color: white;text-align: center;font-weight: bold;" >PHẦN DÀNH CHO KẾ TOÁN </td>
        <td style="font-size: 12px" >
            <table style="border-left:1px solid #ddd;">
                <tr>
                    <td style="font-size: 12px" >
                        Loại thẻ:
                    </td>
                        <td style="text-align: left"> <?php echo ($modelMembershipType?$modelMembershipType->membership_name:"");?>
                    </td>


                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 50%;font-size: 16px">
            <table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
                <tr>
                    <td style="width: 60%"><span >Phí Hội viên</span></td>
                    <td> <?php echo number_format($amount,2);?> </td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Khuyến mại</span></td>
                    <td> <?php echo number_format($discount_amount,2);?></td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Thuế</span></td>
                    <td> <?php echo number_format($tax_amount,2);?></td>
                    <td>VND</td>

                </tr>
                <tr>
                    <td style="width: 60%"><span >Tổng phải thanh toán</span></td>
                    <td > <?php echo number_format($subTotal,2);?> </td>
                    <td>VND</td>

                </tr>

            </table>
                
        </td>
        <td style="width: 60%;font-size: 12px;border-left:1px solid #ddd">
            <table style="width: 100%;font-size: 12px;">

                <tr>
                    <td  >
                        Số thẻ Hội viên liên quan:
                    </td>
                        <td style="text-align: left"> 
                    </td>


                </tr>
                <tr>
                    <td >
                        
                        <span >Số thẻ Hội viên ban đầu:</span>
                    </td>
                        <td style="text-align: left"> 
                    </td>


                </tr>
                <tr>
                    <td colspan="2">
                        <table style="">
                            <tr>
                                <td style="width: 25%"><input type="checkbox"><span >Thẻ Hội viên trả trước</span></td>
                                <td style="width: 20%"> Ngày bắt đầu</td>
                                <td style="width: 10%"><span >Ngày</span></td>
                                <td style="width: 10%" >Tháng</td>
                                <td style="width: 10%" >Năm</td>
                                <td style="width: 20%" >Số tháng đã trả trước</td>

                            </tr>


                        </table>
                    </td>
                </tr>
                <br/>
                
                <tr>
       
                    <td colspan="2" style="font-size: 12px">Thẻ Hội viên trả trước là không thể hủy bỏ và phí Hội viên trả trước sẽ không được hoàn lại</td>

                </tr>
                <tr><td colspan="2" style="text-align: right">Chữ ký tắt của hội viên</td> </tr>
                
            </table>
                
        </td>
        
    </tr>
    
</table>

<br/>

<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >NGƯỜI ĐỒNG KÝ TÊN</div> 
<table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
    <tr>
        <td style="width:40%"><input type="checkbox"  value="asasas" />
           Cha mẹ/người giám hộ: Thay mặt cho con chưa thành niên của tôi, tôi thừa nhận và đồng ý chịu sự ràng buộc bởi các điều khoản và điều kiện của Hợp đồng này cũng như có trách nhiệm với bất kỳ nghĩa vụ tài chính nào, bao gồm việc thanh toán phí Hội viên mà con chưa thành niên của tôi thực hiện vì bất kỳ lí do gì.
        </td>
        <td style="width:9%;"></td>
        <td style="width:40%;"><input type="checkbox"  value="asasas" />
            Người đồng ký tên: Tôi thừa nhận và đồng ý chịu sự ràng buộc bởi các điều khoản và điều kiện của hợp đồng này cũng như có trách nhiệm với bất kỳ nghĩa vụ tài chính nào, bao gồm việc thanh toán phí Hội viên mà Hội viên ký tên dưới đây không thực hiện vì bất kỳ lí do gì.
        </td>
       
    </tr>
    <tr>
        <td colspan="4">Với tư cách là cha me/người giám hộ hoặc người đồng ký tên, tôi hiểu rằng các nghĩa vụ của tôi theo Hợp đồng này chỉ chấm dứt khi tư cách Hội viên của Hội viên ký tên dưới đây chấm dứt theo các điều khoản của Hợp đồng này. </td>
    </tr>
    
</table>
<table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
    
    <tr>
        <td style="width:40%">Chữ ký:</td>
        <td style="width:30%;">Mối quan hệ:</td>
        <td style="width:30%;">Ngày:
        </td>
       
    </tr>
    <tr>
        <td style="width:40%">Tên:</td>
        <td colspan="2">Số CMND/Hộ chiếu:</td>
       
    </tr>
    <tr>
        <td style="width:40%">Địa chỉ thường trú:</td>
        <td colspan="2">Địa chỉ email:</td>
       
    </tr>
    <tr>
        <td style="width:40%">Số điện thoại di động:</td>
        <td colspan="2">Số điện thoại nhà:</td>
       
    </tr>
</table>
<br/>

<!--<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >LƯU Ý QUAN TRỌNG TRƯỚC KHI KÝ KẾT ĐỐI VỚI HỘI VIÊN</div> 
<table style="float:left; width: 100%;font-size: 12px;line-height: 18px">
    <tr>
        <td style="width:100%">
            1. Bạn có đủ thời gian để đọc kỹ Hợp đồng Hội viên này (sau đây được gọi là "Hợp đồng") và yêu cầu nhân viên của Câu lạc bộ giải thích về bất kỳ nội dung nào. Do đó, vui lòng đừng ký Hợp đồng này cho đến khi bạn đã đọc và hiểu tất cả các Điều khoản và Điều kiện của Hợp đồng và Thẻ Hội viên của bạn. Bằng việc ký Hợp đồng này, bạn hiểu rằng Thẻ Hội viên của bạn sẽ bắt đầu như được nêu trên trong Hợp đồng và bạn đã tham gia vào một quan hệ pháp lý ràng buộc giữa bạn và Công ty cổ phần phát triển Đô thị Quốc tế Việt Nam (sau đây được gọi là "VIDC"). Tất cả quyền và nghĩa vụ được quy định tại Hợp đồng này sẽ có hiệu lực kể từ ngày Hợp đồng này có hiệu lực.
        </td>
    </tr>
    <tr>
        <td style="width:100%">
            2. Các quyền và nghĩa vụ của Hội viên được quy định tại Hợp đồng này, Quy tắc ứng xử tại Câu lạc bộ và trong các quy định nội bộ khác do VIDC ban hành tùy từng thời điểm (được gọi chung là "các Điều khoản và Điều kiện"). Bằng việc ký Hợp đồng này, Hội viên thừa nhận đã hiểu rõ và chấp thuận các quyền và nghĩa vụ theo các Điều khoản và Điều kiện. VIDC bảo lưu quyền sửa đổi các điều khoản và Điều kiện tùy từng thời điểm nếu thấy thích hợp. Hội viên sẽ hỏi nhân viên tại quầy chăm sóc Khách hàng để được giải thích cho bất kỳ câu hỏi hoặc thắc mắc nào trước khi ký.
        </td>
    </tr>
    
    
</table>
<br/>
<table style="float:left; width: 100%;font-size: 12px;line-height: 20px">
    <tr>
        <td style="width:100%;text-align: right">
            Dành cho Khách hàng/ (Dành cho VIDC)
        </td>
    </tr>
    
</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >CÁC ĐIỀU KHOẢN VÀ ĐIỀU KIỆN CỦA HỢP ĐỒNG HỘI VIÊN CỦA VIDC</div> 
<div style="font-size: 12px">
<span> 1. Hội viên phải có đầy đủ năng lực hành vi dân sự theo quy định của pháp luật Việt Nam để ký kết Hợp đồng này (ví dụ: Hội viên phải là người từ 18 tuổi trở lên). Nếu Hội viên bị hạn chế về năng lực hành vi dân sự (ví dụ: Hội viên từ 12 tuổi đến 18 tuổi). Hội viên khẳng định rằng Hội viên đã được người đại diện theo pháp luật của mình đồng</span>                                                                                                                                                                                                                                  
<br/><span>2. Bản chất của Thẻ Hội viên theo Hợp đồng này là có tính cá nhân. Các quyền và đặc quyền của Hội viên sẽ chỉ dành cho Hội và Hội viên được phép chuyển nhượng một lần Thẻ Hội viên của mình tại VIDC cho người thân trong gia đình. Phí chuyển nhượng là 1,000,000 VND. </span>                                                                                 
<br/><span>3. Tất cả khoản tiền đã thanh toán, bao gồm nhưng không hạn chế phí Hội viên, sẽ không được hoàn lại trong bất kỳ trường hợp nào trừ khi được quy định khác trong Hợp đồng này.</span>                                     
<br/><span>4. Hội viên có thể tạm dừng Thẻ Hội viên trong một khoảng thời gian ít nhất là một tháng và nhiều nhất là một năm trong trường hợp có vấn đề về sức khỏe và được chứng minh bằng giấy khám bệnh của một bệnh viện công có xác nhận của cấp có thẩm quyền. Hội viên cũng có thể tạm dừng Thẻ Hội viên trong một khoảng thời gian tương tự trong trường hợp du học hoặc công tác nước ngoài được chứng minh bằng một tài liệu về việc du học hoặc công tác nước ngoài. Trong trường hợp này, Hội viên sẽ thanh toán cho VIDC phí quản lý là 200,000 VND mỗi tháng trong suốt khoản thời gian tạm dừng. Việc tạm dừng Thẻ Hội viên chỉ bắt đầu khi VIDC nhận được tài liệu theo yêu cầu trên đây từ Hội viên và sẽ không áp dụng tính lùi ngày cho việc tạm dừng. Quy định về việc tạm dừng Thẻ Hội viên không áp dụng cho khách sử dụng phiếu tập thử hoặc Thẻ Hội viên ngắn hạn từ một đến năm tháng.</span>
<br/><span>5. Fitness Orientation không có nghĩa và sẽ không được xem như là luyện tập với Huấn luyện viên riêng. Luyện tập với Huấn luyện viên riêng phải trả thêm phí. Hội viên chỉ được tập luyện với Huấn luyện viên cá nhân của VIDC, không HUấn luyện viên cá nhân nào từ bên ngoài được phép hướng dẫn Hội viên tại The ParkCity Club Hanoi.                                                                                                                                                                                                                                </span>
<br/><span>6. Trong trường hợp tử vong hoặc thương tật vĩnh viễn khiến cho Hội viên không thể sử dụng phần lớn trang thiết bị tại Câu lạc bộ. Hội viên có thể chấm dứt Hợp đồng mà không thanh toán phí Hội viên và các khoản phí cho các dịch vụ ngoài các dịch vụ mà Hội viên đã sử dụng trước thời điểm xảy ra tử vong hoặc thương tật. Nếu Hội viên đã thanh toán trước các khoản phí cho phần dịch vụ chưa sử dụng, thì một tỷ lệ phí Hội viên tương ứng cho khoản thời gian còn lại của Hợp đồng này sẽ được hoàn lại cho Hội viên hoặc người thừa kế của Hội viên trong vòng 30 ngày làm việc kể từ ngày nhận được  bản sao giấy chứng tử hoặc giấy chứng nhận y khoa do cấp có thẩm quyền cấp.</span>
<br/><span>7. Vì sự an toàn và tôn trọng các Hội viên khác. VIDC bảo lưu quyền chấm dứt Hợp đồng và Thẻ Hội viên của Hội viên nếu Hội viên đó vi phạm Hợp đồng này, Quy tắc ứng xử tại Câu lạc bộ, hoặc bất kỳ điều khoản nào của các Điều khoản và Điều kiện. Trong trường hợp đó Hội viên sẽ không được hoàn trả lại bất kỳ khoản phí nào.                                                                                                                                                                                                      </span>
<br/><span>8. Để vào Câu lạc bộ, hội viên phải xuất trình Thẻ Hội viên. Nhân viên của VIDC sẽ chụp một tấm hình của Hội viên cho mục đích nhận diện Hội viên. Trong trường hợp Thẻ Hội viên bị mất hoặc bị đánh cắp, Hội viên nên liên hệ với VIDC để được cấp lại thẻ mới. Hội viên sẽ chịu chi phí cấp lại thẻ là 300,000 VND.     </span>
<br/><span>9. Thẻ Hội trả trước như được nêu trong mục "Thông tin Thẻ Hội viên" của Hợp đồng này, Hội viên có quyền sử dụng liên tục các tiện ích của Câu lạc bộ trong suốt thời hạn đã trả trước. Việc sử dụng sân cầu lông và tennis phải được đặt trước tùy thuộc vào tình trạng có sẵn. Lịch đặt trước sẽ được mở lúc 9am ngày Thứ bảy hàng tuần cho tuần kế tiếp (Thứ 2 đến Chủ nhật)</span>
<br><span>10. VIDC bảo lưu quyền sửa đổi loại chương trình aerobic/yoga/huấn luyện riêng và hoặc huấn luyện viên aerobic/yoga/ huấn luyện viên riêng cho các lớp trong Câu lạc bộ mà không cần phải thông báo trước. Hội viên hiểu và đồng ý rằng các lớp này có thể không có sẵn tùy từng thời điểm.</span>
<br><span>11. Trong mỗi buổi luyện tập, Hội viên có quyền sử dụng một ngăn tủ theo ngày để giữ tư trang của mình. Ngăn tủ sẽ là ngăn tủ lớn và hội viên phải dọn tư trang của mình khỏi ngăn tủ sau khi Hội viên kết thúc buổi tập. Ngăn tủ theo ngày sẽ được nhân viên của VIDC dọn dẹp mỗi tối và đồ đạc trong ngăn tủ sẽ được loại bỏ vì lí do vệ sinh.                                                                                                                                                                                        
    
<br><span>12. Hợp đồng này bao gồm phần đầu trong trang đầu, các Điều khoản và Điều kiện về Thẻ Hội viên, các Điều khoản về miễn trừ trách nhiệm, tạo thành toàn bộ Hợp đồng giữa Hội viên và VIDC liên quan đến tư cách hội viên và thay thế tất cả các thảo luận, thương lượng, thỏa thuận trước đây giữa Hội viên và VIDC.                            </span>
<br><span>13. Tất cả các Điều khoản của Hợp đồng này là độc lập, Nếu bất kỳ điều khoản nào của Hợp đồng này không hợp lệ hoặc không thực thi được thì tính hợp lệ và giá trị thi hành của các điều khoản khác của hợp đồng này sẽ không bị ảnh hưởng.                                       </span>
<br><span>14. Bất kỳ sửa đổi hoặc bổ sung nào đối với hợp đồng này phải được lập thành văn bản và được ký bởi Hội viên và người đại diện theo ủy quyền của VIDC. Bất kỳ thay đổi nào bằng viết tay hay bằng lời nói đều không hợp lệ. VIDC có toàn quyền điều chỉnh các Điều khoản và Điều kiện tùy từng thời điểm.                         </span>
<br><span>15. Hợp đồng này sẽ được điều chỉnh và giải thích theo pháp luật Việt Nam.</span>
<br><span>16. Hội viên thừa nhận rằng Hội viên biết mình có quyền lựa chọn ký Hợp đồng này bằng tiếng Anh hay tiếng Việt và bản được Hội viên ký tên là ngôn ngữ mà Hội viên chọn.</span> 
<br><span>17. Hợp đồng này được lập thành hai (2) bản, Hôi viên và VIDC mỗi bên giữ một (1) bản có giá trị như nhau. Hợp đồng này sẽ có hiệu lực kể từ ngày ký kết cho đến kỳ hạn Thẻ Hội viên kết thúc hoặc bị chấm dứt theo các điều khoản.</span> 
 </div>   

<div style="width: 100%;height: 14px; background-color: black;font-size: 12px;color: white;text-align: center;font-weight: bold;" >MIỄN TRỪ TRÁCH NHIỆM</div> 
<span style="font-size: 12px;">1. Việc sử dụng các tiện nghi, thiết bị của Câu lạc bộ vốn tiềm ẩn các rủi ro có thể gây thương tổn cho bản thân Hội viên hoặc các Hội viên khác hoặc khách mời của VIDC. Bất kể là do Hội viên hay một người khác gây ra, Hội viên hiểu và tự nguyện chấp nhận rủi ro đó. Hội viên cam đoan và khẳng định rằng Hội viên đã tham khảo ý kiến bác sĩ của mình trước khi bắt đầu bất kỳ chương trình luyện tập nào. Hội viên đồng ý rằng VIDC không chịu trách nhiệm đối với các thương tổn, bao gồm nhưng không hạn chế bởi các thương tổn thể chất hoặc tinh thần, tổn thất lợi nhuận, hoặc bất kỳ thiệt hại nào cho Hội viên, hoặc những người thân của Hội viên được gây ra bởi hành vi của bất kỳ người nào sử dụng các tiện nghi, thiết bị của Câu lạc bộ hoặc bởi các hành vi của nhân viên của VIDC. Hội viên đồng ý chịu mọi trách nhiệm đối với tất cả các nghĩa vụ tài chính hoặc thiệt hại phát sinh từ bất kỳ thương tổn, bao gồm nhưng không hạn chế bởi, thương tổn thân thể hoặc tinh thần, tổn thất lợi nhuận, hoặc bất kỳ thiệt hại nào cho bất kỳ Hội viên nào khác do hành vi cố ý hoặc sơ suất của Hội viên gây ra. Nếu có bất kỳ một yêu cầu của bất kỳ người nào liên quan đến các thương tổn, tổn thất, hay thiệt hại được nêu taị đây có liên quan đến Hội viên hoặc khách mời của Hội viên đó, Hội viên đồng ý bảo vệ VIDC khỏi các yêu cầu đó và thanh toán cho VIDC tất cả chi phí bao gồm chi phí luật sư liên quan đến yêu cầu đó và bồi thường cho VIDC tất cả trách nhiệm đối với hội viên và vợ/chồng của Hội viên, thai nhi, người thân hoặc người khác phát sinh từ yêu cầu đó.   </span>
<br/><span style="font-size: 12px;">2.VIDC không chịu trách nhiệm đối với bất kỳ đồ đạc bị mất hoặc bị lấy cắp tại Câu lạc bộ vì bất kỳ lý do gì. Hội viên chịu toàn bộ trách nhiệm bảo quản, giữ gìn đồ đạc cá nhân của mình trong khuôn viên của Câu lạc bộ.</span>
<br/>-->
<br><br>
<table style="width:100%;font-size: 12px;">
    <tr>
        <td style="width:70%">Hội viên ký</td>
        <td style="text-align: left;">Đại diện của công ty ký</td>
    </tr>
</table>
<br/>


<table style="width:100%;font-size: 12px;">
    <tr>
        <td style="width:70%">Ngày: </td>
        <td style="text-align: left">Ngày: </td>
    </tr>
</table>


             
      


