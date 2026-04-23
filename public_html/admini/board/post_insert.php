<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '600202';
$add_cate_arr = array('notice');
include '../include/header.php';

$notice_row = $db->query_fetch("select * from nf_notice where `no`=".intval($_GET['no']));
$file_arr = $nf_util->get_unse($notice_row['wr_file']);
$file_name_arr = $nf_util->get_unse($notice_row['wr_file_name']);
?>
<!-- 답글/게시물보기 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

<!--공지사항(notice_insert.php) 파일 복사해서 수정한 것이니 수정할 부분은 수정해주세요!!!-->
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<form name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="notice_write" />
			<input type="hidden" name="no" value="<?php echo $notice_row['no'];?>" />
			<h6>게시물 등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>제목</th>
					<td colspan="3"><input type="text"> <label for=""><input type="checkbox">공지글</label><label for=""><input type="checkbox">비밀글</label></td>
				</tr>
				<tr>
					<th>작성자</th>
					<td><input type="text" class="input10"></td>
				</tr>
				<tr>
					<th>내용</th>
					<td><textarea type="editor" name="wr_content" hname="내용" needed style="height:200px;"><?php echo stripslashes($notice_row['wr_content']);?></textarea></td>
				</tr>
			</table>
			
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
		</div>
		</form>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->