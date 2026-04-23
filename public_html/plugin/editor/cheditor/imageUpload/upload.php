<?php
require_once("config.php");

//----------------------------------------------------------------------------
//
//
$tempfile = $_FILES['file']['tmp_name'];
$filename = $_FILES['file']['name'];

$type = substr($filename, strrpos($filename, ".")+1);
$found = false;
switch ($type) {
	case "jpg":
	case "jpeg":
	case "gif":
	case "png":
		$found = true;
}

if ($found != true) {
	exit;
}

// 저장 파일 이름: 년월일시분초_렌덤문자8자
// 20140327125959_abcdefghi.jpg
// 원본 파일 이름: $_POST["origname"]
$savefile = SAVE_DIR . '/' . $filename;

move_uploaded_file($tempfile, $savefile);

if($_GET['is_news'] ){	// 뉴스 일때만 워터마크 찍기

	$imgsize = @getimagesize($savefile);
	$filesize = filesize($savefile);

	/*
	$get_config = $news_config_control->get_config(1);	 // 워터마크 설정 정보
	$watermark_size = explode("/",$get_config['wr_watermark_size']);

	// 워터마크 사용시
	if( $get_config['wr_watermark'] && ($watermark_size[0] <= $imgsize[0] && $watermark_size[1] <= $imgsize[1] ) ){
		make_watermark($savefile);
	}
	*/

} else {	 // 일반 글 작성 일때

	$imgsize = getimagesize($savefile);
	$filesize = filesize($savefile);

	if (!$imgsize) {
		$filesize = 0;
		$random_name = '-ERR';
		unlink($savefile);
	};

}

$rdata = sprintf('{"fileUrl": "%s/%s", "filePath": "%s", "fileName": "%s", "fileSize": "%d" }',
	SAVE_URL,
	$filename,
	$savefile,
	$filename,
	$filesize );

echo $rdata;
?>
