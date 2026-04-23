<?php
$_site_title_ = "커뮤니티 상세";
include_once "../engine/_core.php";
include_once NFE_PATH.'/engine/function/read_insert.function.php';

$bo_table = trim($_GET['bo_table']);
$_table = $nf_board->get_table($bo_table);

$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($bo_table));
$_GET['cno'] = $bo_row['pcode'];
$auth = $nf_board->auth($bo_table, 'read', 'alert');
$board_info = $nf_board->board_info($bo_row);

$b_row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($_GET['no']).$nf_board->bo_where);
$b_info = $nf_board->info($b_row, $board_info);

$db->_query("update ".$_table." set `wr_hit`=`wr_hit`+1 where `wr_no`=".intval($_GET['no']));
$nf_board->point_process('read', $bo_table, $b_row['wr_no'], 'alert');

$write_href = NFE_URL.'/board/write.php?bo_table='.$bo_table;
if($nf_board->not_bo_table[$bo_table]) $write_href = $nf_board->not_bo_table_write_link[$bo_table];

// : 수정,삭제 버튼 보여주기 여부
// : 내것이거나 비회원인경우
$my_btn_view = $member['no'] && $b_row['mno']==$member['no'] || !$b_row['mno'] && !$b_row['wr_id'] ? true : false;

if(!$b_row) {
	$arr = $nf_board->alert_move("list", $bo_table);
	$arr['msg'] = "삭제된 게시물입니다.";
	$arr['move'] = $arr['move'];
	die($nf_util->move_url($arr['move'], $arr['msg']));
}

if($b_info['is_secret'] && !$_SESSION['board_view_'.$bo_table.'_'.$b_row['wr_no']]) {
	$arr['msg'] = "비밀글입니다.";
	$arr['move'] = $_SESSION['board_list_'.$bo_table];
	die($nf_util->move_url($arr['move'], $arr['msg']));
}

$_site_title_ = $b_row['wr_subject'];
$_site_content_ =  $nf_util->get_text($b_row['wr_subject'].' '.$env['meta_description']);

$_arr = array();
$_arr['bo_table'] = $bo_table;
read_insert($member['no'], 'board', $b_row['wr_no'], $_arr);

include '../include/header_meta.php';
include '../include/header.php';

$m_title = $board_info['cate1_txt'];
include NFE_PATH.'/include/m_title.inc.php';

// : 기간별 조회수 저장
$nf_board->save_int("hit", $_GET['bo_table'], $_GET['no']);
?>

