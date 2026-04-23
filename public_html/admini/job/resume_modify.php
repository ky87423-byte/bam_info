<?php
$_SERVER['__USE_API__'] = array('editor');
$add_cate_arr = array('email', 'job_type', 'job_date', 'job_week', 'job_time', 'job_pay', 'job_type', 'job_computer', 'job_pay_employ', 'job_obstacle', 'job_veteran', 'job_language', 'job_language_exam');
$top_menu_code = "100202";
include '../include/header.php';

$em_no = $_GET['no'] ? $_GET['no'] : $_GET['info_no'];
$re_row = $db->query_fetch("select * from nf_resume where `no`=".intval($em_no));
$resume_individual = $nf_job->resume_individual($re_row['mno']);
$resume_query = $db->_query("select * from nf_resume where `mno`=".intval($re_row['mno'])." order by `no` desc");
$resume_info = $nf_job->resume_info($re_row);
?>
<!--이력서 등록-->
<style type="text/css">
.conbox.popup_box- { display:none; cursor:pointer; }
.category_group- > .parent- { margin-top:5px; }
.category_group- > .parent-:nth-child(1) { margin-top:0px; }
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
	$.post(root+"/admini/regist.php", "mode=put_member&code=resume&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var file_clean = function(id) {
	$(id).val("");
	$(id).closest(".parent_file_upload-").find("img")[0].src = root+"/images/no_imgicon.png";
}

$(window).load(function(){
	form = document.forms['fwrite'];
	$(form).find("[name='input_type']").click(function(){
		$(".input_type-").css({"display":"none"});
		form.mb_id.value = "";
		form.mno.value = "";
		$(".input_type-."+$(this).val()+"-").css({"display":"table-row-group"});
		if($(this).val()=="self") {
			form.mb_id.value = "<?php echo '_admin_'.time();?>";
		}
	});

	if($("#find_member-").val()) find_member();
});
</script>

<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="resume_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" enctype="multipart/form-data" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="resume_write" />
		<input type="hidden" name="mno" value="<?php echo $re_row['mno'];?>" />
		<input type="hidden" name="no" value="<?php echo $re_row['no'];?>" />
		<input type="hidden" name="mb_id" value="<?php echo $re_row['wr_id'] ? "" : '_admin_'.time();?>" />
		<input type="hidden" name="info_no" value="<?php echo $_GET['info_no'] ? intval($re_row['no']) : "";?>" />
		<div class="conbox">
			
			<?php
			include NFE_PATH.'/include/job/resume_write.inc.php';
			?>

			<div class="flex_btn">
				<button type="submit" class="save_btn"><?php echo $re_row && !$_GET['info_no'] ? '수정' : '저장';?>하기</button>
				<button type="button" class="cancel_btn">취소하기</button>
			</div>
		</div>
		<!--//conbox-->

		<?php
		include NFE_PATH.'/admini/include/photo.inc.php';
		?>

		</form>

	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->