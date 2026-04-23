<?php
include "../../engine/_core.php";
$top_menu_code = '500203';
if(!$_GET['code']) $_GET['code'] = 'shop_audit';
include '../include/header.php';

$title_array = array('shop_audit'=>'업체 심사 여부', 'shop'=>'업체 결제 페이지');
?>
<style type="text/css">
.tbody_service.none { display:none; }
</style>
<script type="text/javascript">
var click_service = function(el) {
	if(el.value==='0')
		$(".tbody_service").removeClass("none");
	else
		$(".tbody_service").addClass("none");
}
</script>

<!-- 결제페이지설정(업체) -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide5-1','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="pg_page_write" />
			<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
			<h6>
			<?php echo $title_array[$_GET['code']];?> 설정</h6>
			<table class="table3">
				<colgroup>
					<col width="13%">
					<col width="%">
				</colgroup>
				<tbody>
				<tr>
					<th>페이지 사용여부</th>
					<td>
						<label><input type="radio" name="service_<?php echo $_GET['code'];?>_use" value="1" onClick="click_service(this)" checked>사용</label>
						<label><input type="radio" name="service_<?php echo $_GET['code'];?>_use" value="0" onClick="click_service(this)" <?php echo !$env['service_'.$_GET['code'].'_use'] ? 'checked' : '';?>>미사용</label> <?php if($_GET['code']!='shop_audit') { ?><span>* '0' 입력시엔 서비스 기간을 부여하지 않습니다.</span><?php } ?>
					</td>
				</tr>
				</tbody>

				<?php
				$use_field = 'service_'.$_GET['code'].'_use';
				?>
				<tbody class="tbody_service <?php echo $env[$use_field] ? 'none' : '';?>">
				<?php
				if(array_key_exists($_GET['code'], $nf_shop->kind_of) && is_array($nf_shop->service_name_k[$_GET['code']])) { foreach($nf_shop->service_name_k[$_GET['code']] as $k=>$v) {
					$service_arr = $nf_shop->service_name[$_GET['code']][$v];
					if(is_array($service_arr)) { foreach($service_arr as $k2=>$v2) {
						$service_k = $k.'_'.$k2;
						$service_row = $db->query_fetch("select * from nf_service where `type`=? and `code`=?", array($service_k, $_GET['code']));
						$free_date_arr = explode(" ", $service_row['free_date']);
				?>
				<tr>
					<th><?php echo $nf_shop->service_name_k_txt[$v].' '.$v2;?> 서비스 기간</th>
					<td>
						<input type="text" name="date[<?php echo $service_k;?>][]" value="<?php echo intval($free_date_arr[0]);?>" class="input10">
						<select name="date[<?php echo $service_k;?>][]" class="select10">
							<?php
							if(is_array($nf_util->date_arr)) { foreach($nf_util->date_arr as $k3=>$v3) {
								$selected = $free_date_arr[1]==$k3 ? 'selected' : '';
							?>
							<option value="<?php echo $k3;?>" <?php echo $selected;?>><?php echo $v3;?></option>
							<?php
							} }
							?>
						</select>
					</td>
				</tr>
				<?php
					} }
				} }?>
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