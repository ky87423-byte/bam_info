<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '300304';
include '../include/header.php';

$nf_util->sess_page_save("memo_list");

$_where = "";

$q = "nf_message as nm where 1 ".$_where;
$order = " order by nm.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$message_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<script type="text/javascript">
var click_use = function(el) {
	$.post(root+"/admini/regist.php", "mode=use_message&val="+el.value, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}
</script>
<!-- 회원간쪽지발송내역 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 회원간 쪽지 내역을 확인하실수 있습니다.</li>
					<li>- '삭제시간' 은 수신한 쪽지를 회원 스스로 삭제한 시간입니다.</li>
					<li>- 관리자는 확인하여 우측의 '삭제' 버튼으로 데이터를 완전히 삭제할수 있습니다.</li>
				</ul>
			</div>

			<h6>회원간 쪽지 발송</h6>
			<table class="">	
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>쪽지 기능 사용유무</th>
					<td>
						<label><input type="radio" name="message_use" value="1" onClick="click_use(this)" checked>사용</label>
						<label><input type="radio" name="message_use" value="0" onClick="click_use(this)" <?php echo !$env['use_message'] ? 'checked' : '';?>>미사용</label>
					</td>
				</tr>
			</table>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
			
			<h6>쪽지관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>건의 쪽지가 검색되었습니다.</span></h6>

			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_message" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="13%">					
					<col width="%">
					<col width="10%">
					<col width="10%">
					<col width="10%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>발신 => 수신</th>
						<th>내용</th>
						<th>보낸시간	</th>
						<th>읽은시간</th>
						<th>삭제시간</th>
						<th>최종삭제</th>
					</tr>
				</thead>
				<tbody class="tac">
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr>
						<td colspan="7">쪽지 내역이 없습니다.</td>
					</tr>
					<?php
						break;

						default:
							while($row=$db->afetch($message_query)) {
								$_table = 'nf_'.$row['code'];
								if($db->is_table($_table) && $row['code']!='reply') {
									$info_row = $db->query_fetch("select * from nf_".$row['code']." where `no`=".intval($row['pno']));
								}
					?>
					<tr>
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"></td>
						<td>
							<a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $row['mno'];?>"><?php echo $row['mb_id'];?></a>
							=>
							<?php if($row['pmb_id']=='_admin_') {?>
							관리자
							<?php } else {?>
							<a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $row['pmno'];?>"><?php echo $row['pmb_id'];?></a>
							<?php }?>
						</td>
	
						<td><?php echo $nf_util->get_text($row['content']);?></td>
						<td><?php echo $row['sdate'];?></td>
						<td><?php echo substr($row['rdate'], 0,4)=='1000' ? '미열람' : $row['rdate'];?></td>
						<td>
							<div style="color:blue;"><?php echo $row['ddate']=='1000-01-01 00:00:00' ? '&nbsp;' : $row['ddate'];?></div>
							<div style="color:red;"><?php echo $row['pddate']=='1000-01-01 00:00:00' ? '&nbsp;' : $row['pddate'];?></div>
						</td>
						<td><button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_message" url="../regist.php"><i class="axi axi-minus2"></i> 삭제</button></td>
					</tr>
					<?php
							}
						break;
					}?>
				</tbody>
			</table>

			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="<?php echo NFE_URL;?>/include/regist.php" mode="delete_select_message" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
			</div>

			<div><?php echo $paging['paging'];?></div>
			</form>
			
		</div>
		
		
		<!--//payconfig conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->