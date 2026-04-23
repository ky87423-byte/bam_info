<?php
$_SERVER['__USE_API__'] = array('editor');
$_SERVER['__USE_ETC__'] = array('send_email');
$top_menu_code = '300102';
include '../include/header.php';

$nf_util->sess_page_save("bad_member_list");

$_GET['badness'] = 1; // : 불량회원 고정값
$where_arr = $nf_search->member();

$_where = " and mb_left=0 and mb_left_request=0";
if($_GET['left_chk']=='request') $_where = " and mb_left_request=1";
if($_GET['left_chk']=='left') $_where = " and mb_left=1";
$_where .= $where_arr['where'];
$_where .= " and `is_delete`=0";

$q = "nf_member as nm where 1 ".$_where;
$order = " order by `no` desc";
if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['admin_mem_bad_q'] = $q.$order;
$mem_query = $db->_query("select * from ".$_SESSION['admin_mem_bad_q']." limit ".$paging['start'].", ".$_arr['num']);
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

var ch_page_row = function(el) {
	var form = document.forms['fsearch'];
	form.page_row.value = el.value;
	form.submit();
}

var click_sort = function(sort, sort_lo) {
	var form = document.forms['fsearch'];
	form.sort.value = sort;
	form.sort_lo.value = sort_lo;
	form.submit();
}
</script>
<!-- 불량회원관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 불량회원의 차단유무를 확인가능 하고 삭제, 복귀 등을 관리 하실 수 있습니다.</li>
					<li>- 회원정보를 클릭하시면 해당 회원의 상세페이지로 이동합니다.</li>
				</ul>
			</div>
			<?php
			include NFE_PATH.'/admini/include/search_member.inc.php';
			?>
			
			<h6>회원관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>명의 회원이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" url="" mode="" tag="" check_code="" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray"><strong><i class="axi axi-replay"></i></strong> 선택복귀</button>
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
					<col width="">
					<col width="">
					<col width="">
				</colgroup>
				<?php
				$mb_type_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_type' ? 'desc' : 'asc';
				$mb_id_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_id' ? 'desc' : 'asc';
				$mb_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_name' ? 'desc' : 'asc';
				$mb_nick_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_nick' ? 'desc' : 'asc';
				$mb_level_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_level' ? 'desc' : 'asc';
				$mb_point_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_point' ? 'desc' : 'asc';
				$mb_denied_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_denied' ? 'desc' : 'asc';
				$mb_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_wdate' ? 'desc' : 'asc';
				?>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th><a href="#none" onClick="click_sort('mb_type', '<?php echo $mb_type_order;?>')">회원구분<?php echo $mb_type_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_id', '<?php echo $mb_id_order;?>')">회원ID<?php echo $mb_id_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_name', '<?php echo $mb_name_order;?>')">이름/대표자 (성별/나이)<?php echo $mb_name_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_nick', '<?php echo $mb_nick_order;?>')">닉네임<?php echo $mb_nick_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_level', '<?php echo $mb_level_order;?>')">회원등급<?php echo $mb_level_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="">차단일▼</a></th>
						<th><a href="#none" onClick="click_sort('mb_wdate', '<?php echo $mb_wdate_order;?>')">가입일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th>SMS</th>
						<th>이메일</th>
						<th>메모</th>
						<th>삭제</th>
						<th>복귀</th>
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
								$update_url = $mem_row['mb_type']=='company' ? './company_insert.php' : './individual_insert.php';
					?>
					<tr class="tac" mb_id="<?php echo $mem_row['mb_id'];?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
						<td ><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $mem_row['no'];?>"><?php echo $nf_util->get_text($mem_row['mb_id']);?></a></td>
						<td>
							<?php
							$get_name = $mem_row['mb_name'];
							echo $nf_util->get_text($get_name);?>
						</td>
						<td><?php echo $nf_util->get_text($mem_row['mb_nick']);?></td>
						<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
						<td><?php echo $mem_row['mb_bad_date'];?></td>
						<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
						<td><button type="button" onClick="open_box(this, 'sms-')" class="gray common">문자</button></td>
						<td><button type="button" onClick="member_mno_click(this)" mno="<?php echo $mem_row['no'];?>" code="email-" class="gray common">이메일</td>
						<td><button type="button" onClick="open_box(this, 'memo-')" class="gray common">메모</td>
						<td><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($mem_row['no']);?>" mode="delete_member" url="<?php echo NFE_URL;?>/include/regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제</button></td>
						<td><button type="button" onclick="nf_util.ajax_post(this, '복귀하시겠습니까?')" no="<?php echo $mem_row['no'];?>" mode="member_comeback" url="../regist.php" class="gray common"><i class="axi axi-replay"></i> 복귀</button></td>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="member_select_real_delete" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '선택복귀하시겠습니까?')" url="../regist.php" mode="member_select_comeback" tag="chk[]" check_code="checkbox" class="gray"><strong><i class="axi axi-replay"></i></strong> 선택복귀</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->

		<?php
		include NFE_PATH.'/admini/include/sms.inc.php'; // : 문자
		include NFE_PATH.'/admini/include/memo.inc.php'; // : 메모
		?>

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->