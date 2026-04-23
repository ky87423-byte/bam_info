<?php
$top_menu_code = '600301';
include '../include/header.php';

$q = "nf_poll where 1 ";
$order = " order by `no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 10;
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$poll_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var open_layer_pop = function(no) {
	$.post("../regist.php", "mode=get_poll&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var close_layer = function(el) {
	$(el).closest(".layer_pop").css({"display":"none"});
}
</script>

<!-- 설문조사 관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 출력할 설문조사의 '사용' 체크박스를 선택해야 설문조사가 출력됩니다.</li>
					<li>- 설문조사 주제를 클릭하시면 미리보기를 할 수 있습니다.</li>
					<li>- 설문조사가 사이트 메인에 표시될때 등록된 개수와 상관없이 '메인' 라디오박스가 선택된 설문조사 하나만 보여집니다.</li>
				</ul>
			</div>
			
			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<h6>설문조사관리</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_poll" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/board/poll_insert.php"><button type="button" class="blue"><strong>+</strong> 설문조사등록</button></a>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="5%">
					<col width="9%">
					<col width="7%">
					<col width="7%">
					<col width="5%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>설문주제</th>
						<th>사용</th>
						<th>투표자</th>
						<th>시작일</th>
						<th>종료일</th>
						<th>투표수</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($total['c']<=0) {
						case true:
					?>
					<td colspan="8" class="no_list"></td>
					<?php
						break;


						default:
							while($row=$db->afetch($poll_query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
						<td class="tal"><a class="blue" onClick="open_layer_pop('<?php echo $row['no'];?>')"><?php echo $nf_util->get_text($row['poll_subject']);?></a></td>
						<td><input type="checkbox" onclick="nf_util.ajax_post(this)" no="<?php echo $row['no'];?>" mode="poll_view" url="../regist.php" <?php echo $row['use'] ? 'checked' : '';?>></td>
						<td><?php echo $row['poll_member'] ? '회원용' :'회원+비회원용';?></td>
						<td><?php echo $row['poll_wdate'];?></td>
						<td><?php echo $row['poll_edate'];?></td>
						<td><?php echo number_format(intval($row['cnt']));?></td>
						<td>
							<a href="./poll_insert.php?no=<?php echo $row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_poll" url="../regist.php"><i class="axi axi-minus2"></i> 삭제하기</button>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_poll" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/board/poll_insert.php"><button type="button" class="blue"><strong>+</strong> 설문조사등록</button></a>
			</div>
			</form>
		</div>
		<!--//conbox-->


		<!-- 설문조사결과 팝업-->
		<div class="paste-layer-pop">
			
		</div>
		<!--//설문조사결과 팝업-->

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->