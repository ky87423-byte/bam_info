<?php
$not_mb_type_check = true;
$_SERVER['__USE_API__'] = array('jqueryui', 'editor');
$add_cate_arr = array('job_company', 'job_listed', 'job_company_type', 'email');
include_once "../engine/_core.php";
$nf_member->check_not_login();

$use_regist_page = false;
if($env['use_auth'] && $_SESSION['certify_info']) $use_regist_page = true;
if($member['no'] && !$member['mb_type']) $use_regist_page = true;
if(!$env['use_auth'] && !$member['no']) $use_regist_page = true;

if(!$use_regist_page && $env['use_auth_member']) {
	$_SESSION['page_code_auth'] = $_SERVER['REQUEST_URI'];
	die($nf_util->move_url(NFE_URL."/member/agreement.php", ""));
}

if(!$_GET['code']) $_GET['code'] = 'company';
$_site_title_ = $nf_member->mb_type[$_GET['code']].' 회원가입';
include '../include/header_meta.php';
include '../include/header.php';

if(!$member['no']) {
	$my_member['mb_name'] = $_SESSION['certify_info'][7];
	$my_member['mb_birth'] = substr($_SESSION['certify_info'][8],0,4).'-'.substr($_SESSION['certify_info'][8],4,2).'-'.substr($_SESSION['certify_info'][8],6,2);
	$my_member['mb_gender'] = (string)$_SESSION['certify_info'][9];
	$my_member['mb_hphone'] = substr($_SESSION['certify_info'][12],0,3).'-'.substr($_SESSION['certify_info'][12],3,4).'-'.substr($_SESSION['certify_info'][12],7,4);
	if(strlen($_SESSION['certify_info'][12])==12) $my_member['mb_hphone'] = substr($_SESSION['certify_info'][12],0,3).'-'.substr($_SESSION['certify_info'][12],3,3).'-'.substr($_SESSION['certify_info'][12],6,4);
	$mb_hphone_arr = explode("-", $my_member['mb_hphone']);
}

// : /member/individual.php - 개인회원
// : /member/company.php - 업체회원
$m_title = '업체 회원가입';
if($_GET['code']=='individual') $m_title = '개인 회원가입';
include NFE_PATH.'/include/m_title.inc.php';

if($member) $my_member = $member;
include NFE_PATH.'/member/'.$_GET['code'].'.php';

include '../include/footer.php';
?>