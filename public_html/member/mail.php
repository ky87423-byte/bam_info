<?php
include_once "../engine/_core.php";
$nf_member->check_login();
if(!$env['use_message']) die($nf_util->move_url("/", "사용하지 않는 기능입니다."));

$_site_title_ = '마이페이지 > 쪽지관리';
$page_code = 'input';
include '../include/header_meta.php';
include '../include/header.php';

$__code = $_GET['code']=='send' ? 'send' : 'received';
$__where = " and `pdel`=0 and `rdate`='1000-01-01 00:00:00' and `pmno`=".intval($member['no']);
$_received_where = " and `pdel`=0 and `pmno`=".intval($member['no']);
$_send_where = " and `del`=0 and `mno`=".intval($member['no']);

$q_where_var = '_'.$_GET['code'].'_where';

$q = "nf_message as nm where 1 ".$$q_where_var;
$order = " order by nm.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$message_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

$_total = !$_GET['code'] ? $total : $db->query_fetch("select count(*) as c from nf_message where 1 ".$__where);
$_received_total = $_GET['code']=='received' ? $total : $db->query_fetch("select count(*) as c from nf_message where 1 ".$_received_where);
$_send_total = $_GET['code']=='send' ? $total : $db->query_fetch("select count(*) as c from nf_message where 1 ".$_send_where);
?>
<script type="text/javascript">
var reply_message = function(el, no) {
	var form = document.forms['fmessage'];
	var admin = $(el).attr("admin");
	if(!admin) admin = "";
	$(".other--").find("span").css({"display":"none"});
	nf_util.openWin(".message-");
	if(admin) {
		$(".message-").css({"display":"block"});
		$(".put_nick-").html("관리자");
		$(".put_nick-").css({"display":"inline"});
		form.admin.value = 1;
	} else {
		form.admin.value = 0;
		$(".input_nick-").css({"display":"inline"});
		$.post(root+"/include/regist.php", "mode=get_message_info&code=<?php echo $__code;?>&no="+no, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			try{
			if(data.js) eval(data.js);
			}catch(e){
				alert(e.message);
			}
		});
	}
}

var click_message = function(el, no) {
	$.post(root+"/include/regist.php", "mode=click_message&code=<?php echo $__code;?>&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<?php
$m_title = '쪽지관리';
include NFE_PATH.'/include/m_title.inc.php';
?>

	<section class="my_sub wrap1400">
		<!--기업서비스 왼쪽 메뉴-->
		<?php
		$left_my['mail'] = 'on';
		include '../include/my_leftmenu.php';
		?>
		<div class="my_con">
			<section class="mail_page">
				<p class="s_title">쪽지 관리</p>
				<div class="button_area">
					<ul class="fl">
						<li><button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="white">전체선택</button></li>
						<li><button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../include/regist.php" mode="delete_select_message" tag="chk[]" check_code="checkbox" class="white">선택삭제</button></li>
					</ul>
					<ul class="fl">
						<li class="<?php echo !$_GET['code'] ? 'on' : '';?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>"><button type="button" class="white">미확인 쪽지(<?php echo number_format($_total['c']);?>)</button></a></li>
						<li class="<?php echo $_GET['code']=='received' ? 'on' : '';?>"><a href="?code=received"><button type="button" class="white">받은 쪽지(<?php echo number_format($_received_total['c']);?>)</button></a></li>
						<li class="<?php echo $_GET['code']=='send' ? 'on' : '';?>"><a href="?code=send"><button type="button" class="white">보낸 쪽지(<?php echo number_format($_send_total['c']);?>)</button></a></li>
					</ul>
					<ul class="fr">
						<li><button type="button" onClick="reply_message(this, '')" admin="1" class="border">관리자에게 쪽지보내기</button></li>
						<?php 
						if($member['mb_message_view']) {
						?>
						<li><button type="button" onClick="reply_message(this, '')" class="border">회원에게 쪽지보내기</button></li>
						<?php
						}
						?>
					</ul>
				</div>

				<form name="flist">
				<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
				<table class="style3">
					<colgroup>
						<col width="8%">
						<col width="10%">
						<col width="">
						<col width="16%">
						<col width="8%">
					</colgroup>
					<tr>
						<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th><?php echo $_GET['code']=='send' ? '받은사람' : '보낸사람';?></th>
						<th>내용</th>
						<th>읽은시간</th>
						<th>읽음</th>
					</tr>
					<?php
					switch($_arr['total']<=0) {
						case true:
					?>
					<tr>
						<td colspan="5">쪽지 내역이 없습니다.</td>
					</tr>
					<?php
						break;

						default:
							while($row=$db->afetch($message_query)) {
								if($_GET['code']=='received') {
									
								}

								switch($_GET['code']) {
									case "send":
										$nick = $nf_util->get_text($row['pmb_nick']);
										$other_member = $db->query_fetch("select * from nf_member where `no`=".intval($row['pmno']));
										if(!$nick) $nick = $other_member['mb_nick'];
									break;

									default:
										$other_member = $db->query_fetch("select * from nf_member where `no`=".intval($row['mno']));
										$nick = $nf_util->get_text($row['mb_nick']);
										if(!$nick) $nick = $other_member['mb_nick'];
									break;
								}
					?>
					<tr>
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>"> </td>
						<td><?php echo $nick;?></td>
						<td class="tal" style="cursor:pointer;" onClick="click_message(this, '<?php echo $row['no'];?>')"><button type="button" class="n_check">쪽지보기</button> <?php echo $nf_util->get_text($row['content']);?></td>
						<td class="blue date_read"><?php echo $row['rdate']=='1000-01-01 00:00:00' ? '' : $row['rdate'];?></td>
						<td class="is_read"><?php echo $row['rdate']=='1000-01-01 00:00:00' ? 'X' : 'O';?></td>
					</tr>
					<tr style="display:none;">
						<td colspan="5" class="tal mail_con">
							<p><?php echo nl2br($nf_util->get_text($row['content']));?></p>
							<ul class="fr">
								<?php if($env['use_message'] && ($other_member['mb_message_view'] || !$row['mno'])) {?><li><a href="javascript:void(0)" onClick="reply_message(this, '<?php echo $row['no'];?>')" <?php if(!$row['mno']) {?>admin=1<?php }?>>답장하기</a></li><?php }?>
								<li><a href="javascript:void(0)" onClick="$(this).closest('tr').css({'display':'none'});">닫기</a></li>
							</ul>
						</td>
					</tr>
					<?php
							}
						break;
					}?>
				</table>
				</form>
			</section>
			<!--페이징-->
			<div><?php echo $paging['paging'];?></div>
		</div>
	</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>
