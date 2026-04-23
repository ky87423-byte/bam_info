<?php
if(!$skin) $skin = 'text';
$list_skin = $skin;
if($list_skin=='talk') $list_skin = 'text';

$_table = $nf_board->get_table($_GET['bo_table']);
$board_q = $nf_board->board_query($_GET['bo_table']);
$q = $board_q['q'];
$order = $board_q['order'];

$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
if($skin!='admin') $_arr['tema'] = 'B';
$_arr['num'] = $bo_row['bo_page_rows'];
if($_arr['num']<=0) $_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$b_q = "select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num'];
if(strpos($_SERVER['PHP_SELF'], '/board/list.php')!==false || $_GET['page']) $_SESSION['board_query_'.$_GET['bo_table']] = $b_q; // : 현재 읽은 리스트
if(!$_SESSION['board_query_'.$_GET['bo_table']]) $_SESSION['board_query_'.$_GET['bo_table']] = $b_q;
$query = $db->_query($_SESSION['board_query_'.$_GET['bo_table']]);

if(!$board_info || in_Array($_GET['bo_table'], array('service_advert'))) $board_info = array('bo_table'=>$_GET['bo_table']);
ob_start();
while($row=$db->afetch($query)) {
	$b_info = $nf_board->info($row, $board_info);
	include NFE_PATH."/board/skin/".$list_skin.'.inc.php';
}
$board_list = ob_get_clean();

// : 공지글
$notice_list_tr = true;
ob_start();
$notice_query = $db->_query("select * from ".$_table." where `wr_option`='notice' ".$nf_board->list_where." order by `wr_no` desc");
while($row=$db->afetch($notice_query)) {
	$b_info = $nf_board->info($row, $board_info);
	include NFE_PATH."/board/skin/".$list_skin.'.inc.php';
}
$notice_board_list = ob_get_clean();
$notice_list_tr = false;

switch($list_skin) {

	// : 이미지형
	case "image":
?>
<div class="img_list">
	<?php if($_arr['total']<=0) {?>
	<div class="img_list_no">
		검색된 게시물이 없습니다.
	</div>
	<?php }?>
	<ul>
		<?php
		if($_arr['total']<=0) {
		?>
		<?php
		} else {
			echo $board_list;
		}
		?>
	</ul>
</div>
<?php
	break;


	// : 텍스트, 웹진형, 1:1상담형
	default:
		$table_c = $bo_row['bo_type']=='webzine' ? 'webzine' : 'text';
		$table_cc = 'style3 '.$table_c.'_list';
		if($skin=='admin') $table_cc = 'table4';
?>
<table class="<?php echo $table_cc;?>">
	<colgroup>
		<?php
		switch($skin) {
			case "admin":
		?>
		<col width="3%">
		<col width="">
		<col width="5%">
		<col width="10%">
		<col width="8%">
		<col width="6%">
		<col width="7%">
		<?php
			break;

			default:
		?>
		<col style="width:6%">
		<col style="width:%">
		<col style="width:15%">
		<col style="width:12%">
		<col style="width:7%">
		<?php
			break;
		}
		?>
	</colgroup>
	<?php
		switch($skin) {
			case "admin":
		?>
	<tr>
		<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
		<th colspan="2">제목</th>
		<th><a href="">작성자▼</a></th>
		<th><a href="">조회▼</a></th>
		<th><a href="">등록일▼</a></th>
		<th>편집</th>
	</tr>
		<?php
			break;

			default:
		?>
	<tr>
		<th>번호</th>
		<th>제목</th>
		<th class="m_no">작성자</th>
		<th class="m_no">등록일</th>
		<th class="m_no">조회수</th>
	</tr>
		<?php
			break;
		}
		?>
	<?php
	if($_arr['total']<=0) {
		$colspan = $skin=='admin' ? 7 : 5;
	?>
	<tr><td colspan="<?php echo $colspan;?>" class="no_list"><?php if($skin!='admin') {?>검색된 게시물이 없습니다.<?php }?></td></tr>
	<?php
	} else {
		echo $notice_board_list;
		echo $board_list;
	}
	?>
</table>
<?php
	break;
}
?>
<?php
if($skin=='admin') {
?>
<div class="table_top_btn bbn">
	<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
	<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/board/regist.php" mode="board_select_delete" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
	<button type="button" onClick="$('.board-write-').css({'display':'block'})" class="blue"><strong>+</strong> 게시물등록</button>
</div>
<?php
}?>
<div style="margin-top:20px;"><?php echo $paging['paging'];?></div>