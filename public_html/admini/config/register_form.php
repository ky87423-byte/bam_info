<?php
if(!$_GET['code']) $_GET['code'] = 'company';
$top_menu_code = "200301";
if($_GET['code']=='employ') $top_menu_code = '200302';
if($_GET['code']=='resume') $top_menu_code = '200303';
include '../include/header.php';

$cate_k = 'register_form_'.$_GET['code'];

if(is_array($nf_job->form_arr[$_GET['code']])) { foreach($nf_job->form_arr[$_GET['code']] as $k=>$v) {
	$cate_row = $db->query_fetch("select * from nf_category where `wr_name`=? and `wr_type`=?", array($k, $cate_k));
	$max_rank = $db->query_fetch("select max(`wr_rank`) as wr_rank from nf_category where `wr_type`=?", array($cate_k));
	if(!$cate_row) {
		$_val = array();
		$_val['wr_type'] = $cate_k;
		$_val['wr_view'] = 1;
		$_val['wr_name'] = $k;
		$_val['wr_rank'] = intval($max_rank['wr_rank']+1);
		$_val['wr_0'] = 0;
		$_val['wr_wdate'] = today_time;
		$q = $db->query_q($_val);
		$db->_query("insert into nf_category set ".$q, $_val);
	}
} }

$query = $db->_query("select * from nf_category where `wr_type`=? ".$_where." order by wr_rank asc", array($cate_k));
if($_GET['code'] == 'company') {
	$go_popurl = "../pop/guide.php#guide2-2";
}else if($_GET['code'] == 'employ') {
	$go_popurl = "../pop/guide.php#guide2-3";
}else if($_GET['code'] == 'resume') {
	$go_popurl = "../pop/guide.php#guide2-4";
}
?>

<!-- 기업회원 가입폼 설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('<?php echo $go_popurl; ?>','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<li>- <?php echo $sub_menu_txt;?> 페이지는 <?php echo $nf_job->register_form[$_GET['code']];?></li>
				</ul>
			</div>
			
			<h6><?php echo $sub_menu_txt;?></h6>
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="register_form_write" />
			<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
			<table class="table4">
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="7%" style="display:none;">
					<col width="">
				</colgroup>
				<thead>
					<tr>
						<th>사용유무</th>
						<th>필수유무</th>
						<th style="display:none;">순서</th>
						<th>항목명</th>
					</tr>
				</thead>
				<tbody class="tr-list-">
					<?php
					while($cate_row=$db->afetch($query)) {
						if(!$nf_job->form_arr[$_GET['code']][$cate_row['wr_name']]) {
							$delete = $db->_query("delete from nf_category where `wr_name`='".$cate_row['wr_name']."' and `wr_type`='".addslashes($cate_k)."'");
							continue;
						}
					?>
					<tr class="tac">
						<td><?php if(in_array('use', $nf_job->form_arr[$_GET['code']][$cate_row['wr_name']])) {?><input type="checkbox" name="use[]" <?php echo $cate_row['wr_view'] ? 'checked' : '';?> value="<?php echo $cate_row['no'];?>"><?php }?></td>
						<td><?php if(in_array('need', $nf_job->form_arr[$_GET['code']][$cate_row['wr_name']])) {?><input type="checkbox" name="need[]" <?php echo $cate_row['wr_0'] ? 'checked' : '';?> value="<?php echo $cate_row['no'];?>"><?php }?></td>
						<td class="" style="display:none;"><input type="text" name="rank[]" value="<?php echo intval($cate_row['wr_rank']);?>"><input type="hidden" name="hidd[]" value="<?php echo $cate_row['no'];?>" /></td>
						<td class="tal"><?php echo $nf_util->get_text($cate_row['wr_name']);?><input type="hidden" name="name[]" value="<?php echo $nf_util->get_html($cate_row['wr_name']);?>"></td>
					</tr>
					<?php
					}?>
				</tbody>
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