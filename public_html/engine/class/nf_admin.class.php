<?php
// : 상단메뉴
$_top_menus_ = array(
	'100000' => '상품관리',
	'200000' => '환경설정',
	'300000' => '회원관리',
	'400000' => '디자인관리',
	'500000' => '결제관리',
	'600000' => '커뮤니티관리',
	'700000' => '통계관리',
);

// : 메뉴관리
$_menu_array_ = array(
	'100'=>array(
		"eng_name" => "Shop service",
		"link"=>NFE_URL."/admini/introduce/index.php",
		"new"=>true,
		"menus" => array(
			0 => array("code" => "100100", "name" => "상품 관리", 
				"sub_menu" => array(
					"100101"=>array("name" => "전체 상품 관리", "url" => NFE_URL."/admini/introduce/index.php", "new"=>true),
					"100102"=>array("name" => "진행중 상품관리", "url" => NFE_URL."/admini/introduce/index.php?code=ing", "new"=>true),
					"100103"=>array("name" => "마감된 상품관리", "url" => NFE_URL."/admini/introduce/index.php?code=service_end", "new"=>true),
					"100104"=>array("name" => "삭제한 상품관리", "url" => NFE_URL."/admini/introduce/index.php?code=delete"),
					"100105"=>array("name" => "신고된 상품관리", "url" => NFE_URL."/admini/introduce/shop_report.php", "new"=>true),
					"100106"=>array("name" => "등록대기 상품관리", "url" => NFE_URL."/admini/introduce/index.php?code=wait"),
					"100107"=>array("name" => "쿠폰업체 상품관리", "url" => NFE_URL."/admini/introduce/coupon.php"),
					"100108"=>array("name" => "점프업체 상품관리", "url" => NFE_URL."/admini/introduce/index.php?code=jump"),
					"100109"=>array("name" => "상품등록", "url" => NFE_URL."/admini/introduce/shop_modify.php"),
				),
			),

			1 =>array("code" => "100200", "name" => "상품기타관리",
				"sub_menu" => array(
					"100201"=>array("name" => "스크랩 관리", "url" => NFE_URL."/admini/introduce/scrap.php", "new"=>true),
					"100202"=>array("name" => "이용후기 관리", "url" => NFE_URL."/admini/introduce/review.php", "new"=>true),
					"100203"=>array("name" => "상품문의 관리", "url" => NFE_URL."/admini/introduce/question.php", "new"=>true),
					"100204"=>array("name" => "쿠폰사용 관리", "url" => NFE_URL."/admini/introduce/coupon_use.php", "new"=>true),
					"100205"=>array("name" => "상품마감안내메일", "url" => NFE_URL."/admini/introduce/sevice_end_mail.php"),
				),
			),
		)
	),

	'200'=>array(
		"eng_name" => "Environment",
		"link"=>NFE_URL."/admini/config/index.php",
		"menus" => array(
			0 => array("code" => "200100", "name" => "사이트 관리", 
				"sub_menu" => array(
					"200101"=>array("name" => "기본정보설정", "url" => NFE_URL."/admini/config/index.php"),
					"200102"=>array("name" => "회사소개", "url" => NFE_URL."/admini/config/content.php?code=site_introduce"),
					"200103"=>array("name" => "이용약관", "url" => NFE_URL."/admini/config/content.php?code=membership"),
					"200104"=>array("name" => "개인정보취급방침", "url" => NFE_URL."/admini/config/content.php?code=privacy"),
					"200105"=>array("name" => "개인정보수집이용안내", "url" => NFE_URL."/admini/config/content.php?code=privacy_info"),
					"200106"=>array("name" => "위치정보수집", "url" => NFE_URL."/admini/config/content.php?code=young_info"),
					"200107"=>array("name" => "게시판관리기준", "url" => NFE_URL."/admini/config/content.php?code=board_manage"),
					"200108"=>array("name" => "사이트하단 카피라이트", "url" => NFE_URL."/admini/config/content.php?code=bottom_site"),
					"200109"=>array("name" => "문자(SMS) 설정", "url" => NFE_URL."/admini/config/sms.php"),
					"200110"=>array("name" => "지도 설정", "url" => NFE_URL."/admini/config/map.php"),
					"200111"=>array("name" => "도로명 지역 넣기", "url" => NFE_URL."/admini/config/insert_area.php"),
				),
			),

			1 => array("code" => "200200", "name" => "등록폼 관리", 
				"sub_menu" => array(
					"200201"=>array("name" => "지역별 카테고리", "url" => NFE_URL."/admini/config/category_insert.php?code=area"),
					"200202"=>array("name" => "업종별 카테고리", "url" => NFE_URL."/admini/config/category_insert.php?code=job_part"),
					"200203"=>array("name" => "테마별 카테고리", "url" => NFE_URL."/admini/config/category_insert.php?code=job_tema"),
					"200204"=>array("name" => "공지사항 분류", "url" => NFE_URL."/admini/config/category_insert.php?code=notice"),
					"200205"=>array("name" => "고객문의 분류", "url" => NFE_URL."/admini/config/category_insert.php?code=on2on"),
					"200206"=>array("name" => "상품 신고 분류", "url" => NFE_URL."/admini/config/category_insert.php?code=shop_shop_report_reason"),
					"200207"=>array("name" => "이메일 설정", "url" => NFE_URL."/admini/config/category_insert.php?code=email"),
				),
			),

			2 => array("code" => "200300", "name" => "운영자관리", 
				"sub_menu" => array(
					"200301"=>array("name" => "관리자 정보설정", "url" => NFE_URL."/admini/config/admin.php"),
					"200302"=>array("name" => "부관리 관리", "url" => NFE_URL."/admini/config/sadmin.php"),
					"200303"=>array("name" => "부관리 등록", "url" => NFE_URL."/admini/config/sadmin_modify.php"),
				),
			),
		)
	),


	'300'=>array(
		"eng_name" => "Member",
		"link"=>NFE_URL."/admini/member/index.php",
		"new"=>true,
		"menus" => array(
			0 => array("code" => "300100", "name" => "회원관리", 
			"sub_menu" => array(
					"300101"=>array("name" => "전체회원 관리", "url" => NFE_URL."/admini/member/index.php", "new"=>true),
					"300102"=>array("name" => "업체회원 관리", "url" => NFE_URL."/admini/member/company.php", "new"=>true),
					"300103"=>array("name" => "개인회원 관리", "url" => NFE_URL."/admini/member/individual.php", "new"=>true),
					"300104"=>array("name" => "탈퇴요청 관리", "url" => NFE_URL."/admini/member/left_list.php", "new"=>true),
					"300105"=>array("name" => "탈퇴회원 관리", "url" => NFE_URL."/admini/member/left_list.php?left=1"),
					"300106"=>array("name" => "휴면회원 관리", "url" => NFE_URL."/admini/member/human.php"),
					"300107"=>array("name" => "업체회원 등록", "url" => NFE_URL."/admini/member/company_insert.php"),
					"300108"=>array("name" => "개인회원 등록", "url" => NFE_URL."/admini/member/individual_insert.php"),
				),
			),

			1 => array("code" => "300200", "name" => "기타관리", 
			"sub_menu" => array(
					"300201"=>array("name" => "회원등급/포인트 설정", "url" => NFE_URL."/admini/member/level.php"),
					"300202"=>array("name" => "휴면회원 설정", "url" => NFE_URL."/admini/member/human_setting.php"),
					"300203"=>array("name" => "회원포인트 관리", "url" => NFE_URL."/admini/member/point.php"),
				),
			),

			2 => array("code" => "300300", "name" => "회원CRM관리", 
			"sub_menu" => array(
					"300301"=>array("name" => "회원메일발송", "url" => NFE_URL."/admini/member/mail.php"),
					"300302"=>array("name" => "회원문자발송", "url" => NFE_URL."/admini/member/sms.php"),
					"300303"=>array("name" => "회원쪽지발송", "url" => NFE_URL."/admini/member/memo_send.php"),
					"300304"=>array("name" => "회원간 쪽지 관리", "url" => NFE_URL."/admini/member/memo.php"),
				),
			),
		)
	),


	'400'=>array(
		"eng_name" => "Design",
		"link"=>NFE_URL."/admini/design/index.php",
		"menus" => array(
			0 => array("code" => "400100", "name" => "전체디자인관리", 
			"sub_menu" => array(
					"400101"=>array("name" => "사이트 디자인 설정", "url" => NFE_URL."/admini/design/index.php"),
				),
			),
			1 => array("code" => "400200", "name" => "상품디자인관리", 
			"sub_menu" => array(
					"400201"=>array("name" => "상품 서비스명 설정", "url" => NFE_URL."/admini/design/service_name(main).php"),
					"400202"=>array("name" => "상품 등록 아이콘 설정", "url" => NFE_URL."/admini/design/shop_icon.php"),
					"400203"=>array("name" => "메인 중앙 아이콘 설정", "url" => NFE_URL."/admini/design/main_icon.php"),
				),
			),
			2 => array("code" => "400300", "name" => "기타디자인관리", 
			"sub_menu" => array(
					"400301"=>array("name" => "로고 관리", "url" => NFE_URL."/admini/design/logo.php"),
					"400302"=>array("name" => "배너 관리", "url" => NFE_URL."/admini/design/banner.php"),
					"400303"=>array("name" => "팝업 관리", "url" => NFE_URL."/admini/design/popup.php"),
					"400304"=>array("name" => "메일스킨 관리", "url" => NFE_URL."/admini/design/mail_skin.php"),
				),
			),
		)
	),


	'500'=>array(
		"eng_name" => "Payment",
		"new"=>true,
		"link"=>NFE_URL."/admini/payment/index.php",
		"menus" => array(
			0 => array("code" => "500100", "name" => "결제관리", 
			"sub_menu" => array(
					"500101"=>array("name" => "결제통합 관리", "url" => NFE_URL."/admini/payment/index.php", "new"=>true),
					"500102"=>array("name" => "결제대기 내역", "url" => NFE_URL."/admini/payment/index.php?pay_status=0"),
					"500103"=>array("name" => "결제완료 내역", "url" => NFE_URL."/admini/payment/index.php?pay_status=1"),
					"500104"=>array("name" => "세금계산서 신청 내역", "url" => NFE_URL."/admini/payment/tax.php", "new"=>true),
				),
			),
			1 => array("code" => "500200", "name" => "결제환경관리", 
			"sub_menu" => array(
					"500201"=>array("name" => "결제 환경 설정", "url" => NFE_URL."/admini/payment/pg.php"),
					"500202"=>array("name" => "입금계좌 설정", "url" => NFE_URL."/admini/config/category_insert.php?code=online"),
					"500203"=>array("name" => "결제페이지 설정", "url" => NFE_URL."/admini/payment/pg_page.php?code=shop"),
					"500204"=>array("name" => "서비스별 금액 설정", "url" => NFE_URL."/admini/payment/service_pay_config.php"),
				),
			),
		)
	),

	'600'=>array(
		"eng_name" => "Board",
		"link"=>NFE_URL."/admini/board/index.php",
		"new"=>true,
		"menus" => array(
			0 => array("code" => "600100", "name" => "게시판관리", 
			"sub_menu" => array(
					"600101"=>array("name" => "게시판관리", "url" => NFE_URL."/admini/board/index.php"),
					"600102"=>array("name" => "게시판 노출 설정", "url" => NFE_URL."/admini/board/main.php"),
					"600106"=>array("name" => "메인게시판노출설정", "url" => NFE_URL."/admini/board/main.php?code=site_main"),
					"600103"=>array("name" => "게시물 관리 ", "url" => NFE_URL."/admini/board/list.php"),
					"600104"=>array("name" => "댓글 관리", "url" => NFE_URL."/admini/board/comment.php"),
					"600105"=>array("name" => "신고 게시물관리", "url" => NFE_URL."/admini/board/bad_report.php", "new"=>true),

				),
			),

			1 => array("code" => "600200", "name" => "기타관리", 
			"sub_menu" => array(
					"600201"=>array("name" => "입점문의 관리", "url" => NFE_URL."/admini/board/store_entry.php", "new"=>true),
					"600202"=>array("name" => "공지사항 관리", "url" => NFE_URL."/admini/board/notice.php"),
					"600203"=>array("name" => "고객문의 관리", "url" => NFE_URL."/admini/board/qna.php?type=0", "new"=>true),
					"600205"=>array("name" => "관리자 쪽지 관리", "url" => NFE_URL."/admini/board/admin_memo.php", "new"=>true),
					"600206"=>array("name" => "출석부 관리", "url" => NFE_URL."/admini/board/admin_chulsuk.php", "new"=>true),
					"600207"=>array("name" => "랭킹 관리", "url" => NFE_URL."/admini/board/admin_ranking.php", "new"=>true),
				),
			),
		),
	),

	'700'=>array(
		"eng_name" => "Statistics",
		"link"=>NFE_URL."/admini/statistics/index.php",
		"menus" => array(
			0 => array("code" => "700100", "name" => "통계현황", 
			"sub_menu" => array(
					"700101"=>array("name" => "접속통계", "url" => NFE_URL."/admini/statistics/index.php"),
				),
			),
			1 => array("code" => "700200", "name" => "검색어현황", 
			"sub_menu" => array(
					"700201"=>array("name" => "검색어통계", "url" => NFE_URL."/admini/statistics/keyword.php"),
				),
			),
		)
	),
);

