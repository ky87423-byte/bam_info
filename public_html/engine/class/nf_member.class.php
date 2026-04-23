<?php
class nf_member extends nf_util {

	var $member_arr = array(); // : 회원정보
	var $mb_type = array("company"=>"업체", "individual"=>"개인");
	var $gender = array(1=>"남", 2=>"여");

	var $attach = array(
		'member_level'=>'/data/member_level/',
	);

	function __construct(){
	}

	// : 로그인되있는경우 튕겨내기
	function check_not_login() {
		global $member;
		if(sess_user_uid && $member['mb_type']) {
			$arr['msg'] = "";
			$arr['move'] = "/";
			die($this->move_url($arr['move'], $arr['msg']));
		}
	}

	// : 로그인체크여부 $kind는 회원종류
	function check_login($kind='', $msg='') {
		global $member;
		if(!$member['no']) $arr['move'] = NFE_URL."/member/login.php?page_url=".urlencode(domain.this_page);
		else {
			$arr['move'] = $this->page_back() ? $this->page_back() : "/";
			if(strpos($this->page_back(), $_SERVER['PHP_SELF'])!==false) $arr['move'] = "/";
		}
		if(!sess_user_uid) {
			$arr['msg'] = "로그인하셔야 이용 가능합니다.";
			if($msg) $arr['msg'] = $msg;
		} else {
			if($kind && $kind!=$member['mb_type']) {
				$arr['msg'] = $this->mb_type[$kind]."회원만 이용 가능합니다.";
				if($member['no']) $arr['move'] = NFE_URL.'/';
			}
		}
		if($arr['msg']) {
			die($this->move_url($arr['move'], $arr['msg']));
		}
	}

	function login($mno) {
		global $db, $nf_point, $env;
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($mno));
		if($mem_row) {
			$_SESSION['sess_user_uid'] = $mem_row['mb_id'];
			$db->_query("update nf_member set `mb_login_count`=`mb_login_count`+1, `mb_last_login`='".today_time."' where `no`=".intval($mem_row['no']));

			$point_row = $db->query_fetch("select * from nf_point where `point_rel_action`='login' and `mno`=".intval($mno)." and `point_datetime`>='".today." 00:00:00'");
			if(!$point_row && $env['member_point_arr']['login_point']>0) {
				$_point = array();
				$_point['member'] = $mem_row;
				$_point['code'] = '로그인';
				$_point['use_point'] = $env['member_point_arr']['login_point'];
				$_point['rel_table'] = 'netk_member';
				$_point['rel_id'] = '';
				$_point['rel_action'] = 'login';
				$update = $nf_point->insert($_point);
			}
		}
	}

	function logout() {
		$_SESSION['sess_user_uid']=  "";
	}

	function get_member($mno) {
		global $db, $nf_util;
		if(!$this->member_arr[$mno]) {
			$row = $db->query_fetch("select * from nf_member where `no`=?", array($mno));
			if($row) {
				$row['is_photo'] = is_file(NFE_PATH.'/data/member/'.$row['mb_photo']);
				if($row['is_photo'])
					$row['photo_src'] = NFE_URL.'/data/member/'.$row['mb_photo'];
				else
					$row['photo_src'] = NFE_URL.'/images/no_injae.png';

				$row['check_adult'] = $nf_util->is_adult($row['mb_birth']);

				$this->member_arr[$row['no']] = $row;
			}
			return $row;
		}
	}

	function get_member_ex_info($row) {
		global $env;
		$arr = $row;

		// : 로고
		$arr['is_mb_logo'] = is_file(NFE_PATH.'/data/member/'.$row['mb_logo']);
		if($arr['is_mb_logo'])
			$arr['mb_logo_src'] = NFE_URL.'/data/member/'.$row['mb_logo'];
		else
			$arr['mb_logo_src'] = NFE_URL.'/data/logo/'.$env['employ_logo_img'];

		// : 사업자등록증
		$arr['is_biz_attach'] = is_file(NFE_PATH.'/data/member/'.$arr['mb_biz_attach']);
		if($arr['is_biz_attach'])
			$arr['biz_attach_src'] = NFE_URL.'/data/member/'.$arr['mb_biz_attach'];

		return $arr;
	}

	function get_member_ex($mno) {
		global $db, $env;

		$_where = "";
		if($mno) {
			$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($mno));
			$get_member_se = $db->query_fetch("select * from nf_member_service where `mno`=?", array($get_member['no']));
		}

		switch($get_member['mb_type']) {
			case "company":
				$get_member_ex['is_mb_logo'] = is_file(NFE_PATH.'/data/member/'.$get_member_ex['mb_logo']);
				if($get_member_ex['is_mb_logo'])
					$get_member_ex['mb_logo_src'] = NFE_URL.'/data/member/'.$get_member_ex['mb_logo'];
				else
					$get_member_ex['mb_logo_src'] = NFE_URL.'/data/logo/'.$env['employ_logo_img'];


				$get_member_ex['is_biz_attach'] = is_file(NFE_PATH.'/data/member/'.$get_member_ex['mb_biz_attach']);
				if($get_member_ex['is_biz_attach'])
					$get_member_ex['biz_attach_src'] = NFE_URL.'/data/member/'.$get_member_ex['mb_biz_attach'];
			break;

			case "individual":
				$get_member_ex['is_photo'] = is_file(NFE_PATH.'/data/member/'.$get_member['mb_photo']);
				if($get_member_ex['is_photo'])
					$get_member_ex['photo_src'] = NFE_URL.'/data/member/'.$get_member['mb_photo'];
				else
					$get_member_ex['photo_src'] = NFE_URL.'/images/no_injae.png';
			break;
		}

		$arr['member'] = $get_member;
		$arr['member_ex'] = $get_member_ex;
		$arr['member_se'] = $get_member_se;

		return $arr;
	}

	function level_option($level) {
		global $env;

		ob_start();
		if(is_array($env['member_level_arr'])) { foreach($env['member_level_arr'] as $k=>$v) {
			if($k<=0) continue;
			$selected = $level && $level===(string)$k ? 'selected' : '';
		?>
		<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['name'];?> (<?php echo $k+1;?>레벨)</option>
		<?php
		} }
		$tag = ob_get_clean();

		return $tag;
	}
}
?>