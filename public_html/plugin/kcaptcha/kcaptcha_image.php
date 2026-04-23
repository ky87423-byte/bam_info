<?php
include_once("_engine.php");
include_once('captcha.lib.php');

$captcha = new KCAPTCHA();
$captcha->setKeyString($_SESSION['ss_captcha_key']);
$captcha->getKeyString();
$captcha->image();
?>