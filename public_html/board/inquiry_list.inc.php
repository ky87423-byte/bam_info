<?php
ob_start();
while($top_row=$db->afetch($query)) {
	$bo_row = $db->query_fetch("select * from nf_board where `bo_table`=?", array($top_row['bo_table']));
	$board_info = $nf_board->board_info($bo_row);
	$_table = $nf_board->get_table($top_row['bo_table']);
	$row = $db->query_fetch("select * from ".$_table." where `wr_no`=".intval($top_row['pno']));
	$row['wr_hit'] = $top_row['sum_cnt'];
	$b_info = $nf_board->info($row, $board_info);
	include NFE_PATH."/board/skin/".$list_skin.'.inc.php';
}
$board_list = ob_get_clean();

$table_c = 'text';
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
		<th>순위</th>
		<th>제목</th>
		<th class="m_no">작성자</th>
		<th class="m_no">등록일</th>
		<th class="m_no">조회수</th>
	</tr>
	<!--
		가능하다면 1~3등 순위 표 영역에는 아래 이미지를 사용해주세요
		1등 : <img style="width:20px" src="../images/1st.png" alt="1등 조회수">
		2등 : <img style="width:20px"  src="../images/2st.png" alt="2등 조회수">
		3등 : <img style="width:20px"  src="../images/3st.png" alt="3등 조회수">
	-->
	
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
		echo $board_list;
	}
	?>
</table>

<div style="margin-top:20px;"><?php echo $paging['paging'];?></div>