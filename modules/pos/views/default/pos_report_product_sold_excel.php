<?php 
use app\modules\invoice\models\invoice;

require_once(dirname(__FILE__) . '/../../../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php');
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die(Yii::t('app','This example should only be run from a Web Browser'));

$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$row = 1;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row,YII::$app->params['sogan_report']);
$objPHPExcel->getActiveSheet()
    ->getStyle('G'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$row=$row+3;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,Yii::t('app','POS report'));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$row.':G'.$row);
$objPHPExcel->getActiveSheet()
    ->getStyle('A'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A".$row)->getFont()->setBold(true);

//HEADER COLUNM
$row=$row+3;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,Yii::t('app','No.'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row,Yii::t('app','Date'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row,Yii::t('app','Invoice no'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row,Yii::t('app','Product name'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row,Yii::t('app','Quantity'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row,Yii::t('app','Price'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row,Yii::t('app','Discount'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row,Yii::t('app','Amount'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row,Yii::t('app','Status'));
$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':I'.$row)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '43CD80')
        )
    )
);
$objPHPExcel->getActiveSheet()->getStyle("A".$row.":H".$row)->getFont()->setBold(true);
foreach(range('B','F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$i=0;

foreach ($dataProvider  as $model) {
    $invoice= \app\modules\invoice\models\invoice::findOne($model->invoice_id);
    $ListSetup  = new app\models\ListSetup();
	$invoice_no = $invoice->invoice_no;
        $invoice_date = $ListSetup->getDisplayDate($invoice->invoice_date,'d/m/Y');
        $invoice_status = $invoice->invoice_status;
        $discount = $invoice->invoice_discount.' %';

        $product_name = $model->invoice_item_description;
        $qty = $model->invoice_item_quantity;
        $price = app\models\ListSetup::getDisplayPrice($model->invoice_item_price, 2);
        
        $invoi = new app\modules\invoice\models\invoice();
        $amount = $invoi->getAmountLastTaxItem($invoice->invoice_gst_value,$model->invoice_item_amount,$invoice->invoice_discount);
        $amount = app\models\ListSetup::getDisplayPrice($amount, 2);
                    
    $row ++;$i++;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,$i);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row,$invoice_date);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row,$invoice_no);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row,$product_name);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row,$qty);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$row,$price);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$row,$discount);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$row,$amount);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$row,$invoice_status);
}

$objPHPExcel->getActiveSheet()->setTitle('POS report');

$file_name = 'Pos_report_'.date('d_m_Y_H_i_s');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_name.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
