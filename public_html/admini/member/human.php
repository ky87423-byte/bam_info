<?php
$_SERVER['__USE_API__'] = array('editor');
$_SERVER['__USE_ETC__'] = array('send_email');
$top_menu_code = '300106';
include '../include/header.php';

$nf_util->sess_page_save("member_list");

$where_arr = $nf_search->member();

$_where = " and mb_left=0 and mb_left_request=0";
if($_GET['left_chk']=='request') $_where = " and mb_left_request=1";
if($_GET['left_chk']=='left') $_where = " and mb_left=1";
$_where .= $where_arr['where'];
$_where .= " and `is_delete`=0 and is_dormancy=1";

$q = "nf_member as nm where mb_left=0 and mb_left_request=0 ".$_where;
$order = " order by `no` desc";
if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['admin_mem_q'] = $q.$order;
$mem_query = $db->_query("select * from ".$_SESSION['admin_mem_q']." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
</style>
<script type="text/javascript">
var open_box_func = function(el, no) {
	var form = document.forms['fcustomized'];
	$.post("../regist.php", "mode=get_customized&no="+parseInt(no), function(data) {
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	if(display=='none') {
		obj.css({"display":display});
		return;
	}

	var mb_id = $(el).closest("tr").attr("mb_id");
	$.post("../regist.php", "mode=open_box&type=member&mb_id="+mb_id+"&code="+code, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var admin_login = function(no) {
	$.post(root+"/admini/regist.php", "mode=user_login_process&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var ch_dormancy = function(el, no) {
	var txt = $(el).closest("tr").find(".mb_id--").text();
	if(confirm(txt+" 회원의 휴면회원을 해제하시겠습니까?")) {
		$.post("../regist.php", "mode=ch_dormancy&no="+no, function(data){
			alert(data);
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
		});
	}
}

$(function(){
	$(".conbox.popup_box-").draggable();
});
</script>
<!-- 전체회원관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<li>- 휴면회원 설정에서 설정된 기간만큼 사이트내에 로그인 하지 않은 회원 리스트 입니다. </li>
				</ul>
			</div>
			<?php
			include NFE_PATH.'/admini/include/search_member.inc.php';
			?>
			<!--//search-->
			
			<h6>회원관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>명의 회원이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="nf_util.ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '선택한 회원을 휴면회원 해제 하시겠습니까?')" url="../regist.php" mode="ch_select_dormancy" tag="chk[]" check_code="checkbox">선택된 회원 휴면회원해제</button>
				<button type="button" onClick="select_email_box(this, 'email-dormancy_ing')" class="gray">선택된 회원 휴면예정메일발송</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../../include/regist.php" mode="delete_select_member" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
				<!-- <button type="button" onClick="select_email_box(this, 'email-')" class="gray">메일전송</button> -->
				<a href="../regist.php?mode=excel_admin_mem_q"><button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button></a>
			</div>

			<form name="flist" method="">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="">
					<col width="8%">
				</colgroup>
				<?php
				$mb_type_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_type' ? 'desc' : 'asc';
				$mb_id_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_id' ? 'desc' : 'asc';
				$mb_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_name' ? 'desc' : 'asc';
				$mb_level_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_level' ? 'desc' : 'asc';
				$mb_point_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_point' ? 'desc' : 'asc';
				$mb_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_wdate' ? 'desc' : 'asc';
				?>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_type', '<?php echo $mb_type_order;?>')">회원구분<?php echo $mb_type_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_level', '<?php echo $mb_level_order;?>')">회원등급<?php echo $mb_level_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_id', '<?php echo $mb_id_order;?>')">회원ID<?php echo $mb_id_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_name', '<?php echo $mb_name_order;?>')">이름/대표자 (성별/나이)<?php echo $mb_name_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none">업체명</a></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_point', '<?php echo $mb_point_order;?>')">포인트<?php echo $mb_point_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="nf_util.click_sort('mb_wdate', '<?php echo $mb_wdate_order;?>')">가입일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" >최종로그인<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" >휴면일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th>로그인횟수</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="15" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($mem_row=$db->afetch($mem_query)) {
								$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));
								$update_url = $mem_row['mb_type']=='company' ? './company_insert.php' : './individual_insert.php';
					?>
					<tr class="tac" mb_id="<?php echo $mem_row['mb_id'];?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
						<td ><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
						<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
						<td class="mb_id--"><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $mem_row['no'];?>"><?php echo $nf_util->get_text($mem_row['mb_id']);?></a></td>
						<td>
							<?php
							$get_name = $mem_row['mb_name'];
							echo $nf_util->get_text($get_name);
							?>
						</td>
						<td><?php if($mem_row['mb_type']=='company') { echo $mem_row['mb_company_name']; } else {?><span class="gray">개인</span><?php }?></td>
						<td><?php echo intval($mem_row['mb_point']);?></td>	
						<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
						<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
						<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
						<td>1회</td>
						<td>
							<button type="button" class="gray common" onClick="ch_dormancy(this, '<?php echo $mem_row['no'];?>')">휴면회원해제</button>
							<button type="button" onclick="nf_util.ajax_post(this, '탈퇴회원으로 전환하시겠습니까?')" no="<?php echo intval($mem_row['no']);?>" mode="delete_member" url="<?php echo NFE_URL;?>/include/regist.php" class="gray common" >탈퇴회원전환</button>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '선택한 회원을 휴면회원 해제 하시겠습니까?')" url="../regist.php" mode="ch_select_dormancy" tag="chk[]" check_code="checkbox" class="gray">선택된 회원 휴면회원해제</button>
				<button type="button" onClick="select_email_box(this, 'email-dormancy_ing')" class="gray">선택된 회원 휴면예정메일발송</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../../include/regist.php" mode="delete_select_member" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>				
				<!-- <button type="button" onClick="select_email_box(this, 'email-')" class="gray">메일전송</button> -->
				<a href="../regist.php?mode=excel_admin_mem_q"><button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button></a>
				<?php if($_GET['mb_type']) {?>
				<a href="./individual_insert.php"><button type="button" class="blue"><strong>+</strong> 회원등록</button></a>
				<?php }?>
			</div>
		</div>
		<!--//payconfig conbox-->

		<div><?php echo $paging['paging'];?></div>
		</form>

		<?php
		//include NFE_PATH.'/admini/include/badnees.inc.php'; // : 불량회원
		//include NFE_PATH.'/admini/include/sms.inc.php'; // : 문자
		//include NFE_PATH.'/admini/include/memo.inc.php'; // : 메모
		include NFE_PATH.'/admini/include/member.inc.php'; // : 회원상세정보
		include NFE_PATH.'/admini/include/member_service.inc.php';
		?>

	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->