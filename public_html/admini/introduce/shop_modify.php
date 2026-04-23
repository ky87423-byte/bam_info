<?php
$top_menu_code = '100109';
include '../include/header.php';

$shop_no = $_GET['no'] ? $_GET['no'] : $_GET['info_no'];

$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($shop_no));
$shop_info = $nf_shop->shop_info($shop_row);

if($_GET['mno']) {
	$member_row = $db->query_fetch("select * from nf_member where `no`=".intval($_GET['mno']));
	$mno = $member_row['no'];
}

if($shop_row) $mno = $shop_row['mno'];
$shop_query = $db->query_fetch_rows("select * from nf_shop where `mno`=".intval($mno)." order by `no` desc");
?>
<style type="text/css">
.input_type- { display:none; }
</style>
<script type="text/javascript">
var form = "";
var img_obj = "";

var find_member = function(kind) {
	var val = $("#find_member-").val();
	if(!val) {
		alert("이름,아이디,이메일중 하나를 입력해주세요");
		return;
	}
	$.post(root+"/admini/regist.php", "mode=find_member&kind="+kind+"&val="+encodeURIComponent(val), function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var put_member = function(no) {
	$.post(root+"/admini/regist.php", "mode=put_member&code=shop&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var enterkey = function() {
	if(window.event.keyCode == 13) {
		find_member('company');
	}
	return false;
}

$(window).load(function(){
	form = document.forms['fwrite'];
	$(form).find("[name='input_type']").click(function(){
		$(".input_type-").css({"display":"none"});
		$(".input_type-."+$(this).val()+"-").css({"display":"table-row-group"});
		form.mno.value = "";
		form.mb_id.value = "";
		if($(this).val()=="self") {
			form.mb_id.value = "<?php echo '_admin_'.time();?>";
		}
	});

	if($("#find_member-").val()) find_member('company');
});

var submit_func = function(el) {
	<?php if($env['map_engine']=='google') {?>
	map_insert();
	<?php }?>
	if(validate(el)) {
		return true;
	}
	return false;
}
</script>
<form name="fupload" action="<?php ECHO NFE_URL;?>/include/regist.php" style="display:none;" method="post">
<input type="hidden" name="mode" value="shop_upload" />
<input type="hidden" name="code" value="" />
<input type="file" name="upload" onChange="return nf_shop.upload_process(this)" />
</form>


<!--채용공고 등록-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="employ_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return submit_func(this)">
		<input type="hidden" name="mode" value="shop_write" />
		<input type="hidden" name="no" value="<?php echo $shop_row['no'];?>" />
		<input type="hidden" name="mno" value="" />
		<input type="hidden" name="info_no" value="<?php echo $nf_util->get_html($_GET['info_no']);?>" />
		<div class="conbox">
			<div class="guide h_no">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>
						해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide1-3','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button>
					</li>
				</ul>
			</div>	
			<?php
			include_once NFE_PATH.'/include/shop/shop_write.inc.php';
			?>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="button" class="cancel_btn">취소하기</button>
			</div>
		</div>
		<!--//conbox-->
		</form>

	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->