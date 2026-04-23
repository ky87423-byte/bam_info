<?php
$top_menu_code = '300303';
include '../include/header.php';
?>
<script type="text/javascript">
var click_member_json = {};
var add_member = function(el) {
	var form = document.forms['flist'];
	$(form).find("[name='chk[]']:checked").each(function(i){
		var get_id = $(this).closest("tr").find(".mb_id--").text();
		
		if(!click_member_json[$(this).val()])
			click_member_json[$(this).val()] = get_id;
	});

	set_sms_member();
}

var set_sms_member = function() {
	var json_txt = JSON.stringify(click_member_json);
	$("[name='sms_member_json']").val(json_txt);
	var name_arr = [];
	var cnt = 0;
	for(x in click_member_json) {
		name_arr[cnt] = click_member_json[x];
		cnt++;
	}
	$("[name='sms_member']").val(name_arr.join(","));
}
</script>
<!-- 회원sms발송 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-6','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<div class="send_note">
				<?php include '../include/note.php'; ?> <!--쪽지보내기-->

				<!--아래는 관리자>회원문자발송 페이지에서 가져온것입니다.-->
				<div class="member_box">
					<h6 class="MAT0">회원목록</h6>
					<?php
					if(strpos($_SERVER['PHP_SELF'], 'admini/member/memo_send.php')!==false) {
						$where_arr = $nf_search->member();
						$_where = $where_arr['where'];

						$q = "nf_member as nm where mb_left=0 and mb_left_request=0 and `is_delete`=0 and mb_badness=0 and mb_message_view=1 ".$_where;
						$order = " order by `no` desc";
						if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
						$total = $db->query_fetch("select count(*) as c from ".$q);

						$_arr = array();
						$_arr['num'] = 15;
						if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
						$_arr['total'] = $total['c'];
						$paging = $nf_util->_paging_($_arr);

						$mem_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

						if($_GET['mode']==='search') {
						?>
						<script type="text/javascript">location.href="#search_sms-";</script>
						<?php
						}?>
					<form name="fsearch" method="get" onSubmit="return form_search(this)">
						<input type="hidden" name="mode" value="member_ajax_search" />
						<div id="search_sms-" class="search">
							<div class="bg_w">
								<select name="mb_type" id="" class="select10">
									<option value="">회원종류선택</option>
									<?php
									if(is_Array($nf_member->mb_type)) { foreach($nf_member->mb_type as $k=>$v) {
										$selected = $k===$_GET['mb_type'] ? 'selected' : '';
									?>
									<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
									<?php } }?>
								</select>
								<select name="search_field" class="select10">
									<option value="">선택</option>
									<option value="name" <?php echo $_GET['search_field']=='name' ? 'selected' : '';?>>이름</option>
									<option value="nick" <?php echo $_GET['search_field']=='nick' ? 'selected' : '';?>>닉네임</option>
									<option value="id" <?php echo $_GET['search_field']=='id' ? 'selected' : '';?>>아이디</option>
									<option value="hphone" <?php echo $_GET['search_field']=='hphone' ? 'selected' : '';?>>휴대폰</option>
								</select>
								<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
								<input type="submit" class="blue" value="검색" onClick="form_search();return false;"></input>
								<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
							</div>
						</div>
					</form>
					
					<form name="flist">
					<div class="table_top_btn">
						<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
						<button type="button" class="blue" onClick="add_member()"><strong>+</strong> 발송회원추가</button></a>
					</div>
					<table class="table4">
						<colgroup>
							<col width="3%">
							<col width="">
							<col width="">
							<col width="">
							<col width="">
							<col width="">
							<col width="8%">
						</colgroup>
						<thead>
							<tr>
								<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
								<th>회원구분</th>
								<th>이름</th>
								<th>닉네임</th>
								<th>아이디</th>
								<th>휴대폰</th>
								<th>수신</th>
							</tr>
						</thead>
						<tbody id="member_tr-">
						<?php
						switch($total['c']<=0) {
							case true:
							break;

							default:
								while($row=$db->afetch($mem_query)) {
						?>
						<tr align="center">
							<td><input type="checkbox" class="chk_" name="chk[]" value="<?php echo $row['no'];?>"></td>
							<td><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
							<td><?php echo $row['mb_name'];?></td>
							<td><?php echo $row['mb_nick'];?></td>
							<td class="mb_id--"><?php echo $row['mb_id'];?></td>
							<td><?php echo $row['mb_hphone'];?></td>
							<td><?php echo $row['mb_sms'] ? '수신' : '수신안함';?></td>
						</tr>
						<?php
								}
							break;
						}
						?>
						</tbody>
					</table>
					<div id="member_paging-"><?php echo $paging['paging'];?></div>
					<div class="table_top_btn bbn">
						<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
						<button type="button" class="blue" onClick="add_member()"><strong>+</strong> 발송회원추가</button></a>
					</div>
					</form>
					<?php
					}
					?>
				</div>
			</div>


		</div>
		<!--//conbox-->

	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->