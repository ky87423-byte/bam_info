<?php
$_SERVER['__USE_API__'] = array('editor');
if(!$_GET['code']) $_GET['code'] = 'site_introduce';
if($_GET['code']=='site_introduce') $top_menu_code = '200102';
if($_GET['code']=='membership') $top_menu_code = '200103';
if($_GET['code']=='privacy') $top_menu_code = '200104';
if($_GET['code']=='privacy_info') $top_menu_code = '200105';
if($_GET['code']=='young_info') $top_menu_code = '200106';
if($_GET['code']=='board_manage') $top_menu_code = '200107';
if($_GET['code']=='bottom_site') $top_menu_code = '200108';

include '../include/header.php';

if(!$db->is_field("nf_config", "content_".addslashes($_GET['code'])))
	$db->_query("alter table nf_config add content_".addslashes($_GET['code'])." mediumtext comment '".$sub_menu_txt."'");
?>
<!--사이트 소개-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="config_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="content_write" />
		<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
		<div class="conbox">
			<h6><?php echo $sub_menu_txt;?></h6>
			<div>
				<textarea type="editor" name="content" hname="<?php echo $sub_menu_txt;?>" needed style="height:600px" placeholder="<?php echo $sub_menu_txt;?>(을)를 입력해주세요."><?php echo stripslashes($env['content_'.$_GET['code']]);?></textarea>
			</div>
			<div class="flex_btn">
				<button type="submit" class="save_btn">등록하기</button>
			</div>
		</div>
		</form>
		<!--//conbox-->
	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->