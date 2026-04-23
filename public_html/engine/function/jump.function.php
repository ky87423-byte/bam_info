<?php
function job_jump_insert($code, $row) {
	global $db;

	$_val = array();
	$_val['action'] = $row['action'];
	$_val['code'] = $code;
	$_val['mno'] = $row['mno'];
	$_val['mb_id'] = $row['wr_id'];
	$_val['cnt'] = $row['c'];
	$_val['pno'] = $row['nos'];
	$_val['sdate'] = today_time;
	$q = $db->query_q($_val);
	$insert = $db->_query("insert into nf_jump set ".$q, $_val);
}

function job_jump_func($time) {
	global $nf_shop, $nf_search, $db, $nf_shop;

	// : 업체정보 점프
	if($nf_shop->service_info['shop']['jump']['use']) {
		$service_where = $nf_search->service_where('shop');
		$where_basic = " and (".$service_where['where'].")".$nf_shop->shop_where;
		$_where = " ns.wr_jump_use=1 and '".today_time."'>=DATE_ADD(wr_jdate, INTERVAL ".$time.") ".$where_basic;

		$select = $db->_query("select * from nf_shop as ns where wr_service_jump_int>0 and ".$_where);
		while($row=$db->afetch($select)) {
			$minus_update = $db->_query("update nf_shop as nms set `wr_service_jump_int`=`wr_service_jump_int`-1 where `wr_service_jump_int`>0 and `no`=".intval($row['no']));
			if($minus_update) {
				$update = $db->_query("update nf_shop set `wr_jdate`='".date("Y-m-d H:i:").sprintf("%02d", rand(0,59))."' where `no`=".intval($row['no']));
				$row['action'] = 'auto';
				$row['nos'] = $row['no'];
				$row['c'] = 1;
				job_jump_insert('shop', $row);
			}
		}
	}
}
?>