// : 메뉴별 개수 - /admini/config/sadmin_insert.php 이 페이지에서 사용하기 위해서
$_menu_array_count_ = array();
if(is_array($_menu_array_)) { foreach($_menu_array_ as $k=>$v) {
	if(!isset($_menu_array_count_[$k])) $_menu_array_count_[$k] = 0;
	if(is_array($v['menus'])) { foreach($v['menus'] as $k2=>$v2) {
		$_menu_array_count_[$k] += isset($v2['sub_menu']) ? count($v2['sub_menu']) : 0;
		if(isset($v2['code'])) $_menu_array_count_[$v2['code']] = isset($v2['sub_menu']) ? count($v2['sub_menu']) : 0;
	} }
} }


class nf_admin extends nf_util {

	var $sess_adm_uid = "sess_admin_uid";

	function __construct(){
	}

	function admin_login($adm_id) {
		$_SESSION[$this->sess_adm_uid] = $adm_id;
	}

	function admin_logout() {
		$_SESSION[$this->sess_adm_uid] = "";
	}

	function check_admin($ajax='') {
		if(!$_SESSION[$this->sess_adm_uid]) {
			if($ajax) {
				$arr['msg'] = "관리자만 접근 가능합니다.";
				$arr['move'] = NFE_URL.'/admini/';
				die(json_encode($arr));
			} else {
				die($this->move_url(NFE_URL.'/admini/', "관리자만 접근 가능합니다."));
			}
		}
	}

