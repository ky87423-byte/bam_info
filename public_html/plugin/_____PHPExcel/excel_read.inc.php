<?php
/*
사용방법
$filename = $_FILES['excel']['tmp_name'];
include_once "./excel_read.inc.php";
print_r($excel_arr);
*/

/** PHPExcel */
include base_url.'plugin/PHPExcel/Classes/PHPExcel.php';
include_once base_url.'plugin/PHPExcel/Classes/PHPExcel/IOFactory.php';
// $filename은 이 파일을 include한 상위에서 가져옵니다.
//$filename = $_FILES['tmp_name'];
try {
	// 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
	$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
	// 읽기전용으로 설정
	$objReader->setReadDataOnly(true);
	// 엑셀파일을 읽는다
	$objExcel = $objReader->load($filename);
	// 첫번째 시트를 선택
	$objExcel->setActiveSheetIndex(0);
	$objWorksheet = $objExcel->getActiveSheet();
	$rowIterator = $objWorksheet->getRowIterator();

	$excel_arr = array();

	$excel_table = '<table border="1" cellspacing="0" cellpadding="0">';
	foreach ($rowIterator as $keys=>$row) { // 모든 행에 대해서
		$excel_table .= "<tr>";
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false); 
		foreach ($cellIterator as $keys2=>$cell) { // 해당 열의 모든 셀에 대해서
			if(!is_array($excel_arr[$keys])) $excel_arr[$keys] = array();
			if(!is_array($excel_arr[$keys][$keys2])) $excel_arr[$keys][$keys2] = array();
			$excel_arr[$keys][$keys2] = $cell->getValue();
			$excel_table .= "<td>&nbsp;".$cell->getValue()."&nbsp;</td>";
		}
		$excel_table .= "<tr>";
	}
	/*
	$maxRow = $objWorksheet->getHighestRow();
	for ($i = 2 ; $i <= $maxRow ; $i++) { // 두번째 행부터 읽는다
	$no = $objWorksheet->getCell('A' . $i)->getValue(); // 첫번째 열
	$name = $objWorksheet->getCell('B' . $i)->getValue(); // 두번째 열
	$phone = $objWorksheet->getCell('C' . $i)->getValue(); // 세번째 열
	}
	*/
	$excel_table .= "</table>";

} catch (exception $e) {
	echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
}
?>