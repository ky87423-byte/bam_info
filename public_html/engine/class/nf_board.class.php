<?php
class nf_board extends nf_util {

	var $board_main_rank, $main_row, $board_arr, $board_menu = array();
	var $first_cate, $board_length = 0;
	var $bo_type = array("text"=>"텍스트형", "image"=>"이미지형", "webzine"=>"웹진형", "talk"=>"1:1상담형");
	var $auth_arr = array("list"=>"리스트", "reply"=>"답변", "read"=>"읽기", "comment"=>"댓글", "write"=>"쓰기", "secret"=>"비밀글", 'download'=>'다운로드');
	var $bo_use_name = array('mb_nick', 'mb_id', 'mb_name', '익명');

	// : 게시판에 추가할 수 없는 게시판명
	var $not_bo_table = array('service_advert'=>'talk');
	var $not_bo_table_write_link = array('service_advert'=>'/service/advert_regist.php');

	function __construct() {
		global $db;
		$board_botable_arr = array();

		$main_row = $db->query_fetch("select * from nf_board_main");
		$main_row['print_board_un'] = unserialize($main_row['print_board']);
		$main_row['print_main_un'] = unserialize($main_row['print_main']);
		if(!is_array($main_row['print_board_un'])) $main_row['print_board_un'] = array();
		if(!is_array($main_row['print_main_un'])) $main_row['print_main_un'] = array();
		$this->main_row = $main_row;


		$query = $db->_query("select * from nf_category where `wr_type`='board_menu' order by `pno`, `wr_rank` asc");
		$board_menu = array();
		$first_cate = 0;
		$cnt = 0;
		$first_bo_table = array();
		while($row=$db->afetch($query)) {
			if(!is_array($board_menu[$row['pno']])) $board_menu[$row['pno']] = array();
			if($cnt===0) $first_cate = $row['no'];
			$board_menu[$row['pno']][$row['no']] = $row;
			if($row['wr_view']) $board_menu_view[$row['pno']][$row['no']] = $row;
			$cnt++;
		}

		$query = $db->_query("select * from nf_board order by `rank` asc");
		$board_arr = array();
		while($row=$db->afetch($query)) {
			$board_arr[$row['no']] = $row;
			$board_table_arr[$row['bo_table']] = $row;
			$board_code_arr[$row['pcode']][$row['code']][$row['no']] = $row;
			$board_botable_arr[$row['pcode']][$row['bo_table']] = $row;
			$board_main_rank[$row['pcode']][$row['no']] = array('b_rank'=>$row['b_rank'], 'm_rank'=>$row['m_rank']);
			$board_brank_arr[$row['pcode']][$row['bo_table']] = $row['b_rank'];
			$board_mrank_arr[$row['bo_table']] = $row['m_rank'];
		}

		$this->first_cate = $first_cate;
		$this->board_menu = $board_menu;
		$this->board_menu_view = $board_menu_view;
		$this->board_arr = $board_arr;
		$this->board_table_arr = $board_table_arr; // k값을 bo_table로 잡은 게시판
		$this->board_botable_arr = $board_botable_arr; // : 1차카테고리에 속한 게시판
		$this->board_botable_k_arr = array_keys($this->board_botable_arr);
		$this->board_code_arr = $board_code_arr; // : 1차, 2차 차례대로 배열에 속한 게시판
		$this->board_main_rank = $board_main_rank;
		$this->board_brank_arr = $board_brank_arr;
		$this->board_mrank_arr = $board_mrank_arr;


		$this->list_where = " and `wr_is_comment`=0 and `wr_del`=0 and `wr_blind`=0";
		$this->bo_where = " and `wr_del`=0 and `wr_blind`=0";
	}

	// : 게시글 테이블명
	function get_table($bo_table) {
		return 'nf_write_'.$bo_table;
	}

	function get_dir($bo_table) {
		if(!$date) $date = date("Ym");
		$arr['date'] = $date;
		$arr['dir'] = '/data/board/';
		$oldumask = umask(0);
		if(!is_dir(NFE_PATH.$arr['dir'].$bo_table)) mkdir(NFE_PATH.$arr['dir'].$bo_table, 0707);
		umask($oldumask);
		return $arr;
	}

