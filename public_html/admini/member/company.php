<?php
$_SERVER['__USE_API__'] = array('editor');
$_SERVER['__USE_ETC__'] = array('send_email');
$top_menu_code = '300102';
include '../include/header.php';

$nf_util->sess_page_save("company_list");

$_GET['type'] = 'company';
$where_arr = $nf_search->member();
$_where = $where_arr['where'];
$_where .= " and `is_delete`=0 and mb_left=0 and mb_left_request=0";

$q = "nf_member as nm where 1 ".$_where;
$order = " order by `no` desc";
if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['admin_mem_company_q'] = $q.$order;
$mem_query = $db->_query("select * from ".$_SESSION['admin_mem_company_q']." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
</style>
<script type="text/javascript">
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
</script>
<!-- 기업회원관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-1','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
			<form name="fsearch" action="" method="get">
				<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
				<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
				<input type="hidden" name="sort_lo" value="<?php echo $nf_util->get_html($_GET['sort_lo']);?>" />
				<div class="search">
					 <table>
						<colgroup>
							<col width="5%">
							<col width="45%">
							<col width="10%">
							<col width="40%">
						</colgroup>
						<tbody>
							<tr>
								<th>
									<select name="rdate">
										<option value="wdate" <?php echo $_GET['rdate']=='wdate' ? 'selected' : '';?>>가입일</option>
										<option value="udate" <?php echo $_GET['rdate']=='udate' ? 'selected' : '';?>>수정일</option>
									</select>
								</th>
								<td>
									<?php
									$date_tag = $nf_tag->date_search();
									echo $date_tag['tag'];
									?>
								</td>
								<th>방문수</th>
								<td><label><input type="checkbox" name="login_count_all" value="1">전체</label> <input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][0]);?>" class="input10"> ~ <input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][1]);?>" class="input10"></td>
							</tr>
							<tr>
								<th>불량회원구분</th>
								<td><label><input type="checkbox" name="badness" value="1" <?php echo $_GET['badness'] ? 'checked' : '';?>>불량회원</label></td>
								<th>탈퇴구분</th>
								<td>
									<label><input type="checkbox" name="left_request" value="1" <?php echo $_GET['left_request'] ? 'checked' : '';?>>탈퇴요청</label>
									<label><input type="checkbox" name="left" value="1" <?php echo $_GET['left'] ? 'checked' : '';?>>탈퇴완료</label>
								</td>
							</tr>
						</tbody>
					 </table>
					<div class="bg_w">
						<select name="search_field">
							<option value="">통합검색</option>
							<option value="id" <?php echo $_GET['search_field']=='id' ? 'selected' : '';?>>아이디</option>
							<option value="name" <?php echo $_GET['search_field']=='name' ? 'selected' : '';?>>이름</option>
							<option value="email" <?php echo $_GET['search_field']=='email' ? 'selected' : '';?>>이메일</option>
							<option value="nick" <?php echo $_GET['search_field']=='nick' ? 'selected' : '';?>>닉네임</option>
							<option value="hphone" <?php echo $_GET['search_field']=='hphone' ? 'selected' : '';?>>휴대폰</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
					</div>
				</div>
				<!--//search-->
			</form>
			
			<h6>업체회원관리<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>명의 회원이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
					</select>
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../../include/regist.php" mode="delete_select_member" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onClick="select_email_box(this, 'email-')">메일전송</button>
				<a href="../regist.php?mode=excel_admin_mem_company_q"><button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button></a>
				<a href="./company_insert.php"><button type="button" class="blue"><strong>+</strong> 회원등록</button></a>
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
					<col width="10%">
					<col width="6%">
				</colgroup>
				<?php
				$mb_level_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_level' ? 'desc' : 'asc';
				$mb_id_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_id' ? 'desc' : 'asc';
				$mb_company_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_company_name' ? 'desc' : 'asc';
				$mb_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_name' ? 'desc' : 'asc';
				$mb_point_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_point' ? 'desc' : 'asc';
				$mb_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_wdate' ? 'desc' : 'asc';
				?>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th><a href="#none" onClick="click_sort('mb_level', '<?php echo $mb_level_order;?>')">회원등급<?php echo $mb_level_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_id', '<?php echo $mb_id_order;?>')">회원ID<?php echo $mb_id_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_company_name', '<?php echo $mb_company_name_order;?>')">업체명<?php echo $mb_company_name_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_name', '<?php echo $mb_name_order;?>')">이름/대표자<?php echo $mb_name_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_point', '<?php echo $mb_point_order;?>')">포인트<?php echo $mb_point_order=='desc' ? '▲' : '▼';?></a></th>
						
						<th><a href="">등록건수▼</a></th>
						<th><a href="#none" onClick="click_sort('mb_wdate', '<?php echo $mb_wdate_order;?>')">가입일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
						<th>불량</th>
						<th>편집</th>
						<th>상품등록</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr><td colspan="13" class="no_list"></td></tr>
					<?php
						break;


						default:
							while($mem_row=$db->afetch($mem_query)) {
								$mem_service_row = $db->query_fetch("select * from nf_member_service where `mno`=".intval($mem_row['no']));
								$update_url = './company_insert.php';

								$shop_int = $db->query_fetch("select count(*) as c from nf_shop where `is_delete`=0 and `mno`=".intval($mem_row['no']));
					?>
					<tr class="tac" mb_id="<?php echo $mem_row['mb_id'];?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
						<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $mem_row['no'];?>"><?php echo $nf_util->get_text($mem_row['mb_id']);?></a></td>
						<td><?php echo $nf_util->get_text($mem_row['mb_company_name']);?></td>
						<td><?php echo $nf_util->get_text($mem_row['mb_name']);?></td>
						<td><?php echo intval($mem_row['mb_point']);?></td>

						<td><a href="<?php echo NFE_URL;?>/admini/introduce/index.php?mno=<?php echo $mem_row['no'];?>" class="blue fwb"><?php echo intval($shop_int['c']);?></a></td>
						<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
						<td><a href="#none" onClick="open_box(this, 'badness-')" class="<?php echo $mem_row['mb_badness'] ? 'red' : 'blue';?> fwb"><?php echo $mem_row['mb_badness'] ? '불량' : '정상';?></a></td>
						<td>
							<a href="<?php echo $update_url;?>?mb_id=<?php echo $mem_row['mb_id'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a>
							<button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo intval($mem_row['no']);?>" mode="delete_member" url="<?php echo NFE_URL;?>/include/regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제</button><br>
							<button type="button" onClick="open_box(this, 'sms-')" class="gray common">문자</button>
							<button type="button" onClick="member_mno_click(this)" mno="<?php echo $mem_row['no'];?>" code="email-" class="gray common">메일</button>
							<button type="button" onClick="open_box(this, 'memo-')" class="gray common">메모</button>							
						</td>
						<td>
						<a href="<?php echo NFE_URL;?>/admini/introduce/shop_modify.php?mno=<?php echo $mem_row['no'];?>"><button type="button" class="blue common white">상품등록</button></a>
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
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../../include/regist.php" mode="delete_select_member" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray">메일전송</button>
				<button type="button" class="gray"><img src="../../images/ic/xls.gif" alt=""> 엑셀저장</button>
				<a href="./company_insert.php"><button type="button" class="blue"><strong>+</strong> 회원등록</button></a>
			</div>
			</form>
		</div>
		<!--//conbox-->

		<div><?php echo $paging['paging'];?></div>

		<?php
		include NFE_PATH.'/admini/include/badnees.inc.php'; // : 불량회원
		include NFE_PATH.'/admini/include/member_service.inc.php';
		include NFE_PATH.'/admini/include/sms.inc.php'; // : 문자
		include NFE_PATH.'/admini/include/memo.inc.php'; // : 메모
		?>
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->