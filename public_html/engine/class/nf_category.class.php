<?php
class nf_category {

	var $kind_group = array(0=>'공통적용분류', 1=>'채용정보분류', 2=>'인재정보분류', 3=>'회원가입분류', 4=>'사유분류');
	var $attach = '/data/category';
	var $job_part_adult = 0;
	var $job_part_adult_arr = array();

	var $etc_kind_arr = array(
		'board_menu'=>array('메뉴 설정', 2, 0), // : 게시판 메뉴
	);

	// : 카테고리명, 카테고리차수, kind_group 키값
	var $kind_arr = array(
		'job_part'=>array('업종별', 3, 0),
		'area'=>array('지역', 3, 0),
		'subway'=>array('지하철', 3, 0),
		'job_type'=>array('근무형태', 1, 0),
		'job_date'=>array('근무기간', 1, 0),
		'job_week'=>array('근무요일', 1, 0),
		'job_pay'=>array('급여조건', 2, 0),
		'email'=>array('이메일', 1, 0),
		'online'=>array('무통장입금계좌설정', 1, 0),
		'notice'=>array('공지사항', 1, 0),
		'on2on'=>array('고객문의', 1, 0),
		'concert'=>array('광고 &middot; 제휴문의 분류', 1, 0),

		'job_welfare'=>array('복리후생', 2, 1),
		'job_grade'=>array('직급', 1, 1),
		'job_position'=>array('직책', 1, 1),
		'job_conditions'=>array('우대조건', 2, 1),
		'job_target'=>array('모집대상', 1, 1),
		'job_document'=>array('제출서류', 1, 1),
		'job_pay_support'=>array('급여지원조건', 1, 1),
		'job_tema'=>array('테마', 1, 1),

		'job_time'=>array('근무시간', 1, 2),
		'job_language'=>array('외국어종류', 1, 2),
		'job_language_exam'=>array('외국어공인시험', 1, 2),
		'job_computer'=>array('컴퓨터능력', 1, 2),
		'job_pay_employ'=>array('고용지원금대상', 1, 2),
		'job_veteran'=>array('국가보훈대상', 1, 2),
		'job_obstacle'=>array('장애등급', 1, 2),

		'job_company'=>array('회사분류', 1, 3),
		'job_listed'=>array('상장여부', 1, 3),
		'job_company_type'=>array('기업형태', 1, 3),

		'shop_shop_report_reason'=>array('업체 신고 사유', 1, 4),
		'member_left_reason'=>array('회원 탈퇴요청 사유', 1, 4),
		'job_employ_report_reason'=>array('상품 신고 사유', 1, 4),
		'job_resume_report_reason'=>array('이력서 신고 사유', 1, 4),
	);

	function __construct(){
	}

	function get_cate($type_arr=array()) {
		global $db, $cate_array, $cate_p_array, $cate_first_array;
		$cate_arr = array();
		$cate_p_arr = array();

		$q = " select * from nf_category where `wr_view`=1 and `wr_type` in ('".@implode("','", $type_arr)."') ".$_where." order by wr_rank asc";
		$query = $db->_query($q);
		while($row=$db->afetch($query)) {
			$cate_arr[$row['wr_type']][$row['no']] = $row['wr_name'];
			$cate_p_arr[$row['wr_type']][$row['pno']][$row['no']] = $row;
			if($row['wr_type']=='job_part' && $row['wr_adult']) {
				$this->job_part_adult = true; // : 직종에 성인이 있는경우
				array_push($this->job_part_adult_arr, $row['no']);
			}

			if(!$cate_first_array[$row['wr_type']]) $cate_first_array[$row['wr_type']] = $row;
		}

		$q = " select * from nf_area where `wr_view`=1 and `wr_type` in ('".@implode("','", $type_arr)."') ".$_where." order by wr_rank asc";
		$query = $db->_query($q);
		while($row=$db->afetch($query)) {
			$cate_arr[$row['wr_type']][$row['no']] = $row['wr_name'];
			$cate_p_arr[$row['wr_type']][$row['pno']][$row['no']] = $row;

			if(!$cate_first_array[$row['wr_type']]) $cate_first_array[$row['wr_type']] = $row;
		}

		$cate_array = $cate_arr;
		$cate_p_array = $cate_p_arr;
	}

	// : 카테고리 최대 차수
	function get_depth($kind) {
		global $db;
		$_table = $kind=='area' ? 'nf_area' : 'nf_category';
		$row = $db->query_fetch("select max(length(pnos)-length(replace(pnos, ',', ''))) as cnt from ".$_table." where `wr_type`=? and `wr_view`=1", array($kind));
		return $row['cnt']+1;
	}

	function get_area($SI, $GU='') {
		global $db, $cate_area_array;

		if(!$GU && $cate_area_array['SI'][$SI]) return;
		if($GU && $cate_area_array['GU'][$SI][$GU]) return;

		$area1_row = $db->query_fetch("select * from nf_area where pno=0 and `wr_name`=?", array($SI));
		$pno = $area1_row['no'];
		if($GU) {
			$area2_row = $db->query_fetch("select * from nf_area where pno=? and `wr_name`=?", array($area1_row['no'], $GU));
			$pno = $area2_row['no'];
		}

		$query = $db->_query("select * from nf_area where `wr_view`=1 and pno=".intval($pno)." order by `wr_rank` asc");

		while($row=$db->afetch($query)) {
			if(!$GU) $cate_area_array['SI'][$SI][$row['no']] = $row;
			else $cate_area_array['GU'][$SI][$GU][$row['no']] = $row;
		}
	}

	function get_cate2($type_arr=array()) {
		global $db;
		$cate_arr = array();
		$cate_p_arr = array();

		$q = " select * from nf_category where `wr_view`=1 and `wr_type` in ('".@implode("','", $type_arr)."') ".$_where." order by wr_rank asc";
		$query = $db->_query($q);
		while($row=$db->afetch($query)) {
			$cate_arr[$row['wr_type']][$row['no']] = $row;
			$cate_p_arr[$row['wr_type']][$row['pno']][$row['no']] = $row;
		}

		$arr['cate'] = $cate_arr;
		$arr['cate_p'] = $cate_p_arr;

		return $arr;
	}

	function get_cate_name($type_arr=array()) {
		global $db;
		$cate_arr = array();

		$q = " select * from nf_category where `wr_view`=1 and `wr_type` in ('".@implode("','", $type_arr)."') ".$_where." order by wr_rank asc";
		$query = $db->_query($q);
		while($row=$db->afetch($query)) {
			$cate_arr[$row['wr_type']][$row['wr_name']] = $row;
		}
		return $cate_arr;
	}
}
?>