<?php
if(!isset($_include)) $_include = "";
if(!isset($_POST['mode'])) $_POST['mode'] = "";
if(!isset($_GET['mode'])) $_GET['mode'] = "";
if(in_array($_POST['mode'], array('check_nick', 'member_write', 'delete_member', 'delete_select_member'))) $not_mb_type_check = true;
if(in_array($_GET['mode'], array('logout'))) $not_mb_type_check = true;

if(!$_include) {
	if(in_array($_POST['mode'], array('put_skin_content'))) $add_cate_arr = array('job_target', 'job_document', 'job_conditions', 'job_grade', 'job_grade', 'job_position');
	if($_POST['mode']=='btn_category') $add_cate_arr = array('subway', 'job_pay');
	if(in_array($_POST['mode'], array('load_manager'))) $add_cate_arr = array('email');

	if(in_array($_POST['mode'], array('get_map_shop'))) $add_cate_arr = array('subway', 'job_date', 'job_week', 'job_pay', 'job_pay_support', 'job_type', 'job_welfare', 'job_grade', 'job_position', 'job_age', 'job_target', 'job_document', 'job_tema', 'job_conditions');

	include "../engine/_core.php";
}


// : 결제 pay_oid값으로 결제인지 아닌지 판단하기 - 결제할때 post값이 추가정보를 못받는 경우가 있어서 이렇게 했음.
if($_POST['param_opt_2']) {
	$_POST['mode'] = $_POST['param_opt_1'];
	$_POST['pno'] = $_POST['param_opt_2'];
}

// : toss결제시
if($_GET['paymentKey'] && $_GET['orderId'] && $_GET['amount']) {
	$_POST['mode'] = "payment_process";
	$oid_arr = explode("_", $_GET['orderId']);
	$_POST['orderId'] = $_GET['orderId'];
	$_POST['amount'] = $_GET['amount'];
}

