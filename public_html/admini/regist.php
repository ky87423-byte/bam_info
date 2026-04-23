<?php
if(!isset($_include)) $_include = "";
if(!isset($_POST['mode'])) $_POST['mode'] = "";
if(!isset($_GET['mode'])) $_GET['mode'] = "";

if(!$_include) {
	if($_POST['mode']=='ch_report_status') $add_cate_arr = array('job_employ_report_reason', 'job_resume_report_reason');

	if(in_array($_POST['mode'], array('cs_qna_write', 'open_box'))) $add_cate_arr = array('on2on', 'concert');

	// : 맞춤정보 메일보내기
	if(in_array($_POST['mode'], array('send_setting_mailing', 'get_customized'))) $add_cate_arr = array('subway', 'job_date', 'job_week', 'job_time', 'job_pay', 'job_pay_support', 'job_type', 'job_welfare', 'job_grade', 'job_position', 'job_age', 'job_target', 'job_document', 'job_tema', 'job_conditions');

	include "../engine/_core.php";
}

if(!in_array($_POST['mode'], array('login_process'))) {
	$nf_admin->check_admin($_POST['ajax']);
}

switch($_POST['mode']) {

	############################################
	case "repair_select_report":
		$nos = implode(",", $_POST['chk']);
		$row = $db->query_fetch("select group_concat(`pno`) as pno, `code` from nf_report where `no` in (".$nos.")");

		$_table = 'nf_'.$row['code'];
		$update = $db->_query("update nf_report set `status`=1 where `no` in (".$nos.")");
		$update = $db->_query("update ".$_table." set `wr_report`=1, `wr_report_date`=? where `no` in (".$row['pno'].")", array(today_time));

		$arr['msg'] = "복구로 상태변경이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "delete_select_report":
		$nos = implode(",", $_POST['chk']);
		$delete = $db->_query("delete from nf_report where `no` in (".$nos.")");
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "ch_report_status":
		$row = $db->query_fetch("select * from nf_report where `no`=?", array($_POST['no']));
		$_table = 'nf_'.$row['code'];
		$info_row = $db->query_fetch("select * from ".$_table." where `no`=?", array($row['pno']));
		$arr['msg'] = "정보가 없습니다.";
		if($info_row) {
			$report_con = $row['sel_reason'];
			$update = $db->_query("update nf_report set `status`=? where `no`=?", array($_POST['val'], $row['no']));
			$update = $db->_query("update ".$_table." set `wr_report`=?, `wr_report_date`=?, wr_report_content=? where `no`=?", array($_POST['val'], today_time, $report_con, $row['pno']));
			$arr['msg'] = "상태변경이 완료되었습니다.";
		}
		die(json_encode($arr));
	break;

	case "click_guide_view":
		$guide_row = $db->query_fetch("select * from nf_guide where `no`=".intval($_POST['no']));
		$arr['msg'] = "이용후기 정보가 없습니다.";
		$arr['move'] = $nf_util->page_back();
		if($guide_row) {
			$update = $db->_query("update nf_guide set `view`=".intval($_POST['val'])." where `no`=".intval($guide_row['no']));
			$nf_shop->save_guide_point_cnt($guide_row['pno']);
			$arr['msg'] = ($_POST['val'] ? '출력' : '미출력')."으로 설정했습니다.";
			$arr['move'] = "";
		}
		die(json_encode($arr));
	break;

	case "ch_guide_point":
		$guide_row = $db->query_fetch("select * from nf_guide where `no`=".intval($_POST['no']));
		$update = $db->_query("update nf_guide set `point`=".intval($_POST['val'])." where `no`=".intval($_POST['no']));
		// : 평점값, 후기수 재조정
		$nf_shop->save_guide_point_cnt($guide_row['pno']);
		$arr['msg'] = "별점 변경이 완료되었습니다.";
		die(json_encode($arr));
	break;

	case "guide_answer_write":
	case "qna_answer_write":
		$table_arr = array("qna_answer_write"=>"nf_qna", "guide_answer_write"=>"nf_guide");
		$_table = $table_arr[$_POST['mode']];
		$guide_row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no']));
		$arr['msg'] = "이용후기 정보가 없습니다.";
		$arr['move'] = $nf_util->page_back();
		if($guide_row) {
			$_val = array();
			if($db->is_field($_table, 'good')) $_val['good'] = intval($_POST['good']);
			if($db->is_field($_table, 'point')) $_val['point'] = intval($_POST['point']);
			$_val['subject'] = $_POST['subject'];
			$_val['content'] = $_POST['guide_content'];
			$_val['answer'] = 1;
			if($_POST['code']=='comment') $_val['a_content'] = $_POST['guide_a_content'];
			$q = $db->query_q($_val);
			$query = $db->_query("update ".$_table." set ".$q." where `no`=".intval($guide_row['no']), $_val);
			$arr['msg'] = "수정이 완료되었습니다.";
			if($_POST['code']=='comment') $arr['msg'] = "답변 등록(수정)이 완료되었습니다.";

			// : 평점값, 후기수 재조정
			if(in_array($_POST['mode'], array('guide_answer_write'))) {
				$nf_shop->save_guide_point_cnt($guide_row['pno']);
			}

		}
		die(json_encode($arr));
	break;
	case "open_guide_box":
	case "open_qna_box":
		$table_arr = array("open_guide_box"=>"nf_guide", "open_qna_box"=>"nf_qna");
		$_table = $table_arr[$_POST['mode']];
		$guide_row = $db->query_fetch("select * from ".$_table." where `no`=".intval($_POST['no']));
		$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($guide_row['pno']));
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($shop_row['mno']));
		$guide_row['content'] = stripslashes($guide_row['content']);
		$guide_row['a_content'] = stripslashes($guide_row['a_content']);
		$arr = $guide_row;
		$shop_info = $nf_shop->shop_info($shop_row);
		$arr['js'] = '
		obj.find(".img-").html(\'<img src="'.NFE_URL.$shop_info['main_photo_url'].'" style="width:80px;height:80px; float:left; margin-right:20px;" />\');
		obj.find(".shop-company--").html("'.$nf_util->get_text($shop_row['wr_company']).'");
		obj.find(".shop-subject--").html("'.$nf_util->get_text($shop_row['wr_subject']).'");
		obj.find(".shop-id--").html("'.$nf_util->get_text($shop_row['wr_id']).'");
		obj.find(".guide-name--").html("'.$nf_util->get_text($mem_row['mb_id']).'");
		obj.find(".guide-email--").html("'.$nf_util->get_text($mem_row['mb_email']).'");
		form.no.value = "'.intval($guide_row['no']).'";
		form.subject.value = "'.$nf_util->get_html($guide_row['subject']).'";
		if(form.point) form.point.value = "'.$nf_util->get_html($guide_row['point']).'";
		if(form.good) form.good.value = "'.intval($guide_row['good']).'";
		_editor_use["guide_content"].replaceContents(data.content);
		_editor_use["guide_a_content"].replaceContents(data.a_content);
		obj.css({"display":"block"});
		';
		die(json_encode($arr));
	break;

	// : 등록대기, 등록복구
	case "shop_select_wait":
	case "shop_select_wait_repair":
		$nos = implode(",", $_POST['chk']);
		$val = $_POST['mode']=='shop_select_wait_repair' ? 1 : 0;
		$update = $db->_query("update nf_shop set is_wait=".intval($val)." where `no` in (".$nos.")");
		$txt = $_POST['mode']=='shop_select_wait_repair' ? "등록복구" : "등록대기";
		$arr['msg'] = $txt."로 설정했습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 업체 카테고리 일괄 이동
	case "shop_category_move":
		$arr['msg'] = "";
		$arr['move'] = "";

		// 관리자 권한 확인
		if (!$is_admin) {
			$arr['msg'] = "관리자만 이용 가능합니다.";
			die(json_encode($arr));
			break;
		}

		// 선택 업체 검증
		$chk_arr = is_array($_POST['chk']) ? $_POST['chk'] : array();
		if (empty($chk_arr)) {
			$arr['msg'] = "카테고리를 변경할 업체를 선택해주세요.";
			die(json_encode($arr));
			break;
		}

		// SQL 인젝션 방지: 모두 정수 캐스팅
		$safe_nos = implode(",", array_map('intval', $chk_arr));

		$update_fields = array();
		$update_params = array();
		$changed_labels = array();

		// ── 지역 변경 ─────────────────────────────────────────────────────
		if (!empty($_POST['change_area'])) {
			$area_arr = is_array($_POST['wr_area'])
				? array_filter($_POST['wr_area'], function($v){ return $v !== ''; })
				: array();
			$update_fields[] = "`wr_area`=?";
			$update_params[] = $area_arr ? implode(",", $area_arr)."," : "";
			$changed_labels[] = "지역";
		}

		// ── 테마 변경 ─────────────────────────────────────────────────────
		if (!empty($_POST['change_tema'])) {
			$tema_arr = is_array($_POST['tema_category'])
				? array_filter($_POST['tema_category'], function($v){ return $v !== ''; })
				: array();
			$update_fields[] = "`wr_tema`=?";
			$update_params[] = $tema_arr ? implode(",", $tema_arr)."," : "";
			$changed_labels[] = "테마";
		}

		// ── 업종 변경 ─────────────────────────────────────────────────────
		if (!empty($_POST['change_category'])) {
			$category_arr = array();
			if (is_array($_POST['category'])) {
				foreach ($_POST['category'] as $k => $v) {
					if (is_array($v)) {
						$v_arr = array_diff($v, array(""));
						if (!empty($v_arr)) $category_arr[] = ",".implode(",", $v_arr).",";
					} elseif ($v !== "") {
						$category_arr[] = ",".$v.",";
					}
				}
			}
			$update_fields[] = "`wr_category`=?";
			$update_params[] = implode("\r\n", $category_arr);
			$changed_labels[] = "업종";
		}

		if (empty($update_fields)) {
			$arr['msg'] = "변경할 카테고리 항목(지역/테마/업종)을 하나 이상 선택해주세요.";
			die(json_encode($arr));
			break;
		}

		// 수정일 갱신
		$update_fields[] = "`wr_udate`=?";
		$update_params[] = today_time;

		$set_sql = implode(", ", $update_fields);
		$db->_query(
			"update nf_shop set {$set_sql} where `no` in ({$safe_nos})",
			$update_params
		);

		$cnt = count($chk_arr);
		$label_txt = implode("·", $changed_labels);
		$arr['msg'] = "{$cnt}개 업체의 [{$label_txt}] 카테고리가 변경되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 업체정보삭제, 업체정보복구
	case "delete_shop_repair":
	case "delete_shop":
		$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($_POST['no']));
		$val = $_POST['mode']=='delete_shop_repair' ? 0 : 1;
		if($_POST['mode']=='delete_shop' && $shop_row['is_delete']) {
			$photo_arr = explode(",", $shop_row['wr_photo']);
			if($_POST['mode']!='delete_shop_repair' && is_array($photo_arr)) { foreach($photo_arr as $k=>$v) {
				if(is_file(NFE_PATH.'/data/shop/'.$shop_row['wr_main_photo'])) unlink(NFE_PATH.'/data/shop/'.$shop_row['wr_main_photo']);
				if(is_file(NFE_PATH.'/data/shop/'.$v)) unlink(NFE_PATH.'/data/shop/'.$v);
			} }
			$query = $db->_query("delete from nf_shop where `no`=".intval($shop_row['no']));
			$arr['msg'] = "삭제가 완료되었습니다.";
		} else {
			$query = $db->_query("update nf_shop set `is_delete`=".intval($val)." where `no`=".intval($shop_row['no']));
			$arr['msg'] = $_POST['mode']=='delete_shop_repair' ? "복구가 완료되었습니다." : "삭제가 완료되었습니다.";
		}
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "click_coupon_use":
		$update = $db->_query("update nf_coupon_use set `use`=".intval($_POST['val'])." where `no`=".intval($_POST['no']));
		$arr['msg'] = ($_POST['val'] ? '사용' : '미사용')."으로 설정했습니다.";
		die(json_encode($arr));
	break;

	case "delete_select_coupon_use":
	case "delete_coupon_use":
		$nos = $_POST['mode']=='delete_select_coupon_use' ? implode(",", $_POST['chk']) : $_POST['no'];
		$delete = $db->_query("delete from nf_coupon_use where `no` in (".$nos.")");
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : BEST업체
	case "click_shop_best":
		$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($_POST['no']));
		$arr['msg'] = "업체정보가 없습니다.";
		if($shop_row) {
			$update = $db->_query("update nf_shop set `wr_best`=".intval($_POST['val'])." where `no`=".intval($shop_row['no']));
			$arr['msg'] = ($_POST['val'] ? 'BSET로 설정했습니다.' : 'BEST를 취소했습니다.');
		}
		die(json_encode($arr));
	break;

	/*상품 등록 아이콘 설정*/
	/*메인 중앙 아이콘 설정*/
	case "icon_write":
		$arr = array();
		$icon_row = $db->query_fetch("select * from nf_icon where `wr_id`=".intval($_POST['no']));
		if(!$icon_row) {
			$max_rank = $db->query_fetch("select max(`wr_rank`) as rank from nf_icon where `wr_code`=?", array($_POST['code']));
			$rank = $max_rank['rank']+1;
		}

		$tmp = $_FILES['file']['tmp_name'];
		$dir = $nf_shop->attach_dir['icon'];
		if($tmp) {
			$ext = $nf_util->get_ext($_FILES['file']['name']);
			if(in_array($ext, $nf_util->photo_ext)) {
				$fname = $_POST['code'].'_'.time().'.'.$ext;
				$nf_util->make_thumbnail($tmp, NFE_PATH.$dir.$fname, 800);
				if(is_file(NFE_PATH.$dir.$icon_row['wr_image'])) unlink(NFE_PATH.$dir.$icon_row['wr_image']);
			}
		}

		$_val = array();
		if(!$icon_row) {
			$_val['wr_code'] = $_POST['code'];
			$_val['wr_rank'] = $rank;
			$_val['wr_wdate'] = today_time;
			$_val['wr_use'] = 1;
		}
		$_val['wr_name'] = $_POST['name'];
		$_val['wr_link'] = $_POST['link'];
		if($fname) $_val['wr_image'] = $fname;
		$q = $db->query_q($_val);
		if($icon_row) $query = $db->_query("update nf_icon set ".$q." where `wr_id`=".intval($icon_row['wr_id']), $_val);
		else $query = $db->_query("insert into nf_icon set ".$q, $_val);
		$txt = $icon_row ? '수정' : '등록';
		$arr['msg'] = "상품등록 아이콘을 ".$txt."했습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "delete_icon":
	case "delete_select_icon":
		$dir = $nf_shop->attach_dir['icon'];
		$nos = $_POST['mode']=='delete_select_icon' ? implode(",", $_POST['chk']) : intval($_POST['no']);
		$icon_query = $db->_query("select * from nf_icon where `wr_id` in (".$nos.")");
		while($icon_row=$db->afetch($icon_query)) {
			if(is_file(NFE_PATH.$dir.$icon_row['wr_image'])) unlink(NFE_PATH.$dir.$icon_row['wr_image']);
			$delete = $db->_query("delete from nf_icon where `wr_id`=".intval($icon_row['wr_id']));
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "icon_rank_change":
		$arr = array();
		if(is_array($_POST['hidd'])) { foreach($_POST['hidd'] as $k=>$v) {
			$update = $db->_query("update nf_icon set `wr_rank`=".intval($_POST['rank'][$k])." where `wr_id`=".intval($v));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "icon_use":
		$arr = array();
		$cnt = $db->query_fetch("select count(*) as c from nf_icon where `wr_use`=1 and `wr_code`='main_icon'");
		if($cnt['c']>=8 && $_POST['val']>0) {
			$arr['msg'] = "최대 8개 까지 선택이 됩니다.";
			$arr['js'] = '$(el).prop("checked", false);';
		} else {
			$update = $db->_query("update nf_icon set `wr_use`=".intval($_POST['val'])." where `wr_id`=".intval($_POST['no']));
			$txt = $_POST['val'] ? '사용함' : '사용안함';
			$arr['msg'] = $txt."으로 설정했습니다.";
		}
		die(json_encode($arr));
	break;

	case "icon_type_save":
		$update = $db->_query("update nf_config set `icon_type`=?", array($_POST['icon_type']));
		$arr['msg'] = "아이콘 선택 저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "main_icon_type_save":
		$update = $db->_query("update nf_config set `main_icon_type`=?", array($_POST['main_icon_type']));
		$arr['msg'] = "아이콘 선택 저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	############################################





	case "admin_message_write":
		// : 상품관리에서 쪽지 선택보내기
		if(isset($_POST['nos']) && $_POST['nos']) {
			$m_query = $db->_query("select * from nf_member where `no` in (".$_POST['nos'].")");
			while($mrow=$db->afetch($m_query)) {
				$_val = array();
				$_val['code'] = 'admin';
				$_val['pno'] = 0;
				$_val['rno'] = 0;
				$_val['mno'] = 0;
				$_val['mb_id'] = '_admin_';
				$_val['mb_nick'] = '관리자';
				$_val['pmno'] = $mrow['no'];
				$_val['pmb_id'] = $mrow['mb_id'];
				$_val['pmb_nick'] = $mrow['mb_nick'];
				$_val['sdate'] = today_time;
				$_val['content'] = $_POST['content'];
				$q = $db->query_q($_val);
				$query = $db->_query("insert into nf_message set ".$q, $_val);
			}

		// : 기본 한명에게 답장 보내기
		} else {
			$row = $db->query_fetch("select * from nf_message where `no`=".intval($_POST['no']));
			$pmno = $row['mno'];
			$pmb_id = $row['mb_id'];
			$pmb_nick = $row['mb_nick'];
			if(!$row['mno']) {
				$pmno = $row['pmno'];
				$pmb_id = $row['pmb_id'];
				$pmb_nick = $row['pmb_nick'];
			}
			$_val = array();
			$_val['code'] = $row['code'];
			$_val['pno'] = $row['pno'];
			$_val['rno'] = $row['no'];
			$_val['mno'] = 0;
			$_val['mb_id'] = '_admin_';
			$_val['mb_nick'] = '관리자';
			$_val['pmno'] = $pmno;
			$_val['pmb_id'] = $pmb_id;
			$_val['pmb_nick'] = $pmb_nick;
			$_val['sdate'] = today_time;
			$_val['content'] = $_POST['content'];
			$q = $db->query_q($_val);
			$query = $db->_query("insert into nf_message set ".$q, $_val);
			$arr['move'] = $nf_util->page_back();
		}
		$arr['msg'] = "쪽지전송이 완료되었습니다.";
		$arr['js'] = '
		nf_util.openWin(".message-");
		';
		die(json_encode($arr));
	break;


	case "sms_select_send":

		$sms_member_json = explode(",", $_POST['sms_member']);
		if(is_array($sms_member_json)) { foreach($sms_member_json as $k=>$v) {
			$mem_row = $db->query_fetch("select * from nf_member where `mb_id`=?", array($v));
			$_val = array();
			$_val['code'] = 'admin';
			$_val['mno'] = 0;
			$_val['mb_id'] = '_admin_';
			$_val['mb_nick'] = '관리자';
			$_val['pmno'] = $mem_row['no'];
			$_val['pmb_id'] = $mem_row['mb_id'];
			$_val['pmb_nick'] = $mem_row['mb_nick'];
			$_val['sdate'] = today_time;
			$_val['content'] = $_POST['sms_content'];
			$q = $db->query_q($_val);

			$query = $db->_query("insert into nf_message set ".$q, $_val);
		} }
		$arr['msg'] = "쪽지전송이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], "쪽지 발송이 완료되었습니다."));
	break;

	case "delete_tax":
	case "delete_select_tax":
		$arr['msg'] = "삭제할 정보를 선택해주세요.";
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_tax') $nos = @implode(",", $_POST['chk']);
		if($nos) {
			$delete = $db->_query("delete from nf_tax where `no` in (".$nos.")");
			$arr['msg'] = "삭제가 완료되었습니다.";
		}
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "delete_message":
	case "delete_select_message":
		$nos = $_POST['mode']=='delete_message' ? $_POST['no'] : implode(",", $_POST['chk']);
		if($nos) {
			$delete = $db->_query("delete from nf_message where `no` in (".$nos.")");
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
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

	case "use_message":
		$dd = $db->_query("alter table nf_config add `use_message` tinyint default 1 comment '쪽지사용여부'");
		$update = $db->_query("update nf_config set `use_message`=".intval($_POST['val']));
		$arr['msg'] = "쪽지를 ".($_POST['val'] ? '사용함' : '사용안함').'으로 설정했습니다.';
		die(json_encode($arr));
	break;

	case "get_mail_skin":
		$arr = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array($_POST['val']));
		$arr['content'] = stripslashes($arr['content']);
		if(!$arr['content']) $arr['content'] = "";
		die(json_encode($arr));
	break;

	case "mail_skin_update":
		$row = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array($_POST['skin_name']));
		$_val = array();
		$_val['uid'] = $admin_info['wr_id'];
		$_val['skin_name'] = $_POST['skin_name'];
		$_val['content'] = $_POST['mail_content'];
		if($row) $_val['wdate'] = today_time;
		$q = $db->query_q($_val);

		if($row) $query = $db->_query("update nf_mail_skin set ".$q." where `no`=".intval($row['no']), $_val);
		else $query = $db->_query("insert into nf_mail_skin set ".$q, $_val);

		$arr['msg'] = ($row ? "수정" : "등록")."이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "login_process":
		$arr['msg'] = "관리자 아이디가 정확하지 않습니다.\n관리자 아이디를 확인해주세요.";
		$row = $db->query_fetch("select * from nf_admin where `wr_id`=? and `wr_password`=?", array($_POST['uid'], md5(trim($_POST['passwd']))));
		if($row) {
			$nf_admin->admin_login($row['wr_id']);
			$_val = array();
			$_val['wr_last_login'] = today_time;
			$_val['no'] = $row['no'];
			$update = $db->_query("update nf_admin set `wr_login`=`wr_login`+1, `wr_last_login`=? where `no`=?", $_val);
			$arr['msg'] = "";

			$get_sadmin = $nf_admin->get_sadmin($row['wr_id']);

			// : 최고관리자이거나 전체채용정보관리 권한이 있는경우 전체채용정보관리 리스트로
			if($row['wr_level']>=10 || in_array('100101', $get_sadmin['admin_menu_array']))
				$arr['move'] = NFE_URL."/admini/introduce/index.php";
			// : 그외는 부관리자권한의 저장 첫페이지로 이동
			else {
				$get_top_menu = $nf_admin->get_top_menu($get_sadmin['first_link']);
				$arr['move'] = $get_top_menu['sub_menu_url'];
			}
		}
		die(json_encode($arr));
	break;

	case 'quick_insert':

		$uid = $admin_info['wr_id'];
		$top_menu_code = $_POST['top_menu_code'];
		$middle_menu_code = $_POST['middle_menu_code'];
		$sub_menu_code = $_POST['sub_menu_code'];

		$query = " select * from nf_admin_quick where `wr_id`=? and `wr_top_menu_code`=?";
		$result = $db->_queryR($query, array($uid, $top_menu_code));

		if($result){
			$arr['msg'] = "이미 추가하신 메뉴 입니다.";
		} else {

			$get_top_menu = $nf_admin->get_top_menu($top_menu_code);
			$top_menu_code_head = $get_top_menu['top_menu_code_head'];
			$top_menu_code_middle = $get_top_menu['top_menu_code_middle'];
			$top_menu_txt = $get_top_menu['top_menu_txt'];
			$middle_menu_txt = $get_top_menu['middle_menu_txt'];
			$sub_menu_txt = $get_top_menu['sub_menu_txt'];
			$sub_menu_url = $get_top_menu['sub_menu_url'];

			$_val = array();
			$_val['wr_id'] = $uid;
			$_val['wr_top_menu_code'] = $top_menu_code;
			$_val['wr_wdate'] = today_time;
			$q = $db->query_q($_val);

			$query = "insert into nf_admin_quick set ".$q;
			$result = $db->_query($query, $_val);
			$arr['msg'] = "퀵 메뉴에 추가 되었습니다.";
			$arr['js'] = '
				$(".nav_quick").find("dl").append(\'<dd><a href="'.$sub_menu_url.'">'.$sub_menu_txt.'</a><a onclick="del_favorite(this)" code="'.$top_menu_code.'">X</a></dd>\');
			';
		}
		die(json_encode($arr));

	break;

	## 퀵 메뉴 삭제
	case 'quick_delete':

		$row = $db->query_fetch("select * from nf_admin_quick where `wr_id`=? and `wr_top_menu_code`=?", array($admin_info['wr_id'], $_POST['top_menu_code']));

		$arr['msg'] = "이미 삭제된 퀵메뉴입니다.";
		if($row) {
			$delete = $db->_query("delete from nf_admin_quick where `no`=".intval($row['no']));
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['js'] = '
				$(el).closest("dd").remove();
			';
		}
		die(json_encode($arr));

	break;

	// : 기본환경설정
	case "config_update":
		$tmp_file = $_FILES['favicon']['tmp_name'];
		if($tmp_file) {
			$ext = $nf_util->get_ext($_FILES['favicon']['name']);
			if(in_array($ext, $nf_util->favicon_ext)) {
				$favicon = 'favicon.'.$ext;
				if(is_file(NFE_PATH.'/data/favicon/'.$env['favicon'])) unlink(NFE_PATH.'/data/favicon/'.$env['favicon']);
				$nf_util->make_thumbnail($tmp_file, NFE_PATH.'/data/favicon/'.$favicon, 200, 200, "100");
			}
		}

		$sns_feed = $_POST['sns_feed'][0] ? implode(",", $_POST['sns_feed']) : "";
		$sns_login_feed = $_POST['sns_login_feed'][0] ? implode(",", $_POST['sns_login_feed']) : "";
		$_val = array();
		$_val['site_title'] = $_POST['site_title'];
		$_val['site_name'] = $_POST['site_name'];
		$_val['site_english'] = $_POST['site_english'];
		$_val['email'] = $_POST['email'];
		$_val['call_center'] = $_POST['call_center'];
		$_val['hphone'] = $_POST['hphone'];
		$_val['call_time'] = $_POST['call_time'];
		$_val['pay_view'] = intval($_POST['pay_view']);
		$_val['pay_year'] = intval($_POST['pay_year']);
		$_val['time_pay'] = intval($_POST['time_pay']);
		$_val['use_digital'] = intval($_POST['use_digital']);
		$_val['digital_content'] = $_POST['digital_content'];
		$_val['login_return'] = intval($_POST['login_return']);
		$_val['login_return_page'] = $_POST['login_return_page'];
		$_val['session_time'] = $_POST['session_time'];
		$_val['editor_max_size'] = $_POST['editor_max_size'];
		$_val['use_direct'] = intval($_POST['use_direct']);
		$_val['use_ipin'] = intval($_POST['use_ipin']);
		$_val['ipin_id'] = $_POST['ipin_id'];
		$_val['use_hphone'] = $_POST['use_hphone'];
		$_val['hphone_id'] = $_POST['hphone_id'];
		$_val['use_bbaton'] = $_POST['use_bbaton'];
		$_val['bbaton_id'] = $_POST['bbaton_id'];
		$_val['bbaton_key'] = $_POST['bbaton_key'];
		$_val['use_auth_member'] = $_POST['use_auth_member'];
		$_val['use_adult'] = $_POST['use_adult'];
		$_val['article_denied'] = intval($_POST['article_denied']);
		$_val['google_recaptcha'] = intval($_POST['google_recaptcha']);
		$_val['google_recaptcha_site'] = $_POST['google_recaptcha_site'];
		$_val['google_recaptcha_secret'] = $_POST['google_recaptcha_secret'];
		$_val['under_construction'] = intval($_POST['under_construction']);
		$_val['rss_feed'] = intval($_POST['rss_feed']);
		$_val['sns_feed'] = $sns_feed;
		$_val['sns_login_feed'] = $sns_login_feed;
		$_val['facebook_appid'] = $_POST['facebook_appid'];
		$_val['facebook_secret'] = $_POST['facebook_secret'];
		$_val['twitter_key'] = $_POST['twitter_key'];
		$_val['twitter_secret'] = $_POST['twitter_secret'];
		$_val['kakao'] = $_POST['kakao'];
		$_val['naver_id'] = $_POST['naver_id'];
		$_val['naver_secret'] = $_POST['naver_secret'];
		$_val['meta_author'] = $_POST['meta_author'];
		$_val['meta_description'] = $_POST['meta_description'];
		$_val['meta_copyright'] = $_POST['meta_copyright'];
		$_val['meta_keywords'] = $_POST['meta_keywords'];
		$_val['meta_classifiction'] = $_POST['meta_classifiction'];
		$_val['meta_publisher'] = $_POST['meta_publisher'];
		$_val['head_scripts'] = $_POST['head_scripts'];
		$_val['intercept_ip'] = $_POST['intercept_ip'];

		$_val['board_hit_date'] = $_POST['board_hit_date'];

		if($favicon) $_val['favicon'] = $favicon;

		$q = $db->query_q($_val);
		$query = $db->_query("update nf_config set ".$q, $_val);

		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], "설정이 완료되었습니다."));
	break;

	case "content_write":
		$update = $db->_query("update nf_config set `content_".addslashes($_POST['code'])."`=?", array($_POST['content']));
		die($nf_util->move_url($nf_util->page_back(), "설정이 완료되었습니다."));
	break;

	// : 취소/환불설정
	case "cancel_info_update":
		$update = $db->_query("update nf_config set `wr_remind_info`='".addslashes($_POST['wr_remind_info'])."', `wr_refund_info`=?", array($_POST['wr_refund_info']));
		die($nf_util->move_url($nf_util->page_back(), "설정이 완료되었습니다."));
	break;

	// : 상품 서비스 마감 안내 메일 설정
	case "service_end_email_update":
		$_val = array();
		$_val['use_service_end_email'] = intval($_POST['use_service_end_email']);
		$_val['service_end_email'] = implode(",", $_POST['service_end_email']);
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_config set ".$q, $_val);
		die($nf_util->move_url($nf_util->page_back(), "설정이 완료되었습니다."));
	break;

	// : 휴면회원 설정
	case "dormancy_config_update":
		$_val = array();
		$_val['use_dormancy'] = intval($_POST['use_dormancy']);
		$_val['dormancy_last_login'] = intval($_POST['dormancy_last_login']);
		$_val['dormancy_schedule_last_login'] = intval($_POST['dormancy_schedule_last_login']);
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_config set ".$q, $_val);
		die($nf_util->move_url($nf_util->page_back(), "설정이 완료되었습니다."));
	break;


	## : SMS 관련 ################################################
	case "add_sms_member":
		$nos = implode(",", $_POST['chk']);
		$arr['msg'] = "회원을 선택해주시기 바랍니다.";
		if($nos) {
			$arr['msg'] = "";
			$query = $db->_query("select * from nf_member where `no` in (".$nos.")");
			$arr['js'] = '';
			while($row=$db->afetch($query)) {
				$arr['js'] .= '
				if(!in_array("'.$row['mb_hphone'].'", phone_list_array)) {
					phone_list_array.push("'.$row['mb_hphone'].'");
					no_list_array.push('.intval($row['no']).');
				}
				';
			}
			$arr['js'] .= '
			$(fsms).find("[name=\'no_list\']").val(no_list_array.join("\r\n"));
			$(fsms).find("[name=\'rphone_list\']").val(phone_list_array.join("\r\n"));
			';
		}
		die(json_encode($arr));
	break;

	case "member_ajax_search":

		if(!$_include) {
			$_GET = $_POST;
			$_SESSION['member_sms-ajax-get'] = serialize($_GET);
		} else {
			$_GET = unserialize($_SESSION['member_sms-ajax-get']);
		}

		$where_arr = $nf_search->member();
		$_where = $where_arr['where'];

		$q = "nf_member as nm where `mb_sms`=1 and mb_left=0 and mb_left_request=0 and `is_delete`=0 and mb_badness=0 ".$_where;
		$order = " order by `no` desc";
		$total = $db->query_fetch("select count(*) as c from ".$q);

		$_arr = array();
		$_arr['num'] = 5;
		$_arr['click'] = 'js';
		$_arr['code'] = 'member_sms';
		if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
		$_arr['total'] = $total['c'];
		$paging = $nf_util->_paging_($_arr);
		$mem_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

		ob_start();
		switch($_arr['total']<=0) {
			case true:
		?>
		<tr><td colspan="6" class="no_list"></td></tr>
		<?php
			break;


			default:
				while($mem_row=$db->afetch($mem_query)) {
					$update_url = $mem_row['mb_type']=='company' ? './company_insert.php' : './individual_insert.php';

					$receive_arr = explode(",", $mem_row['mb_receive']);
		?>
		<tr class="tac">
			<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
			<td><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
			<td><?php echo $nf_util->get_text($mem_row['mb_name']);?></td>
			<td><?php echo $nf_util->get_text($mem_row['mb_id']);?></td>
			<td><?php echo $nf_util->get_text($mem_row['mb_hphone']);?></td>
			<td><?php echo in_array('sms', $receive_arr) ? '허용' : '<span class="gray">거부</span>';?></td>
		</tr>
		<?php
				}
			break;
		}
		$arr['tag'] = ob_get_clean();
		$arr['paging'] = $paging['paging'];
		$arr['js'] = '
		$("#member_tr-").html(data.tag);
		$("#member_paging-").html(data.paging);
		';

		die(json_encode($arr));
	break;

	case "get_sms_msg":
		$row = $db->query_fetch("select * from nf_sms_msg where `wr_type`='".addslashes($_POST['k'])."'");
		$arr['row'] = $row;
		$arr['js'] = '
		obj.val(data.row.wr_content);
		$(form).find("#span_bytes").html(nf_util.length_count(obj[0]));
		';
		die(json_encode($arr));
	break;

	case "sms_send":
		$arr['msg'] = "";
		$arr['js'] = "";
		if($_POST['no_list']) {
			if(in_array($_POST['code'], array('member_write', 'member_modify', 'member_secession', 'passwd_find'))) $_table = 'nf_member';
			if(in_array($_POST['code'], array('online_pay_process'))) $_table = 'nf_payment';
			if(in_array($_POST['code'], array('shop_list'))) $_table = 'nf_shop';
			if(!$_table) $_table = "nf_member";

			$phone_arr = explode("\n", $_POST['rphone_list']);
			$no_arr = explode("\n", $_POST['no_list']);
			if(is_Array($phone_arr)) { foreach($phone_arr as $k=>$v) {
				$member_no = $no_arr[$k];
				$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($member_no));

				switch($_POST['code']) {
					case "member_write":
						$sms_arr['name'] = $get_member['mb_name'];
						$sms_arr['phone'] = $v;
						$result = $nf_sms->send_sms_member_write($sms_arr);
					break;

					case "member_modify":
						$sms_arr['name'] = $get_member['mb_name'];
						$sms_arr['phone'] = $v;
						$result = $nf_sms->send_sms_member_write($sms_arr);
					break;

					case "member_secession":
						$sms_arr['name'] = $get_member['mb_name'];
						$sms_arr['phone'] = $v;
						$result = $nf_sms->send_sms_member_secession($sms_arr);
					break;

					case "passwd_find":
						$sms_arr['name'] = $get_member['mb_name'];
						$sms_arr['phone'] = $v;
						$sms_arr['mb_id'] = $get_member['mb_id'];
						$sms_arr['mb_password'] = $get_member['mb_password'];
						$result = $nf_sms->send_sms_passwd_find($sms_arr);
					break;

					case "shop_list":
						$sms_arr['name'] = $get_member['wr_name'];
						$sms_arr['phone'] = $v;
						$sms_arr['mb_id'] = $get_member['wr_id'];
						$sms_arr['msg'] = $_POST['send_msg'];
						$result = $nf_sms->send_sms_shop_admin_send($sms_arr);
						$arr['js'] = '
						open_box(this, "sms-", "none");
						';
					break;

					// : 관리자입력
					default:
						if($nf_sms->config['wr_use']) {
							$sms_destination = $v.'|'.$get_member['mb_name'];
							$nf_sms->netfu_sms_Send($_POST['send_msg'], $v, $env['call_center'], $sms_destination, array( 'mb_name' => $get_member['mb_name'] ) );
						}
					break;
				}
			} }
			$arr['msg'] = $nf_sms->config['wr_use'] ? "전송이 완료되었습니다." : "SMS 사용함으로 변경해주세요.";
			$arr['js'] .= '
			$(".sms_reserve_body_").css({"display":"none"});
			';
		}
		die(json_encode($arr));
	break;
	## : SMS 관련 ################################################


	## : 이메일
	case "mail_list_send":
		$receive_no_arr = explode(",", $_POST['receive_no']);
		$receive_email_arr = explode(",", $_POST['receive_mail']);
		$arr['msg'] = "메일보낼 회원이 없습니다.";

		$_POST['code'] = $_POST['code'] ? $_POST['code'] : 'all';
		switch($_POST['code']) {
			case "all":
				$cnt = 0;
				$arrarr = array(0=>'netk@daum.net', 1=>'netkorea10@naver.com');
				$query = $db->_query($_SESSION['send_email_member']);
				while($row=$db->afetch($query)) {
					if($row) {
						$dfdfd = $cnt%2;
						$email_arr['subject'] = stripslashes($_POST['subject']);
						$email_arr['email'] = $row['mb_email'];
						$email_arr['content'] = strtr(stripslashes($_POST['email_content']), $nf_email->ch_content($row));
						$nf_email->send_mail($email_arr);
						$cnt++;
					}
				}
				$arr['msg'] = "메일발송이 완료되었습니다.";
			break;

			case "check":
				if(is_array($receive_no_arr)) { foreach($receive_no_arr as $k=>$v) {
					$row = $db->query_fetch("select * from nf_member where `no`=".intval($v));
					if($row) {
						$email_arr['subject'] = stripslashes($_POST['subject']);
						$email_arr['email'] = $receive_email_arr[$k] ? $receive_email_arr[$k] : $row['mb_email'];
						$email_arr['content'] = strtr(stripslashes($_POST['email_content']), $nf_email->ch_content($row));
						$nf_email->send_mail($email_arr);
					}
				} }
				$arr['msg'] = "메일발송이 완료되었습니다.";
			break;
		}
		$arr['js'] = '
		open_box(this, "email-", "none");
		';
		die(json_encode($arr));
	break;
	case "member_email_send":
		$receive_no_arr = explode(",", $_POST['receive_no']);
		$receive_email_arr = explode(",", $_POST['receive_mail']);
		$arr['msg'] = "메일보낼 회원이 없습니다.";
		if($receive_no_arr[0]) {
			$nos = $_POST['receive_no'];

			if(is_array($receive_no_arr)) { foreach($receive_no_arr as $k=>$v) {
				$row = $db->query_fetch("select * from nf_member where `no`=".intval($v));
				if($row) {
					$email_arr['subject'] = stripslashes($_POST['subject']);
					$email_arr['email'] = $receive_email_arr[$k] ? $receive_email_arr[$k] : $row['mb_email'];

					$ch_arr = $nf_email->ch_content($row);
					switch($_POST['code']) {
						// : 휴면예정회원
						case "email-dormancy_ing":
							$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='dormancy_ing'");
							$ch_arr = $nf_email->ch_content($row);
							$ch_arr['{휴면예정일}'] = date("Y-m-d", strtotime($env['dormancy_last_login'].' day '.$row['mb_last_login']));
						break;
					}

					$email_arr['content'] = strtr(stripslashes($_POST['email_content']), $ch_arr);
					$nf_email->send_mail($email_arr);
				}
			} }
			$arr['msg'] = "메일발송이 완료되었습니다.";
			$arr['js'] = '
			open_box(this, "email-", "none");
			';
		}
		die(json_encode($arr));
	break;
	## : 이메일

	## : 고객문의 관련 ################################################
	case "delete_select_cs":
	case "delete_cs":
		$arr['msg'] = "삭제할 정보를 선택해주세요.";
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_cs') $nos = @implode(",", $_POST['chk']);
		if($nos) {
			$delete = $db->_query("delete from nf_cs where `no` in (".$nos.")");
			$arr['msg'] = "삭제가 완료되었습니다.";
		}
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "cs_qna_write":
		$arr['msg'] = "답변할 정보가 없습니다.";
		$arr['move'] = $nf_util->page_back();
		$row = $db->query_fetch("select * from nf_cs where `no`=".intval($_POST['no']));
		if($row) {
			$_val = array();
			$mail_subject = $_val['wr_asubject'] = "[".$env['site_name']."] `".$row['wr_subject']."` 에 대한 답변입니다.";
			$_val['wr_aname'] = $_POST['wr_aname'];
			$_val['wr_acontent'] = $_POST['wr_acontent'];
			$wr_adate = $_val['wr_adate'] = today_time;
			$q = $db->query_q($_val);
			$query = $db->_query("update nf_cs set ".$q." where `no`=".intval($row['no']), $_val);
			if($query) {
				$update = $db->_query("update nf_cs set `wr_result`=1 where `no`=".intval($row['no']));
				$cate_k = $row['wr_type']==='1' ? 'concert' : 'on2on';
				$wr_cate = $cate_p_array[$cate_k][0][$row['wr_cate']]['wr_name'];
				$ch_email_text = array("{문의등록일}"=>$row['wr_date'], "{문의답변일}"=>$wr_adate, "{문의종류}"=>$wr_cate, "{문의제목}"=>$row['wr_subject'], "{문의내용}"=>stripslashes($row['wr_content']), "{답변내용}"=>stripslashes($_POST['wr_acontent']));
				$email_arr = array();
				$mail_skin_k = $row['wr_type']==='1' ? 'concert' : 'qna';
				$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='".$mail_skin_k."'");
				$email_arr['subject'] = $mail_subject;
				$email_arr['email'] = $row['wr_email'];
				$email_content = strtr($mail_skin['content'], $ch_email_text);
				$email_arr['content'] = strtr(stripslashes($email_content), $nf_email->ch_content($_val));
				$nf_email->send_mail($email_arr);
			}

			$arr['msg'] = "답변이 완료되었습니다.";
		}
		die(json_encode($arr));
	break;
	## : 고객문의 관련 ################################################


	## : 회원 관련 ################################################
	case "delete_member_level":
		unset($env['member_level_arr'][$_POST['sun']]);
		$member_level_arr = array();
		$k_cnt = 0;
		if(is_array($env['member_level_arr'])) { foreach($env['member_level_arr'] as $k=>$arr_level) {
			$member_level_arr[$k_cnt] = $arr_level;
			$k_cnt++;
		} }
		$update = $db->_query("update nf_config set member_level=?", array(serialize($member_level_arr)));

		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 휴면해제
	case "ch_dormancy":
	case "ch_select_dormancy":
		$nos = $_POST['mode']=='ch_select_dormancy' ? implode(",", $_POST['chk']) : $_POST['no'];
		$db->_query("update nf_member set is_dormancy=0, mb_last_login=? where `no` in (".$nos.")", array(today_time));
		$arr['msg'] = "휴면을 해제했습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 탈퇴승인
	case "click_member_left":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['no']));
		$arr['msg'] = "회원이 없습니다.";
		if($mem_row) {
			$update = $db->_query("update nf_member set `mb_left`=1, mb_left_date=? where `no`=".intval($mem_row['no']), array(today_time));
			$arr['msg'] = "탈퇴를 승인했습니다.";
		}
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 회원메일발송
	case "send_email_member":
		switch($_POST['code']) {
			case "all":
				$query = $db->_query($_SESSION['send_email_member']);
			break;

			case "check":
				$nos = implode(",", $_POST['chk']);
				$arr['msg'] = "회원을 선택해주시기 바랍니다.";
				if($nos) {
					$arr['msg'] = "";
					$query = $db->_query("select * from nf_member where `no` in (".$nos.") order by `no` desc");
				}
			break;
		}

		if($query) {
			while($mem_row=$db->afetch($query)) {
			}
		}
	break;

	case "user_login_process":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['no']));
		$nf_member->login($mem_row['no']);
		$arr['move'] = "/";
		die(json_encode($arr));
	break;

	case "find_member":
		//이름,아이디,이메일로 검색
		$query = $db->_query("select * from nf_member where mb_type=? and (`mb_name`=? or `mb_id`=? or mb_email=?)", array($_POST['kind'], $_POST['val'], $_POST['val'], $_POST['val']));
		$nums = $db->num_rows($query);

		$tag = '';
		$mno_int = "";
		while($row=$db->afetch($query)) {
			$mno_int = $row['no'];
			$tag .= '<li><a onClick="put_member(\''.$row['no'].'\')">'.$row['wr_name'].'('.$row['mb_id'].') / '.$row['mb_email'].'</a></li>';
		}
		if(!$tag) $tag = '<li>검색된 결과가 없습니다. 이름,아이디,이메일을 정확히 입력해주시기 바랍니다.</li>';
		$arr['tag'] = $tag;
		$arr['js'] = '
		$(".find_member_put-").html(data.tag);
		';

		// : 한명인경우에는 자동선택
		if($nums==1) $arr['js'] .= '
		put_member("'.$mno_int.'");
		';
		die(json_encode($arr));
	break;

	case "put_member":
		$arr['msg'] = "회원이 없습니다.";
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['no']));
		if($mem_row) {
			$table = "nf_".$_POST['code'];
			$em_query = $db->_query("select * from ".$table." where `mno`=".intval($mem_row['no'])." order by `no` desc");
			$option = '';
			while($row=$db->afetch($em_query)) {
				$option .= '<option value="'.$row['no'].'">'.$row['wr_subject'].'</option>';
			}
			$arr['option'] = $option;
			$arr['msg'] = "";
			$arr['js'] = '
			var first_txt = $(form).find("[name=\'load_'.addslashes($_POST['code']).'\']").find("option").eq(0).text();
			form.mno.value = "'.$mem_row['no'].'";
			$(form).find("[name=\'load_'.addslashes($_POST['code']).'\']").html(\'<option value="">\'+first_txt+\'</option>\'+data.option);
			';
			die(json_encode($arr));
		}
	break;

	// : 회원등급 수정
	case "member_level_update":

		if(is_array($_FILES['img']['tmp_name'])) { foreach($_FILES['img']['tmp_name'] as $k=>$tmp) {
			$fname = $_FILES['img']['name'][$k];
			$ext = $nf_util->get_ext($fname);
			if($tmp && in_Array($ext, $nf_util->photo_ext)) {
				$icon_name = time().'.'.$k.'.'.$ext;

				if(move_uploaded_file($tmp, NFE_PATH.$nf_member->attach['member_level'].$icon_name)) {
					if(is_file(NFE_PATH.$nf_member->attach['member_level'].$env['member_level_arr'][$k]['icon']))
						unlink(NFE_PATH.$nf_member->attach['member_level'].$env['member_level_arr'][$k]['icon']);
				}

				$icon_arr[$k] = $icon_name;
			} else {
				$icon_arr[$k] = $env['member_level_arr'][$k]['icon'];
			}
		} }

		$member_level = array();
		if(is_array($_POST['view'])) { foreach($_POST['view'] as $k=>$view) {
			$rank = $_POST['rank'][$k]=='first' ? -999999 : intval($_POST['rank'][$k]);
			$member_level[$rank]['view'] = intval($_POST['view'][$k]);
			$member_level[$rank]['name'] = $_POST['name'][$k];
			$member_level[$rank]['point'] = $_POST['point'][$k];
			$member_level[$rank]['icon'] = $icon_arr[$k];
		} }

		ksort($member_level);
		$member_level = array_values($member_level);

		$update = $db->_query("update nf_config set member_level=?", array(serialize($member_level)));

		$arr['msg'] = "등급 설정이 완료되었습니다.";
		die($nf_util->move_url($nf_util->page_back(), $arr['msg']));
	break;

	// : 기본포인트설정
	case "member_point_config_update":
		$post_arr = $_POST;
		unset($post_arr['mode']);

		$update = $db->_query("update nf_config set member_point=?", array(serialize($post_arr)));

		$arr['msg'] = "기본포인트 설정이 완료되었습니다.";
		die($nf_util->move_url($nf_util->page_back(), $arr['msg']));
	break;

	// : 회원삭제
	case "delete_select_member":
		$arr['msg'] = "삭제할 회원이 없습니다.";
		if($_POST['chk'][0]) $nos = implode(",", $_POST['chk']);
		if($nos) {
			$query = $db->_query("select * from nf_member where `no` in (".$nos.")");
			while($row=$db->afetch($query)) {
				// : 채용정보 삭제
				// : 기타 정보 어느거 삭제할것인가?
			}
		}
		exit;
	break;
	## : 회원 관련 ################################################

	## : 포인트 관련 ################################################
	case "point_write":
		$arr['msg'] = "검색된 회원이 없습니다.\n다시 확인해서 입력해주시기 바랍니다.";
		$get_member = $db->query_fetch("select * from nf_member where `mb_id`=? order by `no` desc limit 1", array(trim($_POST['mid'])));
		$input_point = intval(strtr($_POST['point'], array(','=>'')));
		if($get_member && abs($input_point)>0) {
			$arr['msg'] = "포인트 지급이 완료되었습니다.";
			if($input_point<0) $arr['msg'] = "포인트 차감이 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
			$point_arr = array();
			$point_arr['member'] = $get_member;
			$point_arr['code'] = $_POST['code'];
			$point_arr['use_point'] = $input_point;
			$point_arr['rel_id'] = "";
			$point_arr['rel_action'] = "수동입력";
			$point_arr['rel_table'] = 'netk_point';
			$nf_point->insert($point_arr);
		}
		die(json_encode($arr));
	break;

	case "delete_select_point":
		$nos = implode(",", $_POST['chk']);
		$arr['msg'] = "삭제할 정보가 없습니다.";
		$arr['move'] = $nf_util->page_back();
		if($nos) {
			$delete = $db->_query("delete from nf_point where `no` in (".$nos.")");
			$arr['msg'] = "삭제가 완료되었습니다.\n포인트를 줘야한다면 포인트관리에서 포인트를 수동으로 부여해주시기 바랍니다.";
		}
		die(json_encode($arr));
	break;
	## : 포인트 관련 ################################################


	## : 설문조사 관련 ################################################
	case "poll_write":
		$row = $db->query_fetch("select * from nf_poll where `no`=".intval($_POST['no']));
		$_val = array();
		$_val['poll_wdate'] = $_POST['poll_wdate'];
		$_val['poll_edate'] = $_POST['poll_edate'];
		$_val['poll_member'] = $_POST['poll_member'];
		$_val['poll_overlap'] = $_POST['poll_overlap'];
		$_val['poll_subject'] = $_POST['poll_subject'];
		$_val['poll_content'] = serialize($_POST['content']);
		$_val['poll_date'] = today_time;
		$q = $db->query_q($_val);

		if($row) $db->_query("update nf_poll set ".$q." where `no`=".intval($row['no']), $_val);
		else $db->_query("insert into nf_poll set ".$q, $_val);

		$arr['msg'] = ($row ? '수정' : '등록').'이 완료되었습니다.';

		die($nf_util->move_url($nf_util->page_back(), $arr['msg']));
	break;

	case "delete_poll":
	case "delete_select_poll":
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_poll') $nos = @implode(",", $_POST['chk']);
		$delete = $db->_query("delete from nf_poll where `no` in (".$nos.")");
		$delete = $db->_query("delete from nf_poll_comment where `p_no` in (".$nos.")");
		$delete = $db->_query("delete from nf_poll_member where `pno` in (".$nos.")");
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "get_poll":
		$row = $db->query_fetch("select * from nf_poll where `no`=".intval($_POST['no']));
		$arr['msg'] = "삭제된 설문조사정보입니다.";
		if($row) {
			$poll_content = $nf_util->get_unse($row['poll_content']);
			ob_start();
			include NFE_PATH.'/admini/include/poll.inc.php';
			$arr['tag'] = ob_get_clean();
			$arr['msg'] = "";
			$arr['js'] = '
			if($(".pop-'.$row['no'].'")[0]) $(".pop-'.$row['no'].'").css({"display":"block"});
			else $(".paste-layer-pop").append(data.tag);
			$( ".draggable_move" ).draggable();
			';
		}
		die(json_encode($arr));
	break;

	case "poll_view":
		$row = $db->query_fetch("select * from nf_poll where `no`=".intval($_POST['no']));
		$arr['msg'] = "삭제된 설문조사정보입니다.";
		if($row) {
			$use = $row['use'] ? 0 : 1;
			$update = $db->_query("update nf_poll set `use`=".intval($use)." where `no`=".intval($row['no']));
			$view_txt = $use ? '사용함' : '시용안함';
			$arr['msg'] = $view_txt.'으로 변경했습니다.';
		}
		die(json_encode($arr));
	break;

	case "poll_view_main":
		$row = $db->query_fetch("select * from nf_poll where `no`=".intval($_POST['no']));
		$arr['msg'] = "삭제된 설문조사정보입니다.";
		if($row) {
			$update = $db->_query("update nf_poll set `view_main`=0");
			$update = $db->_query("update nf_poll set `view_main`=1 where `no`=".intval($row['no']));
			$arr['msg'] = "설문조사 메인 출력 설정이 변경되었습니다.";
		}
		die(json_encode($arr));
	break;
	## : 설문조사 관련 ################################################


	## : 공지사항 관련 ################################################
	case "notice_write":
		$notice_row = $db->query_fetch("select * from nf_notice where `no`=".intval($_POST['no']));
		$notice_filename_arr = $nf_util->get_unse($notice_row['wr_file_name']);
		$notice_file_arr = $nf_util->get_unse($notice_row['wr_file']);

		$file_name = array();
		$fname_arr = array();
		if(is_array($_FILES['wr_file']['tmp_name'])) { foreach($_FILES['wr_file']['tmp_name'] as $k=>$tmp) {
			if($tmp) {
				$fname_arr[$k] = $_FILES['wr_file']['name'][$k];
				$ext = $nf_util->get_ext($fname_arr[$k]);
				if(in_array($ext, $nf_util->photo_ext)) {
					$file_name[$k] = 'notice'.$k.'_'.time().'.'.$ext;
					if(move_uploaded_file($tmp, NFE_PATH.'/data/notice/'.$file_name[$k])) {
						if(is_file(NFE_PATH.'/data/notice/'.$notice_file_arr[$k])) unlink(NFE_PATH.'/data/notice/'.$notice_file_arr[$k]);
					}
				}
			} else {
				$fname_arr[$k] = $notice_filename_arr[$k];
				$file_name[$k] = $notice_file_arr[$k];
			}
		} }

		$_val = array();
		$_val['wr_subject'] = $_POST['wr_subject'];
		$_val['wr_type'] = $_POST['wr_type'];
		$_val['wr_name'] = $_POST['wr_name'];
		$_val['wr_content'] = $_POST['wr_content'];
		$_val['wr_ip'] = $_SERVER['REMOTE_ADDR'];
		$_val['wr_id'] = $admin_info['wr_id'];
		$_val['wr_file'] = serialize($file_name);
		$_val['wr_file_name'] = serialize($fname_arr);

		if(!$notice_row) {
			$_val['wr_date'] = today_time;
		}

		$q = $db->query_q($_val);

		if($notice_row) $db->_query("update nf_notice set ".$q." where `no`=".intval($notice_row['no']), $_val);
		else $db->_query("insert into nf_notice set ".$q, $_val);

		$msg = $notice_row ? '수정' : '등록';
		$arr['msg'] = $msg.'이 완료되었습니다.';
		$arr['move'] = NFE_URL.'/admini/board/notice.php';
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "delete_notice":
	case "delete_select_notice":
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_notice') $nos = @implode(",", $_POST['chk']);
		$query = $db->_query("select * from nf_notice where `no` in (".$nos.")");
		while($row=$db->afetch($query)) {
			$wr_file_arr = $nf_util->get_unse($row['wr_file']);
			if(is_array($wr_file_arr)) { foreach($wr_file_arr as $k=>$file) {
				if(is_file(NFE_PATH.'/data/notice/'.$file)) unlink(NFE_PATH.'/data/notice/'.$file);
			} }
			$delete = $db->_query("delete from nf_notice where `no`=".intval($row['no']));
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;
	## : 공지사항 관련 ################################################


	## : 결제 관련 ################################################
	// : 세금계산서 상태
	case "ch_tax_status":
		$pay_row = $db->query_fetch("select * from nf_payment where `no`=".intval($_POST['no']));
		$arr['msg'] = "삭제된 정보입니다.";
		if($pay_row) {
			$update = $db->_query("update nf_payment set `tax_status`=? where `no`=?", array(intval($_POST['val']), $pay_row['no']));
			$arr['msg'] = "세금계산서 상태 변경이 완료되었습니다.";
		}
		die(json_encode($arr));
	break;

	// :  결제리스트 결제승인여부
	case "payment_process":
		include NFE_PATH.'/engine/function/payment.function.php';
		$pay_row = $db->query_fetch("select * from nf_payment where `no`=".intval($_POST['no']));

		if($_POST['val']==='1') {
			$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($pay_row['pay_mno']));
			$sms_arr = array();
			$sms_arr['phone'] = $mem_row['mb_hphone'];
			$sms_arr['name'] = $mem_row['mb_name'];
			$nf_sms->send_sms_online_pay_confirm($sms_arr);
		}

		payment_process($pay_row, $_POST['val']);

		$arr['msg'] = $nf_payment->pay_status[$_POST['val']]."(으)로 설정했습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 결제환경설정 저장
	case "payment_config_save":

		if(is_array($_FILES['pg']['tmp_name'])) { foreach($_FILES['pg']['tmp_name'] as $k=>$v) {
			if(is_array($v)) { foreach($v as $k2=>$v2) {
				if($v2) {
					$ext = $nf_util->get_ext($_FILES['pg']['name'][$k][$k2]);
					if(in_array($ext, $nf_util->image_attach_ext)) {
						$attach[$k] = $k.'_'.time().'.'.$ext;
						$nf_util->make_thumbnail($v2, NFE_PATH.$nf_payment->attach.'/'.$attach[$k], "100", "100", "100");
						if(is_file(NFE_PATH.$nf_payment->attach.'/'.$nf_payment->pg_config[$k]['logo']))
							unlink(NFE_PATH.$nf_payment->attach.'/'.$nf_payment->pg_config[$k]['logo']);
					}
				}
			} }
		} }

		// : 로고값 넣기
		if(is_array($nf_payment->pg_type)) { foreach($nf_payment->pg_type as $k=>$v) {
			$_POST['pg'][$k]['logo'] = $nf_payment->pg_config[$k]['logo'];
			if($attach[$k]) $_POST['pg'][$k]['logo'] = $attach[$k];
		} }

		$pg_config_json = serialize($_POST['pg']);

		$_val = array();
		$_val['pg'] = $_POST['pg_type'];
		$_val['pg_site_name_eng'] = $_POST['site_name_eng'];
		$_val['pg_config'] = $pg_config_json;
		$_val['pg_method'] = @implode(",", $_POST['method']);
		$q = $db->query_q($_val);
		$query = $db->_query("update nf_config set ".$q, $_val);

		die($nf_util->move_url($nf_util->page_back(), "설정이 완료되었습니다."));
	break;


	case "service_write":
		$service_config_unse = $nf_util->get_unse($env['service_config']);
		if($service_config_unse['employ'][$_POST['type']]) {
			if($_POST['use']) $service_config_unse['employ'][$_POST['type']]['use'] = 1;
			else $service_config_unse['employ'][$_POST['type']]['use'] = 0;
			$service_config_seri = serialize($service_config_unse);
			$update = $db->_query("update nf_config set `service_config`=?", array($service_config_seri));
		}

		$row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_POST['code'], $_POST['type']));
		$_val = array();
		if(!$row) {
			$_val['code'] = $_POST['code'];
			$_val['type'] = $_POST['type'];
			$_val['uid'] = $admin_info['wr_id'];
			$_val['wdate'] = today_time;
		}
		$_val['is_pay'] = $_POST['is_pay'];
		$_val['use'] = $_POST['use'];
		$_val['udate'] = today_time;
		$q = $db->query_q($_val);
		if($row) $db->_query("update nf_service set ".$q." where `no`=".intval($row['no']), $_val);
		else $db->_query("insert into nf_service set ".$q, $_val);

		$arr['msg'] = "저장이 완료되었습니다.";
		die(json_encode($arr));
	break;


	case "service_price_write":
		$arr['msg'] = "";
		$arr['move'] = $nf_util->page_back();

		$row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_POST['code'], $_POST['type']));
		$price_row = $db->query_fetch("select * from nf_service_price where `no`=".intval($_POST['no']));
		$max_rank = $db->query_fetch("select max(`rank`) as c from nf_service_price where `code`=? and `type`=?", array($_POST['code'], $_POST['type']));

		$price_int = strtr($_POST['price'], array(","=>""));
		$same_price = $db->query_fetch("select * from nf_service_price where `code`=? and `type`=? and `service_price`=? and `no`!=?", array($_POST['code'], $_POST['type'], intval($price_int), $_POST['no']));
		$same_date = $db->query_fetch("select * from nf_service_price where `code`=? and `type`=? and `service_cnt`=? and service_unit=? and `no`!=?", array($_POST['code'], $_POST['type'], $_POST['date'][0], $_POST['date'][1], $_POST['no']));

		if(intval($same_price)) {
			$arr['msg'] = "해당 서비스에 같은 금액이 있습니다.";
		}
		if($same_date) {
			$arr['msg'] = "해당 서비스에 같은 기간이 있습니다.";
		}

		if(!$arr['msg']) {
			$_val = array();
			if(!$row) {
				$_val['code'] = $_POST['code'];
				$_val['type'] = $_POST['type'];
				$_val['uid'] = $admin_info['wr_id'];
				$_val['wdate'] = today_time;
			}
			$_val['is_pay'] = $_POST['is_pay'];
			$_val['use'] = $_POST['use'];
			$_val['udate'] = today_time;
			$q = $db->query_q($_val);
			if($row) $db->_query("update nf_service set ".$q." where `no`=".intval($row['no']), $_val);
			else $db->_query("insert into nf_service set ".$q, $_val);


			$_val = array();
			$_val['code'] = $_POST['code'];
			$_val['type'] = $_POST['type'];
			$_val['service_cnt'] = $_POST['date'][0];
			$_val['service_unit'] = $_POST['date'][1];
			$_val['service_price'] = $price_int;
			$_val['service_percent'] = $_POST['sale'];

			if(!$price_row) {
				$_val['rank'] = $max_rank['c']+1;
				$_val['uid'] = $admin_info['wr_id'];
				$_val['wdate'] = today_time;
				$_val['udate'] = today_time;
			}

			$q = $db->query_q($_val);

			if($price_row) $db->_query("update nf_service_price set ".$q." where `no`=".intval($price_row['no']), $_val);
			else $db->_query("insert into nf_service_price set ".$q, $_val);

			$arr['msg'] = ($price_row ? "수정":"등록")."이 완료되었습니다.";
		}
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "service_option_write":
		$row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_POST['code'], $_POST['type']));

		$tmp = $_FILES['attach']['tmp_name'];
		if($tmp) {
			$ext = $nf_util->get_ext($_FILES['attach']['name']);
			if(in_array($ext, $nf_util->photo_ext)) {
				if(in_array($_POST['type'], array('busy'))) {
					$file_name = $_POST['type'].'_'.$_POST['code'].'.'.$ext;
				} else {
					$file_name = $_POST['type'].'_'.time().'.'.$ext;
				}
				move_uploaded_file($tmp, NFE_PATH.'/data/service_option/'.$file_name);
			}
		}

		switch($_POST['type']) {
			case "busy":
				$service_option = $file_name;
			break;

			case "icon":
			case "0_0":
			case "1_0":
				if(!$row['option']) {
					$service_option = $file_name;
				} else {
					$option_arr = explode("/", $row['option']);
					if(strlen($_POST['sun'])<=0)
						$service_option = $row['option'].'/'.$file_name;
					else {
						// : 아이콘은 관리자에서 삭제시 이용중인 사용자가 엑박이 뜰 수 있으므로 삭제주석처리합니다.
						//if(is_file(NFE_PATH.'/data/service_option/'.$option_arr[$_POST['sun']-1])) unlink(NFE_PATH.'/data/service_option/'.$option_arr[$_POST['sun']-1]);
						$option_arr[$_POST['sun']-1] = $file_name;
						$service_option = implode("/", $option_arr);
					}
				}
			break;

			default:
				$service_option = implode("/", $_POST['color']);
			break;
		}

		$_val = array();
		if(!$row) {
			$_val['code'] = $_POST['code'];
			$_val['type'] = $_POST['type'];
			$_val['uid'] = $admin_info['wr_id'];
			$_val['wdate'] = today_time;
		}
		$_val['option'] = $service_option;
		$_val['is_pay'] = 1;
		$_val['udate'] = today_time;
		$q = $db->query_q($_val);

		if($row) $update = $db->_query("update nf_service set ".$q." where `no`=".intval($row['no']), $_val);
		else $update = $db->_query("insert into nf_service set ".$q, $_val);
		$arr['msg'] = "설정이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "service_price_delete":
		$delete = $db->_query("delete from nf_service_price where `no`=".intval($_POST['no']));
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['js'] = '
		$(el).closest("tr").remove();
		';
		die(json_encode($arr));
	break;

	case "service_option_delete":
		$row = $db->query_fetch("select * from nf_service where `no`=".intval($_POST['no']));
		if($row) {
			$option_arr = explode("/", $row['option']);
			unset($option_arr[$_POST['sun']]);
			$option_arr = array_values($option_arr);
			$option_txt = implode("/", $option_arr);
			$update = $db->_query("update nf_service set `option`=? where `no`=".intval($row['no']), array($option_txt));
		}
		$arr['js'] = '
		$(el).closest("li").remove();
		';
		die(json_encode($arr));
	break;

	case "rank_update_service_price":
		if(is_array($_POST['rank'])) { foreach($_POST['rank'] as $k=>$v) {
			$no = $_POST['hidd'][$k];
			$update = $db->_query("update nf_service_price set `rank`=".intval($v)." where `no`=".intval($no));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "service_option_rank_update":
		$row = $db->query_fetch("select * from nf_service where `no`=".intval($_POST['no']));
		$option_arr = explode("/", $row['option']);
		$option_new_arr = array();
		if(is_array($_POST['rank'])) { foreach($_POST['rank'] as $k=>$v) {
			$option_new_arr[$v] = $option_arr[$k];
		} }
		ksort($option_new_arr);
		$option_new_arr = array_values($option_new_arr);
		$option_txt = implode("/", $option_new_arr);
		$update = $db->_query("update nf_service set `option`=? where `no`=".intval($row['no']), array($option_txt));
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "service_icon_delete":
		$row = $db->query_fetch("select * from nf_service where `no`=".intval($_POST['no']));
		$option_arr = explode("/", $row['option']);
		// : 아이콘은 관리자에서 삭제시 이용중인 사용자가 엑박이 뜰 수 있으므로 삭제주석처리합니다.
		//if(is_file(NFE_PATH.'/data/service_option/'.$option_arr[$_POST['sun']-1])) unlink(NFE_PATH.'/data/service_option/'.$option_arr[$_POST['sun']-1]);
		unset($option_arr[$_POST['sun']-1]);
		$service_option = implode("/", $option_arr);
		$update = $db->_query("update nf_service set `option`=? where `no`=".intval($row['no']), array($service_option));
		$arr['msg'] = "아이콘 삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "pg_page_write":
		$use_field = 'service_'.$_POST['code'].'_use';
		$use_value = intval($_POST[$use_field]);
		$update = $db->_query("update nf_config set `".$use_field."`=?", array($use_value));

		if(array_key_exists($_POST['code'], $nf_shop->kind_of)) {
			if(is_array($_POST['date'])) { foreach($_POST['date'] as $k=>$v) {
				$date_txt = implode(" ", $v);
				$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_POST['code'], $k));
				if($service_row) {
					$query = $db->_query("update nf_service set `free_date`=?, is_charge=? where `code`=? and `type`=?", array($date_txt, intval($use_value), $_POST['code'], $k));
				} else {
					$query = $db->_query("insert into nf_service set `uid`=?, `free_date`=?, `code`=?, `type`=?, `wdate`=?, `udate`=?, is_charge=?", array($admin_info['wr_id'], $date_txt, $_POST['code'], $k, today_time, today_time, intval($use_value)));
				}
			} }
		}
		$arr['msg'] = "설정이 완료되었습니다.";
		die(json_encode($arr));
	break;

	case "service_update":
		$_val = array();
		if(is_array($_POST['service'])) { foreach($_POST['service'] as $k=>$v) {
			switch($k) {
				case "jump":
					if($v) $_val['wr_service_jump_int'] = intval($v);
				break;

				default:
					if($_POST['code']=='sel_service' && !$v) {
						// : 선택서비스로 서비스기간을 주는경우에는 값이 없는경우 저장하지 않음.
					} else {
						$_val['wr_service'.$k] = date("Y-m-d", strtotime($v));
					}
				break;
			}
		} }

		if($_POST['code']=='sel_service')
			$_POST['no'] = implode(",", $_POST['chk']);

		if($_POST['no']) {
			$table = 'nf_'.$_POST['kind'];
			$q = $db->query_q($_val);
			$query = $db->_query("update nf_".$_POST['kind']." set ".$q." where `no` in (".$_POST['no'].")", $_val);
		}

		$arr['msg'] = "서비스 승인이 완료되었습니다.";
		$arr['move'] = urldecode($_POST['prev_page']);
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "delete_payment":
	case "delete_select_payment":
		$arr['msg'] = "이미 삭제된 결제내역입니다.";
		$arr['move'] = $nf_util->page_back();
		$nos = $_POST['mode']=='delete_payment' ? $_POST['no'] : implode(",", $_POST['chk']);
		if($nos) {
			$delete = $db->_query("delete from nf_payment where `no` in (".$nos.")");
			$arr['msg'] = "삭제가 완료되었습니다.\n포인트를 줘야한다면 포인트관리에서 포인트를 수동으로 부여해주시기 바랍니다.";
		}
		die(json_encode($arr));
	break;
	## : 결제 관련 ################################################


	// : 관리자 정보 변경
	case "admin_modify":
		$dupl_row = $db->query_fetch("select * from nf_admin where `wr_id`=? and `wr_id`!=?", array(trim($_POST['admin_id']), admin_id));
		if($dupl_row['wr_id']) {
			$arr['msg'] = $dupl_row['wr_id']." 아이디는 중복된 아이디입니다.";
		} else {
			$_val = array();
			$_val['wr_id'] = trim($_POST['admin_id']);
			$_val['wr_nick'] = trim($_POST['admin_nick']);
			$arr['move'] = $nf_util->page_back();
			if($_POST['admin_password']) {
				$_val['wr_password'] = md5(trim($_POST['admin_password']));
				$nf_admin->admin_logout();
				$arr['move'] = NFE_URL.'/admini/';
			}
			$adm_q = $db->query_q($_val);
			$_val['change_id'] = admin_id;
			$arr['msg'] = "관리자 정보 변경이 완료되었습니다.";
			$query = $db->_query("update nf_admin set ".$adm_q." where `wr_id`=?", $_val);
		}
		die(json_encode($arr));
	break;

	/*#########################################
	부관리자관련 처리
	#########################################*/
	// : 부관리자등록
	case "sadmin_write":
		$admin_overlap = $db->query_fetch("select * from nf_admin where `wr_id`=?", array($_POST['wr_id']));
		$admin_row = $db->query_fetch("select * from nf_admin where `no`=?", array(intval($_POST['no'])));

		if($admin_overlap && !$admin_row) {
			$arr['msg'] = "중복된 아이디가 있습니다.";
		} else {
			$_val = array();
			$_val['wr_level'] = 0;
			$_val['wr_name'] = trim($_POST['wr_name']);
			$_val['wr_nick'] = trim($_POST['wr_nick']);
			$_val['admin_menu'] = serialize($_POST['admin_menu']);
			if(!$admin_row) {
				$_val['wr_id'] = trim($_POST['wr_id']);
			}

			if($_POST['wr_password']) {
				$_val['wr_password'] = md5(trim($_POST['wr_password']));
			}

			$q = $db->query_q($_val);

			if($admin_row) $db->_query("update nf_admin set ".$q." where `no`=".intval($admin_row['no']), $_val);
			else $db->_query("insert into nf_admin set ".$q, $_val);

			$arr['msg'] = "부관리자".($admin_row ? '수정' : '등록')."이 완료되었습니다.";
		}
		$arr['move'] = NFE_URL."/admini/config/sadmin.php";
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	// : 부관리자 삭제하기
	case "delete_sadmin":
	case "delete_select_sadmin":
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_sadmin') $nos = @implode(",", $_POST['chk']);
		$query = $db->_query("select * from nf_admin where `no` in (?)", array($nos));
		while($row=$db->afetch($query)) {
			$delete = $db->_query("delete from nf_admin where `no`=?", array(intval($row['no'])));
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	/*#########################################
	SMS관련 처리
	#########################################*/
	// : SMS 환경설정
	case "sms_config_write":
		$_val = array();
		$_val['wr_use'] = intval($_POST['wr_use']);
		$_val['wr_lms_use'] = intval($_POST['wr_lms_use']);
		$_val['wr_api_id'] = $_POST['wr_api_id'];
		$_val['wr_api_key'] = $_POST['wr_api_key'];
		$_val['wr_admin_num'] = $_POST['wr_admin_num'];
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_sms_config set ".$q, $_val);
		$arr['msg'] = "설정이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	// : SMS 문구등록
	case "sms_msg_write":
		if(is_array($_POST['sms_msg'])) { foreach($_POST['sms_msg'] as $no=>$v) {
			$_val = array();
			$_val['wr_use'] = intval($v['msg_use']);
			$_val['wr_is_user'] = intval($v['msg_is_user']);
			$_val['wr_is_admin'] = intval($v['wr_is_admin']);
			$_val['wr_content'] = $v['msg_content'];
			$_val['wr_admin_content'] = $v['wr_admin_content'];
			$q = $db->query_q($_val);
			$_val['no'] = intval($no);
			$update = $db->_query("update nf_sms_msg set ".$q." where `no`=?", $_val);
		} }
		$arr['msg'] = "설정이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	#########################################


	## : 지도 ##
	case "map_config":
		$_val = array();
		$_val['map_engine'] = $_POST['map_engine'];
		$_val['map_daum_key'] = $_POST['map_daum_key'];
		$_val['map_naver_key'] = $_POST['map_naver_key'];
		$_val['map_google_key'] = $_POST['map_google_key'];
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_config set ".$q, $_val);
		$arr['msg'] = "설정이 완료되었습니다.";
		die(json_encode($arr));
	break;


	## : 카테고리 ##
	case "category_insert":

		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		if($_POST['no'])
			$cate_row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($_POST['no']));
		else
			$cate_p_row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($_POST['pno']));

		$wr_type = $_POST['wr_type'] ? $_POST['wr_type'] : $cate_row['wr_type'];
		$category_this = $nf_category->kind_arr[$wr_type];
		$category_depth = 2;
		if($category_this[1]>0) $category_depth = $category_this[1];

		if(is_array($_POST['subject'])) {
			$subject = json_encode($_POST['subject']);
		} else {
			$subject = $_POST['subject'];
		}

		// : 첨부파일
		$tmp = $_FILES['attach']['tmp_name'];
		if($tmp) {
			$ext = $nf_util->get_ext($_FILES['attach']['name']);
			if(in_array($ext, $nf_util->photo_ext)) {
				$file_name = $_POST['wr_type'].'_'.time().'.'.$ext;
				if(move_uploaded_file($tmp, NFE_PATH.$nf_category->attach.'/'.$file_name)) {
					$subject = $file_name;
				}
			}
		}

		$view = 1;
		if($category_depth>1) $view = gettype($_POST['view'])==='NULL' ? 0 : 1;
		$adult = gettype($_POST['adult'])==='NULL' ? 0 : 1;

		$max_rank = $db->query_fetch("select max(`wr_rank`) as rank from ".$cate_table." where `wr_type`=? and `pno`=".intval($cate_p_row['no']), array($_POST['wr_type']));
		$max_rank = intval($max_rank['rank'])+1;

		$_val = array();
		$_val['wr_name'] = $subject;
		$_val['wr_adult'] = intval($adult);

		if(!$cate_row) {
			$_val['wr_rank'] = intval($max_rank);
			$_val['wr_type'] = $_POST['wr_type'];
			$_val['wr_wdate'] = today_time;
			$_val['wr_view'] = intval($view);
		}

		if($cate_p_row) {
			$_val['pno'] = intval($_POST['pno']);
			$_val['pnos'] = $cate_p_row['pnos'].$_POST['pno'].",";
		}

		$q = $db->query_q($_val);

		$tno = $cate_row['no'];
		if($cate_row) $db->_query("update ".$cate_table." set ".$q." where `no`=".intval($cate_row['no']), $_val);
		else {
			$db->_query("insert into ".$cate_table." set ".$q, $_val);
			$tno = $db->last_id();
		}

		$msg = $cate_row ? '수정' : '등록';
		$arr['msg'] = $msg.'이 완료되었습니다.';

		// : tr태그들 재정리하기
		$wr_type = $_POST['wr_type'] ? $_POST['wr_type'] : $cate_row['wr_type'];
		$row = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($tno));
		switch($wr_type) {
			// : 무통장입금
			case "online":
				$wr_name_arr = json_decode($row['wr_name']);
			break;

			default:
			break;
		}

		ob_start();
		if(!$_POST['pno']) $i = 0;
		if(is_file(NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php'))
			include NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php';
		else {
			if($category_depth<=1) {
				include NFE_PATH.'/admini/include/category/one_basic.inc.php';
			} else {
				include NFE_PATH.'/admini/include/category/multi_basic.inc.php';
			}
		}

		$arr['tag'] = ob_get_clean();

		$arr['js'] = '
		form.no.value = "";
		';

		// : 1차 카테고리
		if($category_depth<=1) {
			$arr['js'] .= '
			$(".not_list-").closest("tr").css({"display":"none"});
			';

		// : 다중카테고리
		} else {
			$arr['js'] .= '
			$(thisObj).closest(".category_in-").find(".not_list-").closest("tr").css({"display":"none"});
			';
		}

		if(!$cate_row) {
			// : 반대로 할려면 prepend
			$arr['js'] .= '
			$(thisObj).closest(".category_in-").find(".cate_list").append(data.tag);
			$(thisObj).closest("tr").find("input").val("");
			';
		}

		die(json_encode($arr));

	break;

	// : 순서저장
	case "rank_update_category":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		if(is_array($_POST['hidd'])) { foreach($_POST['hidd'] as $k=>$v) {
			$rank_int = $_POST['rank'][$k];
			$update = $db->_query("update ".$cate_table." set `wr_rank`=".intval($rank_int)." where `no`=".intval($v));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	// : 카테고리 삭제
	case "delete_category":
	case "delete_select_category":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_category') $nos = @implode(",", $_POST['chk']);
		$query = $db->_query("select * from ".$cate_table." where `no` in (".$nos.")");
		while($row=$db->afetch($query)) {
			if(in_Array($row['wr_type'], array('job_resume_icon'))) @unlink(NFE_PATH.$nf_category->attach.'/'.$row['wr_name']);
			$delete = $db->_query("delete from ".$cate_table." where `no`=".intval($row['no']));
			$update = $db->_query("delete from ".$cate_table." where `pnos` like '".$row['no'].",%'");
		}

		$arr['msg'] = "삭제가 완료되었습니다.";

		/*
		if($_POST['mode']=='delete_select_category') {
			$arr['js'] = '
			$(form).find("[name=\'chk[]\']:checked").closest("tr").remove();
			';
		} else {
			$arr['js'] = '
			$(el).closest("tr").remove();
			';
		}
		*/
		$arr['js'] = '
		location.href = location.href;
		';

		die(json_encode($arr));
	break;

	// : 카테고리 출력여부
	case "category_view":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		$field = $_POST['fcode'] ? $_POST['fcode'] : 'wr_view';
		$update = $db->_query("update ".$cate_table." set `".$field."`=? where `no`=".intval($_POST['no']), array(intval($_POST['val'])));

		$msg = $_POST['val'] ? '사용함' : '사용안함';
		$arr['msg'] = $msg."으로 설정했습니다.";
		die(json_encode($arr));
	break;

	// : 카테고리 다음차수 태그 가져오기
	case "get_next_category":
		$wr_type = $_POST['wr_type'];

		$cate_table = 'nf_category';
		if($wr_type=='area') $cate_table = 'nf_area';

		$this_row = $db->query_fetch("select * from ".$cate_table." where `no`=?", array(intval($_POST['pno'])));

		switch($_POST['depth']) {

			// : 마지막 차수
			case "end":
				switch($wr_type) {
					// : 게시판 메뉴
					case "board_menu":
						$arr['js'] = '
						if($(".btn-add-")[0]) $(".btn-add-").css({"display":"inline"});
						';
					break;
				}
			break;

			default:
				$pnos_arr = explode(",", $this_row['pnos']);
				if(is_array($pnos_arr)) $next_depth = count($pnos_arr);
				if(!$this_row['pnos']) $next_depth = 1;
				$query = $db->_query("select * from ".$cate_table." where `pno`=? and `wr_type`=? order by `wr_rank` asc", array(intval($_POST['pno']), $wr_type));

				$cate_obj = $nf_category->kind_arr[$wr_type];
				if(!$cate_obj) $cate_obj = $nf_category->etc_kind_arr[$wr_type];

				ob_start();
				while($row=$db->afetch($query)) {
					if(is_file(NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php')) {
						$dfd = 0;
						include NFE_PATH.'/admini/include/category/'.$wr_type.'.inc.php';
					} else {
						$dfd = 1;
						include NFE_PATH.'/admini/include/category/multi_basic.inc.php';
					}
				}
				$arr['tag'] = ob_get_clean();
				if(!$arr['tag']) {
					ob_start();
				?>
				<tr >
					<td align="center" class="not_list-" colspan="4"><span><?php echo $next_depth+1;?>차</span> <?php echo $cate_obj[0];?>을(를) 설정해주세요.</td>
				</tr>
				<?php
					$arr['tag'] = ob_get_clean();
				}

				$arr['js'] = '
				nf_category.depth_pos = index+1;
				$(".category_in-").eq(nf_category.depth_pos).attr({"pno":"'.intval($_POST['pno']).'"});
				if(data.tag) $(".category_in-").eq(nf_category.depth_pos).find(".cate_list").html(data.tag);
				for(var i=0; i<length; i++) {
					if(nf_category.depth_pos<i || (!data.tag && nf_category.depth_pos==i)) {
						$(".not_list_tag-").find("tr").find("td").find("span").html((i)+"차");
						$(".category_in-").eq(i).find(".cate_list").html($(".not_list_tag-").find("tbody").eq(0).html());
					}
				}
				';

				$arr['js'] .= '
				if($(".btn-add-")[0]) $(".btn-add-").css({"display":"none"});
				';
			break;
		}

		die(json_encode($arr));
	break;


	// : 멀티카테고리폼의 순서변경
	case "ch_rank_category":
		$cate_table = 'nf_category';
		if($_POST['wr_type']=='area') $cate_table = 'nf_area';

		$cate_this = $db->query_fetch("select * from ".$cate_table." where `no`=".intval($_POST['no']));
		$_where_basic = " and `wr_type`='".$cate_this['wr_type']."'";
		switch($_POST['code']) {
			case "up":
				$order = " order by `wr_rank` desc limit 1";
				$_where = " and `wr_rank`<".intval($cate_this['wr_rank']);
				$msg = "최상위";
				$js = '
				var $tr = $(obj).closest("tr"); // 클릭한 버튼이 속한 tr 요소
				$tr.prev().before($tr); // 현재 tr 의 이전 tr 앞에 선택한 tr 넣기
				';
			break;

			case "first":
				$minmax_row = $db->query_fetch("select min(`wr_rank`) as `wr_rank` from ".$cate_table." where `pno`=".intval($cate_this['pno'])." ".$_where_basic);
				$order = " limit 1";
				$_where = " and `wr_rank`=(select min(`wr_rank`) from ".$cate_table." where `pno`=".intval($cate_this['pno']).$_where_basic.")";
				$msg = "최상위";
				$js = '
				tbodyObj.prepend(clone_tr);
				obj.remove();
				';
			break;

			case "end":
				$minmax_row = $db->query_fetch("select max(`wr_rank`) as `wr_rank` from ".$cate_table." where `pno`=".intval($cate_this['pno'])." ".$_where_basic);
				$order = " limit 1";
				$_where = " and `wr_rank`=(select max(`wr_rank`) from ".$cate_table." where `pno`=".intval($cate_this['pno']).$_where_basic.")";
				$msg = "최하위";
				$js = '
				tbodyObj.append(clone_tr);
				obj.remove();
				';
			break;

			default:
				$order = " order by `wr_rank` asc limit 1";
				$_where = " and `wr_rank`>".intval($cate_this['wr_rank']);
				$msg = "최하위";
				$js = '
				var $tr = $(obj).closest("tr"); // 클릭한 버튼이 속한 tr 요소
				$tr.next().after($tr); // 현재 tr 의 다음 tr 뒤에 선택한 tr 넣기
				';
			break;
		}
		$arr['q'] = "select * from ".$cate_table." where 1 ".$_where_basic." and `pno`=".intval($cate_this['pno'])." ".$_where.$order;
		$cate_other = $db->query_fetch($arr['q']);

		// : 위로,아래로
		if(!$cate_other['no']) {
			$arr['msg'] = $msg." 순위이므로 변경이 불가능합니다.";

		// : 맨처음,끝으로
		} else if($minmax_row && $minmax_row['wr_rank']==$cate_this['wr_rank']) {
			$arr['msg'] = $msg." 순위이므로 변경이 불가능합니다.";

		// : 수정하기
		} else {
			// : 순위변경 쿼리문
			$update = $db->_query("update ".$cate_table." set `wr_rank`=".intval($cate_other['wr_rank'])." where `no`=".intval($cate_this['no']));
			if(in_array($_POST['code'], array('up', 'down'))) $update = $db->_query("update ".$cate_table." set `wr_rank`=".intval($cate_this['wr_rank'])." where `no`=".intval($cate_other['no']));
			else if($_POST['code']=='first') $db->_query("update ".$cate_table." set `wr_rank`=".intval($cate_other['wr_rank']-1)." where `no`=".intval($cate_this['no']));
			else $db->_query("update ".$cate_table." set `wr_rank`=".intval($cate_other['wr_rank']+1)." where `no`=".intval($cate_this['no']));

			$arr['js'] = $js;
		}
		die(json_encode($arr));
	breaK;


	// : 게시판 카테고리 항목 클릭시 실행 - 아래 게시판 리스트가 나오게 해야함.
	case "board_cate_click":
		$_where = $_POST['depth']>0 ? " and `code`=".intval($_POST['no']) : " and `pcode`=".intval($_POST['no']);
		$query = $db->_query("select * from nf_board where 1 ".$_where." order by `rank` asc");

		ob_start();
		while($row=$db->afetch($query)) {
			$parent0 = $db->query_fetch("select * from nf_category where `no`=".intval($row['pcode']));
			$parent1 = $db->query_fetch("select * from nf_category where `no`=".intval($row['code']));
			include NFE_PATH.'/admini/include/board_cate_list.inc.php';
		}
		$arr['tag'] = ob_get_clean();

		if(!$arr['tag']) $arr['tag'] = '<tr><td colspan="8" class="no_list"></td></tr>';

		$arr['js'] = '
		$(".board_cate_list-").html(data.tag);
		';

		die(json_encode($arr));
	break;
	## : 카테고리 ##






	## 배너 ##
	case "banner_write";

		$row = $db->query_fetch("select * from nf_banner where `no`=".intval($_POST['no']));
		$rank = $db->query_fetch("select max(`wr_rank`) as rank from nf_banner where `wr_position`='".addslashes($_POST['position'])."'");
		$rank = $rank['rank']+1;

		$content = $_POST['type']=='self' ? $_POST['self_content'] : $_POST['adsense_content'];
		if($_POST['type']=='image') $content = $row['wr_content'];

		$tmp_file = $_FILES['upload']['tmp_name'];
		if($_POST['type']=='image' && $tmp_file) {
			$timg = @getimagesize($tmp_file);
			if(!$_POST['size_set']) {
				$_POST['width'] = $timg[0];
				$_POST['height'] = $timg[1];
			}
		}
		if($_POST['type']=='image' && $tmp_file) {
			$content = "";
			$ext = $nf_util->get_ext($_FILES['upload']['name']);
			if(in_array($ext, $nf_util->image_attach_ext)) {
				$file_name = 'banner_'.time().'.'.$ext;
				$nf_util->make_thumbnail($tmp_file, NFE_PATH.$nf_banner->attach.'/'.$file_name, $_POST['width'], $_POST['height'], "100");
				$content = $file_name;
				@unlink(NFE_PATH.$nf_banner->attach.'/'.$row['wr_content']);
			}
			if(!$content) {
				die($nf_util->move_url($nf_util->page_back(), "이미지만 업로드 가능합니다. "));
			}
		}

		$g_name = $_POST['g_name_select'] ? $_POST['g_name_select'] : $_POST['g_name'];
		$group_row = $db->query_fetch("select * from nf_banner where `wr_position`=? and `wr_g_name`=?", array($_POST['position'], $g_name));
		$g_rank = $db->query_fetch("select max(`wr_g_rank`) as rank from nf_banner where `wr_position`=?", array($_POST['position']));
		$g_rank = $g_rank['rank']+1;
		if($group_row) $g_rank = $group_row['wr_g_rank'];

		$_val = array();
		$_val['wr_id'] = $admin['mb_id'];
		$_val['wr_print'] = '';
		$_val['wr_g_name'] = $g_name;
		$_val['wr_position'] = $_POST['position'];
		$_val['wr_type'] = $_POST['type'];
		$_val['wr_size'] = intval($_POST['size_set']);
		$_val['wr_width'] = intval($_POST['width']);
		$_val['wr_height'] = intval($_POST['height']);
		$_val['wr_content'] = $content;
		$_val['wr_url'] = $_POST['url'];
		$_val['wr_target'] = $_POST['target'];
		$_val['wr_file_type'] = intval($timg[2]);
		$_val['wr_padding'] = @implode(",", $_POST['padding']);

		if(!$row) {
			$_val['wr_view'] = 1;
			$_val['wr_g_rank'] = intval($g_rank);
			$_val['wr_rank'] = intval($rank);
			$_val['wr_wdate'] = today_time;
		}

		$q = $db->query_q($_val);

		if($row) $query = $db->_query("update nf_banner set ".$q." where `no`=".intval($row['no']), $_val);
		else $query = $db->_query("insert into nf_banner set ".$q, $_val);

		$arr['msg'] = $row ? '수정이 완료되었습니다.' : "등록이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	case "modify_banner_open":
		$arr = $db->query_fetch("select * from nf_banner where `no`=".intval($_POST['no']));

		if(in_array($arr['wr_type'], array('self', 'adsense'))) {
			$arr['wr_content_txt'] = stripslashes($arr['wr_content']);
			$add_js = '
			if(data.wr_type=="self")
				_editor_use[\''.$arr['wr_type'].'_content\'].replaceContents(data.wr_content_txt);
			else
				$(form).find("[name=\'adsense_content\']").val(data.wr_content_txt);
			';
		}

		$arr['js'] = '
		form.no.value = no;
		$(form).find("[name=\'g_name_select\']").val(data.wr_g_name);
		$(form).find("[name=\'type\']:radio[value=\''.$arr['wr_type'].'\']").prop("checked", true);
		$(form).find("[name=\'size_set\']:radio[value=\''.$arr['wr_size'].'\']").prop("checked", true);
		$(form).find("[name=\'width\']").val(data.wr_width);
		$(form).find("[name=\'height\']").val(data.wr_height);
		$(form).find("[name=\'url\']").val(data.wr_url);
		_editor_use[\'self_content\'].replaceContents("");
		$(form).find("[name=\'adsense_content\']").val("");
		'.$add_js.'
		click_type($(form).find("[name=\'type\']:checked").val());
		setTimeout(function(){
			$(".site_set_input_").css({"display":"none"});
			if($(form).find("[name=\'size_set\']:checked").val()=="1") $(".site_set_input_").css({"display":"inline"});
		},10);
		ch_group($(form).find("[name=\'g_name_select\']")[0]);
		//var top = $(document).scrollTop()+100;
		nf_util.initLayerPosition($(".banner_write_body_")[0], 785, 402, 3);

		//$(".banner_write_body_").css({"display":"block", "top":top, "right":500});

		$(form).find("[name=\'padding[]\']").prop("checked", false);
		$(form).find("[name=\'padding[]\']").each(function(){
			if(data.wr_padding.indexOf($(this).val())>=0) $(this).prop("checked", true);
		});
		';
		die(json_encode($arr));
	break;

	case "rank_save_banner":
		if(is_array($_POST['rank'])) { foreach($_POST['rank'] as $k=>$rank) {
			$banner_no = $_POST['hidd'][$k];
			$update = $db->_query("update nf_banner set `wr_rank`=".intval($rank)." where `no`=".intval($banner_no));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "rank_group_save_banner":
		if(is_array($_POST['g_rank'])) { foreach($_POST['g_rank'] as $k=>$rank) {
			$g_hidd_arr = explode("@/@", stripslashes($_POST['g_hidd'][$k]));
			$_where = " where `wr_position`=? and `wr_g_name`=?";
			$update = $db->_query("update nf_banner set `wr_g_rank`=".intval($rank)." ".$_where, array($g_hidd_arr[0], $g_hidd_arr[1]));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "save_etc_banner":
		$banner_row = $db->query_fetch("select * from nf_banner where `no`=".intval($_POST['no']));

		$_val = array();
		$_val['wr_g_rank'] = intval($_POST['g_rank']);
		$_val['wr_roll_type'] = intval($_POST['roll_type']);
		$_val['wr_roll_direction'] = intval($_POST['roll_direction']);
		$_val['wr_roll_time'] = intval($_POST['roll_time']);
		$_val['wr_roll_turn'] = intval($_POST['roll_turn']);
		$q = $db->query_q($_val);
		$_val['wr_position'] = $banner_row['wr_position'];
		$_val['wr_g_name'] = $banner_row['wr_g_name'];

		$update  = $db->_query("update nf_banner set ".$q." where `wr_position`=? and `wr_g_name`=?", $_val);
		$arr['msg'] = "저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "delete_banner":
	case "delete_select_banner":
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_banner') $nos = @implode(",", $_POST['chk']);
		$query = $db->_query("select * from nf_banner where `no` in (?)", array($nos));
		while($row=$db->afetch($query)) {
			if($row['wr_type']=='image') @unlink(NFE_PATH.$nf_banner->attach.'/'.$row['wr_content']);
			$delete = $db->_query("delete from nf_banner where `no`=".intval($row['no']));
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "save_use_banner":
		$update = $db->_query("update nf_banner set `wr_view`=".intval($_POST['val'])." where `no`=".intval($_POST['no']));
		$arr['msg'] = "설정했습니다.";
		die(json_encode($arr));
	break;

	case "save_target_banner":
		$update = $db->_query("update nf_banner set `wr_target`=? where `no`=".intval($_POST['no']), array($_POST['val']));
		$arr['msg'] = "설정했습니다.";
		die(json_encode($arr));
	break;
	## 배너 ##


	## : 사이트로고설정
	case "logo_write":
		$tmp = $_FILES[$_POST['code']]['tmp_name'];
		$name = $_FILES[$_POST['code']]['name'];
		$field = 'logo_'.$_POST['code'];
		$arr['msg'] = "이미지를 등록할 수 없습니다.";
		if($tmp) {
			$ext = $nf_util->get_ext($name);
			$arr['msg'] = $ext." 확장자는 등록할 수 없습니다.";
			if(in_array($ext, $nf_util->photo_ext)) {
				$logo_name = $field.'.'.$ext;
				if(is_file(NFE_PATH.'/data/logo/'.$env[$field])) unlink(NFE_PATH.'/data/logo/'.$env[$field]);
				move_uploaded_file($tmp, NFE_PATH.'/data/logo/'.$logo_name);
				$update = $db->_query("update nf_config set `".$field."`=?", array($logo_name));
				$arr['msg'] = "등록이 완료되었습니다.";
				$arr['js'] = '
				var obj = $("[name=\''.$_POST['code'].'\']");
				obj.closest(".logo_regist").find("img").attr("src", "'.NFE_URL.'/data/logo/'.$logo_name.'?t='.time().'");
				';
			}
		}
		die(json_encode($arr));
	break;


	## : 기본로고
	case "shop_logo_write":
		$tmp = $_FILES[$_POST['code']]['tmp_name'];
		$name = $_FILES[$_POST['code']]['name'];
		$field = 'shop_logo_'.$_POST['code'];
		$arr['msg'] = "이미지를 등록할 수 없습니다.";
		if($tmp) {
			$ext = $nf_util->get_ext($name);
			$arr['msg'] = $ext." 확장자는 등록할 수 없습니다.";
			if(in_array($ext, $nf_util->photo_ext)) {
				$logo_name = $field.'.'.$ext;
				if(is_file(NFE_PATH.'/data/logo/'.$env[$field])) unlink(NFE_PATH.'/data/logo/'.$env[$field]);
				move_uploaded_file($tmp, NFE_PATH.'/data/logo/'.$logo_name);
				$update = $db->_query("update nf_config set `".$field."`=?", array($logo_name));
				$arr['msg'] = "등록이 완료되었습니다.";
				$arr['js'] = '
				var obj = $("[name=\''.$_POST['code'].'\']");
				obj.closest("table").find("img").attr("src", "'.NFE_URL.'/data/logo/'.$logo_name.'?t='.time().'");
				';
			}
		}
		die(json_encode($arr));
	break;


	## : 사이트디자인설정
	case "design_config":

		// : 사용여부 설정작업
		if(is_array($_POST['service_config'])) { foreach($_POST['service_config'] as $kind=>$service_arr) {
			if(is_array($service_arr)) { foreach($service_arr as $service_k=>$service_post) {
				$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($kind, $service_k));
				if($service_row) {
					$update = $db->_query("update nf_service set `use`=? where `no`=?", array(intval($service_post['use']), $service_row['no']));
				}
			} }
		} }

		$_val = array();
		$_val['site_color'] = $_POST['site_color'];
		$_val['use_map'] = intval($_POST['use_map']);
		$_val['menu_skin'] = $_POST['menu_skin'];
		$_val['service_config'] = serialize($_POST['service_config']);
		$_val['service_intro'] = serialize($_POST['service_intro']);

		$_val['use_shop_industry'] = $_POST['use_shop_industry'];
		$_val['use_shop_tema'] = $_POST['use_shop_tema'];
		$_val['use_shop_price'] = $_POST['use_shop_price'];
		$_val['use_shop_qna'] = $_POST['use_shop_qna'];
		$_val['use_shop_point'] = $_POST['use_shop_point'];
		$_val['use_shop_menu_map'] = $_POST['use_shop_menu_map'];
		$_val['use_shop_list'] = $_POST['use_shop_list'];
		$_val['use_shop_write'] = $_POST['use_shop_write'];
		$_val['use_shop_guide'] = $_POST['use_shop_guide'];
		$_val['use_shop_guide_sort'] = $_POST['use_shop_guide_sort'];
		$_val['use_shop_guide_open'] = $_POST['use_shop_guide_open'];
		$_val['use_shop_guide_view_auth'] = $_POST['use_shop_guide_view_auth'];
		$_val['use_shop_guide_best'] = $_POST['use_shop_guide_best'];
		$_val['use_shop_guide_best_int'] = $_POST['use_shop_guide_best_int'];
		$_val['use_shop_guide_date'] = $_POST['use_shop_guide_date'];
		$_val['use_shop_top'] = $_POST['use_shop_top'];
		$_val['use_shop_top_int'] = $_POST['use_shop_top_int'];
		$_val['use_shop_top_date'] = $_POST['use_shop_top_date'];
		$_val['use_shop_chu'] = $_POST['use_shop_chu'];
		$_val['use_shop_chu_int'] = $_POST['use_shop_chu_int'];
		$_val['use_shop_chu_date'] = $_POST['use_shop_chu_date'];
		$_val['use_shop_new_time'] = $_POST['use_shop_new_time'];

		$q = $db->query_q($_val);
		$update = $db->_query("update nf_config set ".$q, $_val);
		$arr['msg'] = "설정이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;


	## : 서비스명 설정
	case "service_name_write":
		$env['service_name_arr'][$_POST['kind']][$_POST['service_k']] = $_POST['val'];
		$service_name = serialize($env['service_name_arr']);
		$q = $db->_query("update nf_config set `service_name`=?", array($service_name));
		$arr['msg'] = "수정이 완료되었습니다.";
		$arr['js'] = '
		$(el).closest("tr").find(".service_name_txt").text("'.$service_name.'");
		';
		die(json_encode($arr));
	break;


	## : 팝업 시작 ##
	##  : 팝업등록
	case "popup_write":
		$popup_row = $db->query_fetch("select * from nf_popup where `no`=".intval($_POST['no']));
		$max_rank = $db->query_fetch("select max(`rank`) as r from nf_popup");
		$max_rank = $max_rank['r']+1;
		
		$_val = array();
		$_val['rank'] = $max_rank;
		$_val['popup_title'] = $_POST['popup_title'];
		$_val['popup_title_view'] = intval($_POST['popup_title_view']);
		$_val['popup_type'] = intval($_POST['popup_type']);
		$_val['popup_view'] = intval($_POST['popup_view']);
		$_val['popup_sub_view'] = intval($_POST['popup_sub_view']);
		$_val['popup_width'] = intval($_POST['popup_width']);
		$_val['popup_height'] = intval($_POST['popup_height']);
		$_val['popup_top'] = intval($_POST['popup_top']);
		$_val['popup_left'] = intval($_POST['popup_left']);
		$_val['popup_begin'] = $_POST['popup_begin'].' '.$_POST['popup_begin_time'].':00:00';
		$_val['popup_end'] = $_POST['popup_end'].' '.$_POST['popup_end_time'].':00:00';
		$_val['popup_unlimit'] = intval($_POST['popup_unlimit']);
		$_val['popup_content'] = $_POST['popup_content'];
		$_val['wdate'] = today_time;
		$q = $db->query_q($_val);
		if($popup_row) $query = $db->_query("update nf_popup set ".$q." where `no`=".intval($popup_row['no']), $_val);
		else $query = $db->_query("insert into nf_popup set ".$q, $_val);

		$msg = $popup_row ? "수정" : "등록";
		$arr['msg'] = $msg."이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;

	// : 삭제
	case "delete_popup":
	case "delete_select_popup":
		$nos = intval($_POST['no']);
		if($_POST['mode']=='delete_select_popup') $nos = @implode(",", $_POST['chk']);
		$arr['msg'] = "삭제할 팝업을 선택해주시기 바랍니다.";
		if($nos) {
			$delete = $db->_query("delete from nf_popup where `no` in (?)", array($nos));
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	// : 사용여부
	case "popup_view":
		$popup_row = $db->query_fetch("select * from nf_popup where `no`=".intval($_POST['no']));
		$view = $popup_row['popup_view'] ? 0 : 1;
		$view_txt = $view ? '출력함' : '출력안함';
		$update = $db->_query("update nf_popup set `popup_view`=".intval($view)." where `no`=".intval($popup_row['no']));
		$arr['msg'] = $view_txt.'으로 변경했습니다.';
		die(json_encode($arr));
	break;

	// : 서브페이지 사용여부
	case "popup_sub_view":
		$popup_row = $db->query_fetch("select * from nf_popup where `no`=".intval($_POST['no']));
		$view = $popup_row['popup_sub_view'] ? 0 : 1;
		$view_txt = $view ? '서브페이지 출력함' : '서브페이지 출력안함';
		$update = $db->_query("update nf_popup set `popup_sub_view`=".intval($view)." where `no`=".intval($popup_row['no']));
		$arr['msg'] = $view_txt.'으로 변경했습니다.';
		die(json_encode($arr));
	break;

	// : 서브페이지 사용여부
	case "popup_title_view":
		$popup_row = $db->query_fetch("select * from nf_popup where `no`=".intval($_POST['no']));
		$view = $popup_row['popup_title_view'] ? 0 : 1;
		$view_txt = $view ? '제목 출력함' : '제목 출력안함';
		$update = $db->_query("update nf_popup set `popup_title_view`=".intval($view)." where `no`=".intval($popup_row['no']));
		$arr['msg'] = $view_txt.'으로 변경했습니다.';
		die(json_encode($arr));
	break;

	// : 순서저장
	case "rank_update_popup":
		if(is_array($_POST['hidd'])) { foreach($_POST['hidd'] as $k=>$no) {
			$rank = $_POST['rank'][$k];
			$update = $db->_query("update nf_popup set `rank`=".intval($rank)." where `no`=".intval($no));
		} }
		$arr['msg'] = "순서저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;
	## : 팝업 마지막 ##


	## : 게시판 / 커뮤니티 ##
	case "ch_board_bo_type":
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($_POST['bo_table']));
		$arr['msg'] = "삭제된 게시판입니다.";
		$arr['move'] = $nf_util->page_back();
		if($bo_row) {
			$update = $db->_query("update nf_board set `bo_type`=? where `no`=".intval($bo_row['no']), array($_POST['val']));
			$arr['msg'] = $nf_board->bo_type[$_POST['val']]." 형태로 변경이 완료되었습니다.";
			$arr['move'] = "";
		}
		die(json_encode($arr));
	break;

	case "click_board_write_open":
		$arr = array();
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$board_info = $nf_board->board_info($bo_row);
		if(!$bo_row && !array_key_exists($bo_table, $nf_board->not_bo_table)) $arr['msg'] = "삭제된 게시판입니다.";
		if($_POST['no']) {
			$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_POST['no']));
			$b_info = $nf_board->info($b_row, $board_info);
			if(!$b_row) $arr['msg'] = "삭제된 게시물입니다.";
		}
		if(!$arr['msg']) {
			$arr['bo_row'] = $board_info;
			$arr['b_row'] = $b_info;

			$is_write_name = !$b_row || $b_row['wr_name'] ? true : false;
			$code = $_POST['code'] ? $_POST['code'] : 'insert';
			$_GET['code'] = $code;
			$skin = 'admin';
			ob_start();
			include_once NFE_PATH.'/board/write.inc.php';
			$arr['tag'] = ob_get_clean();
			$arr['js'] = '
				var form = document.forms["fwrite"];
				form.no.value = "'.intval($b_row['wr_no']).'";
				form.code.value = "'.$code.'";
				$(".board-write-div-").html(data.tag);
				nf_util.editor_start();
				$(".board-write-").css({"display":"block"});
			';
		}
		die(json_encode($arr));
	break;

	case "board_move_process":
		$board_no_arr = explode(",", $_POST['board_no']);
		$arr['msg'] = "이동할 게시판이 없습니다.";
		$move_cnt = 0;
		if(is_array($board_no_arr)) { foreach($board_no_arr as $k=>$v) {
			$q = "update nf_board set `pcode`=".intval($_POST['cate_board'][0]).", `code`=".intval($_POST['cate_board'][1])." where `no`=".intval($v);
			$update = $db->_query($q);
			if($update) $move_cnt++;
		} }
		if($move_cnt>0) {
			$arr['msg'] = "게시판 이동이 완료되었습니다.";
		}
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;


	// : 테이블명 중복체크
	case "check_bo_table":
		$bo_table = trim($_POST['bo_table']);
		$check_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$arr['msg'] = "";
		if($check_row['bo_table']==$bo_table) {
			$arr['msg'] = "중복된 테이블명이 존재합니다.";
			$arr['js'] = '
			$(".check_bo_table-").val("");
			';
		} else if(!ctype_alnum($bo_table)) {
			$arr['msg'] = "테이블명은 영문이나 숫자로만 입력해야합니다.";
			$arr['js'] = '
			$(".check_bo_table-").val("");
			';
		} else {
			$arr['msg'] = "사용 가능합니다.";
			$arr['js'] = '
			$(".check_bo_table-").val(1);
			';
		}
		die(json_encode($arr));
	break;

	// : 게시판 권한 창 열기
	case "open_bo_level":
		$arr['msg'] = "삭제된 게시판입니다.";
		$bo_row = $db->query_fetch("select * from nf_board where `no`=".intval($_POST['no']));
		$bo_row['bo_list_level'] = $bo_row['bo_list_level']<=0 ? "" : $bo_row['bo_list_level'];
		$bo_row['bo_read_level'] = $bo_row['bo_read_level']<=0 ? "" : $bo_row['bo_read_level'];
		$bo_row['bo_write_level'] = $bo_row['bo_write_level']<=0 ? "" : $bo_row['bo_write_level'];
		$bo_row['bo_reply_level'] = $bo_row['bo_reply_level']<=0 ? "" : $bo_row['bo_reply_level'];
		$bo_row['bo_comment_level'] = $bo_row['bo_comment_level']<=0 ? "" : $bo_row['bo_comment_level'];
		$bo_row['bo_secret_level'] = $bo_row['bo_secret_level']<=0 ? "" : $bo_row['bo_secret_level'];
		if($bo_row) {
			$arr['msg'] = "";
			$arr['js'] = '
			var form = document.forms["flevel"];
			form.no.value = no;
			$(form).find("[name=\'bo_level[list]\']").val("'.$bo_row['bo_list_level'].'").prop("selected", true);
			$(form).find("[name=\'bo_level[read]\']").val("'.$bo_row['bo_read_level'].'").prop("selected", true);
			$(form).find("[name=\'bo_level[write]\']").val("'.$bo_row['bo_write_level'].'").prop("selected", true);
			$(form).find("[name=\'bo_level[reply]\']").val("'.$bo_row['bo_reply_level'].'").prop("selected", true);
			$(form).find("[name=\'bo_level[comment]\']").val("'.$bo_row['bo_comment_level'].'").prop("selected", true);
			$(form).find("[name=\'bo_level[secret]\']").val("'.$bo_row['bo_secret_level'].'").prop("selected", true);
			';
		}
		die(json_encode($arr));
	break;

	// : 게시판 메인설정
	case "board_main_setting":
		if(!is_array($_POST['board'])) $_POST['board'] = array();

		$_val = array();
		switch($_POST['code']) {
			case "site_main":
				if(is_array($_POST['bo_rank'])) { foreach($_POST['bo_rank'] as $bo_table=>$v) {
					$update = $db->_query("update nf_board set `m_rank`=".intval($v)." where `bo_table`=?", array($bo_table));
				} }

				unset($nf_board->main_row['print_main_un'][0]);
				unset($nf_board->main_row['print_main_un'][1]);
				unset($nf_board->main_row['print_main_un'][2]);
				unset($nf_board->main_row['print_main_un'][3]);

				$board_arr = $_POST['board']+$nf_board->main_row['print_main_un'];

				$_val['print_main'] = serialize($board_arr);
			break;

			default:
				if(is_array($_POST['bo_rank'])) { foreach($_POST['bo_rank'] as $bo_table=>$v) {
					$update = $db->_query("update nf_board set `b_rank`=".intval($v)." where `bo_table`=?", array($bo_table));
				} }

				$board_arr = $_POST['board']+$nf_board->main_row['print_board_un'];

				$_val['use_main'] = $_POST['use_main'];
				$_val['use_best'] = $_POST['use_best'];
				$_val['use_best_count'] = $_POST['use_best_count'];
				$_val['use_print'] = $_POST['use_print'];
				$_val['print_board'] = serialize($board_arr);
				$_val['wdate'] = today_time;
			break;
		}
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_board_main set ".$q, $_val);

		$arr['msg'] = "설정이 완료되었습니다.";
		die(json_encode($arr));
	break;

	case "board_level_update":
		$arr['msg'] = "삭제된 게시판입니다.";
		$bo_row = $db->query_fetch("select * from nf_board where `no`=".intval($_POST['no']));

		if($bo_row) {
			$_val = array();
			$_val['bo_list_level'] = $_POST['bo_level']['list'];
			$_val['bo_read_level'] = $_POST['bo_level']['read'];
			$_val['bo_secret_level'] = $_POST['bo_level']['secret'];
			$_val['bo_write_level'] = $_POST['bo_level']['write'];
			$_val['bo_reply_level'] = $_POST['bo_level']['reply'];
			$_val['bo_comment_level'] = $_POST['bo_level']['comment'];

			$q = $db->query_q($_val);

			$query = $db->_query("update nf_board set ".$q." where `no`=".intval($bo_row['no']), $_val);

			$arr['msg'] = "실패되었습니다.";
			if($query) {
				$arr['msg'] = "수정 완료했습니다.";
				$arr['js'] = '
				open_box(this, "board-auth-", "none")
				';
			}
		}
		die(json_encode($arr));
	break;

	// : 게시판 삭제
	case "delete_board":
	case "delete_select_board":
		$arr['msg'] = "삭제할 게시판이 없습니다.";
		if($_POST['mode']=='delete_select_board' && is_Array($_POST['chk']) &&count($_POST['chk'])>0) $nos = implode(",", $_POST['chk']);
		else $nos = intval($_POST['no']);
		$bo_query = $db->_query("select * from nf_board where `no` in (".$nos.")");
		$length = $db->num_rows($bo_query);
		if($length>0) {
			$arr['msg'] = "";

			function rmdir_all($delete_path) {
				$dirs = dir($delete_path);

				while(false !== ($entry = $dirs->read())) {
					// 디렉토리의 내용을 하나씩 읽는다.
					if(($entry != '.') && ($entry != '..')) {
						// 디렉토리의 내용중 현재폴더, 상위폴더가 아니면 (즉 파일 및 디렉토리)            
						if(is_dir($delete_path.'/'.$entry)) {
							//디렉토리이면 재귀호출로 다시 삭제 시작.
							rmdir_all($delete_path.'/'.$entry);
						} else {
							//해당 파일 삭제
							@unlink($delete_path.'/'.$entry);
						}
					}
				}

				$dirs->close();

				// 최종 디렉토리 삭제
				@rmdir($delete_path);
			}

			while($row=$db->afetch($bo_query)) {
				$delete = $db->_query("delete from nf_board where `no`=".intval($row['no']));
				if($delete) {
					$_table = 'nf_write_'.$row['bo_table'];
					if($db->is_table($_table)) {
						$drops = $db->_query("drop table ".$_table);
						if(is_dir(NFE_PATH.'/data/board/'.$row['bo_table'])) {
							rmdir_all(NFE_PATH.'/data/board/'.$row['bo_table']);
						}
						$delete = $db->_query("delete from nf_board_file where bo_table=?", array($row['bo_table']));
						$delete = $db->_query("delete from nf_board_good where bo_table=?", array($row['bo_table']));
						$delete = $db->_query("delete from nf_board_new where bo_table=?", array($row['bo_table']));
						$delete = $db->_query("delete from nf_board_int where bo_table=?", array($row['bo_table'])); // : TOP조회게시물, 추천게시물
						$delete = $db->_query("delete from nf_read where mb_type=? and `code`='board'", array($row['bo_table']));
					}
				}
			}
			$arr['msg'] = "삭제가 완료되었습니다.";
			$arr['move'] = $nf_util->page_back();
		}
		die(json_encode($arr));
	break;

	// : 게시판 등록
	case "board_write":
		$bo_table = trim($_POST['bo_table']);
		$check_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$bo_row = $db->query_fetch("select * from nf_board where `no`=".intval($_POST['no']));
		if(!$bo_table) $bo_table = $bo_row['bo_table'];

		if($check_row['bo_table']==$bo_table && !$bo_row) {
			$arr['msg'] = "중복된 테이블명이 존재합니다.";
		} else if(!ctype_alnum($bo_table) && !$bo_row) {
			$arr['msg'] = "테이블명은 영문이나 숫자로만 입력해야합니다.";
		} else {
			$_val = array();
			if(!$bo_row) {
				$rank = $db->query_fetch("select max(`rank`) as c from nf_board where `code`=?", array($_POST['bno']));
				$b_rank = $db->query_fetch("select max(`b_rank`) as c from nf_board");
				$m_rank = $db->query_fetch("select max(`m_rank`) as c from nf_board");
				$pcate = $db->query_fetch("select * from nf_category where `no`=".intval($_POST['bno']));

				$_val['rank'] = intval($rank['c']+1);
				$_val['b_rank'] = intval($b_rank['c']+1);
				$_val['m_rank'] = intval($m_rank['c']+1);
				$_val['code'] = $_POST['bno'];
				$_val['pcode'] = $pcate['pno'];
				$_val['bo_table'] = $bo_table;
				$_val['wdate'] = today_time;
			}
			$_val['bo_skin'] = $_POST['bo_skin'];
			$_val['bo_subject'] = $_POST['bo_subject'];
			$_val['bo_type'] = $_POST['bo_type'];
			$_val['bo_table_width'] = intval(strtr($_POST['bo_table_width'], array(","=>"")));
			$_val['bo_subject_len'] = intval(strtr($_POST['bo_subject_len'], array(","=>"")));
			$_val['bo_page_rows'] = intval(strtr($_POST['bo_page_rows'], array(","=>"")));
			$_val['bo_board_view'] = $_POST['bo_board_view'];
			$_val['bo_list_level'] = $_POST['bo_level']['list'];
			$_val['bo_read_level'] = $_POST['bo_level']['read'];
			$_val['bo_secret_level'] = $_POST['bo_level']['secret'];
			$_val['bo_write_level'] = $_POST['bo_level']['write'];
			$_val['bo_reply_level'] = $_POST['bo_level']['reply'];
			$_val['bo_comment_level'] = $_POST['bo_level']['comment'];
			$_val['bo_use_comment'] = $_POST['bo_use_comment'];
			$_val['bo_use_good'] = $_POST['bo_use_good'];
			$_val['bo_use_delete'] = $_POST['bo_use_delete'];
			$_val['bo_use_secret'] = $_POST['bo_use_secret'];
			$_val['bo_use_name'] = $_POST['bo_use_name'];
			$_val['bo_cut_name'] = $_POST['bo_cut_name'];
			$_val['bo_new'] = $_POST['bo_new'];
			$_val['bo_image_width'] = intval(strtr($_POST['bo_image_width'], array(","=>"")));
			$_val['bo_use_list_view'] = $_POST['bo_use_list_view'];
			$_val['bo_write_point'] = intval(strtr($_POST['bo_write_point'], array(","=>"")));
			$_val['bo_read_point'] = intval(strtr($_POST['bo_read_point'], array(","=>"")));
			$_val['bo_comment_point'] = intval(strtr($_POST['bo_comment_point'], array(","=>"")));
			$_val['bo_download_point'] = intval(strtr($_POST['bo_download_point'], array(","=>"")));
			$_val['bo_category_list'] = $_POST['bo_category_list'];
			$_val['bo_use_upload'] = $_POST['bo_use_upload'];
			$_val['bo_upload_count'] = intval($_POST['bo_upload_count']);
			$_val['bo_upload_size'] = intval(strtr($_POST['bo_upload_size'], array(","=>"")));
			$_val['bo_upload_ext'] = $_POST['bo_upload_ext'];
			$_val['bo_content_head'] = $_POST['bo_content_head'];
			$_val['bo_content_tail'] = $_POST['bo_content_tail'];
			$_val['bo_insert_content'] = $_POST['bo_insert_content'];
			$_val['bo_filter'] = $_POST['bo_filter'];
			$_val['bo_sort_field'] = $_POST['bo_sort_field'];

			$q = $db->query_q($_val);

			if(!$bo_row['no']) $query = $db->_query("insert into nf_board set ".$q, $_val);
			else $query = $db->_query("update nf_board set ".$q." where `no`=".intval($bo_row['no']), $_val);

			if($query) {
				$_table_ = 'nf_write_'.$bo_table;
				if(!$db->is_table($_table_)) {
$nf_board->get_dir($bo_table);
$db->_query("CREATE TABLE `".$_table_."` (
`wr_no` bigint unsigned not null AUTO_INCREMENT,
`mno` bigint unsigned default 0 comment '회원주키',
`wr_num` bigint DEFAULT 0,
`wr_reply` varchar(255)  DEFAULT '',
`wr_parent` bigint unsigned DEFAULT 0,
`wr_parent_ori` varchar(255) default '',
`wr_is_comment` tinyint(4)  DEFAULT '0',
`wr_comment` int(11)  DEFAULT 0,
`wr_comment_reply` varchar(255)  DEFAULT '',
`wr_category` varchar(255)  DEFAULT '' COMMENT '카테고리',
`wr_option` varchar(50)  DEFAULT '',
`wr_secret` tinyint(4)  DEFAULT 0,
`wr_thumb` varchar(255) default '',
`wr_subject` varchar(255) DEFAULT '',
`wr_content` text,
`wr_link1` text ,
`wr_link2` text ,
`wr_link1_hit` int(11)  DEFAULT 0,
`wr_link2_hit` int(11)  DEFAULT 0,
`wr_trackback` varchar(255)  DEFAULT '',
`wr_hit` int(11)  DEFAULT '0',
`wr_good` int(11)  DEFAULT '0',
`wr_nogood` int(11)  DEFAULT '0',
`wr_id` varchar(255)  DEFAULT '' COMMENT '작성자ID',
`wr_password` varchar(255)  DEFAULT '',
`wr_name` varchar(255)  DEFAULT '',
`wr_email` varchar(255)  DEFAULT '',
`wr_homepage` varchar(255)  DEFAULT '',
`wr_datetime` datetime  DEFAULT '0000-00-00 00:00:00',
`wr_last` varchar(19)  DEFAULT '',
`wr_ip` varchar(255)  DEFAULT '',
`wr_del` tinyint(4)  DEFAULT '0' COMMENT '삭제여부',
`wr_report` tinyint default 0 comment '신고유무',
`wr_blind` tinyint default 0 comment '블라인드처리',
`wr_reply_cnt` int(11)  DEFAULT '0',
`wr_is_admin` tinyint(4)  DEFAULT '0' COMMENT '관리자작성유무',
`wr_0` varchar(255)  DEFAULT '',
`wr_1` varchar(255)  DEFAULT '',
`wr_2` varchar(255)  DEFAULT '',
`wr_3` varchar(255)  DEFAULT '',
`wr_4` varchar(255)  DEFAULT '',
`wr_5` varchar(255)  DEFAULT '',
`wr_6` varchar(255)  DEFAULT '',
`wr_7` varchar(255)  DEFAULT '',
`wr_8` varchar(255)  DEFAULT '',
`wr_9` varchar(255)  DEFAULT '',
PRIMARY KEY (`wr_no`),
KEY `wr_num_reply_parent` (`wr_num`,`wr_reply`,`wr_parent`),
KEY `wr_is_comment` (`wr_is_comment`,`wr_no`),
key `mno` (`mno`)
)");
				}
			}

			$arr['msg'] = "게시판 생성이 실패되었습니다.";
			if($query) {
				$msg = $bo_row ? '수정' : '등록';
				$arr['msg'] = $msg." 완료했습니다.";
				$arr['move'] = NFE_URL."/admini/board/index.php";
			}
		}
		die(json_encode($arr));
	break;

	// : 게시판 블라인드
	case "click_board_blind":
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$get_write = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_POST['no']));
		$arr['msg'] = "삭제된 게시물입니다.";
		$arr['move'] = "";
		if($get_write) {
			$arr['txt'] = $get_write['wr_blind'] ? '블라인드 처리' : '블라인드 해지';
			$ch_txt = $get_write['wr_blind'] ? '블라인드 해지' : '블라인드 처리';
			$val = $get_write['wr_blind'] ? 0 : 1;
			$update = $db->_query("update ".$_table." set `wr_blind`=".intval($val)." where `wr_no`=".intval($get_write['wr_no']));
			$arr['msg'] = $ch_txt." 완료했습니다.";
			$arr['js'] = '
			$(el).html(data.txt);
			';
		}
		die(json_encode($arr));
	break;

	// : 게시판 신고 삭제하기
	case "delete_board_report":
	case "delete_select_board_report":
		$bo_table = trim($_POST['bo_table']);
		$nos = $_POST['no'];
		if($_POST['mode']=='delete_select_board_report') $nos = implode(",", $_POST['chk']);
		$report_query = $db->_query("select * from nf_board_report where `no` in (".$nos.") and `bo_table`=?", array($bo_table));
		while($row=$db->afetch($report_query)) {
			$delete = $db->_query("delete from nf_board_report where `bo_table`=? and `pno`=?", array($bo_table, intval($row['pno'])));
		}

		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;


	case "delete_board_comment":
	case "delete_select_board_comment":
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$nos = $_POST['no'];
		if($_POST['mode']=='delete_select_board_comment') $nos = implode(",", $_POST['chk']);
		$query = $db->_query("select * from ".$_table." where `wr_no` in (".$nos.")");
		while($row=$db->afetch($query)) {
			if(!$row['wr_del']) $update = $db->_query("update ".$_table." set `wr_comment`=`wr_comment`-1 where `wr_no`=".intval($row['wr_parent'])); // : 삭제안된걸 삭제한경우
			$delete = $db->_query("delete from ".$_table." where `wr_no`=".intval($row['wr_no']));
		}

		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;


	case "board_save_update":
		if(is_array($_POST['hidd'])) { foreach($_POST['hidd'] as $k=>$no) {
			$rank = $_POST['rank'][$k];
			$bo_type = $_POST['bo_type'][$k];
			$update = $db->_query("update nf_board set `rank`=?, `bo_type`=? where `no`=?", array(intval($rank), $bo_type, intval($no)));
		} }
		$arr['msg'] = "저장이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die($nf_util->move_url($arr['move'], $arr['msg']));
	break;
	## : 게시판 ##



	## : div박스 관련 ################################################
	case "member_mno_click":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['mno']));
		$arr['msg'] = "회원정보가 없습니다.";
		if($mem_row) {
			$arr['msg'] = "";
			$mem_service = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));

			switch($_POST['code']) {
				// : 메일폼
				case "email-":
					$arr['mem_row'] = $mem_row;
					$mail_content = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array('member_mailing'));
					$arr['mail_content'] = "";
					if($mail_content['content']) $arr['mail_content'] = $mail_content['content'];
					$js .= '
					$(".conbox.email-.popup_box-").css({"display":"block"});
					obj.find("[name=\'receive_mail\']").val(data.mem_row.mb_email);
					obj.find("[name=\'receive_no\']").val(data.mem_row.no);
					_editor_use[\'content\'].replaceContents(data.mail_content);
					';
				break;

				// : 회원상세정보
				default:
					ob_start();
					include NFE_PATH.'/admini/include/'.$mem_row['mb_type'].'_part.inc.php';
					$arr['tag'] = ob_get_clean();
					$js .= '
					$(".member-detail-").css({"display":"block"});
					$(".member-detail-").find(".table-").html(data.tag);
					';
				break;
			}

			$js .= '
			if($(obj).find(".name--")[0]) obj.find(".name--").html(data.mem_row.mb_name);
			if($(obj).find(".mb_id--")[0]) obj.find(".mb_id--").html("("+data.mem_row.mb_id+")");
			obj.css({"display":"block"});
			';

			$arr['js'] = $js;
		}
		die(json_encode($arr));
	break;

	case "open_box":
		$mem_row = $db->query_fetch("select * from nf_member where `mb_id`=?", array($_POST['mb_id']));
		$arr['mem_row'] = $mem_row;

		$js = '';
		switch($_POST['code']) {
			// : 고객문의
			case "cs-":
				$qna_row = $db->query_fetch("select * from nf_cs where `no`=".intval($_POST['no']));
				ob_start();
				include NFE_PATH.'/admini/include/cs_qna.inc.php';
				$arr['tag'] = ob_get_clean();

				$arr['wr_aname'] = $qna_row['wr_aname'];
				$arr['wr_acontent'] = $qna_row['wr_acontent'];

				$js .= '
				var fca = document.forms["fca"];
				fca.no.value = "'.intval($qna_row['no']).'";
				fca.wr_aname.value = data.wr_aname;
				_editor_use["wr_acontent"].replaceContents(data.wr_acontent);
				$(".popup_box-.cs-").find(".paste-body-").html(data.tag);
				obj.find("[name=\'mb_badness\'][value=\'"+parseInt(data.mem_row.mb_badness)+"\']").prop("checked", true);
				obj.find("[name=\'mb_memo\']").val(data.mem_row.mb_memo);
				obj.closest("form").find("[name=\'mb_no\']").val(data.mem_row.no);
				';
			break;

			case "badness-":
				$js .= '
				obj.find("[name=\'mb_badness\'][value=\'"+parseInt(data.mem_row.mb_badness)+"\']").prop("checked", true);
				obj.find("[name=\'mb_memo\']").val(data.mem_row.mb_memo);
				obj.closest("form").find("[name=\'mb_no\']").val(data.mem_row.no);
				';
			break;

			case "memo-":
				$js .= '
				obj.find("[name=\'mb_memo\']").val(data.mem_row.mb_memo);
				obj.closest("form").find("[name=\'mb_no\']").val(data.mem_row.no);
				';
			break;

			case "email-":
				$mail_content = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array('member_mailing'));
				$arr['mail_content'] = "";
				if($mail_content['content']) $arr['mail_content'] = $mail_content['content'];
				$js .= '
				obj.find("[name=\'receive_mail\']").val(data.mem_row.mb_email);
				obj.find("[name=\'receive_no\']").val(data.mem_row.no);
				_editor_use[\'content\'].replaceContents(data.mail_content);
				';
			break;

			case "sms-":
				$js .= '
				obj.find("[name=\'rphone_list\']").val(data.mem_row.mb_hphone);
				obj.find("[name=\'no_list\']").val(data.mem_row.no);
				';
			break;

			case "member_service-":
				$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));
				switch($mem_row['mb_type']) {
					case "individual":
						$read = $mem_service_row['mb_employ_read']>'1000-01-01' ? $mem_service_row['mb_employ_read'] : "";
						$read_int = $mem_service_row['mb_employ_read_int']>0 ? $mem_service_row['mb_employ_read_int'] : 0;
						$jump_int = $mem_service_row['mb_resume_jump_int']>0 ? $mem_service_row['mb_resume_jump_int'] : 0;
					break;

					case "company":
						$read = $mem_service_row['mb_resume_read']>'1000-01-01' ? $mem_service_row['mb_resume_read'] : "";
						$read_int = $mem_service_row['mb_resume_read_int']>0 ? $mem_service_row['mb_resume_read_int'] : 0;
						$jump_int = $mem_service_row['mb_employ_jump_int']>0 ? $mem_service_row['mb_employ_jump_int'] : 0;
					break;
				}
				$js .= '
				var form = document.forms["fservice"];
				form.mno.value = "'.intval($mem_row['no']).'";
				form.read.value = "'.$read.'";
				form.read_int.value = "'.intval($read_int).'";
				form.jump_int.value = "'.intval($jump_int).'";
				';
			break;
		}
		$js .= '
		if($(obj).find(".name--")[0]) obj.find(".name--").html(data.mem_row.mb_name);
		if($(obj).find(".mb_id--")[0]) obj.find(".mb_id--").html("("+data.mem_row.mb_id+")");
		obj.css({"display":"block"});
		';

		$arr['js'] = $js;
		die(json_encode($arr));
	break;

	case "member_service_write":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['mno']));

		$_val = array();
		switch($mem_row['mb_type']) {
			case "individual":
				$_val['mb_employ_read'] = $_POST['read'];
				$_val['mb_employ_read_int'] = intval($_POST['read_int']);
				$_val['mb_resume_jump_int'] = intval($_POST['jump_int']);
			break;

			case "company":
				$_val['mb_resume_read'] = $_POST['read'];
				$_val['mb_resume_read_int'] = intval($_POST['read_int']);
				$_val['mb_employ_jump_int'] = intval($_POST['jump_int']);
			break;
		}
		$q = $db->query_q($_val);
		$update = $db->_query("update nf_member_service set ".$q." where `mno`=".intval($mem_row['no']), $_val);
		$arr['msg'] = "서비스 설정이 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	case "select_email_box":
		$arr = array();
		$arr['email_arr'] = array();
		if(is_array($_POST['chk'])) $nos = implode(",", $_POST['chk']);
		if($nos) {
			$query = $db->_query("select * from nf_member where `no` in (".$nos.")");
			while($row=$db->afetch($query)) {
				$arr['email_arr'][] = $row['mb_email'];
				$arr['no_arr'][] = $row['no'];
			}

			// : 스킨정보가 있을때 실행
			$skin_arr = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array($_POST['code2']));
			$arr['content'] = stripslashes($skin_arr['content']);
			if(!$arr['content']) $arr['content'] = "";

			$arr['js'] = '
			var form = document.forms["femail"];
			form.receive_no.value = data.no_arr.join(",");
			form.receive_mail.value = data.email_arr.join(",");
			if(data.content) _editor_use["email_content"].replaceContents(data.content);
			obj.css({"display":"block"});
			';
		}
		die(json_encode($arr));
	break;

	## : 불량회원
	case "member_badness":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['mb_no']));
		$arr['msg'] = "삭제된 회원입니다.";
		$arr['move'] = $nf_util->page_back();
		if($mem_row) {
			$arr['msg'] = "설정이 완료되었습니다.";

			$_val = array();
			$_val['mb_badness'] = $_POST['mb_badness'];
			$_val['mb_denied'] = $_POST['mb_badness'];
			if($_POST['mb_badness']) $_val['mb_bad_date'] = today_time;
			$_val['mb_memo'] = $_POST['mb_memo'];
			$q = $db->query_q($_val);

			$update = $db->_query("update nf_member set ".$q." where `no`=".intval($mem_row['no']), $_val);
		}
		die(json_encode($arr));
	break;

	## : 회원복귀
	case "member_comeback":
	case "member_select_comeback":
		$mno = $_POST['no'];
		if($_POST['mode']=='member_select_comeback') $mno = implode(",", $_POST['chk']);
		$query = $db->_query("select * from nf_member where `no` in (".$mno.")");
		while($row=$db->afetch($query)) {
			$update = $db->_query("update nf_member set `mb_left`=0, `mb_left_request`=0, `mb_badness`=0 where `no`=".intval($row['no'])); // : 회원내역
			$update = $db->_query("update nf_member_service set `is_delete`=0 where `mno`=".intval($row['no'])); // : 회원서비스

			$update = $db->_query("update nf_shop set `is_delete`=0 where `mno`=".intval($row['no'])); // : 업체정보
			//$update = $db->_query("update nf_message set `del`=0 where `mno`=".intval($row['no'])); // : 쪽지내역
			//$update = $db->_query("update nf_accept set `del`=0 where `mno`=".intval($row['no'])); // : 입사지원
			$delete = $db->_query("update nf_point set `is_delete`=0 where `mno`=".intval($row['no'])); // : 포인트내역
		}
		$arr['msg'] = "복귀가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	## : 탈퇴회원에서 삭제시
	case "member_select_real_delete":
	case "member_real_delete":
		$mno = $_POST['no'];
		if($_POST['mode']=='member_select_real_delete') $mno = implode(",", $_POST['chk']);
		$query = $db->_query("select * from nf_member where `no` in (".$mno.")");
		while($row=$db->afetch($query)) {
			$update = $db->_query("update nf_member set `mb_left`=1, is_delete=1 where `no`=".intval($row['no'])); // : 회원내역
		}
		$arr['msg'] = "삭제가 완료되었습니다.";
		$arr['move'] = $nf_util->page_back();
		die(json_encode($arr));
	break;

	## : 회원메모
	case "member_memo":
		$mem_row = $db->query_fetch("select * from nf_member where `no`=".intval($_POST['mb_no']));
		$arr['msg'] = "삭제된 회원입니다.";
		$arr['move'] = $nf_util->page_back();
		if($mem_row) {
			$arr['msg'] = "설정이 완료되었습니다.";

			$_val = array();
			$_val['mb_memo'] = $_POST['mb_memo'];
			$q = $db->query_q($_val);

			$update = $db->_query("update nf_member set ".$q." where `no`=".intval($mem_row['no']), $_val);
		}
		die(json_encode($arr));
	break;
	## : div박스 관련 ################################################

	case "post_area_write":

		ini_set("memory_limit", "-1"); // 메모리 제한을 여유 있게 설정
		ini_set('max_execution_time', 300);
	
		$db->_query("drop table nf_area");
		$db->_query("
		CREATE TABLE `nf_area` (
		`no` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`pno` bigint(20) DEFAULT '0',
		`pnos` varchar(255) DEFAULT '',
		`wr_type` varchar(40) DEFAULT NULL COMMENT '종류',
		`wr_name` varchar(255) DEFAULT NULL COMMENT '카테고리명',
		`wr_name_eng` varchar(255) DEFAULT NULL COMMENT '카테고리명 영어',
		`wr_view` tinyint(1) NOT NULL DEFAULT '0' COMMENT '출력(사용)여부',
		`wr_adult` tinyint(4) NOT NULL DEFAULT '0' COMMENT '성인여부',
		`wr_rank` int(11) DEFAULT '0',
		`wr_hit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'hit수',
		`wr_wdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '작성일',
		`wr_url` varchar(255) DEFAULT NULL COMMENT '링크주소',
		`wr_url_use` tinyint(4) DEFAULT '0',
		`bo_type` varchar(20) DEFAULT NULL,
		`xml_code` varchar(255) DEFAULT NULL,
		`wr_file` varchar(50) DEFAULT NULL,
		`wr_point` int(11) DEFAULT '0',
		`wr_0` varchar(255) DEFAULT NULL,
		`wr_1` varchar(255) DEFAULT NULL,
		PRIMARY KEY (`no`),
		KEY `pno` (`pno`),
		KEY `pnos` (`pnos`)
		)");

		$area_arr_flip = array_flip($nf_util->area_arr);
		$area_long_arr_flip = array_flip($nf_util->area_long_arr);
		$area_long_arr_flip['전북특별자치도'] = '전북'; // : 추가된 도입니다.

		// Loop through our array, show HTML source as HTML source; and line numbers too.
		$area_post = "";
		$GU = array();
		$DONG =array();

		$rank_arr = array('SI'=>0, 'GU'=>array(), 'DONG'=>array());
		function area_insert($arr) {
			global $rank_arr, $db;
			$_val['pno'] = $arr['pno'];
			$_val['pnos'] = $arr['pnos'];
			$_val['wr_type'] = 'area';
			$_val['wr_name'] = $arr['name'];
			$_val['wr_name_eng'] = $arr['name_eng'];
			$_val['wr_view'] = 1;
			$_val['wr_rank'] = $arr['rank'];
			$_val['wr_wdate'] = today_time;
			$q = $db->query_q($_val);
			$query = $db->_query("insert into nf_area set ".$q, $_val);
			return $insert_no = $db->last_id();
		}

		$lines = file($_FILES['post_file']['tmp_name']);
		$count = 0;
		foreach ($lines as $line_num => $line) {
			if($line_num==0) continue;
			$line_arr = explode("|", $line);

			$post = $line_arr[0];
			$SI = $area_long_arr_flip[$line_arr[1]];
			$SI_eng = $line_arr[2];
			$GU = $SI=='세종' ? '세종시' : $line_arr[3];
			$Gu_eng = $SI=='세종' ? 'Sejong-si' : $line_arr[4];
			$DONG = $line_arr[5];
			$DONG_eng = $line_arr[6];

			$SI_row = $db->query_fetch("select * from nf_area where `wr_name`=? and `pno`=0", array($SI));
			$GU_row = $db->query_fetch("select * from nf_area where `pno`=? and wr_name=?", array($SI_row['no'], $GU));
			$DONG_row = $db->query_fetch("select * from nf_area where `pno`=? and wr_name=?", array($GU_row['no'], $DONG));

			$__arr = array();
			$SI_no = $SI_row['no'];
			if(!$SI_row) {
				$__arr['pno'] = 0;
				$__arr['pnos'] = '';
				$__arr['name'] = $SI;
				$__arr['name_eng'] = $SI_eng;
				$__arr['rank'] = $area_arr_flip[$SI]+1;
				$SI_no = area_insert($__arr);
			}

			$__arr = array();
			$GU_no = $GU_row['no'];
			if(!$GU_row) {
				if($SI=='세종') $GU = '세종시';
				if(!$rank_arr['GU'][$SI]) $rank_arr['GU'][$SI] = 0;
				$__arr['pno'] = $SI_no;
				$__arr['pnos'] = $SI_no.',';
				$__arr['name'] = $GU;
				$__arr['name_eng'] = $Gu_eng;
				$rank_arr['GU'][$SI]++;
				$__arr['rank'] = $rank_arr['GU'][$SI];
				$GU_no = area_insert($__arr);
			}

			$__arr = array();
			$DONG_no = $DONG_row['no'];
			if(!$DONG_row) {
				if(!$rank_arr['DONG'][$SI]) $rank_arr['DONG'][$SI] = array();
				if(!$rank_arr['DONG'][$SI][$GU]) $rank_arr['DONG'][$SI][$GU] = 0;
				$__arr['pno'] = $GU_no;
				$__arr['pnos'] = $SI_no.','.$GU_no.',';
				$__arr['name'] = $DONG;
				$__arr['name_eng'] = $DONG_eng;
				$rank_arr['DONG'][$SI][$GU]++;
				$__arr['rank'] = $rank_arr['DONG'][$SI][$GU];
				$DONG_no = area_insert($__arr);
			}

			$count++;
		}

		$arr['msg'] = '지역 디비를 밀어넣었습니다.';
		$arr['js'] = '
		$(".upload_ing").removeClass("on");
		';
		die(json_encode($arr));
	break;

	default:
		switch($_GET['mode']) {
			case "logout_process":
				$_SESSION[$nf_admin->sess_adm_uid] = "";
				$arr['move'] = NFE_URL."/admini/";
				die($nf_util->move_url($arr['move']));
			break;


			case "excel_admin_mem_q":
			case "excel_admin_mem_individual_q":
			case "excel_admin_mem_company_q":
				ini_set("memory_limit", "-1"); // 메모리 제한을 여유 있게 설정
				ini_set('max_execution_time', 300);

				require_once NFE_PATH."/plugin/PHPExcel/Classes/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
				require_once NFE_PATH."/plugin/PHPExcel/Classes/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
				function column_char($i) { return chr( 65 + $i ); }

				$excel_header = array('excel_admin_mem_q'=>'회원리스트');
				$excel_txt = $excel_header[$_GET['mode']];

				$sess_txt = substr($_GET['mode'], 6);

				$excel = new PHPExcel();

				switch($_GET['mode']) {
					case "excel_admin_mem_q":
						$mem_query = $db->_query("select * from ".$_SESSION[$sess_txt]);

						$headers = array($excel_txt.' - '.date("Y-m-d H:i:s"));
						$headers_menu = array('회원구분', '회원ID', '이름/대표자', '회원등급', '포인트', '회사명', '업체공고', '가입일', '불량');
						$widths = array(15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);
						$result = array();
						foreach($headers_menu as $k=>$v) {
							$result[2][$k+1] = $v;
						}

						$count = 3;

						while($mem_row=$db->afetch($mem_query)) {
							$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));

							switch($mem_row['mb_type']) {
								case "individual":
									$get_name = $mem_row['mb_name'];
									$shop_int = array();
									$company_name = "";
								break;

								case "company":
									$get_name = $mem_row['mb_ceo_name'];
									$shop_int = $db->query_fetch("select count(*) as c from nf_shop where `is_delete`=0 and `mno`=".intval($mem_row['no']));
									$company_name = $mem_row['mb_company_name'];
								break;
							}

							if(!$shop_int) $shop_int['c'] = 0;

							$result[$count][] = $nf_member->mb_type[$mem_row['mb_type']].'회원';
							$result[$count][] = $nf_util->get_text($mem_row['mb_id']);
							$result[$count][] = $nf_util->get_text($get_name);
							$result[$count][] = $env['member_level_arr'][$mem_row['mb_level']]['name'];
							$result[$count][] = intval($mem_row['mb_point']);
							$result[$count][] = $company_name;
							$result[$count][] = number_format(intval($shop_int['c']));
							$result[$count][] = substr($mem_row['mb_wdate'],0,10);
							$result[$count][] = $mem_row['mb_badness'] ? '불량' : '';

							$count++;
						}
					break;


					case "excel_admin_mem_company_q":
						$mem_query = $db->_query("select * from ".$_SESSION[$sess_txt]);

						$headers = array($excel_txt.' - '.date("Y-m-d H:i:s"));
						$headers_menu = array('회원등급', '회원ID', '업체명', '이름/대표자', '닉네임', '포인트', '등록건수', '가입일', '불량');
						$widths = array(15, 15, 25, 15, 15, 15, 15, 15, 15, 15);
						$result = array();
						foreach($headers_menu as $k=>$v) {
							$result[2][$k+1] = $v;
						}

						$count = 3;

						while($mem_row=$db->afetch($mem_query)) {
							$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));
							$shop_int = $db->query_fetch("select count(*) as c from nf_shop where `is_delete`=0 and `mno`=".intval($mem_row['no']));

							$result[$count][] = $nf_member->mb_type[$mem_row['mb_type']].'회원';
							$result[$count][] = $nf_util->get_text($mem_row['mb_id']);
							$result[$count][] = $nf_util->get_text($mem_row['mb_company_name']);
							$result[$count][] = $nf_util->get_text($mem_row['mb_name']);
							$result[$count][] = $nf_util->get_text($mem_row['mb_nick']);
							$result[$count][] = intval($mem_row['mb_point']);
							$result[$count][] = intval($shop_int['c']);
							$result[$count][] = substr($mem_row['mb_wdate'],0,10);
							$result[$count][] = $mem_row['mb_badness'] ? '불량' : '';

							$count++;
						}
					break;


					case "excel_admin_mem_individual_q":
						$mem_query = $db->_query("select * from ".$_SESSION[$sess_txt]);

						$headers = array($excel_txt.' - '.date("Y-m-d H:i:s"));
						$headers_menu = array('회원등급', '회원ID', '이름', '닉네임', '포인트', '가입일', '불량');
						$widths = array(15, 15, 25, 15, 15, 15, 15, 15, 15, 15);
						$result = array();
						foreach($headers_menu as $k=>$v) {
							$result[2][$k+1] = $v;
						}

						$count = 3;

						while($mem_row=$db->afetch($mem_query)) {
							$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));

							$result[$count][] = $nf_member->mb_type[$mem_row['mb_type']].'회원';
							$result[$count][] = $nf_util->get_text($mem_row['mb_id']);
							$result[$count][] = $nf_util->get_text($mem_row['mb_name']);
							$result[$count][] = $nf_util->get_text($mem_row['mb_nick']);
							$result[$count][] = intval($mem_row['mb_point']);
							$result[$count][] = substr($mem_row['mb_wdate'],0,10);
							$result[$count][] = $mem_row['mb_badness'] ? '불량' : '';

							$count++;
						}
					break;
				}

				$data = array_merge(array($headers), $result);

				// 스타일 지정
				$header_bgcolor = 'FFABCDEF';

				// 엑셀 생성
				$last_char = column_char( count($headers) - 1 );

				$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
				$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
				foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
				$excel->getActiveSheet()->fromArray($data,NULL,'A1');

				$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$excel_txt.'_'.date("Y-m-d H:i:s").'.xls"');
				header('Cache-Control: max-age=0');

				$writer->save('php://output');

				exit;
			break;


			case "excel_payment_list_q":
				ini_set("memory_limit", "-1"); // 메모리 제한을 여유 있게 설정
				ini_set('max_execution_time', 300);

				require_once NFE_PATH."/plugin/PHPExcel/Classes/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
				require_once NFE_PATH."/plugin/PHPExcel/Classes/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
				function column_char($i) { return chr( 65 + $i ); }


				$excel_txt = '결제리스트';
				$excel = new PHPExcel();

				$payment_query = $db->_query("select * from ".$_SESSION['payment_list']);

				$headers = array($excel_txt.' - '.date("Y-m-d H:i:s"));
				$headers_menu = array('회원구분', '이름', '아이디', '공고(이력서)', '결제정보', '결제수단', '진행상태', '할인금액', '결제금액', '결제일');
				$widths = array(15, 30, 20, 50, 70, 15, 15, 15, 15, 35);
				$result = array();
				foreach($headers_menu as $k=>$v) {
					$result[2][$k+1] = $v;
				}

				$count = 3;
				while($pay_row=$db->afetch($payment_query)) {
					$pay_info = $nf_payment->pay_info($pay_row);
					$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($pay_row['pay_mno']));
					if(in_array($pay_row['pay_type'], array('employ', 'resume')))
					$info_row = $db->query_fetch("select * from nf_".$pay_row['pay_type']." where `no`=".intval($pay_row['pay_no']));

					ob_start();
					if(!array_key_exists($pay_row['pay_type'], $nf_payment->payment_basic_code)) {
						$post_service = $pay_info['post_unse']['service'];
						$post_arr = $pay_info['post_unse'];
						$price_arr = $pay_info['price_unse'];
						$tag_skin = 'skin1';
						include NFE_PATH.'/include/payment/service.inc.php';
					} else {
						echo $nf_payment->payment_basic_code[$pay_row['pay_type']];
					}

					if($pay_info['post_unse']['tax_use']) {
						echo chr(10).chr(10).'현금영수증 정보'.chr(10);
						echo $nf_payment->pay_tax_type[$pay_info['post_unse']['pay_tax_type']].chr(10);
						if($pay_info['post_unse']['pay_tax_type']==='1') {
							echo $nf_payment->pay_tax_num_type[$pay_info['post_unse']['pay_tax_num_type']].' : '.$pay_info['post_unse']['pay_tax_num_person'];
						} else {
							echo '사업자등록번호 : '.implode("-", $pay_info['post_unse']['pay_tax_num_biz']);
						}
					}
					$service_info = strip_tags(ob_get_clean());

					$result[$count][] = $nf_member->mb_type[$get_member['mb_type']].'회원';
					$result[$count][] = $pay_row['pay_name'];
					$result[$count][] = $pay_row['pay_uid'];
					$result[$count][] = $nf_util->get_text($info_row['wr_subject']);
					$result[$count][] = $service_info;
					$result[$count][] = $pay_info['pay_method_txt'];
					$result[$count][] = $nf_payment->pay_status[$pay_row['pay_status']];
					$result[$count][] = number_format(intval($pay_row['pay_dc']));
					$result[$count][] = number_format(intval($pay_row['pay_price']));
					$result[$count][] = '결제신청일 : '.$pay_row['pay_wdate'].chr(10).'결제일 : '.($pay_row['pay_status']==='1' ? $pay_row['pay_sdate'] : "입금대기");

					$excel->getActiveSheet()->getStyle('E'.$count)->getAlignment()->setWrapText(true); // 줄바꿈 허용
					$excel->getActiveSheet()->getStyle('J'.$count)->getAlignment()->setWrapText(true); // 줄바꿈 허용

					$excel->getActiveSheet()->getStyle("A".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("B".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("C".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("D".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("E".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("F".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("G".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("H".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("I".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$excel->getActiveSheet()->getStyle("J".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

					$count++;
				}

				$data = array_merge(array($headers), $result);

				// 스타일 지정
				$header_bgcolor = 'FFABCDEF';

				// 엑셀 생성
				$last_char = column_char( count($headers) - 1 );

				$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
				$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
				foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
				$excel->getActiveSheet()->fromArray($data,NULL,'A1');

				$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.$excel_txt.'_'.date("Y-m-d H:i:s").'.xls"');
				header('Cache-Control: max-age=0');

				$writer->save('php://output');

				exit;
			break;
		}
	break;
}
?>