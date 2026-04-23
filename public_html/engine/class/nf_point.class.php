<?php
class nf_point extends nf_util {

	function __construct(){
		
	}

	/*
	$arr['member'] -> 회원배열
	$arr['use_point'] -> 사용, 얻을 포인트 음수 양수 가능
	*/
	function insert($arr) {
		global $db, $env;

		$_val = array();
		$_val['mno'] = $arr['member']['no'];
		$_val['mb_id'] = $arr['member']['mb_id'];
		$_val['mb_name'] = $arr['member']['mb_name'];
		$_val['mb_nick'] = $arr['member']['mb_nick'];
		$_val['point_datetime'] = today_time;
		$_val['point_content'] = today.' '.$arr['code'];
		$_val['point_point'] = $arr['use_point'];
		$_val['point_rel_table'] = $arr['rel_table'];
		$_val['point_rel_id'] = $arr['rel_id'];
		$_val['point_rel_action'] = $arr['rel_action'];
		$_val['point_mb_point'] = intval($arr['member']['mb_point'])+$arr['use_point'];
		$q = $db->query_q($_val);

		$point_query = $db->_query("insert into nf_point set ".$q, $_val);
		if($point_query) {
			// : 사용할수 있는 포인트
			$update = $db->_query("update nf_member set `mb_point`=`mb_point`+? where `no`=?", array($arr['use_point'], $arr['member']['no']));

			// : 적립된 총 포인트 - 등급계산할때 사용함.
			if($arr['use_point']>0) {// && $arr['rel_action']!='수동입력') {
				$update = $db->_query("update nf_member set `mb_add_point`=`mb_add_point`+? where `no`=?", array($arr['use_point'], $arr['member']['no']));

				// : 회원등급자동설정
				if($env['member_point_arr']['auto_level']) {
					$mb_add_point = $arr['member']['mb_add_point']+$arr['use_point'];
					$mb_level = $arr['member']['mb_level'];

					$ch_mb_level = "";
					$ch_mb_point = "";
					if(is_array($env['member_level_arr'])) { foreach($env['member_level_arr'] as $k=>$v) {
						$up_point = $v['point'];
						$up_level = $k;

						if($mb_add_point>=$up_point && $mb_level<$up_level) {
							$ch_mb_point = $up_point;
							$ch_mb_level = $up_level;
						}
					} }

					if($ch_mb_level) {
						$update = $db->_query("update nf_member set `mb_level`=".intval($ch_mb_level)." where `no`=".intval($arr['member']['no']));
					}
				}
			}
		}
	}
}
?>