	function get_dir_date($bo_table, $date="") {
		if(strlen($date)!=6) $date = date("Ym", strtotime($date));
		if(!$date) $date = date("Ym");
		$arr['date'] = $date;
		$arr['dir'] = '/data/board/'.$bo_table.'/';
		$oldumask = umask(0);
		if(!is_dir(NFE_PATH.$arr['dir'].$arr['date'])) mkdir(NFE_PATH.$arr['dir'].$arr['date'], 0707);
		umask($oldumask);
		return $arr;
	}

	// : 권한체크
	// : $code - 읽기, 쓰기... 값
	function auth($bo_table, $code, $code2='') {
		global $member, $nf_util, $is_admin, $db, $b_row;
		$bo_row = $this->board_table_arr[$bo_table];
		$b_level = $bo_row['bo_'.$code.'_level'];
		$bo_code_point = $bo_row['bo_'.$code.'_point'];
		if(in_array($b_row['wr_option'], array('notice'))) $auth = true;

		if(intval($bo_code_point)<0 && !$member['no']) {
			switch($code2) {
				case 'alert':
					$arr['msg'] = '회원권한입니다.';
					//$arr['move'] = NFE_URL.'/board/index.php?cno='.$bo_row['pcode'];
					$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode(domain.$_SERVER['REQUEST_URI']);
					die($nf_util->move_url($arr['move'], $arr['msg']));
				break;
			}
		} else {
			// : 사용안한경우
			if($b_level==-1) {
				if($code2=='alert') {
					$arr['msg'] = $this->auth_arr[$code].' 기능을 사용하지 않습니다.';
					$arr['move'] = '/';
					die($nf_util->move_url($arr['move'], $arr['msg']));
				}
				return false;
			}

			// : 1:1상담으로 상세 접속한경우
			if($bo_row['bo_type']=='talk' && strpos($_SERVER['PHP_SELF'], '/board/view.php')!==false) {
				$_table = $this->get_table($bo_table);
				$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_GET['no']));

				if($is_admin) $auth = true; // : 관리자 허용
				if($member['no'] && $b_row['mno']==$member['no']) $auth = true; // : 본인 허용

				// : 답글 클릭시 원글주인은 클릭되게
				if($b_row['wr_reply']) {
					$wr_parent_ori_arr = explode(",", $b_row['wr_parent_ori']);
					$parent_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($wr_parent_ori_arr[0]));
					if($member['no'] && $parent_row['mno']==$member['no']) $auth = true; // : 원글주인은 답글 확인 가능함.
				}
				if(!$auth && !$b_row['mno']) {
					$arr['msg'] = "권한이 없습니다.";
					$arr['move'] = NFE_URL."/board/list.php?bo_table=".$bo_table;
				}
				if($code2=='alert' && !$auth && !$_SESSION['board_view_'.$bo_table.'_'.$b_row['wr_no']]) {
					die($nf_util->move_url($arr['move'], $arr['msg']));
				}
			} else {
				// : 등급에 맞지 않는경우
				$member_row = $member;
				if(!$member_row['no']) $member_row['mb_level'] = 0;
				if(intval($member_row['mb_level'])<intval($b_level) && !$is_admin && $b_level>0) {
					switch($code2) {
						case 'alert':
							$arr['msg'] = '게시물 '.$this->auth_arr[$code].' 권한이 없습니다.';
							$arr['move'] = NFE_URL.'/board/index.php?cno='.$bo_row['pcode'];
							if($this->auth($bo_table, 'list')) $arr['move'] = $_SESSION['board_list_'.$bo_table];
							die($nf_util->move_url($arr['move'], $arr['msg']));
						break;
					}
					return false;
				}
			}
		}

		return true;
	}

	function union_all($cno) {
		$_search = $this->search();
		$field_set = "`wr_no`, `wr_subject`, `wr_content`, `wr_hit`, `wr_good`, `wr_name`, `mno`, `wr_datetime`";
		if(is_array($this->board_botable_arr[$cno])) { foreach($this->board_botable_arr[$cno] as $k=>$v) {
			$table_name = 'nf_write_'.$k;
			$q_arr[] = "select ".$field_set.", '".$v['bo_table']."' as `bo_table` from `".$table_name."` where wr_reply='' ".$this->list_where.$_search['where'];
		} }

		$arr['q'] = @implode(" union all ", $q_arr);
		return $arr;
	}

	function point_process_check($code, $bo_table) {
		global $member, $db, $nf_point;

		$_table = $this->get_table($bo_table);

		$bo_row = $this->board_table_arr[$bo_table];
		$bo_code_point = $bo_row['bo_'.$code.'_point'];

		$use_point = false;
		if($bo_code_point<0) {
			if(!$use_point && $bo_code_point<0 && $member['mb_point']<abs($bo_code_point)) $use_point = true;
			if(!$member['no']) $use_point = true;
		}

		return $use_point;
	}

	function point_process($code, $bo_table, $pno, $alert='') {
		global $member, $db, $nf_point;

		if($code=='download') {
			$file_row = $db->query_fetch("select * from nf_board_file where `no`=".intval($pno));
			$bo_table = $file_row['bo_table'];
			$wr_no = $file_row['wr_no'];
			$is_file = true;
			if(!is_file(NFE_PATH.'/data/board/'.$file_row['bo_table'].'/'.$file_row['file_name'])) {
				$is_file = false;
				$arr['msg'] = "다운로드할 파일이 없습니다.";
			}
		} else {
			$wr_no = $pno;
		}

		if($bo_table) {
			$_table = $this->get_table($bo_table);
			$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($wr_no).$this->bo_where);
		}

		$bo_row = $this->board_table_arr[$bo_table];
		$bo_code_point = $bo_row['bo_'.$code.'_point'];

		if(in_array($code, array('read', 'download'))) {
			if($b_row['mno'] && $b_row['mno']==$member['no']) return true;
			if($code=='download' && !$is_file) return true;
		}

		if(abs($bo_code_point)>0) {
			
			// : $is_result값이 true면 무료로 확인가능
			$is_result = false;
			$point_rel_table = in_array($code, array('download')) ? 'nf_board_file' : $_table;
			$is_use_point = $db->query_fetch("select * from nf_point where `mno`=? and point_rel_table=? and point_rel_id=? and point_rel_action=?", array($member['no'], $point_rel_table, $pno, $this->auth_arr[$code]));
			if($is_use_point) $is_result = true; // : 무사통과 [ 한번 이용한것은 무료 ]

			if((!$is_result && $bo_code_point<0 && $member['mb_point']<abs($bo_code_point)) || !$member['no'] || $arr['msg']) {
				if(!$arr['msg']) {
					if($bo_code_point<0) {
						$arr['msg'] = "포인트가 없어서 ".$bo_row['bo_subject'].' 게시판 '.$this->auth_arr[$code]."를 할 수 없습니다.\n필요 포인트는 ".abs($bo_code_point).'p 입니다.';
						if(!$member['no']) $arr['msg'] = "회원만 이용 가능합니다.";
					}
				}
				if(in_Array($code, array('download'))) {
					$arr['move'] = "";
				} else {
					$arr['move'] = NFE_URL.'/board/list.php?bo_table='.$bo_table;
				}

				if($arr['msg']) {
					switch($alert) {
						case "alert":
							$this->move_url($arr['move'], $arr['msg']);
						break;

						case "ajax":
							die(json_encode($arr));
						break;
					}
				}
				
			} else {
				if(!$arr['msg'] && !$is_result) {
					if($member['no']) {
						$point_arr = array();
						$point_arr['member'] = $member;
						$point_arr['code'] = $bo_row['bo_subject'].'('.$bo_row['bo_table'].') 게시판 '.$this->auth_arr[$code];
						$point_arr['use_point'] = $bo_code_point;
						$point_arr['rel_id'] = $pno;
						$point_arr['rel_action'] = $this->auth_arr[$code];
						$point_arr['rel_table'] = $point_rel_table;
						$nf_point->insert($point_arr);
					}
				}
			}
		}
	}

	// : 검색
	function search() {
		$arr = array();
		$arr['where'] = "";
		if($_GET['bunru']) $arr['where'] .= " and `wr_category`='".addslashes($_GET['bunru'])."'";

		$_search['wr_subject'] = "`wr_subject` like '%".addslashes($_GET['search_keyword'])."%'";
		$_search['wr_content'] = "`wr_content` like '%".addslashes($_GET['search_keyword'])."%'";
		$_search['wr_name'] = "`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
		if($_GET['search_keyword']) {
			if($_GET['search_field']=='sub+con') {
				$arr['where'] = " and (`wr_subject` like '%".addslashes($_GET['search_keyword'])."%' or `wr_content` like '%".addslashes($_GET['search_keyword'])."%')";
			} else {
				if($_GET['search_field']) $arr['where'] .= " and ".$_search[$_GET['search_field']];
				else $arr['where'] .= " and (".implode(" or ", $_search).")";
			}
		}

		return $arr;
	}

	function move_sess($bo_table) {
		$page = $_SESSION['board_list_'.$bo_table];
		$page_arr = explode("?", $page);
		if(is_file(NFE_PATH.$page_arr[0])) return $page;
		else return NFE_URL.'/board/list.php?bo_table='.$bo_table;
	}

	function board_query($bo_table, $_arr=array()) {
		global $db;
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$_table = $this->get_table($bo_table);
		$order = " order by wr_num, wr_reply";
		$_search = $this->search();
		$_where = "";
		if($this->not_bo_table[$bo_table]) $bo_row['bo_type'] = $this->not_bo_table[$bo_table];
		if(!in_array($bo_row['bo_type'], array('talk', 'text')) && strpos($_SERVER['PHP_SELF'], '/admini/')===false) $_where = " and `wr_reply`=''";
		if($_arr['where']) $_where .= $_arr['where'];
		$arr['q'] = $_table." as nwb where 1 ".$this->list_where.$_search['where'].$_where;
		$arr['order'] = $order;

		return $arr;
	}

	// : 게시판정보
	function board_info($bo_row) {
		if($bo_row) {
			$arr = $bo_row;
			$arr['bo_category_list'] = trim($bo_row['bo_category_list']);
			$arr['bo_category_list_arr'] = explode("|", $arr['bo_category_list']);
			$bo_table_width = $bo_row['bo_table_width'];
			if(!$bo_table_width) $bo_table_width = 1260;
			$arr['bo_table_width'] = $bo_table_width;

			$arr['cate1_txt'] = $this->board_menu[0][$bo_row['pcode']]['wr_name'];
		}

		return $arr;
	}

	// : 게시글 정보
	function info($row, $bo_row=array()) {
		if($bo_row['bo_table']=='service_advert') $bo_row['bo_type'] = 'talk';
		global $db, $nf_util, $is_admin;
		if($row) {
			$arr = $row;
			$rdate_dir = date("Ym", strtotime($row['wr_datetime']));

			$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($row['mno']));
			$mb_id = $mem_row ? $mem_row['mb_id'] : $row['wr_id'];
			$mb_name = $mem_row ? $mem_row['mb_name'] : $row['wr_name'];
			$mb_nick = $mem_row['mb_nick'];
			$get_name_k = $this->bo_use_name[$bo_row['bo_use_name']];
			$arr['get_name'] = $bo_row['bo_use_name']==='3' ? '익명' : $$get_name_k;
			if(!$arr['get_name']) $arr['get_name'] = $row['wr_name'];
			$arr['wr_reply_len'] = strlen($row['wr_reply']);
			$arr['wr_thumb_img'] = $row['wr_thumb'] ? $row['wr_thumb'] :  NFE_URL.'/images/no_img2.png';

			// : 금지어 변경
			$bo_filter_arr = explode(",", $bo_row['bo_filter']);
			$bo_filter_ch_arr = array();
			if($bo_row['bo_filter'] && is_array($bo_filter_arr)) { foreach($bo_filter_arr as $k=>$v) {
				$bo_filter_ch_arr[$v] = $nf_util->ch_bad_txt;
			} }

			$bo_new = $bo_row['bo_new'] ? $bo_row['bo_new'] : 24;
			$arr['is_new'] = today_time<=date("Y-m-d H:i:s", strtotime($row['wr_datetime'].' '.$bo_new.' hour')) ? true : false;
			if($arr['is_new']) $arr['icon_new'] = '<img src="'.NFE_URL.'/images/icon/new_icon.png" style="vertical-align:middle;" />';
			$arr['is_secret'] = $is_admin || !$row['wr_secret'] || $row['wr_secret'] && $member['no'] && $row['mno']==$member['no'] ? false : true;
			$arr['img_secret'] = $row['wr_secret'] ? '<i class="axi axi-lock2 blue"></i>' : '';

			$arr['a_href'] = !in_array($row['wr_option'], array('notice')) && ($arr['is_secret'] || in_array($bo_row['bo_type'], array('talk'))) ?
				"javascript:;\" onClick=\"nf_board.auth(this, 'read', '".$bo_row['bo_table']."', '".$row['wr_no']."')" :
				NFE_URL."/board/view.php?bo_table=".$bo_row['bo_table']."&no=".$row['wr_no'];

			$arr['wr_subject_txt'] = strtr($nf_util->get_text($row['wr_subject']), $bo_filter_ch_arr);
			$arr['list_subject'] = $arr['is_secret'] ? '비밀글입니다.' : $arr['wr_subject_txt'];
			if($arr['is_new']) $arr['list_subject'] = $arr['icon_new'].$arr['img_secret'].$arr['list_subject'];
			$arr['wr_content_txt'] = strtr($row['wr_content'], $bo_filter_ch_arr);

			if($arr['wr_reply_len']>0) $arr['reply_depth'] = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $arr['wr_reply_len']).'<img src="'.NFE_URL.'/images/icon/icon_reply.gif"/> ';
		}
		return $arr;
	}


	function max_visit($cno, $_arr=array()) {
		global $db;

		// : BEST OF BEST + http://job.netfu.co.kr/admini/board/main.php 의 금주의 베스트 출력개수
		$limit = intval($this->main_row['use_best_count'])+1;
		$_where = " and `rdate`>='".date("Y-m-d H:i:s", strtotime("-1 week"))."'";
		if(is_demo) $_where = " and `rdate`>='".date("Y-m-d H:i:s", strtotime("-10000 day"))."'";
		$read_query = $db->_query("select *, sum(`visit`) as c from nf_read where `pnos` like '".$cno.",%' and `code`='board' ".$_where." group by `mb_type`, `pno` order by c desc limit ".$limit);
		$arr['length'] = $db->num_rows($read_query);
		while($row=$db->afetch($read_query)) {
			$row = $db->query_fetch("select *, '".$row['mb_type']."' as `bo_table` from nf_write_".$row['mb_type']." where `wr_no`=".intval($row['pno']));
			$arr[] = $row;
		}

		return $arr;
	}


	function auth_move($code, $bo_table, $no) {
		switch($code) {
			case "write":
				$arr['move'] = NFE_URL.'/board/write.php?bo_table='.$bo_table.'&no='.$no;
			break;

			case "read":
				$arr['move'] = NFE_URL.'/board/view.php?bo_table='.$bo_table.'&no='.$no;
			break;
		}
		return $arr;
	}


	function alert_move($code, $bo_table, $no="") {
		switch($code) {
			case "write":
				$arr['move'] = NFE_URL.'/board/write.php?bo_table='.$bo_table.'&no='.$no;
			break;

			case "list":
				$board_list_move = $_SESSION['board_list_'.$bo_table];
				$arr['move'] = $board_list_move ? $board_list_move : NFE_URL."/board/list.php?bo_table=".$bo_table;
			break;
		}
		return $arr;
	}

	// : 조회,추천 저장하기
	function save_int($code, $bo_table, $pno) {
		global $db, $env, $board_save_int_minus;
		if(in_array($bo_table, array('service_advert'))) return;

		$b_row = $db->query_fetch("select * from nf_write_".$bo_table." where `wr_no`=".intval($pno));
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));

		// : 지난날짜에 건수가 하나이상 있는경우 삭제하기
		$date_cnt = $db->query_fetch("select count(*) as c from nf_board_int where `rdate`<?", array(date("Y-m-d", strtotime("-".$env['board_hit_date']." day"))));
		if($date_cnt<=0) $db->_query("delete from nf_board_int where `rdate`<?", array(date("Y-m-d", strtotime("-".$env['board_hit_date']." day"))));

		$row = $db->query_fetch("select * from nf_board_int where `code`=? and `bo_table`=? and `pno`=? and `rdate`=?", array($code, $bo_table, $pno, today));
		if(!$row) {
			$_val = array();
			$_val['cate1'] = $bo_row['pcode'];
			$_val['cate2'] = $bo_row['code'];
			$_val['code'] = $code;
			$_val['bo_table'] = $bo_table;
			$_val['pno'] = $pno;
			$_val['rdate'] = today;
		}
		$_val['wdate'] = $b_row['wr_datetime'];
		if($board_save_int_minus) $_val['cnt'] = $row['cnt']-1;
		else $_val['cnt'] = $row['cnt']+1;
		$q = $db->query_q($_val);
		if($row) $db->_query("update nf_board_int set ".$q." where `no`=".intval($row['no']), $_val);
		else $db->_query("insert into nf_board_int set ".$q, $_val);
	}
}
?>