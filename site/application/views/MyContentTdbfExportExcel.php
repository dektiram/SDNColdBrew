<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


// Add some data
$pFilterNote=trim($TDBFPARAM['customParam']['viewResult']['viewFilterNote']);
if($pFilterNote!=''){
	$pFilterNote='Data filter = '.$pFilterNote;
} 
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', $TDBFPARAM['otherParam']['sectionHeader'])
            ->setCellValue('A2', $TDBFPARAM['otherParam']['sectionNote'])
            ->setCellValue('A3', $pFilterNote);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5','No.');
$pExcelColIndex=1;
for($i=0;$i<sizeof($TDBFPARAM['customParam']['viewPref']['colPref']);$i++){
	if($TDBFPARAM['customParam']['viewPref']['colPref'][$i]['exportExcel']){
		$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pExcelColIndex);
		$objPHPExcel->setActiveSheetIndex(0)
	           		->setCellValue($pExcelColString.'5', $TDBFPARAM['customParam']['viewPref']['colPref'][$i]['caption']);
		$pExcelColIndex++;
	}
}

$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pExcelColIndex-1);
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:'.$pExcelColString.'1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:'.$pExcelColString.'2');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:'.$pExcelColString.'3');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(14)->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12)->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setSize(11)->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$pExcelColString.'5')->getFont()->setSize(11)->setBold(true);
$pArFillColor=array(
					'fill'=>array(
						'type'=>PHPExcel_Style_Fill::FILL_SOLID,
						'color'=>array('rgb'=>'DDDDDD')
					)
				);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$pExcelColString.'5')->applyFromArray($pArFillColor);
$pArBorder=array(
					'borders'=>array(
						'allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN)
					)
				);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$pExcelColString.'5')->applyFromArray($pArBorder);


//print datanya
$pExcelRow=6;
$pDataNumber=1;
for($i=0;$i<sizeof($TDBFPARAM['viewHTMLRecord']);$i++){
	$pExcelColIndex=0;
	$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pExcelColIndex);
	$objPHPExcel->setActiveSheetIndex(0)
	       		->setCellValue($pExcelColString.$pExcelRow, $pDataNumber);
			
	$pExcelColIndex=1;
	for($j=0;$j<sizeof($TDBFPARAM['customParam']['viewPref']['colPref']);$j++){
		if($TDBFPARAM['customParam']['viewPref']['colPref'][$j]['exportExcel']){
			$pExcelColString=PHPExcel_Cell::stringFromColumnIndex($pExcelColIndex);
			//$objPHPExcel->setActiveSheetIndex(0)
		    //       		->setCellValue($pExcelColString.$pExcelRow, $TDBFPARAM['viewHTMLRecord'][$i][$TDBFPARAM['customParam']['viewPref']['colPref'][$j]['name']]);
			$objPHPExcel->setActiveSheetIndex(0)
		           		->setCellValueExplicit($pExcelColString.$pExcelRow, $TDBFPARAM['viewHTMLRecord'][$i][$TDBFPARAM['customParam']['viewPref']['colPref'][$j]['name']],PHPExcel_Cell_DataType::TYPE_STRING);
			$pExcelColIndex++;
		}
	}
	$pExcelRow++;
	$pDataNumber++;
}
$pArBorder=array(
					'borders'=>array(
						'vertical'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN),
						'left'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN),
						'right'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN)
					)
				);
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$pExcelColString.$pExcelRow)->applyFromArray($pArBorder);

foreach(range('A',$pExcelColString) as $columnID){
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

//print '<pre>';
//print_r($TDBFPARAM['customParam']['viewPref']['colPref']);
//print '</pre>';

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($TDBFPARAM['otherParam']['sectionHeader']);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$TDBFPARAM['otherParam']['sectionHeader'].'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

?>