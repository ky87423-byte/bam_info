<?php
include "../engine/_core.php";

switch ($_POST['mode']) {

	//################## 커뮤니티 ##################//
	case "file_download_delete":
		$file_row = $db->query_fetch("select * from nf_board_file where `no`=" . intval($_POST['no']));
		$is_delete = false;
		if ($file_row['mno'] && $file_row['mno'] == $member['no'])
			$is_delete = true; // : 회원권한
		if (!$file_row['mno'] && $_SESSION['board_view_' . $file_row['bo_table'] . '_' . $file_row['wr_no']])
			$is_delete = true; // : 비회원권한
		$arr['msg'] = "파일을 삭제할 권한이 없습니다.";
		if ($is_delete) {
			$arr['msg'] = "삭제된 파일입니다.";
			if (is_file(NFE_PATH . '/data/board/' . $file_row['bo_table'] . '/' . $file_row['file_name'])) {
				unlink(NFE_PATH . '/data/board/' . $file_row['bo_table'] . '/' . $file_row['file_name']);
				$arr['msg'] = "삭제가 완료되었습니다.";
				$delete = $db->_query("update nf_board_file set file_del=1 where `no`=" . intval($file_row['no']));
				$arr['js'] = '
				nf_board.attach_item_remove(el);
				';
				die(json_encode($arr));
			}
		}
		break;

	case "add_attach":
		$bo_table = trim($_POST['bo_table']);
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$board_info = $nf_board->board_info($bo_row);

		$arr = array();
		$arr['msg'] = "";
		if ($_POST['cnt'] >= $board_info['bo_upload_count'])
			$arr['msg'] = number_format(intval($board_info['bo_upload_count'])) . '개까지 업로드가 가능합니다.';
		else {
			$arr['js'] = '
			var obj = $(".attach-item-td-").find(".attach-item-").eq(0).clone(true).wrapAll("<div/>").parent();
			obj.find("[name=\'attach[]\']").val("");
			$(".attach-item-td-").append(obj);
			';
		}

		die(json_encode($arr));
		break;

	case "auth_check":
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$board_info = $nf_board->board_info($bo_row);
		$b_row = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($_POST['no']));

		$arr['msg'] = "";
		if (!in_array($_POST['code'], array('write', 'delete')))
			$arr['msg'] = "게시물이 없습니다.";
		if ($b_row)
			$arr['msg'] = "";

		$auth = false;
		if ($member['no'] && $b_row['mno'] == $member['no'])
			$auth = true; // : 내글 통과
		if (!$b_row['mno'])
			$auth = 'password';
		if ($_SESSION['board_view_' . $bo_table . '_' . $b_row['wr_no']])
			$auth = true; // : 세션통과

		if ($bo_row['bo_type'] == 'talk' || in_array($bo_table, array('service_advert'))) {
			if ($member['no'] && $b_row['mno'] == $member['no']) {
				$auth = true;
			} else {
				$parent_ori_arr = explode(",", $b_row['wr_parent_ori']);
				$bb_row = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($parent_ori_arr[0]));
				if ($member['no'] && $bb_row['mno'] == $member['no'])
					$auth = true;
			}
		}

		switch ($_POST['code']) {
			case "write":
				if ($auth === true) {
					$arr['msg'] = '';
					$arr['move'] = NFE_URL . '/board/write.php?bo_table=' . $bo_table . '&no=' . $b_row['wr_no'];
				}
				break;

			case "delete":
				if ($auth === true) {
					$arr['msg'] = '';
					$arr['js'] = '
					nf_board.click_delete(el, "' . $bo_table . '", "' . intval($b_row['wr_no']) . '");
					';
				}
				break;

			case "read":
				if ($auth === true) {
					$arr['msg'] = '';
					$arr['move'] = NFE_URL . '/board/view.php?bo_table=' . $bo_table . '&no=' . $b_row['wr_no'];
				}
				break;
		}

		if ($auth === 'password') {
			$arr['js'] = '
				var pass_form = document.forms["fpassword"];
				$(pass_form).find("[name=\'bo_table\']").val(bo_table);
				$(pass_form).find("[name=\'no\']").val(no);
				$(pass_form).find("[name=\'kind\']").val("board");
				$(pass_form).find("[name=\'code\']").val(code);
				if($(el).parent()[0].tagName=="TD") var offset = $(el).parent().offset();
				else var offset = $(el).offset();
				$(".password-box-").css({"top":offset.top+100});
				$(".password-box-").addClass("on");
			';
		} else {
			if (!$auth) {
				$arr['msg'] = '게시물 ' . $nf_board->auth_arr[$_POST['code']] . ' 권한이 없습니다.';
				$arr['move'] = NFE_URL . '/board/index.php?cno=' . $bo_row['pcode'];
				if ($nf_board->auth($bo_table, 'list'))
					$arr['move'] = $_SESSION['board_list_' . $bo_table];
			}
		}

		die(json_encode($arr));
		break;

	case "report_board":
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL . '/member/login.php?page_url=' . urlencode($nf_util->page_back());
		if ($member['no']) {
			$arr['move'] = "";
			$bo_table = trim($_POST['bo_table']);
			$_table = $nf_board->get_table($bo_table);
			$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
			$board_info = $nf_board->board_info($bo_row);
			$b_row = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($_POST['no']));
			$report_row = $db->query_fetch("select * from nf_board_report where `pno`=? and `mno`=? and `bo_table`=?", array($_POST['no'], $member['no'], $bo_table));

			$arr['msg'] = "신고할 수 없습니다.";
			if ($b_row['mno'] && $member['no'] == $b_row['mno'])
				$arr['msg'] = "본인글은 신고할 수 없습니다.";
			else if ($report_row)
				$arr['msg'] = "이미 신고한 글입니다.";
			else if ($b_row) {
				$arr['msg'] = "신고가 완료되었습니다.";

				$_val = array();
				$_val['pno'] = $_POST['no'];
				$_val['bo_table'] = $bo_table;
				$_val['mno'] = $member['no'];
				$_val['mid'] = $member['mb_id'];
				$_val['content'] = $_POST['content'];
				$_val['ip'] = $_SERVER['REMOTE_ADDR'];
				$_val['wdate'] = today_time;
				$q = $db->query_q($_val);
				$insert = $db->_query("insert into nf_board_report set " . $q, $_val);

				if ($insert) {
					$update = $db->_query("update " . $_table . " set `wr_reply_cnt`=`wr_reply_cnt`+1 where `wr_no`=?", array($b_row['wr_no']));
				}
			}
		}
		die(json_encode($arr));
		break;


	case "delete_board":
	case "board_select_delete":
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$nos = intval($_POST['no']);
		if ($_POST['mode'] == 'board_select_delete' && $is_admin)
			$nos = implode(",", $_POST['chk']);
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$board_info = $nf_board->board_info($bo_row);

		if ($nos) {
			$query = $db->_query("select * from " . $_table . " where `wr_no` in (" . $nos . ")");
			while ($b_row = $db->afetch($query)) {
				if ($b_row['mno'])
					$_where = " and `mno`=" . intval($member['no']);

				$is_delete = false;

				$arr['msg'] = "삭제권한이 없습니다.";
				if ($b_row['mno'] && $b_row['mno'] == intval($member['no']))
					$is_delete = true; // : 회원삭제
				if (!$b_row['mno'] && $_SESSION['board_view_' . $bo_table . '_' . $b_row['wr_no']])
					$is_delete = true; // : 비회원삭제
				if (strpos($nf_util->page_back(), '/admini/') !== false) {
					$is_delete = true;
					$_where = "";
				}
				if ($is_delete) {
					$arr['msg'] = "삭제가 실패되었습니다.\n관리자에 문의하시기 바랍니다.";
					if ($b_row['wr_no']) {
						$delete = $db->_query("update " . $_table . " set `wr_del`=1 where `wr_no`=" . intval($b_row['wr_no']) . " " . $_where);
						if ($delete) {
							$db->_query("update nf_board set `bo_write_count`=`bo_write_count`-1 where `bo_table`=?", array($bo_table));
							$db->_query("delete from nf_board_int where bo_table=? and `pno`=?", array($bo_table, $b_row['wr_no']));
						}
					}
					if ($delete) {
						$is_move = true;
						$arr['msg'] = "삭제가 완료되었습니다.";
						$update = $db->_query("update " . $_table . " set `wr_comment`=`wr_comment`-1 where `wr_no`=" . intval($b_row['wr_parent']));
					}
				}
			}
		}

		switch (!$b_row['wr_is_comment']) {
			// : 게시글
			case true:
				if ($is_move) {
					$get_arr = $nf_board->alert_move("list", $bo_table);
					$arr['move'] = $get_arr['move'];
					if (strpos($nf_util->page_back(), '/admini/') !== false)
						$arr['move'] = $_SESSION['board_admin_list_' . $bo_table];
				}
				break;

			// : 댓글
			default:
				if ($is_move)
					$arr['move'] = $nf_util->page_back();
				break;
		}

		die(json_encode($arr));
		break;


	// : 대댓글 보기
	case "comment_comment_view":
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);

		$comment_row = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($_POST['no']));
		$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
		$board_info = $nf_board->board_info($bo_row);

		$wr_parent = $comment_row['wr_parent'] . ',' == $coment_row['wr_parent_ori'] ? $comment_row['wr_parent_ori'] : $comment_row['wr_parent_ori'] . intval($comment_row['wr_no']);
		$q = $_table . " where `wr_parent_ori` like '" . $wr_parent . ",%' and `wr_is_comment`=1 and `wr_comment_reply`!=''" . $nf_board->bo_where;
		$total = $db->query_fetch("select count(*) as c from " . $q);

		$_arr = array();
		$_arr['num'] = 99999;
		$_arr['total'] = $total['c'];
		$paging = $nf_util->_paging_($_arr);

		$arr['q'] = "select * from " . $q . " order by wr_num, `wr_comment_reply` limit " . $paging['start'] . ", " . $_arr['num'];
		$query = $db->_query($arr['q']);
		$nums = $db->num_rows($query);

		ob_start();
		include NFE_PATH . '/board/comment_comment.inc.php';
		$arr['tag'] = ob_get_clean();

		if ($_arr['total'] <= 0)
			$arr['msg'] = "댓글이 없습니다.";
		else {
			$arr['js'] = '
			$(".comment_comment_list-").html(data.tag);
			var display = $(el).closest(".comment_li-").find(".comment_comment_list-").css("display");
			$(".comment_li-").find(".comment_comment_list-").css({"display":"none"});
			if(display=="none")
				$(el).closest(".comment_li-").find(".comment_comment_list-").css({"display":"block"});
			';
		}

		die(json_encode($arr));

		break;






	// : 좋아요
	case "click_board_is_good":
		$good_arr = array('good' => '좋아요', 'nogood' => '싫어요');
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$code = $_POST['code'] == 'bad' ? 'nogood' : 'good';
		$row = $db->query_fetch("select * from nf_board_good where `bo_table`=? and `wr_no`=? and `mno`=?", array($bo_table, $_POST['no'], intval($member['no'])));

		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL . '/member/login.php?page_url=' . urlencode($nf_util->page_back());
		if ($member['no']) {
			$arr['move'] = "";
			if (!$row) {
				$arr['js'] = '
					nf_board.good(el, bo_table, no, code);
				';
				$arr['msg'] = "해당 게시물을 추천하였습니다.";
			} else {
				$arr['js'] = '
					if(confirm("추천을 해지 하시겠습니까?"))
						nf_board.good(el, bo_table, no, code);
				';
			}
		}
		die(json_encode($arr));
		break;
	case "click_board_good":
		$good_arr = array('good' => '좋아요', 'nogood' => '싫어요');
		$bo_table = trim($_POST['bo_table']);
		$_table = $nf_board->get_table($bo_table);
		$code = $_POST['code'] == 'bad' ? 'nogood' : 'good';
		$row = $db->query_fetch("select * from nf_board_good where `bo_table`=? and `wr_no`=? and `mno`=?", array($bo_table, $_POST['no'], intval($member['no'])));
		$write = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($_POST['no']));
		$arr['msg'] = "로그인하셔야 이용가능합니다.";
		$arr['move'] = NFE_URL . '/member/login.php?page_url=' . urlencode($nf_util->page_back());
		if ($member['no']) {
			$arr['msg'] = $arr['move'] = "";
			if (!$write) {
				$arr['msg'] = "게시물이 없습니다.";
				$arr['move'] = NFE_URL . '/board/list.php?bo_table=' . $bo_table;
			} else {
				$_val = array();
				$_val['bo_table'] = $bo_table;
				$_val['wr_no'] = $_POST['no'];
				$_val['mno'] = $member['no'];
				$_val['wr_id'] = $member['mb_id'];
				$_val['bg_flag'] = $code;
				$_val['bg_datetime'] = today_time;
				$q = $db->query_q($_val);

				$good_c = $write['wr_is_comment'] ? '$(el).find(".' . $code . '-int-")' : '$(".view-good-int-")';

				if ($row) {
					if ($row['bg_flag'] == $code) {
						$query = $db->_query("delete from nf_board_good where `bg_id`=" . intval($row['bg_id']));
						$query = $db->_query("update " . $_table . " set `wr_" . $code . "`=`wr_" . $code . "`-1 where `wr_no`=" . intval($_POST['no']));
						$arr['js'] = '
						' . $good_c . '.html("' . number_format(intval($write['wr_' . $code]) - 1) . '");
						';

						// : 기간별 조회수 저장
						$board_save_int_minus = true;
						$nf_board->save_int("good", $bo_table, $_POST['no']);

					} else {
						$arr['msg'] = '이미 ' . $good_arr[$row['bg_flag']] . '로 선택했습니다.';
					}
				} else {
					$query = $db->_query("insert into nf_board_good set " . $q, $_val);
					$query = $db->_query("update " . $_table . " set `wr_" . $code . "`=`wr_" . $code . "`+1 where `wr_no`=" . intval($_POST['no']));
					$arr['js'] = '
					' . $good_c . '.html("' . number_format(intval($write['wr_' . $code]) + 1) . '");
					';

					// : 기간별 조회수 저장
					$nf_board->save_int("good", $bo_table, $_POST['no']);
				}
			}
		}
		die(json_encode($arr));
		break;







	// : 게시물 등록
	case "board_write":
		if (strpos($nf_util->page_back(), '/admini/') === false && !(in_Array($_POST['code'], array('comment_insert')) || $_POST['bo_table'] == 'service_advert')) {
			$recaptcha_allow = $nf_util->recaptcha_check();
			if (!$recaptcha_allow['success']) {
				die(json_encode($recaptcha_allow));
			}
		}

		$is_captcha = false;
		$is_use_admin = strpos($nf_util->page_back(), '/admini/') !== false ? true : false;
		if (!in_array($_POST['code'], array('comment_insert', 'comment_comment_insert')))
			$is_captcha = true;
		if ($member['no'])
			$is_captcha = false;
		if ($is_use_admin)
			$is_captcha = false;
		if ($is_captcha) {
			$arr = $nf_util->captcha_key_check();
			$arr['move'] = $nf_util->page_back();
			if ($arr['msg']) {
				die(json_encode($arr));
			}
		}
		if (!$arr['msg']) {
			$bo_table = trim($_POST['bo_table']);
			$_table = $nf_board->get_table($bo_table);

			$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
			$get_write = $db->query_fetch("select * from " . $_table . " where `wr_no`=" . intval($_POST['no']));
			$my_write = $get_write['mno'] == $member['no'] || $_SESSION['board_view_' . $bo_table . '_' . $get_write['wr_no']] ? true : false; // : 내가쓴글인지 [ 비회원 포함 ]
			if ($is_admin)
				$my_write = true; // : 관리자는 수정요건 무조건 허용
			$is_modify = $get_write && $my_write && $_POST['code'] == 'insert' ? true : false; // : 수정여부

			$wr_id = $member['mb_id'] ? $member['mb_id'] : "";
			$wr_email = ($_POST['wr_email']) ? $_POST['wr_email'] : $member['mb_email'];
			if (is_array($_POST['wr_email']))
				$wr_email = implode("@", $_POST['wr_email']);
			$wr_homepage = ($_POST['wr_homepage']) ? $_POST['wr_homepage'] : $member['mb_homepage'];
			if ($_POST['bo_table'] == 'service_advert' && is_array($_POST['wr_2']))
				$_POST['wr_2'] = implode("-", $_POST['wr_2']);

			/* 20초 제한 */
			if ($_SESSION["ss_datetime"] >= (time() - 20)) {
				//$arr['msg'] = '너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.';
			}

			$allow_ext = $nf_util->photo_ext;
			$bo_upload_ext = explode("|", strtolower($bo_row['bo_upload_ext']));
			if (!is_array($bo_upload_ext))
				$bo_upload_ext = array();
			$allow_ext = array_diff(array_merge($allow_ext, $bo_upload_ext), array(""));
			if (is_array($_FILES['attach']['tmp_name'])) {
				foreach ($_FILES['attach']['tmp_name'] as $k => $tmp) {
					$f_source = $_FILES['attach']['name'][$k];
					$f_size = filesize($tmp);
					$f_size_compare = strtr($nf_util->get_filesize($f_size, 'M'), array('M' => ''));
					$hidd_no = $_POST['attach_hidd'][$k];
					$ext = $nf_util->get_ext($f_source);
					if (!in_array($ext, $allow_ext)) {
						$arr['msg'] = '첨부파일은 ' . implode(", ", $allow_ext) . ' 확장자만 이용 가능합니다.';
					}

					if (!$arr['msg'] && $f_size_compare > $bo_row['bo_upload_size']) {
						$arr['msg'] = '첨부파일은 ' . $bo_row['bo_upload_size'] . 'MB 이하로 등록하셔야 합니다.';
					}
				}
			}
			if ($arr['msg']) {
				$arr['move'] = $nf_util->page_back();
				die(json_encode($arr));
			}


			switch ($_POST['code']) {

				// : 등록, 수정
				case "insert":
					// : 수정
					if (!$get_write && $_POST['no'] && !$arr['msg'])
						$arr['msg'] = "글이 존재하지 않습니다.\n글이 삭제되었거나 이동하였을 수 있습니다.";
					if ($get_write && $my_write) {
						if (!$is_admin && $_POST['wr_option'] == 'notice' && !$arr['msg'])
							$arr['msg'] = "공지사항은 관리자만 작성 가능합니다.";

						// : 비회원이 수정한 경우 [ 세션값 없으면 수정불가 ]
					} else if ($get_write && !$get_write['mno'] && !$_SESSION['board_view_' . $bo_table . '_' . $get_write['wr_no']]) {
						$arr['msg'] = "권한이 없습니다.";

						// : 등록
					} else {
						$min_wr_num = $db->query_fetch("select min(wr_num) as c from " . $_table);
						$wr_num = intval($min_wr_num['c']) - 1;
					}
					break;

				// : 답변
				case "reply":
					// 최대 답변은 테이블에 잡아놓은 wr_reply 사이즈만큼만 가능합니다.
					if (strlen($get_write['wr_reply']) == 10 && !$arr['msg']) {
						$arr['msg'] = "더 이상 답변하실 수 없습니다.\n답변은 10단계 까지만 가능합니다.";
					}

					$reply_len = strlen($get_write['wr_reply']) + 1;

					$begin_reply_char = "Z";
					$end_reply_char = "A";
					$reply_number = -1;
					$re_q = " select MIN(SUBSTRING(wr_reply, " . $reply_len . ", 1)) as reply from " . $_table . " where wr_num = '" . $get_write['wr_num'] . "' and SUBSTRING(wr_reply, " . $reply_len . ", 1) <> '' ";
					if ($get_write['wr_reply'])
						$re_q .= " and `wr_reply` like '" . $get_write['wr_reply'] . "%' ";
					$re_row = $db->query_fetch($re_q);

					if (!$re_row['reply']) {
						$reply_char = $begin_reply_char;
					} else if ($re_row['reply'] == $end_reply_char && !$arr['msg']) { // A~Z은 26 입니다.
						$arr['msg'] = "더 이상 답변하실 수 없습니다.\n답변은 26개 까지만 가능합니다.";
					} else {
						$reply_char = chr(ord($re_row['reply']) + $reply_number);
					}
					$reply = $get_write['wr_reply'] . $reply_char;
					$wr_num = $get_write['wr_num'];

					$wr_parent_ori = $get_write['wr_parent_ori'] . $get_write['wr_no'] . ',';
					break;

				// : 댓글 등록, 수정
				case "comment_insert":
				case "comment_comment_insert":
					$is_modify = false;
					if (!$arr['msg'] && !$nf_board->auth($bo_table, 'comment'))
						$arr['msg'] = "코멘트를 쓸 권한이 없습니다.";
					if (!$arr['msg'] && !$member['no'] && !$_POST['comment_id'] && $_SESSION['_reply_rand_' . $bo_table . '_' . $_POST['no']] != strtolower($_POST['rand_number'])) {
						$arr['msg'] = "자동등록방지 문자입력을 정확히 입력해주시기 바랍니다.";
					} else if (!$arr['msg'] && !$member['no'] && $_POST['comment_id'] && $_SESSION['_reply_rand_' . $bo_table . '_' . $_POST['comment_id']] != strtolower($_POST['rand_number'])) {
						$arr['msg'] = "자동등록방지 문자입력을 정확히 입력해주시기 바랍니다.";
					}

					// 동일내용 연속 등록 불가
					$row = $db->query_fetch("select CONCAT(wr_ip, wr_subject, wr_content) as prev_concat from `" . $_table . "` order by wr_id desc limit 1");
					$curr_concat = $_SERVER['REMOTE_ADDR'] . $_POST['wr_subject'] . $_POST['wr_content'];

					if (!$arr['msg'] && $row['prev_concat'] == $curr_concat)
						$arr['msg'] = "동일한 내용을 연속해서 등록할 수 없습니다.";
					if (!$get_write)
						$arr['msg'] = "글이 존재하지 않습니다.\n글이 삭제되었거나 이동하였을 수 있습니다.";

					if ($_POST['comment_id']) {
						$reply_array = $db->query_fetch("select wr_no, wr_comment, wr_comment_reply, wr_parent_ori from `" . $_table . "` where `wr_no` = '" . $_POST['comment_id'] . "'");
						if (!$arr['msg'] && !$reply_array['wr_no'])
							$arr['msg'] = "답변할 코멘트가 없습니다.\n답변하는 동안 코멘트가 삭제되었을 수 있습니다.";

						//$tmp_comment = $reply_array['wr_comment'];

						//if(!$arr['msg'] && strlen($reply_array['wr_comment_reply'])==5) $arr['msg'] = "더 이상 답변하실 수 없습니다.\n답변은 5단계 까지만 가능합니다.";

						$reply_len = strlen($reply_array['wr_comment_reply']) + 1;
						if ($bo_row['bo_reply_order']) {
							$begin_reply_char = "A";
							$end_reply_char = "Z";
							$reply_number = +1;
							$query = "select MAX(SUBSTRING(wr_comment_reply, " . intval($reply_len) . ", 1)) as reply from `" . $_table . "` where `wr_parent` = " . intval($_POST['no']) . " and `wr_comment`='" . addslashes($tmp_comment) . "' and SUBSTRING(wr_comment_reply, " . intval($reply_len) . ", 1) <> ''";
						} else {
							$begin_reply_char = "Z";
							$end_reply_char = "A";
							$reply_number = -1;
							$query = " select MIN(SUBSTRING(wr_comment_reply, " . intval($reply_len) . ", 1)) as reply from `" . $_table . "` where `wr_parent` = " . intval($wr_no) . " and `wr_comment`='" . addslashes($tmp_comment) . "' and SUBSTRING(wr_comment_reply, " . $reply_len . ", 1) <> ''";
						}

						if ($reply_array['wr_comment_reply'])
							$query .= " and wr_comment_reply like '" . addslashes($reply_array['wr_comment_reply']) . "%'";
						$row = $db->query_fetch($query);

						if (!$row['reply']) {
							$reply_char = $begin_reply_char;
						} else if (!$arr['msg'] && $row['reply'] == $end_reply_char) { // A~Z은 26 입니다.
							$arr['msg'] = "더 이상 답변하실 수 없습니다.\n답변은 26개 까지만 가능합니다.";
						} else {
							$reply_char = chr(ord($row['reply']) + $reply_number);
						}

						$tmp_comment_reply = $reply_array['wr_comment_reply'] . $reply_char;
						$wr_parent_ori = $reply_array['wr_parent_ori'] . $reply_array['wr_no'] . ',';
					} else {
						$row = $db->query_fetch("select max(wr_comment) as max_comment from `" . $_table . "` where `wr_parent`=" . intval($_POST['no']) . " and `wr_is_comment`=1");
						$row['max_comment'] += 1;
						//$tmp_comment = $row['max_comment'];
						$tmp_comment_reply = "";
						$wr_parent_ori = $get_write['wr_parent_ori'] . $get_write['wr_no'] . ',';
					}

					$wr_is_comment = 1;
					break;
			}

			preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", stripslashes($_POST['wr_content']), $content_images);
			if ($content_images[1])
				$wr_thumb = $content_images[1][0];

			// : 글쓰기,댓글 포인트체크 [ 음수인경우 체크 ]
			$point_code_txt = (in_array($_POST['code'], array('insert', 'reply'))) ? 'write' : 'comment';
			if (!$is_use_admin && $nf_board->point_process_check($point_code_txt, $bo_table)) {
				$arr['msg'] = '포인트가 없어서 ' . $bo_row['bo_subject'] . ' 게시판 ' . $nf_board->auth_arr[$point_code_txt] . "(을)를 할 수 없습니다.\n필요 포인트는 " . abs($bo_row['bo_' . $point_code_txt . '_point']) . 'p 입니다.';
				if ($point_code_txt == 'write')
					$arr['move'] = NFE_URL . '/board/list.php?bo_table=' . $bo_table;
				if ($point_code_txt == 'comment')
					$arr['move'] = "";
				die(json_encode($arr));
			}

			/*
			echo $arr['msg'];
			echo $_POST['code']."@@=>".$wr_parent_ori."@".$_POST['comment_id'];
			$wr_comment_reply_arr = explode(",", $reply_array['wr_parent_ori']);
			print_R($wr_comment_reply_arr);
			exit;
			*/

			if (!$arr['msg']) {
				$_val = array();
				if (!$is_modify) {
					$_val['wr_num'] = $wr_num;
					if ($reply)
						$_val['wr_reply'] = $reply;
					if ($reply || in_array($_POST['code'], array('comment_insert', 'comment_comment_insert')))
						$_val['wr_parent_ori'] = $wr_parent_ori;
					$_val['wr_datetime'] = today_time;
					$_val['wr_id'] = $_POST['page_code'] == 'admin' ? '_admin_' : $member['mb_id'];
					$_val['mno'] = $_POST['page_code'] == 'admin' ? 0 : $member['no']; // : 비회원시에 자동으로 회원주키 저장하고 싶었지만 남의것을 우연히 비밀번호를 맞춰서 들어갈경우 문제가 생겨서 안함.
				}

				$_val['wr_thumb'] = $wr_thumb;
				$_val['wr_category'] = $_POST['wr_category'];
				$_val['wr_secret'] = intval($_POST['wr_secret']);
				$_val['wr_option'] = $_POST['wr_option'];
				$_val['wr_subject'] = $_POST['wr_subject'];
				$_val['wr_content'] = $_POST['wr_content'];
				if ($_POST['wr_password'])
					$_val['wr_password'] = md5($_POST['wr_password']);

				if (in_array($_POST['code'], array('comment_insert', 'comment_comment_insert'))) {
					$_val['wr_parent'] = ($_POST['code'] == 'comment_comment_insert') ? $_POST['comment_id'] : $_POST['no'];
					$_val['wr_is_comment'] = intval($wr_is_comment);
					$_val['wr_comment_reply'] = $tmp_comment_reply;
				}

				if ($_POST['wr_name'])
					$_val['wr_name'] = $_POST['wr_name'];
				$_val['wr_email'] = $wr_email;
				$_val['wr_homepage'] = $wr_homepage;
				$_val['wr_last'] = today_time;
				$_val['wr_ip'] = $_SERVER['REMOTE_ADDR'];
				$_val['wr_0'] = $_POST['wr_0'];
				$_val['wr_1'] = $_POST['wr_1'];
				$_val['wr_2'] = $_POST['wr_2'];
				$_val['wr_3'] = $_POST['wr_3'];
				$_val['wr_4'] = $_POST['wr_4'];
				$_val['wr_5'] = $_POST['wr_5'];
				$_val['wr_6'] = $_POST['wr_6'];
				$_val['wr_7'] = $_POST['wr_7'];
				$_val['wr_8'] = $_POST['wr_8'];
				$_val['wr_9'] = $_POST['wr_9'];
				$q = $db->query_q($_val);

				if ($_POST['bo_table'] == 'service_advert' && $_POST['code'] == 'reply' && $is_admin) {
					$mail_skin = $db->query_fetch("select * from nf_mail_skin where `skin_name`='advert_answer'");
					$email_arr = array();
					$email_arr['subject'] = '고객님께서 문의하신 입점문의에 대한 답변입니다.';
					$email_arr['email'] = $get_write['wr_email'];
					$ch_txt = array();
					$ch_txt['{문의등록일}'] = $get_write['wr_last'];
					$ch_txt['{문의답변일}'] = $_val['wr_last'];
					$ch_txt['{문의제목}'] = $get_write['wr_subject'];
					$ch_txt['{문의내용}'] = strtr($get_write['wr_content'], array('src=\"/data/editor/' => 'src=\"' . domain . '/data/editor/'));
					$ch_txt['{답변내용}'] = strtr($_val['wr_content'], array('src=\"/data/editor/' => 'src=\"' . domain . '/data/editor/'));
					$email_content = strtr($mail_skin['content'], $ch_txt);
					$email_arr['content'] = strtr(stripslashes($email_content), $nf_email->ch_content());
					$nf_email->send_mail($email_arr);

					// : 입점문의 답변 sms
					$sms_arr['name'] = $get_write['wr_name'];
					$sms_arr['phone'] = $get_write['wr_2'];
					$result = $nf_sms->send_sms_service_advert_answer_send($sms_arr);
				}

				// : 입점문의 작성 sms
				if ($_POST['bo_table'] == 'service_advert' && $_POST['code'] == 'insert') {
					$sms_arr['name'] = $_val['wr_name'];
					$sms_arr['phone'] = $_val['wr_2'];
					$result = $nf_sms->send_sms_service_advert_send($sms_arr);
				}

				if ($is_modify)
					$query = $db->_query("update " . $_table . " set " . $q . " where `wr_no`=" . intval($get_write['wr_no']), $_val);
				else
					$query = $db->_query("insert into " . $_table . " set " . $q, $_val);

				$wr_no = $get_write['wr_no'];
				if (!$is_modify)
					$wr_no = $db->last_id();

				$allow_ext = $nf_util->photo_ext;
				$bo_upload_ext = explode("|", strtolower($bo_row['bo_upload_ext']));
				if (!is_array($bo_upload_ext))
					$bo_upload_ext = array();
				$allow_ext = array_merge($allow_ext, $bo_upload_ext);
				$attach_Ym = $get_write['wr_datetime'] ? $get_write['wr_datetime'] : today_time;
				$attach_Ym = date("Ym", strtotime($attach_Ym));
				$nf_board->get_dir($bo_table);
				$nf_board->get_dir_date($bo_table, $attach_Ym);
				if (is_array($_FILES['attach']['tmp_name'])) {
					foreach ($_FILES['attach']['tmp_name'] as $k => $tmp) {
						$f_source = $_FILES['attach']['name'][$k];
						$f_size = filesize($tmp);
						$f_size_compare = strtr($nf_util->get_filesize($f_size, 'M'), array('M' => ''));
						$hidd_no = $_POST['attach_hidd'][$k];
						if ($f_size_compare > 0 && $f_size_compare <= $bo_row['bo_upload_size']) {
							$attach_where = "";
							if ($get_write['mno'])
								$attach_where .= " and `mno`=" . intval($member['no']);
							$get_file = $db->query_fetch("select * from nf_board_file where `no`=" . intval($hidd_no) . " and `wr_no`=" . intval($wr_no) . " and `bo_table`=?" . $attach_where, array($bo_table));
							$ext = $nf_util->get_ext($f_source);
							if (in_array($ext, $allow_ext)) {
								$file_size = getimagesize($tmp);
								$f_name = $attach_Ym . '/file' . $wr_no . '_' . time() . '.' . $k . '.' . $ext;
								if (move_uploaded_file($tmp, NFE_PATH . '/data/board/' . $bo_table . '/' . $f_name)) {
									$_val = array();
									$_val['bo_table'] = $bo_table;
									$_val['wr_no'] = $wr_no;
									$_val['mno'] = $_POST['page_code'] == 'admin' ? 0 : $member['no'];
									$_val['mb_id'] = $_POST['page_code'] == 'admin' ? '_admin_' : $member['mb_id'];
									$_val['file_source'] = $f_source;
									$_val['file_name'] = $f_name;
									$_val['file_filesize'] = $f_size;
									$_val['file_width'] = $file_size[0];
									$_val['file_height'] = $file_size[1];
									$_val['file_type'] = $file_size[2];
									$_val['file_datetime'] = today_time;
									$q = $db->query_q($_val);
									if ($get_file)
										$attach_insert = $db->_query("update nf_board_file set " . $q . " where `no`=" . intval($get_file['no']), $_val);
									else
										$attach_insert = $db->_query("insert into nf_board_file set " . $q, $_val);
									if ($attach_insert && $get_file) {
										if (is_file(NFE_PATH . '/data/board/' . $bo_table . '/' . $get_file['file_name']))
											unlink(NFE_PATH . '/data/board/' . $bo_table . '/' . $get_file['file_name']);
									}
								}
							}
						}
					}
				}

				$msg = $is_modify ? '수정' : '등록';
				$arr['msg'] = $msg . "이 실패되었습니다.";
				if ($query) {

					$_SESSION['board_view_' . $bo_table . '_' . $wr_no] = today_time;

					// : 댓글관련
					if (in_array($_POST['code'], array('comment_insert', 'comment_comment_insert'))) {
						$comment_id = $wr_no;

						switch ($_POST['code']) {
							case "comment_insert":
								// 원글에 코멘트수 증가 & 마지막 시간 반영
								$result = $db->_query(" update `" . $_table . "` set `wr_comment`=`wr_comment`+1, `wr_last`='" . today_time . "' where `wr_no` = " . intval($_POST['no']));

								// 코멘트 1 증가
								$result = $db->_query(" update `nf_board` set `bo_comment_count`=`bo_comment_count`+1 where `bo_table`=?", array($bo_table));

								// 최근게시물 wr_comment update
								$result = $db->_query(" update `nf_board_new` set `wr_comment`=`wr_comment`+1 where `bo_table`=? and `wr_no`=?", array($bo_table, $_POST['no']));
								break;

							case "comment_comment_insert":
								$wr_comment_reply_arr = explode(",", $reply_array['wr_parent_ori']);
								if (is_array($wr_comment_reply_arr)) {
									foreach ($wr_comment_reply_arr as $k => $v) {
										if (!$v)
											continue;
										// 원글에 코멘트수 증가 & 마지막 시간 반영
										$result = $db->_query(" update `" . $_table . "` set `wr_comment`=`wr_comment`+1, `wr_last`='" . today_time . "' where `wr_no` = " . intval($v));
									}
								}
								$result = $db->_query(" update `" . $_table . "` set `wr_comment`=`wr_comment`+1, `wr_last`='" . today_time . "' where `wr_no` = " . intval($_POST['comment_id']));
								break;
						}

						// : 포인트 적립 및 차감
						if (!$is_modify) {
							$nf_board->point_process('comment', $bo_table, $wr_no);
						}

						$arr['msg'] = $msg . "이 완료되었습니다.";
						$arr['move'] = $nf_util->page_back();//NFE_URL."/board/view.php?bo_table=".$bo_table."&no=".$get_write['wr_no']."#c_".$comment_id;

						// : 게시글 관련
					} else {
						// 부모 아이디에 UPDATE
						$db->_query("update " . $_table . " set `wr_parent`=? where `wr_no`=?", array($wr_no, $wr_no));

						// 최근게시물 INSERT - 등록일때만 사용
						if (!$get_write) {
							$db->_query("insert into nf_board_new set `bo_table`=?, `wr_no`=?, `wr_parent`=?, `bn_datetime`=?, `mb_id`=?", array($bo_table, $wr_no, $wr_no, today_time, $wr_id));
						}

						// 게시글 1 증가
						if ($_POST['code'] == 'insert' && !$_POST['no'])
							$db->_query("update nf_board set `bo_write_count`=`bo_write_count`+1 where `bo_table`=?", array($bo_table));

						if ($_POST['wr_secret'])
							$_SESSION["view_secret_" . $bo_table . "_" . $wr_no] = true;

						// : 포인트 적립 및 차감
						if (!$is_modify) {
							$nf_board->point_process('write', $bo_table, $wr_no);
						}

						$arr['msg'] = $msg . "이 완료되었습니다.";
						$arr['move'] = NFE_URL . "/board/view.php?bo_table=" . $bo_table . "&no=" . $wr_no;
						if (strpos($nf_util->page_back(), '/admini/') !== false)
							$arr['move'] = $_SESSION['board_admin_list_' . $bo_table];
						if (strpos($nf_util->page_back(), '/service/advert_regist.php') !== false)
							$arr['move'] = NFE_URL . '/service/advert.php';
					}
				}
			} else {
				$arr['move'] = $nf_util->page_back();
			}
		}
		die(json_encode($arr));
		break;

	case "chulsuk":
		if (!$member['mb_id']) {
			$arr['msg'] = "출석체크는 로그인 후 가능합니다.";
			die(json_encode($arr));
		}

		$id = $member['mb_id'];
		$nick = $member['mb_nick'];
		$date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
		$text = nl2br(stripslashes($_POST['wr_text']));

		$check_sql = "
        SELECT COUNT(*) AS cnt
        FROM nf_chulsuk
        WHERE id = '$id' AND date = '$date'
    ";
		$result = $db->_query($check_sql);
		$row = $db->afetch($result);

		$cnt = 0;
		if (isset($row['cnt'])) {
			$cnt = (int) $row['cnt'];
		} elseif (isset($row['COUNT(*)'])) {
			$cnt = (int) $row['COUNT(*)'];
		} else {
			$cnt = (int) array_values($row)[0];
		}

		if ($cnt == 0) {
			$sql = "
            INSERT INTO nf_chulsuk (id, nick, date, datetime, text)
            VALUES ('$id', '$nick', '$date', '$datetime', '$text')
        ";
			$db->_query($sql);
			$mno = $mem_row['no'];
			$member_chulsuk_point = $env['member_point_arr']['chulsuk_point'];
			if($member_chulsuk_point>0) {
					$nf_member->get_member($mno);
					$_point = array();
					$_point['member'] = $nf_member->member_arr[$mno];
					$_point['code'] = '출석체크 활동 포인트';
					$_point['use_point'] = $env['member_point_arr']['chulsuk_point'];
					$_point['rel_table'] = 'netk_member';
					$_point['rel_id'] = '';
					$_point['rel_action'] = 'member_write';
					$update = $nf_point->insert($_point);
				}
			$arr['msg'] = "출석체크 완료";
			$arr['move'] = $nf_util->page_back();
			die(json_encode($arr));
		} else {
			$arr['msg'] = "오늘은 이미 출석하셨습니다.";
			$arr['move'] = $nf_util->page_back();
			die(json_encode($arr));
		}

		break;

	default:
		switch ($_GET['mode']) {
			// : 파일 다운로드
			case "file_download_check":
			case "file_download":
				$arr['msg'] = "다운로드할 파일이 없습니다.";
				$file_row = $db->query_fetch("select * from nf_board_file where `no`=" . intval($_GET['no']));
				$bo_table = trim($file_row['bo_table']);

				$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
				$board_info = $nf_board->board_info($bo_row);

				if ($file_row) {
					$nf_board->point_process('download', 'nf_board_file', $file_row['no'], 'ajax');

					$arr['msg'] = "";
					$auth = $nf_board->auth($bo_table, 'download');
					$file_dir = NFE_PATH . '/data/board/' . $bo_table . '/' . $file_row['file_name'];
					if (is_file($file_dir)) {
						if ($_GET['mode'] == 'file_download') {
							$nf_util->file_download($file_dir, $file_row['file_source']);
						} else {
							$arr['move'] = NFE_URL . '/board/regist.php?mode=file_download&no=' . $file_row['no'];
						}
					}
				}
				die(json_encode($arr));
				break;
		}
		break;
	//################## 커뮤니티 ##################//
}
?>