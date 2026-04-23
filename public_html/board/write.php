<?php
$_SERVER['__USE_API__'] = array('jqueryui', 'editor');
$_site_title_ = "커뮤니티 글쓰기";
include "../engine/_core.php";

if(!$_GET['code']) $_GET['code'] = 'insert';
$bo_table = $_GET['bo_table'];
$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
$_GET['cno'] = $bo_row['pcode'];
$nf_board->auth($bo_table, 'write', 'alert'); // : 글쓰기권한
if($_GET['code']=='reply') $nf_board->auth($bo_table, 'reply', 'alert'); // : 답변권한 [ 글쓰기권한이 없으면 자동으로 답변권한도 없음 ]
$board_info = $nf_board->board_info($bo_row);

$_table = $nf_board->get_table($bo_table);

$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_GET['no']).$nf_board->list_where);
$b_info = $nf_board->info($b_row, $board_info);

// : 글쓰기 포인트체크 [ 음수인경우 체크 ]
if($nf_board->point_process_check('write', $bo_table)) {
	$arr['msg'] = "포인트가 없어서 ".$bo_row['bo_subject'].' 게시판 '.$nf_board->auth_arr['write'].'를 할 수 없습니다.\n필요 포인트는 '.abs($bo_row['bo_write_point']).'p 입니다.';
	$arr['move'] = NFE_URL.'/board/list.php?bo_table='.$bo_table;
	die($nf_util->move_url($arr['move'], $arr['msg']));
}

if($_GET['no'] && (!$b_row || $b_row['wr_del'])) {
	$arr['msg'] = "삭제된 게시물입니다.";
	$arr['move'] = $_SESSION['board_list_'.$bo_table];
	die($nf_util->move_url($arr['move'], $arr['move']));
}

if($_GET['code']!='reply') {
	// : 비회원인경우 - 비밀번호 입력후 세션통과시에만 확인 가능
	if($b_row && !$b_row['mno'] && !$_SESSION['board_view_'.$bo_table.'_'.$b_row['wr_no']]) {
		die($nf_util->move_url($nf_util->page_back(), "권한이 없습니다."));

	// : 회원권한 - 수정인경우 본인만 확인가능
	} else if($b_row && $b_row['mno']!=$member['no']) {
		if($b_row['mno']) die($nf_util->move_url($nf_util->page_back(), "권한이 없습니다."));
	}
}

if(!in_array($bo_row['bo_type'], array('text','talk')) && $_GET['code']=='reply') {
	$arr['msg'] = "답변을 달 수 없습니다.";
	$arr['move'] = $_SESSION['board_list_'.$bo_table];
	die($nf_util->move_url($arr['move'], $arr['msg']));
}

include '../include/header_meta.php';
include '../include/header.php';

$m_title = $board_info['cate1_txt'];
include NFE_PATH.'/include/m_title.inc.php';
?>

<section class="my_sub wrap1400">
		<!--개인서비스 왼쪽 메뉴-->
		<?php
		if(!$nf_board->not_bo_table[$bo_table]) include '../include/board_leftmenu.php';
		?>
		<div class="my_con <?php echo !$nf_board->not_bo_table[$bo_table] ? '' : 'w100';?>">
			<section class="commu write_list">
				<div class="side_con">
					<ul class="fl">
						<li><h6><?php echo $nf_util->get_text($nf_board->board_table_arr[$bo_table]['bo_subject']);?></h6></li>
					</ul>
				</div>
				<!--//side_con-->
				<form name="fwrite" action="../board/regist.php" method="post" enctype="multipart/form-data" onSubmit="return nf_util.ajax_submit(this)">
				<input type="hidden" name="mode" value="board_write" />
				<input type="hidden" name="code" value="<?php echo $_GET['code']=='reply' ? $_GET['code'] : 'insert';?>" />
				<input type="hidden" name="bo_table" value="<?php echo $nf_util->get_html($bo_table);?>" />
				<input type="hidden" name="no" value="<?php echo intval($b_row['wr_no']);?>" />
				<?php
				include NFE_PATH.'/include/etc/google_recaptcha3.inc.php';
				?>
				<div class="box_wrap">
					<ul class="help_text">
						<li>개인정보(연락처나 이메일 주소, 상호명을 포함한 글)은 사전 동의 없이 삭제됩니다.</li>
						<li>특정인 또는 특정 업체 비방성 글, 성인 광고, 사이트 홍보글, 지적 재산권 침해 게시물을 등록하는 경우 게시글 삭제 후 이용제한 처리됩니다.</li>
						<li>커뮤니티 게시물에 대한 모든 법적인 책임은 작성자에게 있습니다.</li>
					</ul>

					<?php
					if(!$member['no']) $is_write_name = true;
					include_once NFE_PATH.'/board/write.inc.php';
					?>
				</div>
				
				<div class="next_btn">
					<button type="button" class="base graybtn" onClick="history.back()">돌아가기</button>	
					<button type="submit" class="base"><?php echo $b_row && $_GET['code']!='reply' ? '수정' : '등록';?>하기</button>
				</div>
				<form>

			</section>
		</div>
	</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>
