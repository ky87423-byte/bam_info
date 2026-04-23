<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '600202';
$add_cate_arr = array('notice');
include '../include/header.php';

$notice_row = $db->query_fetch("select * from nf_notice where `no`=".intval($_GET['no']));
$file_arr = $nf_util->get_unse($notice_row['wr_file']);
$file_name_arr = $nf_util->get_unse($notice_row['wr_file_name']);
?>
<!-- 공지사항 등록 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			
			<form name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="notice_write" />
			<input type="hidden" name="no" value="<?php echo $notice_row['no'];?>" />
			<h6>공지사항 등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>제목</th>
					<td><input type="text" name="wr_subject" hname="제목" needed value="<?php echo $nf_util->get_html($notice_row['wr_subject']);?>"></td>
				</tr>
				<tr>
					<th>분류</th>
					<td>
						<select name="wr_type" hname="분류" >
							<option value="">분류</option>
							<?php
							if(is_array($cate_p_array['notice'][0])) { foreach($cate_p_array['notice'][0] as $k=>$v) {
								$selected = $notice_row['wr_type']==$v['wr_name'] ? 'selected' : '';
							?>
							<option value="<?php echo $v['wr_name'];?>" <?php echo $selected;?>><?php echo $v['wr_name'];?></option>
							<?php
							} }
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>작성자</th>
					<td><input type="text" name="wr_name" hname="작성자" needed value="<?php echo $nf_util->get_html($notice_row['wr_name']);?>" class="input10"></td>
				</tr>
				<tr>
					<th>내용</th>
					<td><textarea type="editor" name="wr_content" hname="내용" needed style="height:300px;"><?php echo stripslashes($notice_row['wr_content']);?></textarea></td>
				</tr>
				<tr>
					<th>파일첨부</th>
					<td>
						<ul>
							<?php
							for($i=0; $i<5; $i++) {
							?>
							<li class="<?php echo $i==4 ? '' : 'MAB5';?>">
								<input type="file" name="wr_file[]">
								<?php
								if(is_file(NFE_PATH.'/data/notice/'.$file_arr[$i])) {
									echo '<a href="'.NFE_URL.'/include/regist.php?mode=download_notice&no='.$notice_row['no'].'&k='.$i.'">'.$file_name_arr[$i].'</a>';
								}
								?>
							</li>
							<?php
							}?>
						</ul>
					</td>
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