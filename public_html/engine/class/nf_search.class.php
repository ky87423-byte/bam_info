<?php
class nf_search extends nf_util {

	function __construct() {
		global $db;
	}

	## : 기본 검색 ########################

	function insert($arr) {
		global $db;
		$arr['content'] = trim($arr['content']);
		if(!$arr['content']) return false; // : 검색어가 없으면 저장하지 않습니다.

		$row = $db->query_fetch("select * from nf_search where `wr_wdate`>=? and `code`=? and `wr_content`=?", array(today.' 00:00:00', $arr['code'], $arr['content']));
		$_val = array();
		if(!$row) {
			$_val['code'] = $arr['code'];
			$_val['wr_content'] = $arr['content'];
			$_val['wr_wdate'] = today_time;
		}
		$_val['wr_hit'] = intval($row['wr_hit'])+1;
		$_val['wr_udate'] = today_time;
		$q = $db->query_q($_val);
		if($row) $db->_query("update nf_search set ".$q." where `no`=".intval($row['no']), $_val);
		else $db->_query("insert into nf_search set ".$q, $_val);
	}

	// : 회원검색
	function member() {
		global $is_admin;

		$_where = "";
		$_date_arr = array();
		$_keyword = array();
		$_login_count_arr = array();

		// : 관리자 권한 검색
		if($is_admin) {
			// : 날짜
			$field = $_GET['rdate'] ? 'mb_'.$_GET['rdate'] : 'mb_wdate';
			if($_GET['date1']) $_date_arr[] = "nm.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
			if($_GET['date2']) $_date_arr[] = "nm.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
			if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

			if($_GET['mb_type']) $_where .= " and `mb_type`='".addslashes($_GET['mb_type'])."'";
			if(strlen($_GET['mb_email_receive'])>0) $_where .= $_GET['mb_email_receive'] ? " and find_in_set('email', `mb_receive`)" : " and !find_in_set('email', `mb_receive`)";
			if(strlen($_GET['mb_sms_receive'])>0) $_where .= $_GET['mb_sms_receive'] ? " and find_in_set('sms', `mb_receive`)" : " and !find_in_set('sms', `mb_receive`)";
			if($_GET['mb_biz_type'][0]) $_where .= " and `mb_biz_type` in ('".implode("','", $_GET['mb_biz_type'])."')";
			if($_GET['mb_biz_success'][0]) $_where .= " and `mb_biz_success` in ('".implode("','", $_GET['mb_biz_success'])."')";
			if($_GET['mb_biz_form'][0]) $_where .= " and `mb_biz_form` in ('".implode("','", $_GET['mb_biz_form'])."')";

			// : 통합검색
			$_keyword['id'] = "nm.`mb_id` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['name'] = "nm.`mb_name` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['email'] = "nm.`mb_email` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['nick'] = "nm.`mb_nick` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['hphone'] = "replace(nm.`mb_hphone`,'-','') like '%".addslashes(strtr($_GET['search_keyword'], array('-'=>'')))."%'";
			$_keyword['phone'] = "replace(nm.`mb_phone`,'-','') like '%".addslashes(strtr($_GET['search_keyword'], array('-'=>'')))."%'";

			/*
			$_keyword['biz_fax'] = "replace(nmc.`mb_biz_fax`,'-','')='".addslashes(strtr($_GET['search_keyword'], array('-'=>'')))."'";
			$_keyword['ceo_name'] = "nmc.`mb_ceo_name`='".addslashes($_GET['search_keyword'])."'";
			$_keyword['company_name'] = "nmc.`mb_company_name`='".addslashes($_GET['search_keyword'])."'";
			$_keyword['biz_no'] = "nmc.`mb_biz_no`='".addslashes($_GET['search_keyword'])."'";
			*/
			if($_GET['search_keyword']) {
				if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
				else $_where .= " and (".implode(" or ", $_keyword).")";
			}

			if(!$_GET['login_count_all']) {
				if($_GET['login_count'][0]) $_login_count_arr[] = "nm.mb_login_count>=".intval($_GET['login_count'][0]);
				if($_GET['login_count'][1]) $_login_count_arr[] = "nm.mb_login_count<=".intval($_GET['login_count'][1]);
				if($_login_count_arr[0]) $_where .= " and (".implode(" and ", $_login_count_arr).")";
			}

			if($_GET['badness']) $_badness = " and nm.`mb_badness`=1";
			if($_GET['left_request']) $_where .= " and nm.`mb_left_request`=1";
			if($_GET['left']) $_where .= " and nm.`mb_left`=1";
			if($_GET['type']) $_where .= " and nm.`mb_type`='".addslashes($_GET['type'])."'";
		}

		$_where .= $_badness;

		$arr['where'] = $_where;

		return $arr;
	}

