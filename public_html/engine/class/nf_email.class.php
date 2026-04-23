<?php
class nf_email extends nf_util {

	var $email_type_arr = array(
		"member_regist"=>"회원가입",
		"member_find"=>"아이디/비밀번호찾기",
		"qna"=>"고객센터 상담문의",
		"advert_answer"=>"입점문의 답변",
		"shop_end"=>"상품 서비스 마감 메일링",
		"dormancy_ing"=>"휴면회원 예정 메일링",
		"dormancy_end"=>"휴면회원 처리 메일링",
		"member_mailing"=>"회원 메일링"
	);

	var $mail_skin = array(
		'common'=>array("{메일상단로고}", "{메일하단로고}", "{메일하단}", "{사이트명}", "{회원이름}", "{회원아이디}", "{비밀번호}", "{가입일시}", "{업체명}", "{보낸사람}", "{전달메시지}", "{도메인주소}", "{서비스}", "{서비스마감일}", "{휴면예정일}", "{휴면처리일}", "{휴면처리설정일}"),

		'qna'=>array("{문의등록일}", "{문의답변일}", "{문의제목}", "{문의내용}", "{답변내용}"),
	);

	function __construct() {
		global $db;

		$this->email_type_arr_key = array_keys($this->email_type_arr);
	}

	function ch_content($member=array()) {
		global $env;

		$arr = array(
			'{메일상단로고}'=>'<img src="'.domain.'/data/logo/'.$env['logo_mail_top'].'" alt="메일상단로고" />',
			'{메일하단로고}'=>'<img src="'.domain.'/data/logo/'.$env['logo_mail_bottom'].'" alt="메일하단로고" />',
			'{메일하단}'=>$env['content_bottom_site'],
			'{사이트명}'=>$env['site_name'],
			'{회원이름}'=>$member['mb_name'],
			'{회원아이디}'=>$member['mb_id'],
			'{비밀번호}'=>$member['mb_password'],
			'{가입일시}'=>$member['mb_wdate'],
			'{업체명}'=>$member['mb_company_name'],
			'{도메인주소}'=>domain
		);

		return $arr;
	}

	function send_email_reserve($arr) {
		global $nf_time2, $db, $env, $cate_p_array, $nf_payment;
		$price_arr = $arr['price_arr'];
		$pno = $arr['pno'];
		$post_unse = $arr['post_unse'];
		$email = $arr['email'];
		// : 메일보내기
		$price_result_text_arr = $price_arr;
		$reserve_row = $db->query_fetch("select * from nf_time2_reserve where `rno`=".intval($pno));
		$room_row = $db->query_fetch("select * from nf_time2_room where `no`=".intval($reserve_row['cate2']));

		$get_reserve = $nf_time2->get_reserve_post($post_unse, $reserve_row['room_code']);
		$get_pay_price = $price_arr;
		ob_start();
		include NFE_PATH."/mailing.php";
		$arr_email['content'] = ob_get_clean(); // : 메일내용
		$arr_email['subject'] = $env['site_name'].' 예약 정보입니다.'; // : 메일제목
		$arr_email['email'] = $email; // : 수신메일주소
		$this->send_mail($arr_email);
	}

	function send_mail($arr) {
		global $env;
		if(!$arr['master_email']) $arr['master_email'] = $env['email'];
		if(!$arr['master_name']) $arr['master_name'] = $env['site_name'];

		$subject = "=?EUC-KR?B?".base64_encode(iconv("UTF-8","EUC-KR",$arr['subject']))."?=";
		$from = "=?EUC-KR?B?".base64_encode(iconv("UTF-8","EUC-KR",$arr['master_name']))."?=<".$arr['master_email'].">\r\n";
		$content = stripslashes($arr['content']);

		$headers = "Return-Path: ".$arr['master_email']."\r\n"; 
		$headers .= "Reply-To: ".$arr['master_email']."\r\n"; 
		$headers .= "From: ".$from;
		$headers .= "Content-Type:text/html;charset=".encode."\r\n";
		//return mail($arr['email'], $subject, $content, $headers);
	}
}
?>