switch($_POST['mode']) {

	case "click_memo":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "개인회원만 쪽지작성이 가능합니다.";
			$arr['move'] = "";
			if($member['mb_type']=='individual') {
				$arr['msg'] = "";
				$arr['move'] = "";
				$info_row = $db->query_fetch("select * from nf_shop as ns where `no`=".intval($_POST['no'])." ".$nf_shop->shop_where.$nf_shop->service_where2);
				if(!$info_row) {
					$arr['msg'] = "정보가 없습니다.";
					$arr['move'] = NFE_URL."/";
				} else {
					$arr['js'] = '
					nf_util.open_group(".message-");
					';
				}
			}
		}
		die(json_encode($arr));
	break;

	case "click_tel":
		$arr = array();
		// 허용된 click 코드만 화이트리스트로 제한 (컬럼 인젝션 방지)
		$allowed_click_codes = array('sms', 'call', 'chat', 'kakao', 'line', 'url', 'map');
		$_click_code = in_array($_POST['code'], $allowed_click_codes) ? $_POST['code'] : 'sms';
		$field_val = 'click_'.$_click_code;
		$update = $db->_query("update nf_shop set `".$field_val."`=`".$field_val."`+1 where `no`=?", array(intval($_POST['no'])));
		die(json_encode($arr));
	break;

	case "get_list_latlng":
		$arr = array();
		$json_data = json_decode($_POST['json'], true);
		// IN절 인젝션 방지: 각 값을 정수로 강제 캐스팅
		$query = $db->query_in("select * from nf_shop where `no` in (%s)", (array)$json_data);
		if (!$query) { die(json_encode($arr)); }
		while($row=$db->afetch($query)) {
			if($row['wr_lat']) {
				$get_km = $nf_util->get_distance(array('this_lat'=>$_POST['lat'], 'this_lng'=>$_POST['lng'], 'lat'=>$row['wr_lat'], 'lng'=>$row['wr_lng']));
				$km = $nf_util->get_distance_int($get_km);
				$arr['km'][$row['no']] = array($km, $row['wr_lat']);
			}
		}
		die(json_encode($arr));
	break;

	case "report_write":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = "";
			$member_sel_kind = array("shop"=>"individual");
			if($member_sel_kind[$_POST['page_code']]!=$member['mb_type']) {
				$arr['msg'] = $nf_member->mb_type[$member_sel_kind[$_POST['page_code']]]."회원만 신고가능합니다.";
			} else {
				$report_row = $db->query_fetch("select * from nf_report where `pno`=? and `code`=? and `mno`=?", array($_POST['pno'], $_POST['page_code'], $member['no']));
				$info_row = $db->query_fetch("select * from nf_".$_POST['page_code']." where `no`=".intval($_POST['pno']));
				if(!$info_row) $arr['msg'] = "삭제된 ".$nf_shop->kind_of[$_POST['page_code']]."정보입니다.";
				if($report_row) $arr['msg'] = "이미 신고한 ".$nf_shop->kind_of[$_POST['page_code']]."정보입니다.";
				if(!$arr['msg']) {
					$get_member = $db->query_fetch("select * from nf_member where `no`=?", array($info_row['mno']));
					$_val = array();
					$_val['code'] = $_POST['page_code'];
					$_val['pno'] = $_POST['pno'];
					$_val['sel_reason'] = $cate_p_array['shop_'.$_POST['page_code'].'_report_reason'][0][$_POST['select']]['wr_name'];
					$_val['mno'] = $member['no'];
					$_val['mb_id'] = $member['mb_id'];
					$_val['mb_name'] = $member['mb_name'];
					$_val['pmno'] = $get_member['no'];
					$_val['pmb_id'] = $get_member['mb_id'];
					$_val['pmb_name'] = $get_member['mb_name'];
					$_val['sdate'] = today_time;
					$q = $db->query_q($_val);
					$query = $db->_query("insert into nf_report set ".$q, $_val);
					if($query) $arr['msg'] = "신고했습니다.";
				}
			}
			$arr['js'] = '
			nf_util.openWin(".report-", "none");
			';
		}
		die(json_encode($arr));
	break;

	case "click_guide_good":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
		if($member['no']) {
			$guide_good = $db->query_fetch("select * from nf_click_good where `code`=? and `pno`=? and `mno`=?", array($_POST['code'], $_POST['no'], $member['no']));

			$arr['move'] = "";
			switch($_POST['code']) {
				case "shop":
					$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($_POST['no']));
					$msg = "이미 좋아요로 설정했습니다.";
				break;

				default:
					$guide_row = $db->query_fetch("select * from nf_guide where `no`=".intval($_POST['no']));
					$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($guide_row['pno']));
					$msg = "이미 ".(($guide_good['good']) ? "공감" : "비공감")."으로 설정했습니다.";
				break;
			}

			$arr['msg'] = "삭제된 업체정보입니다.";
			if($shop_row) {
				if($guide_good) {
					$arr['msg'] = $msg;
				} else {
					$_val = array();
					$_val['code'] = $_POST['code'];
					$_val['good'] = $_POST['good'] ? 1 : 0;
					$_val['pno'] = $_POST['no'];
					$_val['mno'] = $member['no'];
					$_val['pmno'] = $mem_row['no'];
					$_val['wr_id'] = $member['mb_id'];
					$_val['c_id'] = $mem_row['mb_id'];
					$_val['name'] = $member['mb_name'];
					$_val['c_name'] = $mem_row['mb_name'];
					if(!$guide_good) $_val['rdate'] = today_time;
					$_val['udate'] = today_time;
					$q = $db->query_q($_val);
					if($guide_good) $db->_query("update nf_click_good set ".$q." where `no`=".intval($guide_good['no']), $_val);
					else $db->_query("insert into nf_click_good set ".$q, $_val);

					$field_val = $_POST['good'] ? "good" : "bad";
					$budng = $field_val=='good' ? '+' : '-';
					switch($_POST['code']) {
						case "guide":
							$db->_query("update nf_guide set `".$field_val."`=`".$field_val."`+1 where `no`=".intval($guide_row['no']));
							$db->_query("update nf_shop set `wr_guide_".$field_val."`=`wr_guide_".$field_val."`+1 where `no`=".intval($shop_row['no']));
							$arr['msg'] = (($_POST['good']) ? "공감" : "비공감")."으로 설정했습니다.";
							$int_val = $guide_good[$field_val]+1;
						break;

						case "shop":
							$db->_query("update nf_shop set `wr_".$field_val."`=`wr_".$field_val."`+1 where `no`=".intval($shop_row['no']));
							$arr['msg'] = "좋아요로 설정했습니다.";
							$int_val = $shop_row['wr_good']+1;
							$js = '
							if($(el).find("i")[0]) {
								$(el).find("i").removeClass("axi-heart");
								$(el).find("i").addClass("axi-heart2");
							}
							';
						break;
					}

					if(strpos($nf_util->page_back(), 'include/review.php')!==false) $int_val = $guide_row['good']-$guide_row['bad']+1;
					$arr['js'] = '
					$(el).find(".int--").html("'.($int_val).'");
					';

					$arr['js'] .= $js;
				}
			}
		}
		die(json_encode($arr));
	break;

	case "qna_check":
		$arr = array();
		$qna_row = $db->query_fetch("select * from nf_qna where `no`=".intval($_POST['no']));
		$allow = false;
		if(($member['no'] && $qna_row['mno']==$member['no']) || $_COOKIE['shop_qna_'.$qna_row['no']]) {
			if($_POST['code']=='modify') {
				$arr['js'] = '
				location.href = "'.NFE_URL.'/shop/qna_form.php?no='.$qna_row['no'].'";
				';
			}
			if($_POST['code']=='delete') {
				if($qna_row['mno']) $delete = $db->_query("delete from nf_qna where `mno`=".intval($member['no'])." and `no`=".intval($_POST['no']));
				else $delete = $db->_query("delete from nf_qna where `no`=".intval($_POST['no']));
				$arr['msg'] = "삭제가 완료되었습니다.";
				$arr['move'] = $nf_util->page_back();
			}
		} else if(!$qna_row['mno']) {
			$arr['js'] = '
			var passform = document.forms["fpassword"];
			passform.code.value = get_code;
			click_secret(el, "'.$qna_row['no'].'");
			';
		} else {
			$arr['msg'] = "권한이 없습니다.";
		}
		die(json_encode($arr));
	break;

	case "guide_open":
	case "guide_open_not":
	case "qna_open":
	case "qna_open_not":
		$_table = strpos($_POST['mode'], 'guide')!==false ? 'nf_guide' : 'nf_qna';
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
		if($member['no']) {
			$mno_field = $member['mb_type']=='individual' ? 'mno' : 'pmno';
			$row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no'])." and `".$mno_field."`=".intval($member['no']));
			$arr['move'] = $nf_util->page_back();
			$txt = strpos($_POST['mode'], 'open_not')!==false ? '미출력' : '출력';
			$view = strpos($_POST['mode'], 'open_not')!==false ? 0 : 1;
			if(!$row) {
				$arr['msg'] = $txt."권한이 없습니다.";
			} else {
				$update = $db->_query("update ".$_table." set `view`=".intval($view)." where `no`=".intval($row['no']));
				$arr['msg'] = $txt."으로 설정했습니다.";

				// : 평점값, 후기수 재조정
				if(in_array($_POST['mode'], array('guide_open', 'guide_open_not'))) {
					$nf_shop->save_guide_point_cnt($row['pno']);
				}
			}
		}
		die(json_encode($arr));
	break;

	// : 이용후기 Q&A qna 답변 삭제
	case "delete_guide_reply":
	case "delete_qna_reply":
		$_table = strpos($_POST['mode'], 'guide')!==false ? 'nf_guide' : 'nf_qna';
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
		if($member['mb_type']!='company') {
			$arr['msg'] = "기업회원만 가능합니다.";
			$arr['move'] = $nf_util->page_back();
		} else if($member['mb_id']) {
			$guide_row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no'])." and `pmno`=".intval($member['no']));
			$arr['move'] = $nf_util->page_back();
			if(!$guide_row) {
				$arr['msg'] = "삭제권한이 없습니다.";
			} else {
				$update = $db->_query("update ".$_table." set `a_content`='', `answer`=0 where `no`=".intval($guide_row['no']));
				$arr['msg'] = "답변삭제가 완료되었습니다.";
			}
		}
		die(json_encode($arr));
	break;

	// : 이용후기, qna Q&A 삭제
	case "delete_guide":
	case "delete_select_guide":
	case "delete_qna":
	case "delete_select_qna":
		$_table = strpos($_POST['mode'], 'guide')!==false ? 'nf_guide' : 'nf_qna';
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
		if($member['mb_id'] || $is_admin) {
			$arr['move'] = $nf_util->page_back();
			$mno_field = $member['mb_type']=='individual' ? 'mno' : 'pmno';
			$nos = strpos($_POST['mode'], '_select_')!==false ? implode(",", $_POST['chk']) : $_POST['no'];
			$_where = "";
			if(strpos($nf_util->page_back(), "/admini/")===false) {
				$_where .= " and ".$mno_field."=".intval($member['no']);
				if($_table=='nf_guide') $_where .= " and `view`=1";
			}

			$query = $db->_query("select * from ".$_table." where 1 and `no` in (".$nos.")".$_where);
			while($row=$db->afetch($query)) {
				$delete = $db->_query("delete from ".$_table." where `no`=".intval($row['no']));
				if(in_array($_POST['mode'], array('delete_guide', 'delete_select_guide'))) $nf_shop->save_guide_point_cnt($row['pno']);
			}

			if($delete) {
				$arr['msg'] = "삭제가 완료되었습니다.";
			} else {
				$arr['msg'] = "삭제가 실패되었습니다.";
			}
		}
		die(json_encode($arr));
	break;

	// : 이용후기, Q&A, qna 작성
	case "guide_write":
	case "qna_write":
		$allow = false;
		$is_modify = false;

		if($_POST['mode']=='qna_write' && !$member['no']) {
			$recaptcha_allow = $nf_util->recaptcha_check();
			if(!$recaptcha_allow['success']) {
				die($nf_util->move_url($recaptcha_allow['move'], $recaptcha_allow['msg']));
			}
		}

		$_table = $_POST['mode']=='qna_write' ? 'nf_qna' : 'nf_guide';

		// : 글등록, 답변
		$arr = $nf_util->captcha_key_check();
		if(!$arr['msg']) {
			if(!$_POST['no']) {
				$_where = "";
				if(!admin_id) $_where .= $nf_shop->shop_where;
				$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`=".intval($_POST['pno']).$_where);
				if($_POST['qno'])
					$guide_row = $db->query_fetch("select * from ".$_table." where `pmno`=".intval($shop_row['mno'])." and `no`=".intval($_POST['qno'])); // : 답변쓸경우에 원글정보 가져오기

				if($_POST['qno']) {
					if($member['mb_type']=='company' && $guide_row) $allow = true; // : 답변 기업회원만
					else $alert_txt = '기업회원만 이용 가능합니다.';
				}
				if(!$_POST['qno']) {
					if(($member['mb_type']=='individual' && $shop_row) || $_POST['mode']=='qna_write' || admin_id) $allow = true; // : 글작성 개인회원 또는 관리자
					else $alert_txt = '개인회원만 이용 가능합니다.';
				}

			// : 수정
			} else {
				$mno_field = $member['mb_type']=='individual' ? 'mno' : 'pmno';
				$_where = "";
				if(!admin_id && !$_COOKIE['shop_qna_'.intval($_POST['no'])]) $_where .= " and `".$mno_field."`=".intval($member['no']);
				$guide_row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no']).$_where); // : 답변쓸경우에 원글정보 가져오기
				if($guide_row) $allow = true;
				if($_COOKIE['shop_qna_'.$guide_row['no']]) $allow = true;
				else $alert_txt = "수정권한이 없습니다.";
				$is_modify = true;
			}

			if($allow===false) {
				$arr['msg'] = $alert_txt;
				$arr['move'] = NFE_URL."/";
			} else {
				$arr['msg'] = "로그인하셔야 이용가능합니다.";
				$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
				if($member['no'] || $_POST['mode']=='qna_write') {
					if($_POST['no']) {
						$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`=".intval($_POST['pno']).$_where);
					} else {
						$cmem_row = $nf_member->get_member($shop_row['mno']);
					}

					$_val = array();
					// : 답변이 아닌경우
					if(!$_POST['qno']) {
						if(!$guide_row) { // : 새글등록시에 [ 수정시에는 실행하지 않습니다. ]
							$_val['pno'] = $_POST['pno'];
							$_val['view'] = $env['use_shop_guide_open'] && $_POST['mode']=='guide_write' ? 1 : 0; // : 이용후기 바로출력
							$_val['qpno'] = $_POST['qno'];
							$_val['mno'] = $member['no'];
							$_val['pmno'] = $cmem_row['no'];
							$_val['wr_id'] = $member['mb_id'];
							$_val['c_id'] = $cmem_row['mb_id'];
							$_val['name'] = isset($_POST['wr_name']) ? $_POST['wr_name'] : $member['mb_name'];
							$_val['c_name'] = $cmem_row['mb_name'];
							$_val['nick'] = isset($_POST['wr_name']) ? $_POST['wr_name'] : $member['mb_nick'];
							$_val['c_nick'] = $cmem_row['mb_nick'];
							$_val['passwd'] = md5($_POST['wr_password']);
							$_val['phone'] = isset($_POST['wr_phone'][0]) ? implode("-", $_POST['wr_phone']) : $member['mb_hphone'];
							$_val['email'] = isset($_POST['wr_email'][0]) ? implode("@", $_POST['wr_email']) : $member['mb_email'];
							$_val['rdate'] = today_time;
						} else if(!$member['no']) {
							$_val['name'] = $_POST['wr_name'];
							$_val['nick'] = $_POST['wr_name'];
							$_val['passwd'] = md5($_POST['wr_password']);
							$_val['phone'] = implode("-", $_POST['wr_phone']);
							$_val['email'] = implode("@", $_POST['wr_email']);
						}
						if($db->is_field($_table, 'point')) $_val['point'] = $_POST['point'];
						$_val['secret'] = isset($_POST['wr_secret']) ? intval($_POST['wr_secret']) : 0;
						$_val['subject'] = $_POST['subject'];
						$_val['content'] = $_POST['content'];
					}
					// : 답변인경우, 답변수정인경우
					if($_POST['qno'] && $guide_row) {
						$_val['a_content'] = $_POST['content'];
						$_val['answer'] = 1;
						$_val['adate'] = today_time;
					}
					$_val['udate'] = today_time;
					$q = $db->query_q($_val);
					if($is_modify || $_POST['qno']) {
						$db->_query("update ".$_table." set ".$q." where `no`=".intval($guide_row['no']), $_val);

						// : 평점값, 후기수 재조정
						if(in_array($_POST['mode'], array('guide_write'))) {
							$nf_shop->save_guide_point_cnt($guide_row['pno']);
						}
					} else {
						$db->_query("insert into ".$_table." set ".$q, $_val);

						// : 평점값, 후기수 재조정
						if(in_array($_POST['mode'], array('guide_write'))) {
							$nf_shop->save_guide_point_cnt($_POST['pno']);
						}
					}

					if($_POST['qno']) $arr['msg'] = ($guide_row['answer'] ? '답변수정' : '답변')."이 완료되었습니다.";
					else $arr['msg'] = ($is_modify ? '수정' : '등록')."이 완료되었습니다.";

					if(strpos($nf_util->page_back(), "/admini/")!==false) $arr['move'] = $nf_util->sess_page("admin_shop_view");
					else $arr['move'] = $nf_util->sess_page("shop_view");
				}
			}
		}
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "select_coupon_use":
	case "select_coupon_use_not":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/member/login.php?url=".$nf_util->page_back();
		if($member['mb_type']!='company') {
			$arr['msg'] = "기업회원만 가능합니다.";
			$arr['move'] = $nf_util->page_back();
		} else if($member['mb_id']) {
			$use_val = $_POST['mode']=='select_coupon_use' ? 1 : 0;
			$use_txt = $use_val ? '사용' : '미사용';
			$nos = implode(",", $_POST['chk']);
			$query = $db->_query("select * from nf_coupon_use where `pmno`=".intval($member['no'])." and `no` in (".$nos.") order by `no` desc");
			$use_val_flag = $use_val ? '+' : '-'; // : 사용이면 더하기, 미사용이면 차감

			$coupon_remain_int = array();
			$ch_use_int = 0;
			while($row=$db->afetch($query)) {
				$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($row['pno']));
				if(!isset($coupon_remain_int[$row['pno']])) $coupon_remain_int[$row['pno']] = intval($shop_row['coupon_allow_int'])-intval($shop_row['coupon_use_int']); // : 사용가능건수-사용건수 값

				// : 남은건수로 체크하기
				if($coupon_remain_int[$row['pno']]>0) {
					$update = $db->_query("update nf_shop set `coupon_use_int`=`coupon_use_int`".$use_val_flag."1 where `no`=".intval($row['pno']));
					$db->_query("update nf_coupon_use set `use`=".intval($use_val).", use_date=? where `pmno`=".intval($member['no'])." and `no`=".intval($row['no']), array(today_time));
					if($use_val) $coupon_remain_int[$row['pno']]--; // : 사용으로 체크할때에만 실행합니다.
					$ch_use_int++;
				}
			}
			$arr['msg'] = $use_txt."으로 설정했습니다.";
			if($ch_use_int<=0) $arr['msg'] = "현재 쿠폰은 사용건수를 모두 소진한 쿠폰입니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	case "delete_select_coupon":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = "/m/member/login.php?url=".$nf_util->page_back();
		if($member['mb_id']) {
			$m_field = $member['mb_type']=='company' ? 'pmno' : 'mno';
			$nos = implode(",", $_POST['chk']);
			$delete = $db->_query("delete from nf_coupon_use where `".$m_field."`=".intval($member['no'])." and `no` in (".$nos.")");
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	case "get_coupon":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".$nf_util->page_back();
		if($member['mb_id']) {
			$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`='".addslashes($_POST['no'])."'".$nf_shop->shop_where);
			$m_row = $db->query_fetch("select * from nf_member where `mb_id`='".$shop_row['wr_id']."'");

			$coupon_chk = $nf_shop->coupon_down_allow($_POST['no']);

			if($member['mb_type']=='company') {
				$arr['msg'] = "업체회원은 쿠폰다운이 불가능합니다.";
				$arr['move'] = "";
			} else  if(!$shop_row) {
				$arr['msg'] = "쿠폰이 없습니다.";
				$arr['move'] = "";
			} else if($shop_row['coupon_use_int']>=$shop_row['coupon_allow_int']) {
				$arr['msg'] = "쿠폰이 매진됬습니다.";
				$arr['move'] = "";
			} else if($coupon_chk['row'] && $shop_row['coupon_limit']!='one_mu') {
				$arr['msg'] = $coupon_chk['msg'];
				$arr['move'] = "";
			} else {
				$arr['msg'] = "쿠폰이 발송되었습니다.";
				$arr['move'] = "";

				$_val = array();
				$_val['mno'] = $member['no'];
				$_val['pmno'] = $m_row['no'];
				$_val['wr_id'] = $member['mb_id'];
				$_val['c_id'] = $m_row['mb_id'];
				$_val['name'] = $member['mb_name'];
				$_val['c_name'] = $nf_member->member_arr[$shop_row['mno']]['mb_name'];
				$_val['nick'] = $member['mb_nick'];
				$_val['c_nick'] = $nf_member->member_arr[$shop_row['mno']]['mb_nick'];
				$_val['code'] = 'shop';
				$_val['pno'] = $_POST['no'];
				$_val['rdate'] = today_time;
				$_val['number'] = $_SESSION['_coupon_number_'.$shop_row['no']];
				$q = $db->query_q($_val);
				$db->_query("insert into nf_coupon_use set ".$q, $_val);

				// : 다운로드수
				$db->_query("update nf_shop set coupon_down_int=coupon_down_int+1 where `no`=".intval($_POST['no']));
			}
		}
		die(json_encode($arr));
		exit;
	break;

	case "jump_process":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = "";
			$arr['js'] = 'if(confirm("점프건수가 없습니다. 점프를 구매하시겠습니까?")) location.href="'.NFE_URL.'/service/product_payment.php?code=shop&no='.intval($_POST['no']).'#shop_jump";';
			$_table = 'nf_'.$_POST['code'];
			$info_row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no']));
			if($info_row['wr_service_jump_int']>0) {
				$arr['js'] = '';
				$arr['msg'] = "정상적인 방식으로 이용해주시기 바랍니다.";
				if(array_key_exists($_POST['code'], $nf_shop->kind_of)) {
					$arr['msg'] = "삭제된 ".$nf_shop->kind_of[$_POST['code']]."정보입니다.";
					$info_row = $db->query_fetch("select * from ".$_table." where `no`=? and `mno`=?", array($_POST['no'], $member['no']));
					switch($_POST['code']) {
						case 'shop':
							$max_row = $db->query_fetch("select * from ".$_table." as ne where 1 order by `wr_jdate` desc limit 1");
						break;
					}
					if($max_row['no']==$_POST['no']) $arr['msg'] = "이미 최상단에 노출되고 있습니다.";
					else {
						if($info_row) {
							if(!$info_row['is_wait']) {
								$arr['msg'] = "등록대기 상품은 점프를 할 수 없습니다.";
							} else if($info_row['wr_report']<0) {
								$arr['msg'] = "신고된 상품은 점프를 할 수 없습니다.";
							} else {
								$jump_field = "wr_service_jump_int";
								$update = $db->_query("update ".$_table." set `wr_jdate`=? where `no`=?", array(today_time, $_POST['no']));
								if($update) {
									$update = $db->_query("update ".$_table." set `".$jump_field."`=`".$jump_field."`-1 where `no`=?", array($info_row['no']));

									$_arr['mno'] = $member['no'];
									$_arr['wr_id'] = $member['mb_id'];
									$_arr['c'] = 1;
									$_arr['nos'] = $_POST['no'];
									$_arr['action'] = 'click';
									job_jump_insert($_POST['code'], $_arr);
									$arr['js'] = '
									$(".'.$_POST['code'].'_jump_int-'.$info_row['no'].'-").html("'.number_format(intval($info_row[$jump_field])-1).'");
									';
								}
								$arr['msg'] = $nf_shop->kind_of[$_POST['code']]."정보를 점프했습니다.";
							}
						}
					}
				}
			}
		}
		die(json_encode($arr));
	break;
	
	
	case "shop_map_gps":
		include NFE_PATH."/data/map/map_shop_latlng.php";
		foreach($pos_arr as $k=>$v) {
			$arr['positions'][] = array('no'=>$k, 'lat'=>$v['lat'], 'lng'=>$v['lng'], 'photo'=>$v['photo'], 'wr_company'=>stripslashes($v['wr_company']), 'addr'=>stripslashes($v['addr']));
		}
		die(json_encode($arr));
	break;


	case "get_map_shop":
		$_data = $_POST;
		$_data['alias'] = 'ne.';
		$_data['field_lat'] = 'wr_lat0';
		$_data['field_lng'] = 'wr_lng0';
		$distance_field = $nf_util->distance_q($_data);
		$_data['type'] = 'where';
		$_data['distance'] = $nf_util->distance_int($_POST['width'], $_POST['zoom'])/2;
		$distance_w = $nf_util->distance_q($_data);

		$where_arr = $nf_search->shop();
		$service_where = $nf_search->service_where('shop');
		$_where = $where_arr['where'];
		$where_basic = "(".$service_where['where'].")".$nf_shop->shop_where;

		$arr = array();
		$arr['service_k'] = '1_list';
		//$arr['where'] = $where_basic.$shop_where['where'];
		if($env['map_engine']!='google') $arr['where'] .= $distance_w;
		$arr['order'] = " order by map_distance asc";
		$arr['page'] = $_POST['page'];
		$arr['limit'] = 4;

		$start = ($_POST['page']>0) ? $nf_util->start_page($_POST['page'], $arr) : 0;
		if(!$arr['field_set']) $arr['field_set'] = '*';
		$q = "nf_shop as ns where 1 ".$arr['where'].$nf_shop->shop_where;
		$q_all = "select *, ".$distance_field.$_field." from ".$q.$arr['order'];
		$query = $db->_query($q_all);
		$arr['q_all'] = $q_all;

		$total = $db->query_fetch("select count(*) as c from ".$q);

		$_arr = array();
		$_arr['tema'] = 'B';
		$_arr['num'] = $arr['limit'];
		$_arr['total'] = $total['c'];
		$_arr['click'] = 'js';
		$_arr['code'] = 'map';
		$_arr['page'] = $_POST['page'];
		$_arr['group'] = 5;
		$paging = $nf_util->_paging_($_arr);

		ob_start();
		$count = 0;
		while($shop_row=$db->afetch($query)) {
			$em_info = $nf_shop->shop_info($shop_row);
			ob_start();
			include NFE_PATH.'/include/job/shop.map.php';
			$_content = ob_get_clean();
			$arr['positions'][] = array('lat'=>$shop_row['wr_lat0'], 'lng'=>$shop_row['wr_lng0'], 'content'=>$_content);

			if($count>=$start && $count<($start+$arr['limit'])) {
				include NFE_PATH.'/include/job/shop_map.box.php';
			}
			$count++;
		}
		$arr['tag_list'] = ob_get_clean();
		if(!$arr['tag_list']) $arr['tag_list'] = '<p class="no_s_shop">주변에 검색된 업체정보가 없습니다.</p>';
		$arr['paging'] = $paging['paging'];

		$arr['js'] = '
		$(".map-list-").html(data.tag_list);
		$(".map-list-paging-").html(data.paging);
		nf_map.ajax_clusterer(data);
		';

		die(json_encode($arr));
	break;

	// : 업체정보 사진 업로드
	case "shop_upload":
		$arr['msg'] = "업체정보만이 이용 가능합니다.";
		$arr['move'] = !$member['no'] ? NFE_URL.'/member/login.php?url='.$nf_util->page_back() : $nf_util->page_back();
		if($member['mb_type']=='company' || admin_id) {
			$upload_allow = $nf_util->filesize_check($_FILES['upload']['size']);
			$dir = '/data/shop/tmp/';
			$arr['msg'] = $upload_allow;
			$arr['move'] = "";
			if(!$upload_allow) {
				$tmp = $_FILES['upload']['tmp_name'];
				$fname = $_FILES['upload']['name'];
				$accept_date = date("Ym");
				//$arr['msg'] = "이미지가 없습니다.";
				if($tmp) {
					$arr['msg'] = implode(", ", $nf_util->photo_ext)." 파일만 업로드 가능합니다.";
					$ext = $nf_util->get_ext($_FILES['upload']['name']);
					if(in_array($ext, $nf_util->photo_ext)) {
						$arr['msg'] = "";
						$arr['move'] = "";
						// WebP 변환 저장 (GD imagewebp 지원 시), 품질 82, 최대 1200px
						$out_ext   = function_exists('imagewebp') ? 'webp' : $ext;
						$file_name = $_POST['code'].'_'.time().'.'.$out_ext;
						$nf_util->make_thumbnail($tmp, NFE_PATH.$dir.$file_name, 1200, 1200, 82);

						switch($_POST['code']) {
							case "shop":
								$arr['js'] = '
								var photo_tag = "<span class=\"photo-item-\" style=\"position:relative;\"><input type=\"checkbox\" name=\"shop_photo_chk[]\" style=\"position:absolute;top:0px;right:8px;\" /><input type=\"hidden\" name=\"shop_photo[]\" value=\"'.$file_name.'\" /><img src=\"'.NFE_URL.$dir.$file_name.'\" alt=\"업체이미지\" onClick=\"nf_shop.click_photo(this)\"></span>";
								$(".shop-photo-paste-").append(photo_tag);
								$(".shop-photo-paste-").find(".not-image-").css({"display":"none"});
								$(".shop-photo-paste-").find(".check_photo-").val(1);
								';
							break;

							default:
								$arr['js'] = '
								var photo_tag = "<span class=\"photo-item-\" style=\"position:relative;\"><input type=\"hidden\" name=\"photo_photo[]\" value=\"'.$file_name.'\" /><img src=\"'.NFE_URL.$dir.$file_name.'\" alt=\"대표이미지\"></span>";
								$(".photo-photo-paste-").html(photo_tag);
								$(".photo-photo-paste-").find(".check_photo-").val(1);
								';
							break;
						}
					}
				}
			}
		}
		die(json_encode($arr));
	break;

	// : 업체정보 등록
	case "shop_write":

		$arr['msg'] = "로그인해야 이용 가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?url='.$nf_util->page_back();
		if($member['no'] && $member['wr_type']!='company') {
			$arr['msg'] = "업체회원만 이용 가능합니다.";
			$arr['move'] = $nf_util->page_back();
		}
		if($member['no'] || admin_id) {
			$_where = "";
			if(strpos($nf_util->page_back(), '/admini/')===false) $_where .= " and `mno`=".intval($member['no']);
			$shop_row = $db->query_fetch("select * from nf_shop where `no`=?".$_where, array($_POST['no']));
			if(strpos($nf_util->page_back(), "/admini/")!==false) $shop_row = $db->query_fetch("select * from nf_shop where `no`=?", array($_POST['no']));
			$is_modify = !$shop_row || $_POST['info_no'] || $_POST['code']=='copy' ? false : true;
			$photo_arr = explode(",", $shop_row['wr_photo']);
			$main_photo_arr = explode("/", $shop_row['wr_main_photo']);

			$wr_id = $_POST['wr_id'] && strpos($_SERVER['HTTP_REFERER'], '/admini/')!==false ? $_POST['wr_id'] : $member['mb_id'];
			if($shop_row['no']) $wr_id = $shop_row['wr_id'];
			$input_member = $db->query_fetch("select * from nf_member where `mb_id`='".addslashes($wr_id)."'");
			if(!$wr_id) $wr_id = '_admin_';

			$category_arr = array();
			if(is_array($_POST['category'])) { foreach($_POST['category'] as $k=>$v) {
				$v_arr = array_diff($v, array(""));
				$category_arr[] = ",".implode(",", $v_arr).",";
			} }

			$price_info = array(
				'title'=>$_POST['price_title_inp'],
				'head'=>$_POST['price_head_inp'],
				'subject'=>$_POST['price_subject_inp'],
				'sub'=>$_POST['price_sub_inp'],
				'price_use'=>$_POST['price_use_inp'],
				'ori_price'=>$_POST['price_ori_inp'],
				'sale'=>$_POST['price_sale_inp'],
				'price'=>$_POST['price_price_inp'],
			);

			$sns_info = array('name'=>$_POST['sns_name'], 'address'=>$_POST['sns_address']);

			// : 사진정보
			$shop_date = $shop_row['wr_rdate'];
			$dir_arr = $nf_util->get_dir_date("shop", $shop_date);
			$dir_txt = $dir_arr['dir'].$dir_arr['date'];

			// 1일지난 tmp파일 자동삭제
			//$auto_delete_dir = NFE_PATH.$dir_arr['dir'].'tmp/';
			//$delete_fime = `find {$auto_delete_dir} -mtime +1 -exec rm -f {} \;`;

			// : 업체이미지 업로드
			$wr_photo = array();
			if(is_array($_POST['shop_photo'])) { foreach($_POST['shop_photo'] as $k=>$v) {
				if(is_file(NFE_PATH.$dir_arr['dir'].'tmp/'.$v)) {
					$wr_photo[] = $dir_arr['date'].'/'.$v;
					if(!is_file(NFE_PATH.$dir_txt.'/'.$v)) {
						copy(NFE_PATH.$dir_arr['dir'].'tmp/'.$v, NFE_PATH.$dir_txt.'/'.$v);
					}
					if(is_file(NFE_PATH.$dir_txt.'/'.$v) && is_file(NFE_PATH.$dir_arr['dir'].'tmp/'.$v)) unlink(NFE_PATH.$dir_arr['dir'].'tmp/'.$v);

				// : 관리자에서 수정인경우
				} else if($_POST['no'] && !$_POST['info_no'] && is_file(NFE_PATH.$dir_arr['dir'].$dir_arr['date'].'/'.$v)) {
					$wr_photo[] = $dir_arr['date'].'/'.$v;
				} else if($_POST['info_no']) {
					$wr_logo_ext = $nf_util->get_ext($v);
					$wr_logo_val = $dir_arr['date'].'/shop'.$k.'_'.time().'.'.$wr_logo_ext;
					if(copy(NFE_PATH.$dir_txt.'/'.$v, NFE_PATH.$dir_arr['dir'].$wr_logo_val)) {
						$wr_photo[] = $wr_logo_val;
					}
				}
			} }
			// : 삭제한 이미지 삭제하기
			if(!$_POST['info_no'] && is_array($photo_arr)) { foreach($photo_arr as $k=>$v) {
				if(!in_array($v, $wr_photo) && is_file(NFE_PATH.$dir_arr['dir'].'/'.$v))
					unlink(NFE_PATH.$dir_arr['dir'].'/'.$v);
			} }

			// : 대표이미지 업로드
			if($_POST['photo_photo']) {
				$main_photo = $dir_arr['date'].'/'.$_POST['photo_photo'][0];
				if(is_file(NFE_PATH.$dir_arr['dir'].'tmp/'.$_POST['photo_photo'][0])) {
					copy(NFE_PATH.$dir_arr['dir'].'tmp/'.$_POST['photo_photo'][0], NFE_PATH.$dir_txt.'/'.$_POST['photo_photo'][0]);
					unlink(NFE_PATH.$dir_arr['dir'].'tmp/'.$_POST['photo_photo'][0]);
				} else if($_POST['info_no']) {
					if(is_file(NFE_PATH.$dir_arr['dir'].$shop_row['wr_main_photo'])) {
						$wr_logo_ext = $nf_util->get_ext($shop_row['wr_main_photo']);
						$main_photo = $dir_arr['date'].'/photo_'.time().'.'.$wr_logo_ext;
						copy(NFE_PATH.$dir_arr['dir'].$shop_row['wr_main_photo'], NFE_PATH.$dir_arr['dir'].$main_photo);
					}
				}
			}
			// : 대표이미지 삭제하기
			if(!$_POST['info_no'] && $_POST['photo_photo'][0]!=$main_photo_arr[1] && is_file(NFE_PATH.$dir_arr['dir'].'/'.$shop_row['wr_main_photo']))
				unlink(NFE_PATH.$dir_arr['dir'].'/'.$shop_row['wr_main_photo']);

			$_val = array();
			$_val['mno'] = $input_member['no'];
			$_val['wr_id'] = $wr_id;
			$_val['wr_name'] = $wr_id!='_admin_' ? $input_member['mb_name'] : '';

			if($_POST['info_no']) {
				$_val['mno'] = $shop_row['mno'];
				$_val['wr_id'] = $shop_row['wr_id'];
				$_val['wr_name'] = $shop_row['wr_name'];
			}

			$_val['wr_icon'] = @implode(",", $_POST['wr_icon']);
			$_val['wr_subject'] = $_POST['subject'];
			$_val['wr_company'] = $_POST['company_name'];
			$_val['wr_area'] = @implode(",", $_POST['wr_area']).",";
			$_val['wr_category'] = implode("\r\n", $category_arr);
			$_val['wr_tema'] = @implode(",", $_POST['tema_category']).",";
			$_val['wr_main_photo'] = $main_photo;
			$_val['wr_photo'] = @implode(",", $wr_photo);
			$_val['wr_address'] = @implode("||", $_POST['address']);
			$_val['wr_jibun'] = $_POST['jibun_address'];
			$_val['wr_doro'] = $_POST['doro_post'];
			$_val['wr_lat'] = $_POST['map_int'][0];
			$_val['wr_lng'] = $_POST['map_int'][1];
			$_val['wr_load'] = serialize($_POST['road_int']);
			$_val['wr_phone'] = implode("-", $_POST['phone']);
			$_val['wr_hphone'] = implode("-", $_POST['hphone']);
			$_val['wr_sale'] = $_POST['sale'];
			$_val['wr_price1'] = $_POST['price1'];
			$_val['wr_price'] = $_POST['price'];
			$_val['wr_content'] = $_POST['content'];
			$_val['wr_m_content'] = $_POST['m_content'];
			$_val['wr_event'] = $_POST['event'];
			$_val['wr_notice'] = $_POST['notice'];
			if(!$is_modify) $_val['is_wait'] = 1;
			$_val['wr_movie'] = $_POST['movie'];
			if(!$is_modify) {
				$_val['wr_rdate'] = today_time;
				$_val['wr_jdate'] = today_time;
			}
			$_val['wr_udate'] = today_time;
			$_val['price_info'] = 'a:8:{s:5:"title";a:1:{i:0;s:0:"";}s:4:"head";a:1:{i:0;s:0:"";}s:7:"subject";a:1:{i:0;a:1:{i:0;s:0:"";}}s:3:"sub";a:1:{i:0;a:1:{i:0;s:0:"";}}s:9:"price_use";a:1:{i:0;a:1:{s:3:"k_0";a:1:{i:0;s:1:"1";}}}s:9:"ori_price";a:1:{i:0;a:1:{s:3:"k_0";a:1:{i:0;s:0:"";}}}s:4:"sale";a:1:{i:0;a:1:{s:3:"k_0";a:1:{i:0;s:0:"";}}}s:5:"price";a:1:{i:0;a:1:{s:3:"k_0";a:1:{i:0;s:0:"";}}}}';
			$_val['sns_info'] = serialize($sns_info);
			$_val['coupon_use'] = $_POST['coupon_use'];
			$_val['coupon_price'] = $_POST['coupon_price'];
			$_val['coupon_allow_int'] = $_POST['coupon_allow_int'];
			$_val['coupon_date1'] = $_POST['coupon_date1'];
			$_val['coupon_date2'] = $_POST['coupon_date2'];
			$_val['coupon_subject'] = $_POST['coupon_subject'];
			$_val['coupon_content'] = $_POST['coupon_content'];
			$_val['coupon_limit'] = $_POST['coupon_limit'];
			$_val['time1'] = $_POST['time1_apm']=='pm' ? ($_POST['time1']+12).':'.$_POST['time1_m'] : $_POST['time1'].':'.$_POST['time2_m'];
			$_val['time2'] = $_POST['time2_apm']=='pm' ? ($_POST['time2']+12).':'.$_POST['time2_m'] : $_POST['time2'].':'.$_POST['time2_m'];
			$_val['time_full'] = $_POST['full_time'];
			$_val['time_text'] = $_POST['time_text'];

			// : 첫 등록일때에만 결제페이지사용여부가 미사용인경우 무료로 주기
			$service_insert_use = false;
			if(!$is_modify && !$env['service_shop_use']) {
				// : 유료서비스 사용안하는경우
				$service_free_query = $db->_query("select * from nf_service where `code`='shop' and is_charge=0");
				while($service_row=$db->afetch($service_free_query)) {
					if(!array_key_exists($service_row['type'], $nf_shop->service_charge_arr['shop'])) continue; // : 채용정보 기간서비스들만 실행
					$free_date_arr = explode(" ", $service_row['free_date']);
					if($free_date_arr[0] && $free_date_arr[1]) {
						$free_date = date("Y-m-d", strtotime($service_row['free_date']));
						$_val['wr_service'.$service_row['type']] = $free_date;
						$service_insert_use = true;
					}
				}
			}

			$q = $db->query_q($_val);

			if($is_modify) {
				$query = $db->_query("update nf_shop set ".$q." where `no`=".intval($shop_row['no']), $_val);
				$tno = $shop_row['no'];
			} else {
				$query = $db->_query("insert into nf_shop set ".$q, $_val);
				if($query) {
					$tno = $db->last_id();

					$sms_arr = array();
					$sms_arr['phone'] = $member['mb_hphone'];
					$sms_arr['name'] = $member['mb_name'];
					$sms_arr['company_name'] = stripslashes($_val['wr_company']);
					$nf_sms->send_sms_shop_write($sms_arr);
				}
			}

			switch(!$is_modify) {
				case true:
					$arr['msg'] = "등록이 완료되었습니다.";
					$arr['move'] = NFE_URL.'/service/product_payment.php?code=shop&no='.$tno;
					if(!$env['service_shop_use']) $arr['move'] = NFE_URL.'/shop/index.php?no='.$tno;
					if(strpos($nf_util->page_back(), '/admini/')!==false) $arr['move'] = $nf_util->sess_page("admin_shop");
				break;

				default:
					$arr['msg'] = "수정이 완료되었습니다.";
					$arr['move'] = $nf_util->page_back();
				break;
			}
			
			$query = $db->_query("select * from nf_shop where wr_lat!='' and wr_lng!=''");
			$pos_arr = array();
			while($product_row=$db->afetch($query)) {
				$address_arr = explode("||", $product_row['wr_address']);
				$pos_arr[$product_row['no']] = array('lat'=>$product_row['wr_lat'], 'lng'=>$product_row['wr_lng'], 'photo'=>$product_row['wr_main_photo'], 'wr_company'=>$product_row['wr_company'], 'addr'=>implode(" ", $address_arr));
			}
			
			// : 파일생성하기
			ob_start();
			var_export($pos_arr);
			$cache_var = strtr(ob_get_clean(), array("\n"=>""));
			file_put_contents(NFE_PATH.'/data/map/map_shop_latlng.php', '<?php'.chr(10).'$pos_arr = '.$cache_var.' ?>');
			
		}
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	//################## 삭제 ##################//
	case "delete_shop":
	case "delete_select_shop":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no'] || admin_id) {
			$arr['msg'] = "삭제할 이력서가 없습니다.";
			$nos = $_POST['chk'][0] ? implode(",", $_POST['chk']) : $_POST['no'];
			$_where = "";
			if(!admin_id) $_where .= " and `mno`=".intval($member['no']);
			$query = $db->_query("select * from nf_shop where `no` in (".$nos.") ".$_where);
			while($row=$db->afetch($query)) {
				// : 완전삭제 [ delete ]
				if($row['is_delete']) {
					$photo_arr = explode(",", $row['wr_photo']);
					if(is_array($photo_arr)) { foreach($photo_arr as $k=>$v) {
						if(is_file(NFE_PATH.'/data/shop/'.$row['wr_main_photo'])) unlink(NFE_PATH.'/data/shop/'.$row['wr_main_photo']);
						if(is_file(NFE_PATH.'/data/shop/'.$v)) unlink(NFE_PATH.'/data/shop/'.$v);
					} }
					$delete = $db->_query("delete from nf_shop where `no`=".intval($row['no']));

				// : 그냥 삭제 [ update ]
				} else {
					$delete = $db->_query("update nf_shop set `is_delete`=1 where `no`=".intval($row['no']));
				}
			}
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	case "delete_select_scrap":
	case "delete_scrap":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no'] || admin_id) {
			$nos = $_POST['mode']=='delete_scrap' ? $_POST['no'] : implode(",", $_POST['chk']);
			$arr['msg'] = "이미 삭제된 정보입니다.";
			$arr['move'] = $nf_util->page_back();
			if($nos) {
				$_where = " and `mno`=".intval($member['no']);
				if(admin_id && strpos($nf_util->page_back(), "/admini/")!==false) $_where = "";
				$delete = $db->_query("delete from nf_scrap where `no` in (".$nos.") and `code`=?".$_where, array($_POST['code']));
				$arr['msg'] = "삭제가 완료되었습니다.";
			}
		}
		die(json_encode($arr));
	break;

	case "delete_select_read":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no'] || admin_id) {
			$nos = implode(",", $_POST['chk']);
			$arr['msg'] = "이미 삭제된 정보입니다.";
			$arr['move'] = $nf_util->page_back();
			if($nos) {
				$query = $db->_query("select * from nf_read where `no` in (".$nos.") and `mno`=? and `code`=?", array($member['no'], $_POST['code']));
				while($row=$db->afetch($query)) {
					$delete = $db->_query("delete from nf_read where `pno`=? and `mno`=? and `code`=?", array($row['pno'], $row['mno'], $row['code']));
				}
				$arr['msg'] = "삭제가 완료되었습니다.";
			}
		}
		die(json_encode($arr));
	break;
	//################## 삭제 ##################//

	## : 구인구직정보 ############################################

	




	## : 기본 정보 ############################################
	case "click_tab_login":
		switch($_POST['mb_type']) {
			case "individual":
				$arr['js'] = '
				var form = document.forms["flogin"];
				$(form).find("[name=\'mid\']").val("indi01");
				$(form).find("[name=\'passwd\']").val("indi01");
				';
			break;

			default:
				$arr['js'] = '
				var form = document.forms["flogin"];
				$(form).find("[name=\'mid\']").val("company1");
				$(form).find("[name=\'passwd\']").val("company1");
				';
			break;
		}
		die(json_encode($arr));
	break;

	// : 휴면해제
	case "ch_dormancy":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$update = $db->_query("update nf_member set `is_dormancy`=0 where `no`=".intval($member['no']));
			$arr['msg'] = "휴면상태를 해제했습니다.";
			$arr['move'] = NFE_URL.'/';
		}
		die(json_encode($arr));
	break;

	// : 로그인
	case "login_process":
		$arr = array();
		$_login_plain_pw = $_POST['passwd'];
		$_login_id       = trim($_POST['mid']);
		$_login_type     = $_POST['kind'];

		// BCRYPT 는 DB에서 해시 비교 불가 → ID 로만 조회 후 PHP에서 검증
		$mem_row = null;
		if ($_login_type) {
			$mem_row = $db->query_fetch(
				"select * from nf_member where `mb_id`=? and `mb_type`=? and `mb_left_request`=0 and `mb_left`=0 and `is_delete`=0",
				array($_login_id, $_login_type)
			);
		}
		if (!$mem_row) {
			// 회원종류 무관 조회 (탭 무관 로그인 허용)
			$mem_row = $db->query_fetch(
				"select * from nf_member where `mb_id`=? and `mb_left_request`=0 and `mb_left`=0 and `is_delete`=0",
				array($_login_id)
			);
		}
		// 비밀번호 검증 (BCRYPT 우선 → MD5 레거시 자동 마이그레이션)
		if ($mem_row && !nf_verify_password($_login_plain_pw, $mem_row['mb_password'], $mem_row['no'], $db)) {
			$mem_row = null;
		}
		if(!$mem_row) {
			$arr['msg'] = "등록된 아이디를 찾을 수 없습니다.\\n아이디를 확인해주세요.";
		}
		if($mem_row['mb_badness']) {
			$arr['msg'] = "고객님은 임시적으로 로그인을 할수 없습니다.\\n자세한 사항은 관리자에게 문의 해주세요.";
		}

		$arr['move'] = $nf_util->page_back();
		if(!$arr['msg']) {
			if($_POST['save_id']) {
				$save_id = $nf_util->Encrypt($mem_row['mb_id']);
				$nf_util->cookie_save("save_id", $save_id, "yes", "1 month");
			}
			$nf_member->login($mem_row['no']);
			$arr['move'] = urldecode($_POST['url']);
			if(strpos($arr['move'], '/member/login.php')!==false || strpos($arr['move'], '/regist.php')!==false) $arr['move'] = main_page;
		}
		if($env['login_return']==='1') $arr['move'] = main_page;
		if($env['login_return']==='2') $arr['move'] = stripslashes($env['login_return_page']);
		if($mem_row['is_dormancy']) $arr['move'] = NFE_URL.'/member/login_sleep.php';

		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;


	############### 결제관련 ################
	// : 가격 가져오기
	case "click_service":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = "";

			$use_point = intval(strtr($_POST['use_point'], array(','=>'')));
			if($use_point>0) {
				if($member['mb_point']<$use_point) {
					$arr['msg'] = "포인트는 ".number_format(intval($member['mb_point']))."p까지 사용 가능합니다.";
					$arr['js'] = '
					form.use_point.value = "";
					nf_payment.click_service_func();
					';
				}
			}

			if(!$arr['msg']) {
				$arr['price_hap'] = 0;
				$post_service = $_POST['service'];
				$post_arr = $_POST;
				$load_db_price = true;
				ob_start();
				include NFE_PATH.'/include/payment/service.inc.php';
				$arr['tag'] = ob_get_clean();

				$price_result = $arr['price_hap']-$use_point;
				if($use_point>$arr['price_hap']) $price_result = 0;

				$arr['js'] = '
				$(".click_service_list-").html(data.tag);
				if($(".price-hap-")[0]) $(".price-hap-").html("'.number_format(intval($arr['price_hap'])).'");
				if($(".price-result-")[0]) $(".price-result-").html("'.number_format(intval($price_result)).'");
				';

				if($use_point>$arr['price_hap']) {
					$arr['js'] = '
					form.use_point.value = "'.number_format(intval($arr['price_hap'])).'";
					if($(".price-result-")[0]) $(".price-result-").html('.number_format(intval($price_result)).');
					';
				}

				$pay_method_tag = $price_result<=0 ? 'none' : 'block';
				$arr['js'] .= '
					$(".pay-method-p").each(function(){
						var tagname = $(this)[0].tagName;
						var display = "'.$pay_method_tag.'";
						if(display=="none") $(this).css({"display":"none"});
						else {
							if(tagname=="TR") display = "table-row";
							if(tagname=="TABLE") display = "table";
							$(this).css({"display":display});
						}
					});
				';
			}
		}
		die(json_encode($arr));
	break;

	case "payment_start":
		$arr['msg'] = "로그인하셔야 이용 가능합니다.";
		$arr['move'] = NFE_URL."/member/login.php?url=".urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = "";

			// : 무통장입금시 sms
			if($_POST['pay_methods']=='bank') {
				$sms_arr = array();
				$sms_arr['phone'] = $member['mb_hphone'];
				$sms_arr['name'] = $member['mb_name'];
				$nf_sms->send_sms_online_pay_process($sms_arr);
			}

			// : 포인트체크
			$use_point = intval(strtr($_POST['use_point'], array(','=>'')));
			if($use_point>0) {
				if($member['mb_point']<$use_point) {
					$arr['msg'] = "포인트는 ".number_format(intval($member['mb_point']))."p까지 사용 가능합니다.";
					$arr['js'] = '
					form.use_point.value = "";
					nf_payment.click_service_func();
					';
					die(json_encode($arr));
				}
			}

			$price_hap = 0;
			$service_arr = array();

			if(!$_POST['code']) $_POST['code'] = 'shop';

			switch($_POST['code']) {
				case "shop":
				case "jump":
					if(is_array($_POST['service'])) { foreach($_POST['service'] as $k=>$v) {
						if(is_array($v)) { foreach($v as $k2=>$v2) {
							switch($k2) {
								case "package":
									$price_row = $db->query_fetch("select * from nf_service_package where `wr_type`=? and `wr_use`=1 and `no`=?", array($k, $v2));
									$price_hap += $price_row['wr_price'];
									$service_k = $k.'_package';
									$price_arr[$service_k] = $price_row;
									$service_arr[$service_k] = $service_k;
								break;

								default:
									$price_row = $db->query_fetch("select * from nf_service_price where `code`=? and `type`=? and `no`=?", array($k, $k2, $v2));
									$price_hap += $nf_util->get_sale($price_row['service_percent'], $price_row['service_price']);
									$service_k = $price_row['code'].'_'.$price_row['type'];
									$price_arr[$service_k] = $price_row;
									$service_arr[$service_k] = $service_k;
								break;
							}
						} }
					} }
				break;


				case "direct":
					$price_hap = intval(strtr($_POST['price'], array(','=>'')));
					$service_arr[] = $_POST['content'];
					$price_arr = array();
				break;
			}

			// : 결제금액보다 포인트가 더 높은경우는 결제금액만큼
			if($use_point>$price_hap) $use_point = $price_hap;
			$price_result = $price_hap-$use_point;

			// : 주문번호 초기화 - 결제하기 버튼누를때 생성합니다.
			$pay_oid = $_POST['code'].'_'.$_POST['no'].'_'.time();

			$pay_row = $db->query_fetch("select * from nf_payment where `pay_oid`=? and pay_mno=?", array($pay_oid, $member['no']));

			$_val = array();
			$_val['pay_no'] = $_POST['no'];
			$_val['pay_oid'] = $pay_oid;
			$_val['pay_type'] = $_POST['code'];
			$_val['pay_pg'] = $nf_payment->pg;
			$_val['pay_method'] = $_POST['pay_methods'];
			$_val['pay_mno'] = $member['no'];
			$_val['pay_uid'] = $member['mb_id'];
			$_val['pay_name'] = $member['mb_name'];
			$_val['pay_phone'] = $member['mb_hphone'] ? $member['mb_hphone'] : $member['mb_phone'];
			$_val['pay_email'] = $member['mb_email'];
			$_val['pay_price'] = $price_hap;
			$_val['pay_dc'] = $use_point;
			$_val['pay_bank'] = $_POST['depositor'];
			$_val['pay_bank_name'] = $_POST['bank'];
			$_val['pay_wdate'] = today_time;
			$_val['pay_service'] = implode(",", $service_arr);
			$_val['tax_status'] = $_POST['tax_use'] ? 1 : 0;
			$_val['post_text'] = serialize($_POST);
			$_val['price_text'] = serialize($price_arr);
			$q = $db->query_q($_val);
			if($pay_row) $query = $db->_query("update nf_payment set ".$q." where `no`=".intval($pay_row['no']), $_val);
			else $query = $db->_query("insert into nf_payment set ".$q, $_val);

			$pay_no = $pay_row['no'] ? $pay_row['no'] : $db->last_id();

			// : 무료인경우 [ 포인트를 다 사용한 경우 ]
			if($price_result<=0) {
				include NFE_PATH.'/engine/function/payment.function.php';
				$pay_row = $db->query_fetch("select * from nf_payment where `no`=?", array($pay_no));
				payment_process($pay_row, 1);
			}

			// : 포인트를 사용하면서 무통장이거나 무료로 결제시
			if($use_point>0 && ($_POST['pay_methods']=='bank' || $price_result<=0)) {
				$point_arr = array();
				$point_arr['member'] = $member;
				$point_arr['code'] = $nf_shop->pay_service_arr[$_POST['code']].'서비스 결제 무통장 신청';
				$point_arr['use_point'] = -$use_point;
				$point_arr['rel_id'] = $pay_no;
				$point_arr['rel_action'] = $_POST['code'];
				$point_arr['rel_table'] = 'nf_payment';
				if($query) $nf_point->insert($point_arr);
			}

			// : 무통장이나 무료
			if($_POST['pay_methods']=='bank' || $price_result<=0) {
				$arr['move'] = NFE_URL."/service/payment_complete.php?no=".$pay_no;

			// : 결제
			} else {
				$nf_payment->pg_config();

				$pay_arr['price'] = $price_hap;
				$pay_arr['mb_name'] = $member['mb_name'];
				$pay_arr['mb_phone'] = $member['mb_hphone'] ? $member['mb_hphone'] : $member['mb_phone'];
				$pay_arr['mb_email'] = $member['mb_email'];
				$pay_arr['pno'] = $pay_no;
				$pay_arr['gname'] = $nf_shop->pay_service_arr[$_POST['code']];
				$pay_arr['pay_oid'] = $pay_oid;
				$arr = $nf_payment->pg_start($pay_arr);
			}
		}
		die(json_encode($arr));
	break;

	case "payment_process":
		$nf_payment->pg_config();
		$pno = $_POST['pno'];

		switch($nf_payment->pg) {
			case "toss":
				$pay_row = $db->query_fetch("select * from nf_payment where `pay_oid`=?", array($_POST['orderId']));
				$_POST['pno'] = $pay_no = $pay_row['no'];
			break;

			default:
				$pay_row = $db->query_fetch("select * from nf_payment where `no`=".intval($_POST['pno']));
			break;
		}

		// : 결제완료된 경우 처리하지 않게 하기
		if($pay_row['pay_status']) {
			die($nf_util->move_url("이미 결제된 정보입니다.", NFE_URL."/"));
		}

		$post_unse = $nf_util->get_unse($pay_row['post_text']);

		$pg_price = $_POST[$nf_payment->pg_price_arr[$nf_payment->pg]];

		if($pay_row['pay_price']!=$pg_price) {
			$arr['msg'] = "정상적인 방식으로 결제해주시기 바랍니다.";
			$arr['move'] = "/";
			if(is_mobile) $arr['move'] = "/m/";
		} else {
			include NFE_PATH.'/engine/function/payment.function.php';

			$pg_process = false;
			switch($nf_payment->pg) {
				case "kcp":
					$price = $pay_row['pay_price'];
					include NFE_PATH."/plugin/PG/kcp/kcp_api_pay.php";
				break;

				case "nicepay":
					$price = $pay_row['pay_price'];
					include NFE_PATH."/plugin/PG/nicepay/payResult_utf.php";
				break;

				case "toss":
					$data = array(
						'paymentKey' => $_GET['paymentKey'],
						'orderId' => $_POST['orderId'],
						'amount' => intval($pay_row['pay_price']),
					);

					$headers = array(
						"Authorization: Basic dGVzdF9za196WExrS0V5cE5BcldtbzUwblgzbG1lYXhZRzVSOg==",
						"Content-Type: application/json",
					);

					$url = "https://api.tosspayments.com/v1/payments/confirm";
					$ch = curl_init();                                 //curl 초기화
					curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
					curl_setopt($ch, CURLOPT_HEADER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));       //POST data
					curl_setopt($ch, CURLOPT_POST, true);              //true시 post 전송 
					$response = curl_exec($ch);

					$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
					$header = substr($response, 0, $header_size);
					$body = substr($response, $header_size);
					$body_decode = json_decode($body, true);

					curl_close($ch);

					if($body_decode['mId']) {
						payment_process($pay_row, 1);
						$nf_payment->pg_process($pay_row, $body_decode);
						$pg_process = true;
					}
				break;
			}

			if($pg_process===true) {
				$arr['msg'] = "";
				$arr['move'] = NFE_URL."/service/payment_complete.php?no=".$_POST['pno'];
			} else {
				$arr['msg'] = "결제가 실패되었습니다.\\n관리자에게 문의해주시기 바랍니다.";
				$arr['move'] = NFE_URL."/";
			}
		}

		die($nf_util->move_url($arr['move'], $arr['msg']));

	break;
	############### 결제관련 ################

	case "cs_center_write":
		$recaptcha_allow = $nf_util->recaptcha_check();
		if(!$recaptcha_allow['success']) {
			die($nf_util->move_url($recaptcha_allow['move'], $recaptcha_allow['msg']));
		}

		// 회원이 아닐 경우 자동등록방지 확인
		$arr = $nf_util->captcha_key_check();
		if(!$arr['msg']) {
			$_val = array();
			$_val['wr_type'] = intval($_POST['type']);
			$_val['wr_cate'] = $_POST['wr_cate'];
			$_val['wr_id'] = $member['mb_id'];
			$_val['mno'] = $member['no'];
			$_val['wr_name'] = $_POST['wr_name'];
			//$_val['wr_biz'] = $_POST['sdfd'];
			$_val['wr_biz_name'] = $_POST['wr_biz_name']; // : 회사명
			//$_val['wr_biz_type'] = $_POST['sdfd'];
			$_val['wr_email'] = $_POST['wr_email'][0] ? implode("@", $_POST['wr_email']) : "";
			$_val['wr_phone'] = $_POST['wr_phone'][0] ? implode("-", $_POST['wr_phone']) : "";
			$_val['wr_hphone'] = $_POST['wr_hphone'][0] ? implode("-", $_POST['wr_hphone']) : "";
			$_val['wr_site'] = $_POST['wr_site'];
			$_val['wr_subject'] = $_POST['wr_subject'];
			$_val['wr_content'] = $_POST['wr_content'];
			$_val['wr_date'] = today_time;
			$_val['wr_ip'] = $_SERVER['REMOTE_ADDR'];
			$q = $db->query_q($_val);

			$query = $db->_query("insert into nf_cs set ".$q, $_val);
			$arr['msg'] = "등록이 완료되었습니다.";
			$arr['move'] = main_page;
		}
		
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "get_category_list":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		$row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($_POST['no']));
		$query = $db->_query("select * from ".$cate_table." where `pnos`=? and wr_view=1 order by wr_rank asc", array($row['pnos'].$_POST['no'].','));
		$tag = "";
		ob_start();
		while($row=$db->afetch($query)) {
			$value = $_POST['wr_type']=='area' ? $row['wr_name'] : intval($row['no']);
		?>
		<option value="<?php echo $value;?>" no="<?php echo intval($row['no']);?>"><?php echo $row['wr_name'];?></option>';
		<?php
		}
		$arr['tag'] = $tag.ob_get_clean();
		$arr['js'] = '
		$(obj).eq(num).html(\'<option value="">\'+first_txt+\'</option>\'+data.tag);
		';
		die(json_encode($arr));
	break;

	case "check_uid":
		$_where = "";
		$arr['msg'] = "";
		$val = trim($_POST['val']);
		$check_id = $db->query_fetch("select * from nf_member where `mb_id`=?", array($val));
		if(!preg_match("/^[a-z]/i", $val) || preg_match("/[^a-z0-9]/i", $val) || strlen($val)<5 || strlen($val)>20) $arr['msg'] = "영문자로 시작하는 5~20자의 영문 소문자와 숫자의 조합만 사용할 수 있습니다.";
		if($check_id['mb_id']) $arr['msg'] = "이미 사용중인 아이디입니다.";
		if(!$_POST['val']) $arr['msg'] = "아이디를 입력해주시기 바랍니다.";
		if(!$arr['msg']) {
			$arr['msg'] = "사용가능한 아이디입니다.";
			$arr['js'] = '
			$(".check_mb_id-").val("1");
			';
		} else {
			$arr['js'] = '
			$("#"+id).val("");
			';
		}
		die(json_encode($arr));
	break;

	case "check_nick":
		$_where = "";
		$arr['msg'] = "";
		$val = trim($_POST['val']);
		$check_nick = $db->query_fetch("select * from nf_member where `mb_nick`=?", array($val));

		$get_member = $member;
		if(strpos($nf_util->page_back(), '/admini/')!==false) $get_member = $nf_member->get_member($_POST['mno']);

		if($check_nick['mb_nick']) $arr['msg'] = "이미 사용중인 닉네임입니다.";
		if(!$_POST['val']) $arr['msg'] = "닉네임을 입력해주시기 바랍니다.";
		if($get_member['no'] && $get_member['mb_nick']==$check_nick['mb_nick']) $arr['msg'] = ""; // : 본인 닉네임인경우
		if(!$arr['msg']) {
			$arr['msg'] = "사용가능한 닉네임입니다.";
			$arr['js'] = '
			$(".check_mb_nick-").val("1");
			';
		} else {
			$arr['js'] = '
			$("#"+id).val("");
			';
		}
		die(json_encode($arr));
	break;

	case "password_modify":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['move'] = "";
			$arr['move'] = "";
			if($_POST['passwd']!=$_POST['ch_passwd']) $arr['msg'] = "새로운 비밀번호 확인을 정확히 입력해주세요.";
			if(!$nf_util->check_word($_POST['passwd'], 2)) $arr['msg'] = "5~20자 사이의 영문, 숫자, 특수문자중 최소 2가지 이상 조합으로 입력해주세요.";
			else {
				$_pw_row = $db->query_fetch("select `mb_password` from nf_member where `no`=?", array($member['no']));
				if (!nf_verify_password($_POST['this_passwd'], $_pw_row['mb_password'], $member['no'], $db)) {
					$arr['msg'] = "현재 비밀번호를 정확히 입력해주세요.";
				} else {
					$update = $db->_query("update nf_member set `mb_password`=? where `no`=?", array(nf_hash_password($_POST['passwd']), $member['no']));
					$arr['msg'] = "비밀번호 변경이 완료되었습니다.";
					$arr['move'] = "/";
				}
			}
		}
		die(json_encode($arr));
	break;

	case "member_write":
	case "member_modify":
		if(!$_include) {
			$recaptcha_allow = $nf_util->recaptcha_check();
			if(!$recaptcha_allow['success']) {
				die(json_encode($recaptcha_allow));
			}
		}

		$admin_page = false;
		$mem_row = "";
		$get_member = $member; // : 로그인회원
		$mb_id = $get_member['mb_id'];
		if(strpos($nf_util->page_back(), '/admini/')!==false) {
			$admin_page = true;
			$mb_id = $_POST['mb_id'];
		}
		if(strpos($nf_util->page_back(), '/admini/')!==false) {
			$get_member = array();
			if($_POST['mno']) {
				$nf_member->get_member($_POST['mno']); // : 관리자 수정회원
				$get_member = $nf_member->member_arr[$_POST['mno']];
				$mb_id = $get_member['mb_id'];
			}
		}
		if(!sess_user_uid && strpos($nf_util->page_back(), '/admini/')===false) $mb_id = $_POST['mb_id'];

		// : 아이디체크
		if(!$mem_row) $mem_row = $db->query_fetch("select * from nf_member where `mb_id`=?", array($mb_id));
		$is_sns_member_update = $mem_row['mb_is_sns'] && $mem_row['mb_type'] ? true : false; // : sns가입시 회원수정여부
		if($mem_row) {
			// : 로그인하지 않는경우 [ 회원가입인경우 ]
			if(!sess_user_uid && !admin_id) {
				$arr['msg'] = "중복된 아이디가 있습니다.";
				$arr['move'] = $nf_util->page_back();
			} else {
				if(!$admin_page && !$_POST['mb_password'] && !$get_member['no']) {
					$arr['msg'] = "비밀번호를 입력해주시기 바랍니다.";

				// : sns가입은 비밀번호입력이 필요없으므로 아래 if문을 사용하지 않습니다.
				// : 업체는 비밀번호가 맞는지 체크하지 않습니다. 바로 변경합니다.
				/*
				} else if(!$admin_page && md5($_POST['con_password'])!=$mem_row['mb_password'] && !in_array($prev_mode, array('sns_login_process')) && !$mem_row['mb_is_sns']) {
					$arr['msg'] = "비밀번호를 정확히 입력해주시기 바랍니다.";
				*/
				} else {
					if($mem_row['mb_type']) $_POST['mb_type'] = $mem_row['mb_type'];
				}
			}
		}

		// : 아이디와 닉네임체크
		if($_POST['mb_id'] && !in_array($prev_mode, array('sns_login_process'))) {
			if(!$arr['msg']) {
				$check_nick = $db->query_fetch("select * from nf_member where `mb_nick`=?", array($_POST['mb_nick']));
				if($check_nick['mb_nick'] && $get_member['mb_nick']!=$check_nick['mb_nick']) $arr['msg'] = "이미 사용중인 닉네임입니다.";
				if(!$mem_row && !$_POST['mb_nick']) $arr['msg'] = "닉네임을 입력해주시기 바랍니다.";

				if($check_nick['mb_id'] && $get_member['mb_id']!=$check_nick['mb_id']) $arr['msg'] = "이미 사용중인 아이디입니다.";
				if(!$mem_row && !$_POST['mb_id']) $arr['msg'] = "아이디를 입력해주시기 바랍니다.";
			}
		}

		$upload_allow = $nf_util->filesize_check($_FILES['mb_photo']['size']);
		if(!$upload_allow) {
			$mb_photo_tmp = $_FILES['mb_photo']['tmp_name'];
			if($mb_photo_tmp) {
				$dir_arr = $nf_util->get_dir_date('member', $mem_row['mb_wdate']);
				$ext = $nf_util->get_ext($_FILES['mb_photo']['name']);
				if(in_array($ext, $nf_util->photo_ext)) {
					$mb_photo = $dir_arr['date'].'/photo_'.time().".".$ext;
					$nf_util->make_thumbnail($mb_photo_tmp, NFE_PATH.$dir_arr['dir'].$mb_photo, 140, 170);
					if(is_file(NFE_PATH.$dir_arr['dir'].$get_member['mb_photo'])) unlink(NFE_PATH.$dir_arr['dir'].$get_member['mb_photo']);
				} else {
					$arr['msg'] = '사진은 '.implode(", ", $nf_util->photo_ext).'확장자만 허용합니다.';
					$arr['move'] = $nf_util->page_back();
					die(json_encode($arr));
				}
			}
		}

		if(!$arr['msg']) {
			// : 기본 회원 테이블
			$_val = array();
			$_val2 = array();

			if(!$mem_row) {
				$_val['mb_id'] = $mb_id;
				$_val['mb_level'] = $_POST['mb_level'] && $is_admin ? $_POST['mb_level'] : $env['member_point_arr']['register_level'];
				if(in_array($prev_mode, array('sns_login_process'))) $_val['mb_is_sns'] = $_POST['engine'];
			}

			if(!$mem_row['mb_type'] && $_POST['mb_type']) {
				$_val['mb_type'] = $_POST['mb_type'];
			}

			if((!$mem_row || admin_id) && trim($_POST['mb_password'])) $_val['mb_password'] = nf_hash_password($_POST['mb_password']); // : 회원 가입시 비밀번호
			if($_POST['mode']=='member_modify' && $_POST['ch_password']) $_val['mb_password'] = nf_hash_password($_POST['ch_password']); // : 회원 수정시 변경 비밀번호
			$_val['mb_sms'] = @in_array('sms', $_POST['mb_receive']) ? 1 : 0;
			if($_POST['mb_name']) $mb_name = $_val['mb_name'] = $_POST['mb_name'];
			$mb_company_name = $_val['mb_company_name'] = $_POST['mb_company_name'];
			if($_POST['mb_nick']) $_val['mb_nick'] = $_POST['mb_nick'];
			$_val['mb_email'] = @implode("@", $_POST['mb_email']);
			$_val['mb_email_view'] = @in_array('email', $_POST['mb_receive']) ? 1 : 0;
			//$_val['mb_phone'] = @implode("-", $_POST['mb_phone']);
			$mb_hphone = $_val['mb_hphone'] = @implode("-", $_POST['mb_hphone']);
			$_val['mb_message_view'] = @in_array('message', $_POST['mb_receive']) ? 1 : 0;
			$_val['mb_receive'] = @implode(",", $_POST['mb_receive']);
			//$_val['mb_zipcode'] = $_POST['mb_zipcode'];
			//$_val['mb_address0'] = $_POST['mb_address0'];
			//$_val['mb_address1'] = $_POST['mb_address1'];
			//$_val['mb_homepage'] = $nf_util->get_domain($_POST['mb_homepage']);
			$_val['mb_udate'] = today_time;
			if(trim($_POST['mb_memo'])) $_val['mb_memo'] = $_POST['mb_memo'];
			if($mb_photo) $_val['mb_photo'] = $mb_photo;

			if(trim($_POST['mb_birth'])) $_val['mb_birth'] = $_POST['mb_birth'];
			if(trim($_POST['mb_gender'])) $_val['mb_gender'] = $_POST['mb_gender'];

			$_val['mb_company_intro'] = $_POST['mb_company_intro'];

			if(!$mem_row) {
				$_val['mb_point'] = intval($mb_point);
				$_val['mb_wdate'] = today_time;
				$_val['mb_login_count'] = 1;
				$_val['mb_last_login'] = today_time;

				if($_SESSION['certify_info']) {
					if(!$db->is_field('nf_member', 'mb_auth_di')) $db->_query("alter table nf_member add mb_auth_di varchar(255) comment '실명인증 DI'");
					if(!$db->is_field('nf_member', 'mb_auth_ci')) $db->_query("alter table nf_member add mb_auth_ci varchar(255) comment '실명인증 CI'");
					$_val['mb_auth_di'] = $_SESSION['certify_info']['4'];
					$_val['mb_auth_ci'] = $_SESSION['certify_info']['5'];
				}

				if(strpos($_SERVER['PHP_SELF'], '/admini/')!==false) $_val['is_admin'] = 1;
			}

			$_val['is_adult'] = $_POST['mb_birth'] && $nf_util->is_adult($_POST['mb_birth']) ? 1 : 0;

			$q = $db->query_q($_val);

			if(!$mb_name) $mb_name = $mem_row['mb_name'];

			if($mem_row) {
				$mno = $mem_row['no'];
				$query = $db->_query("update nf_member set ".$q." where `no`=".intval($mno), $_val);
			} else {
				$query = $db->_query("insert into nf_member set ".$q, $_val);
				$mno = $db->last_id();
				$query = $db->_query("insert into nf_member_service set `mno`=?, `mb_id`=?", array($mno, $_val['mb_id']));

				$register_level_point = $env['member_point_arr']['register_point'];
				if($register_level_point>0) {
					$nf_member->get_member($mno);
					$_point = array();
					$_point['member'] = $nf_member->member_arr[$mno];
					$_point['code'] = '회원가입 활동 포인트';
					$_point['use_point'] = $env['member_point_arr']['register_point'];
					$_point['rel_table'] = 'netk_member';
					$_point['rel_id'] = '';
					$_point['rel_action'] = 'member_write';
					$update = $nf_point->insert($_point);
				}

				/*
				if($env['member_point_arr']['register_point']>0) {
					$nf_member->get_member($mno);
					$_point = array();
					$_point['member'] = $nf_member->member_arr[$mno];
					$_point['code'] = '회원가입';
					$_point['use_point'] = $env['member_point_arr']['register_point'];
					$_point['rel_table'] = 'netk_member';
					$_point['rel_id'] = '';
					$_point['rel_action'] = 'member_write';
					$update = $nf_point->insert($_point);
				}
				*/
			}

			// : 관리자가 강제로 포인트를 준경우 활동포인트값 강제초기화
			if($_POST['mb_level'] && $is_admin && $_POST['mb_level']!=$mem_row['mb_level']) {
				$update = $db->_query("update nf_member set `mb_level`=".intval($_POST['mb_level']).", `mb_add_point`=".intval($env['member_level_arr'][intval($_POST['mb_level'])]['point'])." where `no`=".intval($mem_row['no']));
			}

			$msg = $mem_row && $member['mb_type'] && $get_member['mb_id'] ? '회원수정' : '회원등록';
			if(strpos($nf_util->page_back(), "/admini/")!==false) $msg = $get_member['mb_id'] ? '회원수정' : '회원등록';
			$arr['msg'] = $msg.'이 완료되었습니다.';

			if(strpos($nf_util->page_back(), '/admini/')!==false) {
				$arr['move'] = './index.php';
				parse_str($_POST['back_page'], $output);
				if(strpos($_POST['back_page'], "/admini/member/index.php")!==false) $arr['move'] = $nf_util->sess_page('member_list');
				if(strpos($_POST['back_page'], "/admini/member/bad_list.php")!==false) $arr['move'] = $nf_util->sess_page('bad_member_list');
				if(strpos($_POST['back_page'], "/admini/member/individual.php")!==false) $arr['move'] = $nf_util->sess_page('individual_list');
				if(strpos($_POST['back_page'], "/admini/member/company.php")!==false) $arr['move'] = $nf_util->sess_page('company_list');
				if(strpos($_POST['back_page'], "/admini/member/left_list.php")!==false) $arr['move'] = $nf_util->sess_page('left_request_list');
				if(strpos($_POST['back_page'], "/admini/member/left_list.php")!==false && $output['left']) $arr['move'] = $nf_util->sess_page('left_list');
				if($_POST['process']=='save_next_write') {
					if($_POST['mb_type']=='company') $arr['move'] = NFE_URL.'/admini/member/company_insert.php';
					else $arr['move'] = NFE_URL.'/admini/member/individual_insert.php';
				}
			} else {
				if(strpos($nf_util->page_back(), "/member/update_form.php")===false) $nf_member->login($mno);
				if($_POST['mb_type']) $arr['move'] = NFE_URL."/mypage/index.php";

				// : sms 가입시
				if(!$mem_row) {
					$sms_arr['phone'] = $mb_hphone;
					$sms_arr['name'] = $mb_name;
					$nf_sms->send_sms_member_write($sms_arr);

					$email_arr = array();
					$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='member_regist'");
					$email_arr['subject'] = "[".$env['site_name']."] 회원가입을 축하합니다.";
					$email_arr['email'] = $_val['mb_email'];
					$email_arr['content'] = strtr(stripslashes($mail_skin['content']), $nf_email->ch_content($_val));
					$nf_email->send_mail($email_arr);

				// : 수정시
				} else {
					$sms_arr['phone'] = $mb_hphone;
					$sms_arr['name'] = $mb_name;
					$nf_sms->send_sms_member_modify($sms_arr);
				}
			}
		}

		if(!in_array($prev_mode, array('sns_login_process'))) {
			die(json_encode($arr));
		} else {
			return false;
		}
	break;

	### : 카테고리 시작 ###############
	case "btn_category":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		$cate_row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($_POST['no']));
		$parent_cate_row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($cate_row['pno']));
		$is_adult = $cate_row['wr_adult'] || $parent_cate_row['wr_adult'] ? 'adult' : '';

		if(in_Array($_POST['wr_type'], array('area'))) {
			if(!$cate_row['pno']) {
				$nf_category->get_area($cate_row['wr_name']);
				$__cate_array = $cate_area_array['SI'][$cate_row['wr_name']];
			} else {
				$nf_category->get_area($parent_cate_row['wr_name'], $cate_row['wr_name']);
				$__cate_array = $cate_area_array['GU'][$parent_cate_row['wr_name']][$cate_row['wr_name']];
			}
		} else {
			$__cate_array = $cate_p_array[$cate_row['wr_type']][$_POST['no']];
		}

		$k_int = $_POST['ul_cnt']==$_POST['num']+1 ? '' : ', '.intval($_POST['num']+1);
		ob_start();
		?>
		<li class=""><button type="button" onClick="nf_category.btn_category(this)" no="<?php echo $cate_row['no'];?>"><input type="checkbox"><?php echo $cate_row['wr_name'];?> 전체</button></li>
		<?php
		$count = 0;
		if(is_array($__cate_array)) { foreach($__cate_array as $k=>$v) {
			if($nf_category->job_part_adult && $_POST['wr_type']=='job_part') {
				if($_POST['code']=='adult' && (!$is_adult && !$v['wr_adult'])) continue;
				if($_POST['code']!='adult' && ($is_adult || $v['wr_adult'])) continue;
			}
		?>
		<li class=""><button type="button" onClick="nf_category.btn_category(this<?php echo $k_int;?>)" no="<?php echo $v['no'];?>"><input type="checkbox"><?php echo $v['wr_name'];?></button></li>
		<?php
			$count++;
		} }
		$arr['tag'] = ob_get_clean();
		$arr['count'] = $count;
		$arr['pno'] = $_POST['no'];
		$display = $arr['count']>0 ? 'block' : 'none';

		$arr['js'] = '
		$(el).closest(".btn_category-").find("ul").eq(num).html(data.tag);
		$(el).closest(".btn_category-").find("ul").eq(num).css({"display":"'.$display.'"});
		nf_category.put_var_category_on(el, $(el).closest(".btn_category-").find("ul").eq(num));
		';

		die(json_encode($arr));
	break;
	### : 카테고리 끝 ################

// : 스크랩
	case "scrap":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['move'] = "";
			$row = $db->query_fetch("select * from nf_scrap where `mno`=? and `pno`=? and `code`=?", array($member['no'], $_POST['no'], $_POST['code']));

			$info_row = $db->query_fetch("select * from nf_".$_POST['code']." where `no`=?", array($_POST['no']));
			$other_member = $db->query_fetch("select * from nf_member where `no`=?", array($info_row['mno']));

			if($row) {
				$delete = $db->_query("delete from nf_scrap where `no`=".intval($row['no']));
				$arr['msg'] = "스크랩을 취소했습니다.";
				$arr['js'] = '
				if($(el).find("i")[0]) {
					if(tag=="button") {
						$(el).find("i").removeClass("axi-heart2");
						$(el).find("i").addClass("axi-heart");
					} else {
						$(el).find("i").removeClass("axi-star3");
						$(el).find("i").removeClass("scrap");
						$(el).find("i").addClass("axi-star-o");
					}
				}
				';
			} else {
				$_val = array();
				$_val['code'] = $_POST['code'];
				$_val['pno'] = intval($_POST['no']);
				$_val['mno'] = intval($member['no']);
				$_val['mb_id'] = $member['mb_id'];
				$_val['mb_name'] = $member['mb_name'];
				$_val['pmno'] = intval($other_member['no']);
				$_val['pmb_id'] = $other_member['mb_id'];
				$_val['pmb_name'] = $other_member['mb_name'];
				$_val['rdate'] = today_time;
				$q = $db->query_q($_val);
				$insert = $db->_query("insert into nf_scrap set ".$q, $_val);
				$arr['msg'] = "스크랩했습니다.";
				$arr['js'] = '
				if($(el).find("i")[0]) {
					if(tag=="button") {
						$(el).find("i").addClass("axi-heart2");
						$(el).find("i").removeClass("axi-heart");
					} else {
						$(el).find("i").removeClass("axi-star-o");
						$(el).find("i").addClass("scrap");
						$(el).find("i").addClass("axi-star3");
					}
				}
				';
			}
		}
		die(json_encode($arr));
	break;

	case "get_message_info":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		$arr['js'] = '';
		if($member['no'] || admin_id) {
			if($_POST['no']) {
				switch($_POST['code']) {
					// : 쪽지리스트에서 보낼때
					default:
						if(strpos($nf_util->page_back(), '/admini/')===false) {
							$_where = " and `rdate`='1000-01-01 00:00:00' and `pmno`=".intval($member['no']);
							if($_POST['code']=='received') $_where = " and `pmno`=".intval($member['no']);
							if($_POST['code']=='send') $_where = " and `mno`=".intval($member['no']);
						}
						$row = $db->query_fetch("select * from nf_message where `no`=".intval($_POST['no']).$_where);
						$get_mno = $_POST['code']=='send' ? 'pmno' : 'mno';
					break;
				}

				$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($row[$get_mno]));
				switch($_POST['code']) {
					// : 쪽지리스트에서 보낼때
					default:
						if(strpos($nf_util->page_back(), '/admini/')===false) $arr['msg'] = "답장 권한이 없습니다.";
					break;
				}
				$arr['move'] = $nf_util->page_back();
			} else {
				$row = array('no'=>'');
			}

			if($row) {
				$arr['msg'] = "";
				$arr['move'] = "";
				$arr['js'] .= '
				$(".message-").css({"display":"block"});
				if($(".put_nick-")[0]) $(".put_nick-").html("'.$get_member['mb_nick'].'");
				if(typeof no!="undefined") form.no.value = no;
				';

				if(in_array($_POST['code'], array('page_code', 'received', 'send'))) {
					if($_POST['nos']) {
						$shop_query = $db->_query("select * from nf_shop where `no` in (".$_POST['nos'].")");
						$mb_nick = array();
						$mnos = array();
						while($srow=$db->afetch($shop_query)){
							$mrow = $db->query_fetch("select * from nf_member where `no`=".intval($srow['mno']));
							if($mrow['mb_nick']) {
								$mb_nick[$mrow['no']] = $mrow['mb_nick'];
								$mnos[$mrow['no']] = $srow['mno'];
							}
						}
						$arr['js'] .= '
						$(form).find(".put_nick-").html("'.implode(",", $mb_nick).'");
						$(form.nos).val("'.$nf_util->get_html(implode(",", $mnos)).'");
						';
					} else {
					$arr['js'] .= '
					$(form.nick).val("'.$get_member['mb_nick'].'");
					';
					}
				}
			}

			if($row['no']) {
				$arr['js'] .= '
				$(form.nick).attr("disabled", "disabled");
				';
			} else {
				$arr['js'] .= '
				$(form.nick).removeAttr("disabled");
				';
			}
		}
		die(json_encode($arr));
	break;

// : 쪽지등록
	case "message_write":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = $nf_util->page_back();
			$row = $db->query_fetch("select * from nf_message where `no`=".intval($_POST['no']));
			// : 쪽지작성
			if($_POST['page_code']!='input') {
				$info_row = $db->query_fetch("select * from nf_".$_POST['page_code']." where `no`=".intval($_POST['pno']));
				$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($info_row['mno']));

			// : 마이페이지 답장이나 쪽지보내기
			} else {
				// : 쪽지보내기
				if($_POST['nick']) {
					$get_member = $db->query_fetch("select * from nf_member where `no`!=? and `mb_nick`=?", array($member['no'], $_POST['nick']));
					$arr['msg'] = "해당 닉네임 정보를 찾을수가 없습니다.";
					if($member['mb_nick']==$_POST['nick']) $arr['msg'] = "본인에게는 쪽지를 보낼 수 없습니다.";
					if($get_member && !$get_member['mb_message_view']) $arr['msg'] = "쪽지를 보낼 수 없습니다.";
					if($get_member) {
						$arr['msg'] = "";
						$arr['move'] = "";
						$info_row['mno'] = $get_member['no'];
						$info_row['wr_id'] = $get_member['mb_id'];
					}
					if($arr['msg']) $arr['move'] = "";
				// : 답장
				} else {
					if($_POST['admin']) {
						$info_row['mno'] = 0;
						$info_row['wr_id'] = '_admin_';
						$get_member['mb_nick'] = '관리자';
					} else {
						$get_mno = $_POST['code']=='send' ? 'pmno' : 'mno';
						$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($row[$get_mno]));
						$arr['msg'] = "답장을 보낼 회원이 존재하지 않습니다.";
						if(!$get_member['mb_message_view']) $arr['msg'] = "쪽지를 보낼 수 없습니다.";
						if($get_member) {
							$arr['msg'] = "";
							$info_row['mno'] = $get_member['no'];
							$info_row['wr_id'] = $get_member['mb_id'];
						}
					}
				}
			}
			if(!$arr['msg']) {

				$pmno = $info_row['mno'];
				$pmb_id = $info_row['wr_id'];
				$pmb_nick = $get_member['mb_nick'];

				$_val = array();
				$_val['mno'] = $member['no'];
				$_val['mb_id'] = $member['mb_id'];
				$_val['mb_nick'] = $member['mb_nick'];
				$_val['pmno'] = $pmno;
				$_val['pmb_id'] = $pmb_id;
				$_val['pmb_nick'] = $pmb_nick;
				$_val['sdate'] = today_time;
				$_val['content'] = $_POST['content'];

				// : 답장
				if($row) {
					$_val['code'] = 'reply';
					$_val['pno'] = $row['pno']; // : 답장 쪽지주키값
					$_val['rno'] = $row['no']; // : 답장 쪽지주키값

				// : 쪽지작성
				} else {
					$_val['code'] = $_POST['page_code'];
					$_val['pno'] = $_POST['pno'];
				}

				$q = $db->query_q($_val);
				$insert = $db->_query("insert into nf_message set ".$q, $_val);
				$arr['move'] = "";
				if(in_array($_POST['page_code'], array('reply', 'input'))) {
					$page_back_arr = explode("?", $nf_util->page_back());
					$arr['move'] = $page_back_arr[0].'?code=send';
				}
				$arr['msg'] = "쪽지전송이 완료되었습니다.";
				$arr['js'] = '
				nf_util.openWin(".message-");
				';
			}
		}
		die(json_encode($arr));
	break;

	// : 쪽지확인
	case "click_message":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		$arr['js'] = '';
		if($member['no']) {
			$_where = " and `rdate`='1000-01-01 00:00:00' and `pmno`=".intval($member['no']);
			if($_POST['code']=='received') $_where = " and `pmno`=".intval($member['no']);
			if($_POST['code']=='send') $_where = " and `mno`=".intval($member['no']);
			$row = $db->query_fetch("select * from nf_message where `no`=".intval($_POST['no']).$_where);
			$get_mno = $_POST['code']=='send' ? 'mno' : 'pmno';

			$arr['msg'] = "";
			$arr['move'] = "";

			// : 받는사람만 읽기저장해야함
			if($row['pmno']==$member['no'] && $row['rdate']=='1000-01-01 00:00:00') {
				$arr['msg'] = "";
				$arr['move'] = "";
				$update = $db->_query("update nf_message set `rdate`=? where `no`=".intval($row['no']), array(today_time));
				$arr['js'] = '
				var trObj = $(el).closest("tr");
				trObj.find(".date_read").html("'.today_time.'");
				trObj.find(".is_read").html("O");
				';
			}

			// : 폼 열기
			if($row[$get_mno]==$member['no']) {
				$arr['row'] = $row;
				$arr['msg'] = "";
				$arr['move'] = "";
				$arr['js'] .= '
				var tr_index = $(el).closest("tr").index();
				$(el).closest("tbody").find("tr").eq(tr_index+1).css({"display":"table-row"});
				';
			}
		}
		die(json_encode($arr));
	break;

	case "tax_write":

		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		$arr['js'] = '';
		if($is_admin || $member['no']) {
			$_where = "";
			if(strpos($nf_util->page_back(), '/admini/')===false) $_where = " and `mno`=".intval($member['no']);
			else $_where = " and `no`=".intval($_POST['no']);
			$tax_row = $db->query_fetch("select * from nf_tax where 1 ".$_where);

			$mem_row = $member;
			if(strpos($nf_util->page_back(), '/admini/')!==false) {
				$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($tax_row['mno']));
			}

			$_val = array();
			if(!$tax_row) {
				$_val['mno'] = $member['no'];
				$_val['wr_type'] = $member['mb_type'];
				$_val['wr_id'] = $member['mb_id'];
			}
			$_val['wr_name'] = $_POST['wr_name'] ? $_POST['wr_name'] : $member['mb_name'];
			if($_POST['wr_email'][0]) $_val['wr_email'] = implode("@", $_POST['wr_email']);
			if($_POST['wr_hphone'][1]) $_val['wr_hphone'] = implode("-", $_POST['wr_hphone']);
			if($_POST['wr_phone'][1]) $_val['wr_phone'] = implode("-", $_POST['wr_phone']);
			$_val['wr_pay_date'] = $_POST['wr_pay_date'];
			$_val['wr_price'] = $_POST['wr_price'];
			$_val['wr_content'] = $_POST['wr_content'];
			if(!$tax_row) $_val['wdate'] = today_time;
			if(strpos($nf_util->page_back(), '/admini/')===false) $_val['udate'] = today_time;

			if($mem_row['mb_type']=='company') {
				$_val['wr_manager'] = $_POST['manager'];
				if($_POST['biz_no'][0]) $_val['wr_biz_no'] = implode("-", $_POST['biz_no']);
				$_val['wr_company_name'] = $_POST['company_name'];
				$_val['wr_ceo_name'] = $_POST['ceo_name'];
				$_val['wr_zipcode'] = $_POST['zipcode'];
				$_val['wr_address0'] = $_POST['address0'];
				$_val['wr_address1'] = $_POST['address1'];
				$_val['wr_condition'] = $_POST['condition'];
				$_val['wr_item'] = $_POST['item'];
			}

			if($_POST['wr_memo']) $_val['wr_memo'] = $_POST['wr_memo'];

			$q = $db->query_q($_val);

			if($tax_row) $query = $db->_query("update nf_tax set ".$q." where `no`=".intval($tax_row['no']), $_val);
			else $query = $db->_query("insert into nf_tax set ".$q, $_val);
			$arr['msg'] = ($tax_row ? '수정' : '등록')."이 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
			if(strpos($nf_util->page_back(), '/admini/')!==false) $arr['move'] = $nf_util->sess_page('tax_list_admin');
		}
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;


	case "click_jump_use":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		$arr['js'] = '';
		if($member['no']) {
			$arr['msg'] = "";
			$arr['move'] = "";
			$_table = 'nf_'.$_POST['code'];
			$info_row = $db->query_fetch("select * from ".$_table." where `no`=? and `mno`=?", array($_POST['no'], $member['no']));
			$arr['msg'] = "삭제된 정보입니다.";
			$arr['move'] = $nf_util->page_back();
			if($info_row) {
				$update = $db->_query("update ".$_table." set `wr_jump_use`=? where `no`=?", array($_POST['val'], $info_row['no']));
				$arr['msg'] = $nf_util->use_arr[$_POST['val']]."으로 변경했습니다.";
				$arr['move'] = "";
			}
		}
		die(json_encode($arr));
	break;


	case "get_ajax_paging":
		switch($_POST['code']) {
			case "map":
				$_include = true;
				$_POST['mode'] = 'get_map_shop';
				include NFE_PATH.'/include/regist.php';
			break;


			// : 관리자 회원문자발송
			case "member_sms":
				$_include = true;
				$_POST['mode'] = 'member_ajax_search';
				include NFE_PATH.'/admini/regist.php';
			break;
		}
		exit;
	break;


	case "find_id":
	case "find_pw":
		$arr['msg'] = "이미 로그인된 상태입니다.";
		$arr['move'] = "/";
		if(!$member['no']) {
			$_val = array();
			$_val['mb_name'] = $_POST['name'];
			$_val['mb_email'] = $_POST['email'];
			$_where = " and `mb_name`=? and `mb_email`=?";
			if($_POST['mode']=='find_pw') {
				$_val['mb_id'] = $_POST['mid'];
				$_where .= " and `mb_id`=?";
			}
			$q = $db->query_q($_val);
			$mem_row = $db->query_fetch("select * from nf_member where 1".$_where, $_val);
			if($mem_row) {
				switch($_POST['mode']) {
					case "find_id":
						$arr['msg'] = "";
						$arr['move'] = "";
						ob_start();
						?>
						<!--아이디찾은 후 아이디노출-->
						<div class="find_id">
							<p class="txt"></p>
							<p class="id_view">입력한 정보로 조회된 아이디는 <b class="red"><?php echo $mem_row['mb_id'];?></b> 입니다.</p>
							<div class="next_btn">
								<a href="<?php echo NFE_URL;?>/"><button type="button" class="base darkbluebtn">메인홈으로</button></a>
								<a href="<?php echo NFE_URL;?>/member/login.php"><button type="button" class="base">로그인하기</button></a>
							</div>
						</div>
						<!--//아이디찾은 후 아이디노출-->
						<?php
						$arr['tag'] = ob_get_clean();
						$arr['js'] = '
						$(".input_wrap-child-").eq(0).html(data.tag);
						';
					break;

					case "find_pw":
						$mem_row['mb_password'] = $nf_util->rand_word(10); // 메일/SMS 발송용 평문 임시 비밀번호
						$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='member_find'");
						$update = $db->_query("update nf_member set `mb_password`=? where `no`=?", array(nf_hash_password($mem_row['mb_password']), $mem_row['no']));
						$email_arr['subject'] = "[".$env['site_name']."] 문의하신 회원 아이디/비밀번호 입니다.";
						$email_arr['email'] = $mem_row['mb_email'];
						$email_arr['content'] = strtr(stripslashes($mail_skin['content']), $nf_email->ch_content($mem_row));
						$nf_email->send_mail($email_arr);
						$arr['msg'] = "비밀번호를 메일로 전송했습니다.";
						$arr['move'] = "/member/login.php";

						$sms_arr = array();
						$sms_arr['phone'] = $mem_row['mb_hphone'];
						$sms_arr['name'] = $mem_row['mb_name'];
						$sms_arr['mb_password'] = $mem_row['mb_password'];
						$nf_sms->send_sms_passwd_find($sms_arr);
					break;
				}
			} else {
				$arr['msg'] = "찾으시는 정보가 없습니다.";
				$arr['move'] = "";
			}
		}
		die(json_encode($arr));
	break;


	case "click_poll":
		$arr['msg'] = "";
		$poll_row = $db->query_fetch("select * from nf_poll where `no`=? and `use`=1", array($_POST['no']));
		$is_my_poll = $db->query_fetch("select * from nf_poll_member where `pno`=? and `mno`=?", array($poll_row['no'], $member['no']));

		
		$code_vote = $_POST['code']=='vote' && $_POST['view']=='vote' ? 'vote' : 'result';
		switch($code_vote) {
			case 'vote':
				if(!$member['no'] && $poll_row['poll_member']) {
					$arr['msg'] = "회원만 이용 가능합니다.";
					$arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
				}

				$poll_vote_arr = explode(",", $_COOKIE['poll_vote_'.$poll_row['no']]);

				// : 한번만 투표가능
				if(!$poll_row['poll_overlap'] && ($is_my_poll || $_COOKIE['poll_vote_'.$poll_row['no']])) $arr['msg'] = "이미 선택한 설문조사입니다.";
				// : 중복 가능하지만 같은번호는 한번만 투표 가능
				if($poll_row['poll_overlap'] && (($member['no'] && $is_my_poll['sel_no']==$_POST['val']) || (in_array($_POST['val'], $poll_vote_arr)))) $arr['msg'] = "이미 선택한 설문조사입니다.";
				if(!$poll_row) $arr['msg'] = "삭제된 설문조사입니다.";

				if(!$arr['msg']) {
					$_val = array();
					$_val['pno'] = $poll_row['no'];
					$_val['mno'] = $member['no'];
					$_val['mb_id'] = $member['mb_id'];
					$_val['sel_no'] = $_POST['val'];
					$_val['ip'] = $_SERVER['REMOTE_ADDR'];
					$_val['sdate'] = today_time;
					$q = $db->query_q($_val);
					$insert = $db->_query("insert into nf_poll_member set ".$q, $_val);
					if($insert) {
						$update = $db->_query("update nf_poll set `cnt`=`cnt`+1 where `no`=?", array($poll_row['no']));
						$arr['msg'] = "투표가 완료되었습니다.";
						$poll_vote_val = $_COOKIE['poll_vote_'.$poll_row['no']].','.$_POST['val'];
						$nf_util->cookie_save('poll_vote_'.$poll_row['no'], $poll_vote_val, "yes", "100 year");
					} else {
						$arr['msg'] = '투표가 실패되었습니다.\n운영자에게 문의하시기 바랍니다.';
					}
				}
				$add_js = '
				$(el).closest(".qa-body-").find(".btn-vote-").css({"display":"none"});
				$(el).closest(".qa-body-").attr("view", "result");
				';
			break;

			default:
				$view_text = $_POST['code']=='result' ? 'vote' : 'vote';
				if($_POST['code']=='result') {
					$add_js = '
					$(el).closest(".qa-body-").find(".btn-result-").css({"display":"none"});
					';
				} else {
					$add_js = '
					$(el).closest(".qa-body-").find(".btn-result-").css({"display":"block"});
					';
				}
				$add_js .= '
				$(el).closest(".qa-body-").attr("view", "'.$_POST['code'].'");
				';
				if($_POST['code']=='vote') {
					$add_js .= '
					$(el).closest(".qa-body-").attr("view", "'.$_POST['code'].'");
					';
				}
			break;
		}

		$poll_content = unserialize($poll_row['poll_content']);
		$vote_ = array();
		$query = $db->_query("select count(`sel_no`) as c, `sel_no` from nf_poll_member where `pno`=? group by `sel_no`", array($poll_row['no']));
		while($row=$db->afetch($query)) {
			$vote_[$row['sel_no']-1] = $row['c'];
		}
		$vote_cnt = array_sum($vote_);

		$per_hap_ = 0;
		if(is_array($poll_content)) $poll_cnt = count($poll_content); 

		ob_start();
		if(is_array($poll_content)) { foreach($poll_content as $k=>$v) {

			switch($_POST['view']) {
				case 'result':
					?>
					<li><label ><input type="radio" name="poll[<?php echo $poll_row['no'];?>]" value="<?php echo ($k+1);?>"><?php echo $v;?></label></li>
					<?php
				break;

				default:
					$per_ = sprintf("%0.1f", $vote_[$k]/$vote_cnt*100);
					if(($poll_cnt-1)==$k) $per_ = sprintf("%0.1f", 100-$per_hap_);
					if(!$vote_[$k]) $per_ = 0;
					$per_hap_ += $per_;
					?>
					<li><?php echo $v;?><p><span style="width:<?php echo $per_;?>%;"></span></p><em><?php echo $per_;?>% [<?php echo number_format(intval($vote_[$k]));?>표]</em></li>
					<?php
				break;
			}
		} }
		$arr['vote_result'] = ob_get_clean();

		$arr['js'] = '
		$(el).closest(".qa-body-").find(".answer-body-").html(data.vote_result);
		$(el).closest(".qa-body-").find(".btn-result-").css({"display":"none"});
		';
		$arr['js'] .= $add_js;
		die(json_encode($arr));
	break;


	case "password_write":
		switch($_POST['kind']) {
			case "shop_qna":
				$qna_row = $db->query_fetch("select * from nf_qna where `no`=".intval($_POST['no']));
				if(!$_POST['pw']) $arr['msg'] = "비밀번호를 입력해주시기 바랍니다.";
				else $arr['msg'] = "비밀번호를 정확히 입력해주시기 바랍니다.";
				if($qna_row['no'] && $qna_row['passwd']==md5($_POST['pw'])) {
					$nf_util->cookie_save("shop_qna_".$_POST['no'], 1, "yes", "1 day");
					$qna_view_allow = true;
					$arr['msg'] = "";
					switch($_POST['code']) {
						case "modify":
							$arr['move'] = NFE_URL."/shop/qna_form.php?no=".$qna_row['no'];
						break;

						case "delete":
							if($qna_row['mno']) $delete = $db->_query("delete from nf_qna where `mno`=".intval($member['no'])." and `no`=".intval($_POST['no']));
							else $delete = $db->_query("delete from nf_qna where `no`=".intval($_POST['no']));
							$arr['msg'] = "삭제가 완료되었습니다.";
							$arr['move'] = $nf_util->page_back();
						break;

						default:
							ob_start();
							include NFE_PATH.'/include/qna_part.inc.php';
							$arr['tag'] = ob_get_clean();
							$arr['js'] = '
							$(qna_el['.intval($_POST['no']).']).closest("em").html(data.tag);
							$(".password-box-").removeClass("on");
							var pass_form = document.forms["fpassword"];
							$(pass_form).find("[name=\'pw\']").val("");
							';
						break;
					}
				}
			break;

			case "board":
				$bo_table = trim($_POST['bo_table']);
				$_table = $nf_board->get_table($bo_table);
				$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
				$board_info = $nf_board->board_info($bo_row);
				$row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_POST['no']));
				$b_info = $nf_board->info($row, $board_info);

				$arr['msg'] = "게시물이 없습니다.";
				if(!$_POST['pw']) $arr['msg'] = "비밀번호를 입력해주시기 바랍니다.";
				else if($row) {
					$arr['msg'] = "";
					if($row['wr_password']!=md5($_POST['pw'])) $arr['msg'] = "비밀번호를 정확히 입력해주시기 바랍니다.";
					else {
						$_SESSION['board_view_'.$bo_table.'_'.$row['wr_no']] = today_time;
						switch($_POST['code']) {
							case "write":
								$arr = $nf_board->auth_move($_POST['code'], $bo_table, $row['wr_no']);
							break;

							case "read":
								if($row['wr_is_comment']) {
									ob_start();
									include NFE_PATH.'/board/comment.inc.inc.php';
									$arr['comment_tag'] = ob_get_clean();
									$arr['js'] = '
									$("#comment_li-'.$row['wr_no'].'-").html(data.comment_tag);
									';
								} else
									$arr = $nf_board->auth_move($_POST['code'], $bo_table, $row['wr_no']);
							break;

							case "delete":
								$arr['js'] = '
								nf_board.click_delete(el, "'.$bo_table.'", "'.intval($row['wr_no']).'");
								';
							break;
						}
					}
				}
			break;
		}

		if($arr['msg']) {
			$arr['js'] = '
			var pass_form = document.forms["fpassword"];
			$(pass_form).find("[name=\'pw\']").val("");
			';
		}
		die(json_encode($arr));
	break;


// : 로그분석 읽기
	case "read_statistics":
		$arr = $nf_statistics->visit();
		die(json_encode($arr));
	break;


	//################## 삭제 ##################//
	case "delete_select_message":
	case "delete_message":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		if(!$member['no'] && !admin_id) $arr['move'] = NFE_URL.'/member/login.php?page_url='.urlencode($nf_util->page_back());
		if($member['no'] || admin_id) {
			$nos = $_POST['mode']=='delete_message' ? $_POST['no'] : implode(",", $_POST['chk']);

			$_where = " and `pmno`=".intval($member['no']);
			$field_del = 'p';

			if($_POST['code']=='send') {
				$_where = " and `mno`=".intval($member['no']);
				$field_del = '';
			}

			if(admin_id && strpos($nf_util->page_back(), "/admini/")!==false) $_where = ""; // : 관리자는 회원체크 안함.

			if($nos) {
				if(admin_id && strpos($nf_util->page_back(), "/admini/")!==false) $delete = $db->_query("delete from nf_message where `no` in (".$nos.")".$_where);
				else $update = $db->_query("update nf_message set `".$field_del."del`=?, `".$field_del."ddate`=? where `no` in (".$nos.")".$_where, array(1, today_time));
			}
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	case "delete_chulsuk":
	case "delete_select_chulsuk":
		$nos = $_POST['mode']=='delete_chulsuk' ? $_POST['no'] : implode(",", $_POST['chk']);
		if($nos) {
			$delete = $db->_query("delete from nf_chulsuk where `no` in (".$nos.")");
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "delete_member":
	case "delete_select_member":
		$arr['msg'] = "정상적으로 접근해주시기 바랍니다.";
		$arr['move'] = NFE_URL."/";
		if($member['no'] || admin_id) {
			$mno = intval($member['no']);
			$admin_control = false;
			if(strpos($nf_util->page_back(), '/admini/')!==false && admin_id) $admin_control = true;

			// : 관리자
			if($admin_control) $mno = intval($_POST['chk'][0])>0 ? implode(",", $_POST['chk']) : intval($_POST['no']);
			// : 사용자 (BCRYPT 비교는 PHP에서 수행하므로 이메일로만 조회 후 검증)
			else {
				$mem_row = $db->query_fetch("select * from nf_member where `no`=? and `mb_email`=?", array($mno, $_POST['email']));
				if (!$mem_row || !nf_verify_password($_POST['passwd'], $mem_row['mb_password'], 0, $db)) {
					$arr['msg'] = "정보를 정확히 입력해주시기 바랍니다.";
					$arr['move'] = $nf_util->page_back();
					die(json_encode($arr));
				}
			}
			$query = $db->_query("select * from nf_member where `no` in (".$mno.")");
			while($row=$db->afetch($query)) {
				$delete = $db->_query("delete from nf_scrap where `mno`=".intval($row['no'])); // : 스크랩
				$delete = $db->_query("delete from nf_cs where `mno`=".intval($row['no'])); // : 광고제휴문의
				$delete = $db->_query("delete from nf_jump where `mno`=".intval($row['no'])); // : 점프내역
				$delete = $db->_query("delete from nf_read where `mno`=".intval($row['no'])); // : 읽은내역
				$delete = $db->_query("delete from nf_guide where `mno`=".intval($row['no'])); // : 이용후기
				$delete = $db->_query("delete from nf_qna where `mno`=".intval($row['no'])); // : qna
				$delete = $db->_query("delete from nf_report where `mno`=".intval($row['no'])); // : 신고

				$service_advert = $db->_query("select * from nf_write_service_advert where wr_reply='' and `mno`=".intval($row['no']));
				// : 입점문의
				while($row2=$db->afetch($service_advert)) {
					$delete = $db->_query("update nf_write_service_advert set wr_del=1 where `wr_no`=".intval($row2['wr_no'])); // : 입점문의
					$delete = $db->_query("update nf_write_service_advert set wr_del=1 where `wr_num`=".intval($row2['wr_num'])); // : 입점문의 답글
				}

				if($admin_control) $update = $db->_query("update nf_member set `mb_left`=1, `mb_left_reason_type`='_admin_', `mb_left_reason`='관리자삭제', `mb_left_date`='".today_time."' where `no`=".intval($row['no'])); // : 회원내역
				$update = $db->_query("update nf_member_service set `is_delete`=1 where `mno`=".intval($row['no'])); // : 회원서비스

				$update = $db->_query("update nf_shop set `is_delete`=1 where `mno`=".intval($row['no'])); // : 업체정보
				$update = $db->_query("update nf_message set `del`=1 where `mno`=".intval($row['no'])); // : 쪽지내역
				$delete = $db->_query("update nf_point set `is_delete`=1 where `mno`=".intval($row['no'])); // : 포인트내역
				$delete = $db->_query("update nf_payment set `is_delete`=1 where `pay_mno`=".intval($row['no'])); // : 결제내역
				// : 좋아요는 삭제하지 않습니다.
			}
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = (strpos($nf_util->page_back(), '/admini/')!==false) ? $nf_util->page_back() : NFE_URL.'/';

			// : 사용자 탈퇴후 로그아웃시키기
			if(!$admin_control) {
				// : 탈퇴SMS
				$sms_arr = array();
				$sms_arr['phone'] = $mem_row['mb_hphone'];
				$sms_arr['name'] = $mb_hphone['mb_name'];
				$nf_sms->send_sms_member_secession($sms_arr);

				$_val = array();
				$_val['mb_left_request'] = 1;
				$_val['mb_left_request_date'] = today_time;
				$_val['mb_left_reason'] = $_POST['left_reason']=='etc' ? $_POST['content'] : $_POST['left_reason'];
				$q = $db->query_q($_val);
				$update = $db->_query("update nf_member set ".$q." where `no`=".intval($mno), $_val);
				$nf_member->logout();
				$arr['msg'] = "탈퇴가 완료되었습니다.";
			}
		}
		die(json_encode($arr));
	break;
	//################## 삭제 ##################//

	## : 기본 정보 ############################################




	default:

		switch($_GET['mode']) {

			###################솔루션########################
			case "get_map_shop":
				$re_arr = array();

				$search_where = $nf_search->shop();

				$_width_px = $nf_util->map_distance_px[$env['map_engine']][$_GET['zoom']];
				$_distance_px = $nf_util->map_distance[$env['map_engine']][$_GET['zoom']];
				$distance = ($_GET['width']/$_width_px*$_distance_px/1000/2); // : 1000->km로 변환, 2->반으로 나눔
				$_data = $_GET;
				if($_GET['lat']) $_data['lat'] = $_GET['lat'];
				if($_GET['lng']) $_data['lng'] = $_GET['lng'];
				$_field = $nf_util->distance_q($_data);
				$_data['type'] = 'where';
				$_data['distance'] = $distance;
				$distance_w = $nf_util->distance_q($_data);

				if($_GET['code']=='big_shop') $_where = "";
				else if(strpos($_GET['code'], 'm_')!==false) $_where = "";
				else $_where = $distance_w;

				$_map_shop_q = $_COOKIE['map_shop_q'];
				if($_SESSION['map_shop_q']) $_map_shop_q = $_SESSION['map_shop_q'];
				if(!$_map_shop_q) $_map_shop_q = "nf_shop as ns where 1 ".$nf_shop->shop_where.$nf_shop->service_where2.$search_where['where'];
				$re_arr['q'] = $_map_shop_q.$_where;
				$_SESSION['map_shop_q_list'] = $re_arr['q'];

				$total = $db->query_fetch("select count(*) as c from ".$re_arr['q']);
				$arr['limit'] = 6;

				$_arr = array();
				$_arr['tema'] = 'B';
				$_arr['num'] = $arr['limit'];
				$_arr['total'] = $total['c'];
				$_arr['click'] = 'js';
				$_arr['code'] = 'map';
				$_arr['page'] = $_GET['page'];
				$_arr['group'] = 5;
				$paging = $nf_util->_paging_($_arr);

				$re_arr['q_all'] = "select *, ".$_field." from ".$re_arr['q']." order by map_distance asc";
				$q_ = $db->_query($re_arr['q_all']);

				$re_arr['paging'] = $paging['paging'];
				$re_arr['total'] = $total['c'];

				$_km_loc = array();
				$_km_loc['this_lat'] = $_data['lat'];
				$_km_loc['this_lng'] = $_data['lng'];

				$cnt = 0;
				$page_cnt = ($_GET['page']-1)*$arr['limit'];
				$page_end = $page_cnt+$arr['limit'];
				while($shop_row=$db->afetch($q_)) {
					$shop_info = $nf_shop->shop_info($shop_row);
					if($shop_row['wr_lat']) {

						$_km_loc['lat'] = $shop_row['wr_lat'];
						$_km_loc['lng'] = $shop_row['wr_lng'];
						$km = $nf_util->get_distance($_km_loc);

						$map_div = "";
						if($page_cnt<=$cnt && $page_end>$cnt) {
							ob_start();
							include NFE_PATH.'/include/shop/shop_map.inc.php';
							$map_div = ob_get_clean();
						}

						ob_start();
						include NFE_PATH.'/include/shop/shop_map_marker.inc.php';
						$map_content = ob_get_clean();

						$re_arr['positions'][] = array('no'=>$shop_row['no'], 'lat'=>$shop_row['wr_lat'], 'lng'=>$shop_row['wr_lng'], 'map_div'=>$map_div, 'map_content'=>$map_content);
					}
					$cnt++;
				}

				$re_arr['detail_view'] = 'Y';
				$re_arr['js'] = '
				var html = "";
				for(x in data.positions) if(data.positions[x]["map_div"]) html += data.positions[x]["map_div"];
				$("#map-shop-paste-").html(html);
				$(".cnt-shop-total-").html(data.total);
				$(".map-shop-paging-").html(data.paging);
				';

				die(json_encode($re_arr));
			break;

			case "download_notice":
				$notice_row = $db->query_fetch("select * from nf_notice where `no`=".intval($_GET['no']));
				$file_arr = $nf_util->get_unse($notice_row['wr_file']);
				$file_name_arr = $nf_util->get_unse($notice_row['wr_file_name']);

				$get_file = $file_arr[$_GET['k']];
				$get_filename = $file_name_arr[$_GET['k']];

				if(is_file(NFE_PATH.'/data/notice/'.$get_file)) {
					$nf_util->file_download(NFE_PATH.'/data/notice/'.$get_file, $get_filename);
				}
				exit;
			break;
			###################솔루션########################



			###################모듈 시작######################

			case "sns_login_process":
				switch($_GET['engine']) {
					case "naver":
						$_POST = $_GET;
						$_POST['mode'] = "sns_login_process";
						$_POST['engine'] = "naver";
						include NFE_PATH."/plugin/login/regist.php";
					break;
				}
				exit;
			break;

			// : 비바톤 인증
			case "login_bbaton":

				function curl_func($url, $param, $headers, $method=1) {
					$cu = curl_init();
					curl_setopt ($cu, CURLOPT_URL, $url);
					curl_setopt ($cu, CURLOPT_POST, $method);
					if(is_array($param)) curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query($param)); 
					curl_setopt ($cu, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt ($cu, CURLOPT_TIMEOUT, 20);
					curl_setopt($cu, CURLOPT_HEADER, true);
					curl_setopt($cu, CURLOPT_HTTPHEADER, $headers);
					$response = curl_exec ($cu);
					curl_close($cu);
					return $response;
				}

				$url = "https://bauth.bbaton.com/oauth/token";
				$param = array(
					'grant_type'=>'authorization_code',
					'redirect_uri'=>$env['bbaton_redirect_uri'],
					'code'=>$_GET['code'],
				);

				$client_id = $env['bbaton_id'];
				$secret_key = $env['bbaton_key'];

				$headers = array(
					"Content-type: application/x-www-form-urlencoded",
					"Authorization: Basic ".base64_encode($client_id.':'.$secret_key)
				);

				$token = curl_func($url, $param, $headers);
				$txt_start = strpos($token, "{");
				$json_txt = substr($token, $txt_start);
				$json_arr = json_decode($json_txt, true);

				$url = "https://bapi.bbaton.com/v2/user/me";
				$param = "";
				$headers = array(
					"Authorization: ".$json_arr['token_type'].' '.$json_arr['access_token']
				);
				$user = curl_func($url, "", $headers, 0);
				$txt_start = strpos($user, "{");
				$json_txt = substr($user, $txt_start);
				$json_arr = json_decode($json_txt, true);

				$gender = $json_arr['gender']=='M' ? 'M' : 'F';
				$adult = $json_arr['adult_flag']=='Y' ? true : false;
				if($json_arr['birth_year']>=20) $adult = true;
				$_arr = array(
					'REQ_SEQ'=>"",
					'RES_SEQ'=>"",
					'AUTH_TYPE'=>"",
					'NAME'=>"",
					'BIRTHDATE'=>"",
					'GENDER'=>$gender,
					'NATIONALINFO'=>"",
					'DI'=>"",
					'CI'=>"",
					'MOBILE_NO'=>"",
					'MOBILE_CO'=>"",
					'adult'=>$adult,
					'age'=>$json_arr['birth_year'],
					'AUTH'=>$json_arr
				);

				if($json_arr['result_code']=='00') {
					$_SESSION['_auth_process_'] = $_arr;
				}

				if($json_arr['adult_flag']!='Y' && $env['use_adult']) {
				?>
					<script type="text/javascript">
					alert("성인만 접속이 가능합니다.");
					window.close();
					</script>
				<?php
				} else {
					$move_url = 'window.opener.location.href = "'.$_SESSION['page_code_auth'].'";';
					if(!$_SESSION['page_code_auth']) {
						$move_url = 'window.opener.location.reload();';
					}
					$_SESSION['page_code_auth'] = "";
				?>
					<script type="text/javascript">
					//window.opener = window.open('', "parent_auth");
					<?php echo $move_url;?>
					window.close();
					</script>
				<?php
				}
				exit;
			break;

			case "logout":
				$nf_member->logout();
				$arr['move'] = NFE_URL.'/';
				die($nf_util->move_url($arr['move']));
			break;

			###################모듈 끝######################
		}

	break;
}
?>