	function cs() {
		global $is_admin;
		if($is_admin) {
			// : 날짜
			$field = 'wr_date';
			if($_GET['date1']) $_date_arr[] = "cs.`".$field."`>='".addslashes($_GET['date1'])." 00:00:00'";
			if($_GET['date2']) $_date_arr[] = "cs.`".$field."`<='".addslashes($_GET['date2'])." 23:59:59'";
			if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

			if($_GET['wr_cate']) $_where .= " and `wr_cate`=".intval($_GET['wr_cate']);

			// : 통합검색
			$_keyword['wr_subject'] = "cs.`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['wr_content'] = "cs.`wr_content` like '%".addslashes($_GET['search_keyword'])."%'";
			$_keyword['wr_name'] = "cs.`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";

			if($_GET['search_keyword']) {
				if($_GET['search_field']=='wr_subject||wr_content') $_where .= " and (".$_keyword['wr_subject']." or ".$_keyword['wr_content'].")";
				if(array_key_exists($_GET['search_field'], $_keyword)) $_where .= " and ".$_keyword[$_GET['search_field']];
				else $_where .= " and (".implode(" or ", $_keyword).")";
			}
		}

		$arr['where'] = $_where;

		return $arr;
	}


	function service_where($code, $service_k="") {
		global $nf_shop, $db;

		$arr = array();
		$arr['not_pay'] = array();
		$count = 0;
		$arr['wheres'] = array();
		$arr['service_field'] = array();
		if(is_array($nf_shop->service_name[$code])) { foreach($nf_shop->service_name[$code] as $k=>$v) {
			if(is_array($v)) { foreach($v as $k2=>$v2) {
				$field_k = $count."_".$k2;
				$field = 'wr_service'.$field_k;
				if(in_array($field_k, array('1_list'))) {
					$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($code, $field_k));
					if(!$service_row['is_pay']) $arr['not_pay'][] = $field_k;
				}
				if($service_k && $service_k==$field_k) $arr['wheres'][] = $field.">='".today."'";
				else if(!$service_k) $arr['wheres'][] = $field.">='".today."'";
				$arr['service_field'][$field_k] = $field;
			} }
			$count++;
		} }

		$arr['where'] = implode(" or ", $arr['wheres']);

