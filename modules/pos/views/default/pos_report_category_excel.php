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
$objPHPExcel->getProperties()->setCreator("Park City")
							 ->setLastModifiedBy("Park City")
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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,Yii::t('app','POS Category Report'));
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$row.':E'.$row);
$objPHPExcel->getActiveSheet()
    ->getStyle('A'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A".$row)->getFont()->setBold(true);
$row=$row+1;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,Yii::t('app','From').' '.$view_start_date.' '.Yii::t('app','To').' '.$view_end_date);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$row.':E'.$row);
$objPHPExcel->getActiveSheet()
    ->getStyle('A'.$row)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A".$row)->getFont()->setBold(true);
//HEADER COLUNM
$row=$row+3;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,Yii::t('app','No.'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row,Yii::t('app','Name'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row,Yii::t('app','Quantity'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row,Yii::t('app','Price'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row,Yii::t('app','Total'));

$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':E'.$row)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '32CD8B')
        )
    )
);
$objPHPExcel->getActiveSheet()->getStyle("A".$row.":E".$row)->getFont()->setBold(true);
foreach(range('A','E') as $columnID) {
	if($columnID == 'A') {
		$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			->setAutoSize(true);
	} else {
		if($columnID == 'B') {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			->setWidth(40);
		} else {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
			->setWidth(20);
		}
	}
}
$i=0;

foreach ($dataProvider  as $data) {
    $row ++;$i++;
	//category hierachy style
	$space = '';
	switch($data['type']){
		case 0:
			$space = '';
			break;
		case 1:
			$space = '        ';
			break;
		case 2:
			$space = '                ';
			break;
	}
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row,$i);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$row,$space.$data['name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$row,$data['qty']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$row,floatval($data['price']));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$row,floatval($data['total']));
	
	//currency format
	$objPHPExcel->getActiveSheet()->getStyle('D'.$row)->getNumberFormat()->setFormatCode("#,##0");
	$objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode("#,##0");
	
}

$objPHPExcel->getActiveSheet()->setTitle('POS report');

$file_name = 'Pos_report_category'.date('d_m_Y_H_i_s');

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