<section class="my_sub wrap1400">
		<!--개인서비스 왼쪽 메뉴-->
		<?php
		if(!$nf_board->not_bo_table[$bo_table]) include '../include/board_leftmenu.php';
		?>
		<div class="my_con <?php echo !$nf_board->not_bo_table[$bo_table] ? '' : 'w100';?>">
			<section class="commu view">
				<div class="sub"><p class="s_title">입점문의</p></div>
				<div class="view_wrap">
					<h2><?php echo $nf_util->get_text($b_info['wr_subject_txt']);?></h2>
					<div class="cmt_view_hd">
						<ul class="cmt_view_info">
							<li class="id"><?php echo $nf_util->get_text($b_info['get_name']);?></li>
							<li class="date"><?php echo substr($b_row['wr_datetime'],0,10);?></li>
						</ul>
						<ul class="cmt_view_fnc">
							<li>조회 : <em><?php echo number_format(intval($b_row['wr_hit']));?></em></li>
							<li>댓글 : <em><?php echo number_format(intval($b_row['wr_comment']));?></em></li>
							<?php if($bo_row['bo_use_good']) {?><li>추천 : <em class="view-good-int-"><?php echo number_format(intval($b_row['wr_good']));?></em></li><?php }?>
							<li class="sns_gp">
								<?php
								ob_start();
								include NFE_PATH.'/include/etc/sns.inc.php';
								$sns_link_tag = strtr(ob_get_clean(), array('<li>'=>'<span>', '</li>'=>'</span>'));
								echo $sns_link_tag;
								?>
							</li>
						</ul>
					</div>
					<?php if($bo_table=='service_advert') {?>
					<!--입점문의 상세페이지에서만 노출-->
					<div class="advert_info">
						<dl>
							<dt>업체명 :</dt>
							<dd><?php echo $nf_util->get_text($b_row['wr_1']);?></dd>
						</dl>
						<dl>
							<dt>연락처 :</dt>
							<dd><?php echo $nf_util->get_text($b_row['wr_2']);?></dd>
						</dl>
						<dl>
							<dt>이메일 :</dt>
							<dd><?php echo $nf_util->get_text($b_row['wr_email']);?></dd>
						</dl>
					</div>
					<!--//입점문의 상세페이지에서만 노출--->
					<?php }?>
				</div>
				<?php
				$file_query = $db->_query("select * from nf_board_file where `bo_table`=? and `wr_no`=? and `file_del`=0", array($bo_table, $b_row['wr_no']));
				$file_length = $db->num_rows($file_query);
				if($file_length>0) {
				?>
				<ul class="down_list">
					<?php
					while($frow=$db->afetch($file_query)) {
						if(!is_file(NFE_PATH.'/data/board/'.$bo_table.'/'.$frow['file_name'])) continue;
					?>
					<li style="cursor:pointer;" onClick="nf_board.download(this, '<?php echo $frow['no'];?>')"><img src="../images/ic/file.gif" alt="이미지다운" style=" vertical-align:middle;"><span class="blue" style="margin:0 5px;"><?php echo $frow['file_source'];?></span><span class="orange">[<?php echo $nf_util->get_filesize($frow['file_filesize'],'KB');?>]</span><span class="gray_txt"> DATE : <?php echo $frow['file_datetime'];?></span></li>
					<?php
					}?>
				</ul>
				<?php
				}?>
				<div class="cmt_view_con">
					<p><?php echo stripslashes($b_info['wr_content_txt']);?></p>
				</div>
				<div class="cmt_view_bottom">
					<ul>
						<li><a href="<?php echo $nf_board->move_sess($bo_table);?>">목록보기</a></li>
						<?php if($my_btn_view) {?>
						<li><a href="#none" onClick="nf_board.auth(this, 'delete', '<?php echo $bo_table;?>', '<?php echo $b_row['wr_no'];?>');">삭제</a></li> <?php /*nf_board.click_delete(this, '<?php echo $bo_table;?>', <?php echo intval($b_row['wr_no']);?>)*/?>
						<li><a href="javascript:void(0)" onClick="nf_board.auth(this, 'write', '<?php echo $bo_table;?>', '<?php echo $b_row['wr_no'];?>')">수정</a></li>
						<?php }?>
						<?php if($nf_board->auth($bo_table, 'reply') && in_array($bo_row['bo_type'], array('text','talk')) && !$nf_board->not_bo_table[$bo_table]) {?><li><a href="<?php echo $write_href;?>&code=reply&no=<?php echo $b_row['wr_no'];?>">답변</a></li><?php }?>
						<?php if(!in_Array($_GET['bo_table'], array('service_advert'))) {?><li><a href="#none" onClick="nf_board.click_report(this, '<?php echo $bo_table;?>', <?php echo intval($b_row['wr_no']);?>)">신고</a></li><?php }?>
					</ul>
					<ul class="btn_gp2">
						<?php if($nf_board->auth($bo_table, 'write')) {?><li class="wr_btn"><a href="<?php echo $write_href;?>"><i class="axi axi-pencil-square"></i>글쓰기</a></li><?php }?>
						<?php if($bo_row['bo_use_good']) {?><li class="good_btn"><a href="#none" onClick="nf_board.is_good(this, '<?php echo $bo_table;?>', '<?php echo intval($b_row['wr_no']);?>')"><i class="axi axi-thumbs-o-up"></i>추천</a></li><?php }?>
					</ul>
				</div>

				<?php
				include NFE_PATH.'/board/comment.inc.php';
				?>

				<?php
				// : 게시판 리스트
				$skin = $bo_row['bo_type'];
				include './list.inc.php';
				?>
			</section>
		</div>
	</section>
<!--푸터영역-->
<?php
include '../include/footer.php';
?>