	function get_sadmin($wr_id) {
		global $db, $_top_menus_;
		$admin_row = $db->query_fetch("select * from nf_admin where `wr_id`='".$wr_id."'");
		$arr['admin_menu_array'] = unserialize(stripslashes($admin_row['admin_menu']));

		$arr['first_link'] = '';
		if(is_array($arr['admin_menu_array'])) { foreach($arr['admin_menu_array'] as $k=>$v) {
			$end_txt = substr($v, 5, 1);
			if(strlen($v)==3) {
				$arr['txt'][] = $_top_menus_[$v.'000'];
			} else if($end_txt>0)  {
				if(!$arr['first_link']) $arr['first_link'] = $v;
			}
		} }
		return $arr;
	}

	function get_top_menu($top_menu_code) {
		global $_top_menus_, $_menu_array_;

		$arr['top_menu_code_head'] = substr($top_menu_code, 0, 3);
		$arr['top_menu_code_middle'] = substr($top_menu_code, 3, 1);
		$arr['top_menu_txt'] = $_top_menus_[$arr['top_menu_code_head'].'000'];
		$arr['middle_menu_txt'] = $_menu_array_[$arr['top_menu_code_head']]['menus'][$arr['top_menu_code_middle']-1]['name'];
		$arr['sub_menu_txt'] = $_menu_array_[$arr['top_menu_code_head']]['menus'][$arr['top_menu_code_middle']-1]['sub_menu'][$top_menu_code]['name'];
		$arr['sub_menu_url'] = $_menu_array_[$arr['top_menu_code_head']]['menus'][$arr['top_menu_code_middle']-1]['sub_menu'][$top_menu_code]['url'];

		return $arr;
	}
}
?>