<?php
$service_process_arr = array();
$service_option_arr = array();
function payment_service_calc($table, $field, $cnt, $unit, $status) {
	global $service_process_arr;

	if($unit=='count')
		if($status>0)
			$service_process_arr[$table][$field.'_int'] += $cnt;
		else
			$service_process_arr[$table][$field.'_int'] -= $cnt;
	else
		if($status>0)
			$service_process_arr[$table][$field] .= $cnt.' '.$unit.' ';
		else
			$service_process_arr[$table][$field] .= '-'.$cnt.' '.$unit.' ';
}

function payment_service_field($arr) {
	global $service_process_arr, $nf_util, $nf_shop;
	$price_row = $arr['price_row'];

	switch($arr['code']) {
		// : 그외 결제
		default:
			switch($price_row['type']) {
				// : 점프, 열람권은 회원권한쪽
				case "jump":
					$table = 'nf_'.$price_row['code'];
					$service_field = 'wr_service_jump';
					if(!is_array($service_process_arr[$table])) $service_process_arr[$table] = array();

					payment_service_calc($table, $service_field, $price_row['service_cnt'], $price_row['service_unit'], $arr['pay_status']);
				break;

				// : 기타는 해당 정보관련
				default:
					$service_field = array_key_exists($price_row['type'], $nf_shop->etc_service) ? 'wr_service_'.$price_row['type'] : 'wr_service'.$price_row['type'];
					if(!is_array($service_process_arr[$table])) $service_process_arr[$table] = array();
					$table = "nf_".$price_row['code'];

					payment_service_calc($table, $service_field, $price_row['service_cnt'], $price_row['service_unit'], $arr['pay_status']);
				break;
			}
		break;
	}
}

