<?php
$_SERVER['__USE_API__'] = array("editor");
$_site_title_ = '업체등록';
include '../include/header_meta.php';
include '../include/header.php';
$nf_member->check_login('company');

if(!$env['use_shop_write']) {
	die($nf_util->move_url($nf_util->page_back(), "업체등록이 불가능합니다."));
}

$m_title = '업체등록';
include NFE_PATH.'/include/m_title.inc.php';

$shop_row = $db->query_fetch("select * from nf_shop where `no`=".intval($_GET['no'])." and `wr_id`=?", array($member['mb_id']));
$shop_info = $nf_shop->shop_info($shop_row);

$shop_icon = $db->_query("select * from nf_icon where `wr_code`=? and `wr_use`=1 order by `wr_rank` asc", array("shop_icon"));
?>
<style type="text/css">
.price-group-paste-body- > .price-group-parent- { margin-top:10px; }
.price-group-paste-body- > .price-group-parent-:first-child { margin-top:0px; }
</style>
<script type="text/javascript">
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
<form name="fupload" action="../include/regist.php" style="display:none;" method="post">
<input type="hidden" name="mode" value="shop_upload" />
<input type="hidden" name="code" value="" />
<span><input type="file" name="upload" onChange="return nf_shop.upload_process(this)" /></span>
</form>
<section class="my_sub wrap1400">
	<!--왼쪽 메뉴-->
	<?php
	$left_my['shop_regist'] = 'on';
	include '../include/my_leftmenu.php';
	?>
	<div class="my_con">
		<form name="fwrite" action="../include/regist.php" method="post" onSubmit="return submit_func(this)">
		<input type="hidden" name="mode" value="shop_write" />
		<input type="hidden" name="no" value="<?php echo $shop_row['no'];?>" />
		<?php
		include_once NFE_PATH.'/include/shop/shop_write.inc.php';
		?>
		<div class="next_btn">
			<button type="button" class="base graybtn">취소</button>
			<button type="submit" class="base"><?php echo $shop_row ? '수정' : '등록';?></button>
		</div>
		</form>
	</div>
</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>