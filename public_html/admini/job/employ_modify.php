<?php
$_SERVER['__USE_API__'] = array('editor', 'map');
$add_cate_arr = array('email', 'subway', 'job_date', 'job_week', 'job_pay', 'job_pay_support', 'job_type', 'job_welfare', 'job_grade', 'job_position', 'job_age', 'job_target', 'job_document', 'job_tema', 'job_conditions');
$top_menu_code = '100106';
include '../include/header.php';

if($_GET['mno']) {
	$member_row = $db->query_fetch("select * from nf_member where `no`=".intval($_GET['mno']));
	$mno = $member_row['no'];
}

$em_no = $_GET['no'] ? $_GET['no'] : $_GET['info_no'];

$em_row = $db->query_fetch("select * from nf_employ where `no`=".intval($em_no));
if($em_row) $mno = $em_row['mno'];
$employ_query = $db->query_fetch_rows("select * from nf_employ where `mno`=".intval($mno)." order by `no` desc");
$employ_info = $nf_job->employ_info($em_row);
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
	$.post(root+"/admini/regist.php", "mode=put_member&code=employ&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
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
</script>
<form name="ffile" action="<?php echo NFE_URL;?>/include/regist.php" method="post" enctype="multipart/form-data" style="display:none">
<input type="hidden" name="mode" value="employ_upload" />
<input type="hidden" name="no" value="<?php echo intval($em_row['no']);?>" />
<input type="hidden" name="code" value="" />
<input type="file" name="file_upload" id="file_upload-" onChange="nf_job.ch_logo(this)" />
</form>

<!--채용공고 등록-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="employ_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="<?php echo NFE_URL;?>/include/regist.php" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="employ_write" />
		<input type="hidden" name="mno" value="<?php echo $em_row['mno'];?>" />
		<input type="hidden" name="no" value="<?php echo $em_row['no'];?>" />
		<input type="hidden" name="mb_id" value="<?php echo $em_row['wr_id'] ? "" : '_admin_'.time();?>" />
		<input type="hidden" name="info_no" value="<?php echo $_GET['info_no'] ? intval($em_row['no']) : "";?>" />
		<div class="conbox">
			<?php
			include NFE_PATH.'/include/job/employ_write.inc.php';
			?>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="submit" class="cancel_btn">취소하기</button>
			</div>
		</div>
		<!--//conbox-->

		<?php
		include NFE_PATH.'/admini/include/logo.inc.php'; // : 로고
		include NFE_PATH.'/admini/include/bg_logo.inc.php'; // : 배경로고
		include NFE_PATH.'/admini/include/grade_position.inc.php'; // : 직급,직책
		include NFE_PATH.'/admini/include/preferential.inc.php'; // : 우대조건
		include NFE_PATH.'/admini/include/welfare.inc.php'; // : 복리후생
		?>

		</form>

	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->