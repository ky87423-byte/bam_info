<?php
// : 읽은 내역 저장하기
// : !!열람권 사용중인경우 열람권으로 차감해서 보지 않는경우에는 읽은내역(열람한채용정보, 열람한인재정보, 내이력서열람기업..)이 저장되면 안됨. - 이렇게 하기로 했음!!.
function read_insert($mno, $code, $pno, $arr=array()) {
	global $db, $nf_payment, $nf_member, $nf_board, $nf_shop;
	if(in_array($code, array_keys($nf_shop->kind_of)) && !$arr['allow']) return false;

	$mem_arr = $nf_member->get_member_ex($mno);
	$get_member = $mem_arr['member'];
	$get_member_ex = $mem_arr['member_ex'];
	$get_member_se = $mem_arr['member_se'];
	$mb_type = $code=='board' ? $arr['bo_table'] : $get_member['mb_type'];

	$row = $db->query_afetch("select * from nf_read where `mno`=? and `pno`=? and `code`=? and `mb_type`=?", array(intval($get_member['no']), $pno, $code, $mb_type));

	switch($code) {
		case "board":
			$info_table = "nf_write_".$arr['bo_table'];
			$info_row = $db->query_fetch("select * from ".$info_table." where `wr_no`=?", array($pno));
			$bo_row = $nf_board->board_table_arr[$arr['bo_table']];
			$other_mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($info_row['mno']));
			$pnos = $bo_row['pcode'].','.$bo_row['code'].',';
			$wr_reply = $info_row['wr_reply'];
		break;

		default:
			$info_table = "nf_".$code;
			$info_row = $db->query_fetch("select * from ".$info_table." where `no`=?", array($pno));
			$other_mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($info_row['mno']));
		break;
	}

	$_val = array();
	if(!$row) {
		$_val['code'] = $code;
		$_val['pno'] = $pno;
		$_val['pnos'] = $pnos;
		$_val['wr_reply'] = $wr_reply;
		$_val['mb_type'] = $mb_type;
		$_val['mno'] = intval($get_member['no']);
		$_val['exmno'] = intval($get_member_ex['no']);
		$_val['mb_id'] = $get_member['mb_id'];
		$_val['pmno'] = intval($other_mem_row['no']);
		$_val['pmb_id'] = $other_mem_row['mb_id'];
		$_val['visit'] = 1;
		$_val['rdate'] = today_time;
	} else {
		$_val['udate'] = today_time;
		$_val['visit'] = $row['visit']+1;
	}
	$_val['del'] = 0; // : 삭제했다 다시 읽으면 삭제값 없애기
	if($arr['_val_pay_read']) {
		$_val['pay_read'] = 1; // : 한건 차감후 읽을경우 [ 실제 결제로 사용한 정보 ]
		$_val['pay_read_date'] = today_time;
	}

	$q = $db->query_q($_val);
	if(!$row) $query = $db->_query("insert into nf_read set ".$q, $_val);
	else $query = $db->_query("update nf_read set ".$q." where `no`=".intval($row['no']), $_val);
}
?>