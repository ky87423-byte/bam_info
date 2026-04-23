<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = "400304";
include '../include/header.php';

$_code = $nf_email->email_type_arr_key[0];
if($_GET['code']) $_code = $_GET['code'];

$mail_skin_row = $db->query_fetch("select * from nf_mail_skin where `skin_name`=?", array($_code));
?>
<script type="text/javascript">
var form;
$(function(){
	form = document.forms['fwrite'];
	$(form).find("[name='skin_name']").click(function(){
		var val = $(this).val();
		$.post("../regist.php", "mode=get_mail_skin&val="+val, function(data){
			data = $.parseJSON(data);
			_editor_use['mail_content'].replaceContents(data.content);
		});
	});
});
</script>
<!-- 메일스킨관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide4-6','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<form name="fwrite" method="post" action="../regist.php" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="mail_skin_update" />
			<h6>MAIL스킨관리</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>편집메일 선택</th>
					<td>
						<?php
						$count = 0;
						if(is_array($nf_email->email_type_arr)) { foreach($nf_email->email_type_arr as $k=>$v) {
							$checked = $_code==$k ? 'checked' : '';
						?>
						<label><input type="radio" name="skin_name" <?php echo $checked;?> value="<?php echo $k;?>"><?php echo $v;?></label>
						<?php
							$count++;
						} }?>
					</td>
				</tr>
				<tr>
					<th>공통 치환문자</th>
					<td><?php echo implode(" ", $nf_email->mail_skin['common']);?></td>
				</tr>
				<tr>
					<th>문의 치환문자</th>
					<td><?php echo implode(" ", $nf_email->mail_skin['qna']);?></td>
				</tr>
				<tr>
					<th>내용편집</th>
					<td><textarea type="editor" name="mail_content" style="height:500px;"><?php echo stripslashes($mail_skin_row['content']);?></textarea></td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->