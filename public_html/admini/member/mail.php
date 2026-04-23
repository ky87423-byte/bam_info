<?php
$_SERVER['__USE_API__'] = array('editor');
$_SERVER['__USE_ETC__'] = array('send_email');
$top_menu_code = '300301';
include '../../engine/_core.php';
$not_receive_mail = true;
$arr = $db->query_fetch("select * from nf_mail_skin where `skin_name`='member_mailing'");
$mail_content = $arr['content'];

include '../include/header.php';

$where_arr = $nf_search->member();
$_where = $where_arr['where'];

$_where = " and mb_left=0 and mb_left_request=0";
if($_GET['left_chk']=='request') $_where = " and mb_left_request=1";
if($_GET['left_chk']=='left') $_where = " and mb_left=1";
$_where .= $where_arr['where'];
$_where .= " and `is_delete`=0";

$q = "nf_member as nm where `mb_email_view`=1 and mb_left=0 and mb_left_request=0 and `is_delete`=0 and mb_badness=0 ".$_where;
$order = " order by `no` desc";
if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
if($_GET['sort'] && $_GET['sort_lo']=='mb_email_receive') $order = " order by ";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$_SESSION['send_email_member'] = "select * from ".$q.$order;

$mem_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
</style>
<script type="text/javascript">
var ch_code = function(el) {
	var form = document.forms['femail'];
	form.code.value = el.value;
}

