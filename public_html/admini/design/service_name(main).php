<?php
$top_menu_code = "400201";
include '../include/header.php';

$service_shop_len = count($nf_shop->service_name['shop']['main']);
$service_len = $service_shop_len;
?>

<script type="text/javascript">
var ch_service_name = function(el, kind, service_k) {
	var form = document.forms['fwrite'];
	var txt = $(form).find("[name='service_name["+kind+"]["+service_k+"]']").val();
	$.post("../regist.php", "mode=service_name_write&kind="+kind+"&service_k="+service_k+"&val="+encodeURIComponent(txt), function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.msg;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 메인서비스명설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide4-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<li>- 서비스명 및 리스트 타이틀을 관리자가 임의로 변경을 하실수 있습니다. </li>
				</ul>
			</div>


			<form name="fwrite" action="../regist.php" method="post">
			<input type="hidden" name="mode" value="service_name_write" />
			<h6>업체정보 서비스명 설정</h6>
			<table class="table3">
				<colgroup>
					<col width="40%">
					<col width="10%">
					<col width="7%">
					<col width="20%">
					<col width="">
				</colgroup>
				<thead>
					<tr>
						<th class="tac">위치안내</th>
						<th class="tac" colspan="3">현재 서비스명</th>
						<th class="tac">서비스명 수정</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 0;
					if(is_array($nf_shop->service_name['shop']['main'])) { foreach($nf_shop->service_name['shop']['main'] as $k=>$v) {
						$service_k = '0_'.$k;
					?>
					<tr>
						<?php if($count===0) {?>
						<td class="tac" rowspan="<?php echo intval($service_len);?>"><img src="../../images/shop_main_product.jpg" alt="업체정보 서비스 명 설정"></td>
						<th rowspan="<?php echo intval($service_shop_len);?>" class="tac">업체정보</th>
						<?php }?>
						<th class="tac">업체<?php echo substr($nf_util->alphabet, $count, 1);?>영역</th>
						<td class="tac"><em class="service_name_txt"><?php echo $v;?></em> 업체정보</td>
						<td class="tac">
							<input type="text" name="service_name[shop][<?php echo $service_k;?>]" value="<?php echo $v;?>">
							<button type="button" onClick="ch_service_name(this, 'shop', '<?php echo $service_k;?>')" class="basebtn gray">수정</button>
						</td>
					</tr>
					<?php
						$count++;
					} }?>
				</tbody>
			</table>
			</form>

		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->