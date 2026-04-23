<?php
include_once("_engine.php");

function make_mp3() {
	
    $number = $_SESSION['ss_captcha_key'];

    if ($number == "") return;
    if ($number == $_SESSION['ss_captcha_save']) return;

    $mp3s = array();
    for($i=0;$i<strlen($number);$i++){
        $file = NFE_PATH.'/plugin/kcaptcha/mp3/basic/'.$number[$i].'.mp3';
        $mp3s[] = $file;
    }

    $ip = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
    $mp3_file = 'data/cache/kcaptcha-'.$ip.'_'.time().'.mp3';

    $contents = '';
    foreach ($mp3s as $mp3) {
        $contents .= file_get_contents($mp3);
    }

    file_put_contents(NFE_PATH.'/'.$mp3_file, $contents);

    // 지난 캡챠 파일 삭제
    if (rand(0,99) == 0) {
        foreach (glob(NFE_PATH.'/data/cache/kcaptcha-*.mp3') as $file) {
            if (filemtime($file) + 86400 < time()) {
                @unlink($file);
            }
        }
    }

	$_SESSION['ss_captcha_save'] = $number;

    return NFE_URL.'/'.$mp3_file;
}

echo make_mp3();
?>