// $status - 결제완료, 입금대기값 --> payment_process($pay_row, 1) : 결제완료, payment_process($pay_row, 0) : 입금대기
// : 결제완료면 서비스를 더하고 입금대기면 서비스를 뺍니다. 단 첫결제시에는 1일이 더 더해지는데 이건.. 어렵네요;;
function payment_process($pay_row, $status) {
	global $nf_payment, $nf_util, $nf_point, $nf_member, $db, $nf_shop, $service_process_arr, $service_option_arr, $env;
	$pay_info = $nf_payment->pay_info($pay_row);
	$post_service = $pay_info['post_unse']['service'];
	$post_arr = $pay_info['post_unse'];
	$price_arr = $pay_info['price_unse'];

	$get_member_service = $db->query_fetch("select * from nf_member_service where `mno`=".intval($pay_row['pay_mno']));
	$nf_member->get_member($pay_row['pay_mno']);
	$get_member = $nf_member->member_arr[$pay_row['pay_mno']];

	switch($pay_row['pay_type']) {
		// : 업체정보
		case "shop":
		// : 점프
		case "jump":
			if(is_array($post_service)) { foreach($post_service as $k=>$v) {
				if(is_array($v)) { foreach($v as $k2=>$v2) {
					switch($k2) {
						// : 기타
						default:
							$service_k = $k.'_'.$k2;
							$price_row = $price_arr[$service_k];
							$table = "nf_".$price_row['code'];
							$info_row = $db->query_fetch("select * from ".$table." where `no`=".intval($pay_row['pay_no']));

							$_arr = array();
							$_arr['price_row'] = $price_row;
							$_arr['code'] = 'etc';
							$_arr['pay_status'] = $status;
							payment_service_field($_arr);

							// : 옵션정보
							if($post_arr['service_option'][$k][$k2] && in_array($k2, array('icon', 'neon', 'color'))) {
								$service_option_arr[$table]['wr_service_'.$k2.'_value'][] = $post_arr['service_option'][$k][$k2];
							}
						break;
					}
				} }
			} }
		break;


		case "direct": // : 다이렉트결제
			// - 결제완료, 입금대기만 바뀌면 되기 때문에 아무것도 없음.
		break;
	}

	$_set = array();
	if(is_array($service_process_arr)) { foreach($service_process_arr as $table=>$array) {
		if(is_array($_set[$table])) $_set[$table] = array();
		if(is_array($array)) { foreach($array as $field=>$date_count) {
			$k = substr($field, -4);
			if($k==='_int') {
				$_set[$table][] = $field."=".$field."+".$date_count;
			} else {
				$this_date = $info_row[$field]>=today ? $info_row[$field] : "";
				$_set[$table][] = $field."='".date("Y-m-d", strtotime($this_date.' '.$date_count))."'";
			}
		} }
	} }

	// : 옵션정보
	if(is_Array($service_option_arr)) { foreach($service_option_arr as $table=>$array) {
		if(is_array($array)) { foreach($array as $field=>$option_val) {
			$q = $db->_query("update ".$table." set ".$field."=? where `no`=?", array(implode(",", $option_val), $pay_row['pay_no']));
		} }
	} }

	// : 최종 서비스값 설정
	if(is_array($_set)) { foreach($_set as $table=>$field) {
		switch($table) {
			case "nf_member_service":
				$q = "update ".$table." set ".implode(", ", $field)." where `mno`=".intval($pay_row['pay_mno']);
			break;

			default:
				$q = "update ".$table." set ".implode(", ", $field)." where `no`=".intval($pay_row['pay_no']);
			break;
		}
		//echo $q."\n";
		$db->_query($q);
	} }

	$db->_query("update nf_payment set `pay_sdate`=?, `pay_status`=? where `no`=?", array(today_time, $status, $pay_row['no']));

	// : 무통장입금은 포인트를 쓴상태여도 돌려받거나 차감되지 않습니다.
	$use_point = intval($pay_row['pay_dc']);
	$point_row = $db->query_fetch("select * from nf_point where `point_rel_id`=? and `mno`=? and `point_rel_table`=? order by `point_datetime` desc limit 1", array($pay_row['no'], $pay_row['pay_mno'], 'nf_payment'));

	// : 첫 결제승인이거나, 과거 포인트승인날짜와 결제승인날짜가 같은경우 [ 승인시 사용포인트차감, 대기사 사용포인트 적립 ]
	$point_allow = ((!$point_row && !$pay_row['pay_status']) || ($point_row && $point_row['point_datetime']==$pay_row['pay_sdate'])) ? true : false;
	if($use_point>0 && !in_array($pay_row['pay_method'], array('bank')) && $point_allow) {
		$point_arr = array();
		$point_arr['member'] = $get_member;
		$point_arr['code'] = $nf_shop->pay_service_arr[$pay_row['pay_type']].'서비스 결제 '.($status ? '승인' : '취소');
		$point_arr['use_point'] = $status ? -$use_point : $use_point; // : 승인이면 차감, 대기면 돌려주기
		$point_arr['rel_id'] = $pay_row['no'];
		$point_arr['rel_action'] = $pay_row['pay_type'];
		$point_arr['rel_table'] = 'nf_payment';
		$nf_point->insert($point_arr);
	}

	// : 회원종류별 결제시 결제금액의 몇%만큼 결제 [ 승인시 추가, 대기시 차감 ]
	$allow_pay_add = false;
	$point_row = $db->query_fetch("select * from nf_point where `mno`=".intval($get_member['no'])." and `point_rel_id`=".intval($pay_row['no'])." and `point_rel_table`='nf_payment' and `point_rel_action`='결제시 추가포인트' order by `no` desc limit 1");
	$pay_add_point_percent = intval($env['member_point_arr'][$get_member['mb_type'].'_point_percent']);
	if($status && $get_member['mb_type'] && $pay_add_point_percent>0 && $pay_row['pay_price']>0) $allow_pay_add = true; // : 결제시 추가적립
	if(!$status && $point_row) $allow_pay_add = true; // : 결제취소시 추가차감 [ 전에 적립된 포인트만큼 차감 ]
	if($allow_pay_add) {
		$add_point_txt = $status ? '추가' : '차감';
		$pay_add_point = $nf_util->get_sale_reverse($pay_add_point_percent, $pay_row['pay_price']);
		$point_arr = array();
		$point_arr['member'] = $get_member;
		$point_arr['code'] = $nf_member->mb_type[$get_member['mb_type']].' 결제 '.$add_point_txt.'포인트';
		$point_arr['use_point'] = $status ? $pay_add_point : -$point_row['point_point']; // : 승인이면 차감, 대기면 돌려주기
		$point_arr['rel_id'] = $pay_row['no'];
		$point_arr['rel_action'] = '결제시 추가포인트';
		$point_arr['rel_table'] = 'nf_payment';
		$nf_point->insert($point_arr);
	}
}
?>