		return $arr;
	}

	## : 기본 검색 ########################



	function shop() {
		global $member, $is_admin, $cate_p_array, $cate_array, $nf_category, $with_guide, $with_qna;
		$get_var = $_GET;

		if(is_array($_GET['wr_area'])) $wr_area_arr = array_diff($_GET['wr_area'], array(""));
		if(is_array($_GET['category'])) $wr_category_arr = array_diff($_GET['category'], array(""));
		if(is_array($_GET['tema'])) $tema_arr = array_diff($_GET['tema'], array(""));

		$_where = "";

		// : 날짜
		$field = $get_var['rdate'] ? $get_var['rdate'] : 'wr_rdate';
		if($get_var['date1']) $_date_arr[] = "ns.`".$field."`>='".addslashes($get_var['date1'])." 00:00:00'";
		if($get_var['date2']) $_date_arr[] = "ns.`".$field."`<='".addslashes($get_var['date2'])." 23:59:59'";
		if($_date_arr[0]) $_where .= " and (".implode(" and ", $_date_arr).")";

		// : 회원주키 [ 관리자만 가능 ]
		if($get_var['mno'] && admin_id) $_where .= " and ns.`mno`=".intval($get_var['mno']);

		// : 서비스
		if(is_array($get_var['service'])) { foreach($get_var['service'] as $k=>$v) {
			$service_arr[] = "ns.`wr_service".$v."`>='".today."'";
		} }
		if($service_arr[0]) $_where .= " and (".implode(" or ", $service_arr).")";

		if($wr_area_arr[0]) $_where .= " and `wr_area` like '".implode(",", $wr_area_arr).",%'";
		if($wr_category_arr[0]) $_where .= " and `wr_category` like '%,".implode(",", $wr_category_arr).",%'";
		if($tema_arr[0]) $_where .= " and (find_in_set('".implode("', `wr_tema`) or find_in_set('", $tema_arr)."', `wr_tema`))";

		// : 필터
		if(is_Array($_GET['company'])) {
			// : 영업시간
			if(in_array('time', $_GET['company'])) {
				$StartDate = intval(date("Hi"));
				$EndDate = intval(date("Hi"));
				$field1 = "replace(`time1`,':','')";
				$field2 = "replace(`time2`,':','')";
				$_where .= " and (time_full=1 or ((".$field1."<".$field2." and ".$StartDate." >= ".$field1." AND ".$EndDate." <= ".$field2.") or (".$field1.">".$field2." and ".$StartDate." >= ".$field1." AND ".$EndDate." >= ".$field2.")))";
			}
			if(in_array('coupon', $_GET['company'])) {
				$StartDate = date("Y-m-d");
				$EndDate = date("Y-m-d");
				$field1 = "`coupon_date1`";
				$field2 = "`coupon_date2`";
				$_where .= " and (`coupon_use`=1 and (('".$StartDate."' <= ".$field1." AND '".$EndDate."' >= ".$field1.") OR ('".$StartDate."' <= ".$field2." AND '".$EndDate."' >= ".$field2.") OR (".$field1." <= '".$EndDate."' AND ".$field2." >= '".$EndDate."')))";
			}
		}

		if(is_array($_GET['icon']) && $_GET['icon'][0]) $_where .= " and (find_in_set('".implode(", `wr_icon`) or find_in_set('", $_GET['icon'])."', `wr_icon`))";

		if($_GET['range_price']) $_where .= " and `wr_price`  between 10000 and ".intval($_GET['range_price']*10000);
		if($_GET['range_point']) $_where .= " and `wr_avg_point` between 0.0 and ".sprintf("%0.1f", $_GET['range_point']);

		// : 통합검색
		$_keyword['wr_company'] = "ns.`wr_company` like '%".addslashes($get_var['search_keyword'])."%'";
		$_keyword['wr_subject'] = "ns.`wr_subject` like '%".addslashes($get_var['search_keyword'])."%'";
		if(strpos($_SERVER['PHP_SELF'], '/admini/')!==false) {
			$_keyword['wr_name'] = "ns.`wr_name` like '%".addslashes($get_var['search_keyword'])."%'";
			$_keyword['wr_hphone'] = "replace(ns.`wr_hphone`,'-','') like '%".addslashes(strtr($get_var['search_keyword'], array('-'=>'')))."%'";
			$_keyword['wr_phone'] = "replace(ns.`wr_phone`,'-','') like '%".addslashes(strtr($get_var['search_keyword'], array('-'=>'')))."%'";
			$_keyword['wr_id'] = "ns.`wr_id` like '%".addslashes($get_var['search_keyword'])."%'";
		}

		if($with_guide) {
			$_keyword['subject'] = "ng.`subject` like '%".addslashes($get_var['search_keyword'])."%'";
			$_keyword['content'] = "ng.`content` like '%".addslashes($get_var['search_keyword'])."%'";
			if(strpos($_SERVER['PHP_SELF'], '/mypage/')!==false) {
				if($member['mb_type']=='company') {
					$_keyword['content'] = "ng.`name` like '%".addslashes($get_var['search_keyword'])."%'";
				}
			}
		}

		if($with_qna) {
			$_keyword['subject'] = "nq.`subject` like '%".addslashes($get_var['search_keyword'])."%'";
			$_keyword['content'] = "nq.`content` like '%".addslashes($get_var['search_keyword'])."%'";
			if(strpos($_SERVER['PHP_SELF'], '/mypage/')!==false) {
				if($member['mb_type']=='company') {
					$_keyword['content'] = "nq.`name` like '%".addslashes($get_var['search_keyword'])."%'";
				}
			}
		}

		if($get_var['search_keyword']) {
			if(array_key_exists($get_var['search_field'], $_keyword)) $_where .= " and ".$_keyword[$get_var['search_field']];
			else $_where .= " and (".implode(" or ", $_keyword).")";
		}

		$arr['where'] = $_where;

		return $arr;
	}
}
?>