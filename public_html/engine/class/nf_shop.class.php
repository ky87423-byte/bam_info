<?php
class nf_shop extends nf_util {

	var $kind_of = array('shop'=>'업체');
	var $kind_of_detail = array('shop'=>'/shop/view.php?no=');
	var $pay_service_arr = array('shop'=>'업체', 'jump'=>'점프', "direct"=>"다이렉트결제");
	var $coupon_limit_arr = array("one"=>"1인 1회", "one_day_1"=>"1인 1일 1회", "one_mu"=>"1인 무제한");

	var $attach_dir = array(
		'shop'=>'/data/shop/',
		'icon'=>'/data/shop/icon/',
	);

	var $site_color_arr = array(
		'black'=>array('background-color'=>'#222', 'color'=>'#222', 'border-color'=>'#111', 'border-top-color'=>'#111', 'accent-color'=>'#111', 'border-left-color'=>'#111', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 검은색
		'yellow'=>array('background-color'=>'#ffd018', 'color'=>'#f79201', 'border-color'=>'#ffd018', 'border-top-color'=>'#ffd018', 'accent-color'=>'#ffd018', 'border-left-color'=>'#ffd018', 'color2'=>'#392203', 'background-color2'=>'#392203', 'color3'=>'#ff970f', ), // : 노란색
		'orange'=>array('background-color'=>'#ff7100', 'color'=>'#ff7100', 'border-color'=>'#ff7100', 'border-top-color'=>'#ff7100', 'accent-color'=>'#ff7100', 'border-left-color'=>'#ff7100', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 주황색
		'red1'=>array('background-color'=>'#f65645', 'color'=>'#f65645', 'border-color'=>'#f65645', 'border-top-color'=>'#f65645', 'accent-color'=>'#f65645', 'border-left-color'=>'#f65645', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 빨간색
		'red2'=>array('background-color'=>'#c60021', 'color'=>'#c60021', 'border-color'=>'#c60021', 'border-top-color'=>'#c60021', 'accent-color'=>'#c60021', 'border-left-color'=>'#c60021', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 빨간색2
		'pink'=>array('background-color'=>'#ff385c', 'color'=>'#ff385c', 'border-color'=>'#ff385c', 'border-top-color'=>'#ff385c', 'accent-color'=>'#ff385c', 'border-left-color'=>'#ff385c', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 분홍색
		'purple'=>array('background-color'=>'#632695', 'color'=>'#632695', 'border-color'=>'#632695', 'border-top-color'=>'#632695', 'accent-color'=>'#632695', 'border-left-color'=>'#632695', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 보라색
		'green1'=>array('background-color'=>'#43c143', 'color'=>'#43c143', 'border-color'=>'#43c143', 'border-top-color'=>'#43c143', 'accent-color'=>'#43c143', 'border-left-color'=>'#43c143', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 연두색
		'green2'=>array('background-color'=>'#129a76', 'color'=>'#129a76', 'border-color'=>'#129a76', 'border-top-color'=>'#129a76', 'accent-color'=>'#129a76', 'border-left-color'=>'#129a76', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 초록색
		'greenblue'=>array('background-color'=>'#1181b2', 'color'=>'#1181b2', 'border-color'=>'#1181b2', 'border-top-color'=>'#1181b2', 'accent-color'=>'#1181b2', 'border-left-color'=>'#1181b2', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 그린블루
		'white_blue'=>array('background-color'=>'#0e8ee8', 'color'=>'#0e8ee8', 'border-color'=>'#0e8ee8', 'border-top-color'=>'#0e8ee8', 'accent-color'=>'#0e8ee8', 'border-left-color'=>'#0e8ee8', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 화이트블루
		'blue'=>array('background-color'=>'#1a73e8', 'color'=>'#1a73e8', 'border-color'=>'#1a73e8', 'border-top-color'=>'#1a73e8', 'accent-color'=>'#1a73e8', 'border-left-color'=>'#1a73e8', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 블루
		'deep_blue'=>array('background-color'=>'#252669', 'color'=>'#fff', 'border-color'=>'#252669', 'border-top-color'=>'#252669', 'accent-color'=>'#252669', 'border-left-color'=>'#252669', 'color2'=>'#fff', 'background-color2'=>'#fff', 'color3'=>'#fff', ), // : 딥블루
	);

	var $pay_service = array(
		'shop'=>
			array('main'=>'서비스', 'jump'=>'점프서비스'),
	);

	var $shop_service_arr = array('main');
	var $etc_service = array("jump"=>"점프");
	var $service_count_arr = array('jump');
	var $service_only_count_arr = array('jump');

	var $service_name_k_txt = array('main'=>'');
	var $service_name_k = array(
		'shop'=>array(0=>'main'),
	);
	var $service_name = array(
		'shop'=>
			array(
				"main"=>array(0=>"프리미엄", 1=>"추천", 2=>"스마트", 3=>"그랜드"),
			),
	);

	function __construct(){
		global $db;
		$this->shop_where = " and ns.`is_delete`=0 and ns.is_wait=1 and ns.`wr_view`=1 and ns.wr_report>=0"; // : is_wait가 1이여야 등록대기가 아닙니다.

		// : 현재위치 gps값 -- /include/conn.php에서 생성해줍니다.
		$this_area_pos_arr = explode("@", $_COOKIE['this_area_pos_here']);
		$this->this_area_pos_arr['lat'] = $this_area_pos_arr[0] ? $this_area_pos_arr[0] : $this->latlng_arr['lat']; // : 쿠키값이 없으면 기본위치
		$this->this_area_pos_arr['lng'] = $this_area_pos_arr[1] ? $this_area_pos_arr[1] : $this->latlng_arr['lng']; // : 쿠키값이 없으면 기본위치

		$service_query = $db->_query("select * from nf_service");
		$this->option_use_ = array();
		while($row=$db->afetch($service_query)) {
			if($row['use'] && array_key_exists($row['type'], $this->etc_service) && !in_array($row['type'], array('jump'))) $this->option_use_[$row['code']]++;
			$service_arr[$row['code']][$row['type']] = $row;
			// : 박스광고+일반줄광고 where절
			$box_service_field = 'wr_service'.$row['type'];
			if($db->is_field('nf_'.$row['code'], $box_service_field) && strpos($box_service_field, 'border')===false) {
				$service_where_arr[$row['code']][$row['type']] = $box_service_field.">='".today."'";
				$service_where_not_arr[$row['code']][$row['type']] = $box_service_field."<'".today."'";
				$service_remain_date_arr[$row['code']][$row['type']] = "(".$box_service_field."<='{날짜}' and ".$box_service_field.">='".today."')";
			}
		}
		$this->service_info = $service_arr;
		$this->service_where = $service_where_arr;
		$this->service_where2 = " and !(".implode(" and ", $service_where_not_arr['shop']).")";
		$this->service_ing_where = " and (".implode(" and ", $service_where_not_arr['shop']).")";
		$this->service_remain_where = implode(" or ", $service_remain_date_arr['shop']);

		// : 유료형 종류별 서비스 키값모음
		if(is_array($this->service_name_k)) { foreach($this->service_name_k as $k=>$v) {
			if(is_array($v)){  foreach($v as $k2=>$v2) {
				$service_arr = $this->service_name[$k][$v2];
				if(is_array($service_arr)) { foreach($service_arr as $k3=>$v3) {
					$service_charge_arr[$k][$k2.'_'.$k3] = $v3;
				} }
			} }
		} }
		$this->service_charge_arr = $service_charge_arr;
	}

	function get_serach_text($get) {
		global $cate_array;
		$txt_arr = array();
		if($_GET['wr_area'][0]) $txt_arr[] = implode(" ", $_GET['wr_area']);
		if($_GET['category'][0]) $txt_arr[] = strtr(implode(",", $_GET['category']), $cate_array['job_part']);
		if($_GET['tema'][0]) $txt_arr[] = strtr(implode(",", $_GET['tema']), $cate_array['job_tema']);

		return implode(" ", $txt_arr);
	}

	// : 후기 평점, 후기수 재조정
	function save_guide_point_cnt($pno) {
		global $db;
		$wr_avg_point = $db->query_fetch("select avg(`point`) as p from nf_guide where view=1 and `pno`=".intval($pno));
		$guide_cnt = $db->query_fetch("select count(*) as c from nf_guide where view=1 and `pno`=".intval($pno));
		$update = $db->_query("update nf_shop set `wr_avg_point`=".sprintf("%0.1f", $wr_avg_point['p']).", `wr_guide_int`=".intval($guide_cnt['c'])." where `no`=".intval($pno));
	}

	function get_member_status($type_member, $mb_type) {
		switch($mb_type) {
			case "company":
			break;

			case "individual":
			break;
		}
	}

	function service_name_setting() {
		global $env;
		if(is_array($env['service_name_arr'])) { foreach($env['service_name_arr'] as $kind=>$array) {
			if(is_array($array)) { foreach($array as $service_k=>$name) {
				$service_k_arr = explode("_", $service_k);
				$sub_k = $this->service_name_k[$kind][$service_k_arr[0]];
				$this->service_name[$kind][$sub_k][$service_k_arr[1]] = $name;
			} }
		} }
	}

	function service_query($code, $arr) {
		global $db, $env;

		$adver_w = $env['service_config_arr'][$code][$arr['service_k']]['width'];
		$adver_h = $env['service_config_arr'][$code][$arr['service_k']]['height'];

		if(!$arr['limit']) {
			$arr['limit'] = intval($adver_w*$adver_h);
		}

		switch($code) {
			case "shop":
				$q = "nf_shop as nr where 1 ".$arr['where'].$this->shop_where;
			break;
		}

		$page_int = $_GET['page'];
		$start = $this->start_page($page_int, $arr);
		if(strpos($arr['service_k'], '_list')===false) $start = 0; // : 박스광고는 limit 시작을 0부터
		$limit = " limit ".$start.", ".$arr['limit'];
		if(!$arr['field_set']) $arr['field_set'] = '*';
		$arr['q_all'] = "select ".$arr['field_set']." from ".$q.$arr['order'].$limit;
		$query = $db->_query($arr['q_all']);

		$arr['query'] = $query;
		if($arr['service_k']=='1_list') {
			$total = $db->query_fetch("select count(*) as c from ".$q);
			$total = $total['c'];
		} else {
			$total = $db->num_rows($query);
		}

		if($adver_w) {
			$arr['list_limit'] = $arr['limit']<$total ? $arr['limit'] : $total+$this->get_remain($total, $adver_w);
		} else {
			$arr['list_limit'] = $arr['limit'];
		}

		while($em_row = $db->afetch($query)) {
			$arr['list'][] = $em_row;
		}

		$arr['q'] = $q;
		$arr['total'] = $total;
		$arr['limit_arr'] = array($adver_w, $adver_h);

		return $arr;
	}

	function shop_info($row) {
		global $cate_array, $env;

		$arr = array();
		if($row) {
			$arr = $row;
			$arr['main_photo_url'] = '/data/shop/'.$row['wr_main_photo'];
			$arr['photo_arr'] = explode(",", $row['wr_photo']);

			// : 쿠폰이 사용중인경우에 기간도 그 안에 있어야 사용중임.
			if($row['coupon_use']) $arr['coupon_use'] = !($row['coupon_date1']<=today && $row['coupon_date2']>=today) ? 0 : 1;

			$area_arr = explode(",", $row['wr_area']);
			if(is_array($area_arr)) $area_arr = array_diff($area_arr, array(""));
			$arr['area_arr'] = $area_arr;
			$arr['area_end'] = array_pop($area_arr);
			$arr['area_txt'] = strtr($row['wr_area'], array(","=>" "));
			$arr['address_txt'] = strtr($row['wr_address'], array("||"=>" "));
			$arr['tema_arr'] = explode(",", $row['wr_tema']);
			if(is_array($arr['tema_arr'])) $arr['tema_arr'] = array_diff($arr['tema_arr'], array(""));
			$arr['tema_cnt'] = count($arr['tema_arr']);

			// : 쿠폰사용여부
			$arr['coupon_remain_int'] = $row['coupon_allow_int']-$row['coupon_use_int'];
			$arr['coupon_use_check'] = ($row['coupon_use'] && $arr['coupon_remain_int']>0 && $row['coupon_date1']<=today && $row['coupon_date2']>=today) ? true : false;


			$category_arr = explode("\r\n", $row['wr_category']);
			$arr['category_arr'] = $category_arr;
			$category_txt = "";
			$cate_one_arr = explode(",", $arr['category_arr'][0]);
			$arr['category_1_txt'] = strtr($cate_one_arr[1], $cate_array['job_part']);

			if(is_array($category_arr)) { foreach($category_arr as $k=>$v) {
				$category_txt .= strtr(strtr(substr(substr($v,1), 0,-1), $cate_array['job_part']), array(","=>">")).'<br/>';
			} }
			$arr['category_txt'] = $category_txt;

			$arr['time_use'] = $row['time1']=='00:00' && $row['time1']=='00:00' && !$row['time_full'] ? false : true;
		}

		return $arr;
	}

	function get_point_avg_img($int) {
		$point_arr = array();
		for($i=0; $i<5; $i++) {
			if($i<floor($int)) $point_arr[] = '<i class="axi axi-star3"></i>';
			else $point_arr[] = '<i class="axi axi-star-o"></i>';
		}
		return implode("", $point_arr);
	}

	function coupon_down_allow($no) {
		global $db, $member;
		$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`='".addslashes($no)."' and ns.`coupon_use`=1 and ns.`is_delete`=0");

		$_where = "";
		$_msg = "이미 쿠폰을 발급받았습니다.\n카톡으로 받지 못하셨다면 마이페이지의 쿠폰내역에서 쿠폰번호를 확인해주시기 바랍니다.";
		if($shop_row['coupon_limit']=='one_day_1') {
			$_where .= " and `rdate`>'".date("Y-m-d")."'";
			$_msg = "오늘은 쿠폰을 받았습니다.\n카톡으로 받지 못하셨다면 마이페이지의 쿠폰내역에서 쿠폰번호를 확인해주시기 바랍니다.";
		}

		$coupon_row = $db->query_fetch("select * from nf_coupon_use where `mno`='".$member['no']."' and `code`='shop' and `pno`='".addslashes($no)."'".$_where);
		if($coupon_row && $shop_row['coupon_limit']!='one_mu') {
			$arr['row'] = $coupon_row;
			$arr['msg'] = $_msg;
		}

		return $arr;
	}

	// : 읽기 함수
	function read($mno, $pno, $code) {
		global $db, $is_admin, $nf_payment, $not_read_div_box, $accept_row;

		//if($is_admin) return true; // : 관리자는 무조건 허용 그리고 읽은것을 디비에 저장안해야함.

		$info_table = "nf_".$code;
		$info_row = $db->query_fetch("select * from ".$info_table." where `no`=?", array($pno));

		$allow_read = true;
		$info_row = $allow_arr['info_row'];

		read_insert($mno, $code, $pno, $allow_arr);

		$update = $db->_query("update ".$info_table." set `wr_hit`=`wr_hit`+1 where `no`=?", array($info_row['no']));

		// : 읽기권한
		return $allow_read;
	}
}
?>