var open_box = function(el, code, display) {
	if(!display) display = 'block';
	$(".conbox.popup_box-").css({"display":"none"});
	var obj = $(".conbox."+code);
	if(display=='none') {
		obj.css({"display":display});
		return;
	}
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

var send_email = function() {
	var form = document.forms['flist'];
	var femail = document.forms['femail'];

	if($(form).find("[name='code']").val()=="check") {
		var len = $(form).find("[name='chk[]']:checked").length;
		if(len<=0) {
			alert("메일보낼 회원을 선택해주시기 바랍니다.");
			return;
		}

		$(femail).find("[name='receive_mail']").attr({"needed":"needed"});

		var email_txt_arr = new Array();
		var email_no_arr = new Array();
		$(form).find("[name='chk[]']:checked").each(function(i){
			var email_val = $(this).closest("tr").find(".email-td").text();
			email_txt_arr[i] = email_val;
			email_no_arr[i] = $(this).val();
		});
		femail.receive_mail.value = email_txt_arr.join("\n");
		femail.receive_no.value = email_no_arr.join("\n");
	} else {
		$(femail).find("[name='receive_mail']").removeAttr("needed");
		femail.receive_mail.value = "";
		femail.receive_no.value = "";
	}

	femail.mode.value = "mail_list_send";

	$(".conbox.popup_box-.email-").css({"display":"block"});
}
/*
form.mode.value = "send_email_member";
form.action = "../regist.php";
form.submit();
*/
</script>
<!-- 회원mail발송 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-4','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
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
								<td>
									<label><input type="checkbox">전체</label>
									<input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][0]);?>" class="input10"> ~ <input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][1]);?>" class="input10">
								</td>
							</tr>
							<tr>
								<th>회원구분</th>
								<td>
									<label><input type="radio" name="mb_type" value="" checked>전체</label>
									<?php
									if(is_array($nf_member->mb_type)) { foreach($nf_member->mb_type as $k=>$v) {
										$checked = $_GET['mb_type']==$k ? 'checked' : '';
									?>
									<label><input type="radio" name="mb_type" <?php echo $checked;?> value="<?php echo $k;?>"><?php echo $v;?></label>
									<?php
									} }?>
								</td>
								<th>메일수신여부</th>
								<td>
									<label><input type="radio" name="mb_email_receive" value="" checked>전체</label>
									<label><input type="radio" name="mb_email_receive" value="1" <?php echo $_GET['mb_email_receive']==='1' ? 'checked' : '';?>>허용</label>
									<label><input type="radio" name="mb_email_receive" value="0" <?php echo $_GET['mb_email_receive']==='0' ? 'checked' : '';?>>거부</label>
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


			<h6>회원주소록<span>총 <b><?php echo number_format(intval($_arr['total']));?></b>명의 회원이 검색되었습니다.</span>
				<p class="h6_right">
					<select name="page_row" onChange="ch_page_row(this)">
						<option value="15" <?php echo $_GET['page_row']=='15' ? 'selected' : '';?>>15개출력</option>
						<option value="30" <?php echo $_GET['page_row']=='30' ? 'selected' : '';?>>30개출력</option>
						<option value="50" <?php echo $_GET['page_row']=='50' ? 'selected' : '';?>>50개출력</option>
						<option value="100" <?php echo $_GET['page_row']=='100' ? 'selected' : '';?>>100개출력</option>
						<option value="200" <?php echo $_GET['page_row']=='200' ? 'selected' : '';?>>200개출력</option>
						<option value="300" <?php echo $_GET['page_row']=='300' ? 'selected' : '';?>>300개출력</option>
						<option value="500" <?php echo $_GET['page_row']=='500' ? 'selected' : '';?>>500개출력</option>
						<option value="1000" <?php echo $_GET['page_row']=='1000' ? 'selected' : '';?>>1000개출력</option>
					</select>
				</p>
			</h6>

			<form name="flist" method="post">
			<input type="hidden" name="mode" value="" />
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<select name="code" onChange="ch_code(this)">
					<option value="all">현재 검색리스트에 있는 모든 회원에게</option>
					<option value="check">선택한 회원에게만</option>
				</select>
				<button type="button" class="blue" onClick="send_email()">메일보내기</button></a>
			</div>
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
				</colgroup>
				<?php
				$mb_type_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_type' ? 'desc' : 'asc';
				$mb_id_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_id' ? 'desc' : 'asc';
				$mb_email_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_email' ? 'desc' : 'asc';
				$mb_email_receive = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_email_receive' ? 'desc' : 'asc';
				$mb_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_name' ? 'desc' : 'asc';
				$mb_nick_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_nick' ? 'desc' : 'asc';
				$mb_login_count_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_login_count' ? 'desc' : 'asc';
				$mb_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_wdate' ? 'desc' : 'asc';
				?>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onClick=""></th>
						<th><a href="#none" onClick="click_sort('mb_type', '<?php echo $mb_type_order;?>')">회원구분<?php echo $mb_type_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_id', '<?php echo $mb_id_order;?>')">회원ID<?php echo $mb_id_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_email', '<?php echo $mb_email_order;?>')">이메일<?php echo $mb_email_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_email_receive', '<?php echo $mb_email_receive;?>')">메인수신여부<?php echo $mb_email_receive=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_name', '<?php echo $mb_name_order;?>')">이름<?php echo $mb_name_order=='desc' ? '▲' : '▼';?></a><a href="#none" onClick="click_sort('mb_nick', '<?php echo $mb_nick_order;?>')">(닉네임<?php echo $mb_nick_order=='desc' ? '▲' : '▼';?>)</a></th>
						<th><a href="#none" onClick="click_sort('mb_login_count', '<?php echo $mb_login_count_order;?>')">방문수<?php echo $mb_login_count_order=='desc' ? '▲' : '▼';?></a></th>
						<th><a href="#none" onClick="click_sort('mb_wdate', '<?php echo $mb_wdate_order;?>')">가입일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
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

								$receive_arr = explode(",", $mem_row['mb_receive']);
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
						<td ><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
						<td><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $mem_row['no'];?>"><?php echo $nf_util->get_text($mem_row['mb_id']);?></a></td>
						<td class="email-td"><?php echo $mem_row['mb_email'];?></td>
						<td><?php echo in_array('email', $receive_arr) ? 'Yes' : 'No';?></td>
						<td><?php echo $mem_row['mb_name'];?> (<?php echo $mem_row['mb_nick'];?>)</td>
						<td><?php echo $mem_row['mb_login_count'];?></td>
						<td><?php echo $mem_row['mb_wdate'];?></td>
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
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->

		<div><?php echo $paging['paging'];?></div>

		<?php
		/*
		<div class="paging">
			<button class="MAR5"><img src="../../images/admini/first.gif" alt=""></button>
			<button class="MAR10"><img src="../../images/admini/pre.gif" alt=""></button>
			<span><a href="" class="blue">1</a></span>
			<span><a href="">2</a></span>
			<span><a href="">3</a></span>
			<button class="MAL10"><img src="../../images/admini/next.gif" alt=""></button>
			<button class="MAL5"><img src="../../images/admini/last.gif" alt=""></button>
		</div>
		*/?>
	</section>
</div>
<!--//wrap-->

<?php
//include '../include/footer.php';
?> <!--관리자 footer-->