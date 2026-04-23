<?php
$top_menu_code = "500201";
include '../include/header.php';
?>

<script type="text/javascript">
var ch_img_height = function() {
	var height = $(".logo_").find("img").css("height");
	$(".logo_").css({"margin-top":-((parseInt(height)/2)+30)+'px'});
}
$(function(){
	$(".pg_click_").find("[type=radio]").click(function(){
		$(".pg_input_").css({"display":"none"});
		$(".pg_input_."+$(this).val()+"_").css({"display":"table-row-group"});
	});

	ch_img_height();
});


var ch_photo = function(el) {
	nf_util.ch_photo(el, $('.logo_').find('img')[0], 'ch_img_height()');
}
</script>
<!-- 결제환경설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 현재 홈페이지는 아래의 PG사 선택 부분에 3개 업체가 프로그램 연동이 되어 있습니다. 3곳중 한곳을 선택해서 계약을 맺으시면 됩니다.</li>
					<li>- 실재 결제된 내용을 보시거나 결제취소를 하시는 경우는 직접 PG사 가맹점 관리자에 접속하셔어 처리 하셔야 합니다. </li>
				</ul>
			</div>
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="payment_config_save" />
			<div class="h6wrap">
				<h6>결제모듈 연동 설정</h6>
				<button class="orange white" onclick="window.open('http://netfu.co.kr/service/payService.php')">전자지불서비스신청하기</button>
			</div>
			<table class="pg_click_">
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>PG사 선택</th>
					<td>
						<?php
						if(is_array($nf_payment->pg_type)) { foreach($nf_payment->pg_type as $k=>$v) {
							$checked = $nf_payment->pg==$k ? 'checked' : '';
						?>
						<label for="pg_<?php echo $k;?>"><input type="radio" name="pg_type" value="<?php echo $k;?>" id="pg_<?php echo $k;?>" <?php echo $checked;?>><?php echo $v;?></label>
						<?php
						} }
						?>
					</td>
				</tr>
				<tr>
					<th>사이트이름(영어)</th>
					<td>
						<input type="text" name="site_name_eng" value="<?php echo $nf_util->get_html($env['pg_site_name_eng']);?>" /><span>영어로 입력해주세요.</span>
					</td>
				</tr>
				<tr>
					<th>결제 선택</th>
					<td>
						<?php
						if(is_array($nf_payment->pay_kind)) { foreach($nf_payment->pay_kind as $k=>$v) {
							if(in_array($k, array("admin"))) continue;
							$checked = in_array($k, $nf_payment->pg_method_arr) ? 'checked' : '';
						?>
						<label for="pg_<?php echo $k;?>"><input type="checkbox" name="method[]" value="<?php echo $k;?>" <?php echo $checked;?> id="pg_<?php echo $k;?>" /><?php echo $v;?></label>
						<?php
						} }?>
					</td>
				</tr>
				<tbody class="pg_input_ nicepay_" style="display:<?php echo $nf_payment->pg=='nicepay' ? 'table-row-group' : 'none';?>;">
					<tr>
						<th>상점 아이디</th>
						<td><input type="text" name="pg[nicepay][id]" value="<?php echo $nf_payment->pg_config['nicepay']['id'];?>"></td>
					</tr>
					<tr>
						<th>상점 비밀번호</th>
						<td><input type="password" name="pg[nicepay][pw]" value="<?php echo $nf_payment->pg_config['nicepay']['pw'];?>"></td>
					</tr>

					<tr>
						<th>키값</th>
						<td><input type="text" name="pg[nicepay][key]" value="<?php echo $nf_payment->pg_config['nicepay']['key'];?>"></td>
					</tr>
					<tr>
						<th>상점관리</th>
						<td><a class="blue_link" href="https://npg.nicepay.co.kr/logIn.do" target="_blank">승인내역조회 / 승인취소 / 상점관리</a></td>
					</tr>
				</tbody>
				<!--KCP-->
				<tbody class="pg_input_ kcp_" style="display:<?php echo $nf_payment->pg=='kcp' ? 'table-row-group' : 'none';?>;">
					<tr>
						<th>사이트 코드</th>
						<td><input type="text" name="pg[kcp][id]" value="<?php echo $nf_payment->pg_config['kcp']['id'];?>"></td>
					</tr>
					<tr>
						<th>사이트 Key</th>
						<td><input type="password" name="pg[kcp][key]" value="<?php echo $nf_payment->pg_config['kcp']['key'];?>"></td>
					</tr>
					<tr>
						<th>플러그인 상점로고</th>
						<td>
							<input type="file" name="pg[kcp][logo]" onChange="ch_photo(this)"><span>최적크기 : 가로 150 × 세로 50 이하</span>
							<div class="logo_"><img src="<?php echo NFE_URL.$nf_payment->attach.'/'.$nf_payment->pg_config['kcp']['logo'];?>" /></div>
						</td>
					</tr>
					<tr>
						<th>CERT 정보</th>
						<td><textarea name="pg[kcp][cert]" style="width:100%;height:180px;"><?php echo stripslashes($nf_payment->pg_config['kcp']['cert']);?></textarea></td>
					</tr>
					<tr>
						<th>PRIKEY 정보</th>
						<td><textarea name="pg[kcp][prikey]" style="width:100%;height:180px;"><?php echo stripslashes($nf_payment->pg_config['kcp']['prikey']);?></textarea></td>
					</tr>
					<tr>
						<th>상점관리</th>
						<td><a class="blue_link" href="https://admin8.kcp.co.kr/assist/login.LoginAction.do" target="_blank">승인내역조회 / 승인취소 / 상점관리</a></td>
					</tr>
				</tbody>

				<!--KCP-->
				<tbody class="pg_input_ toss_" style="display:<?php echo $nf_payment->pg=='toss' ? 'table-row-group' : 'none';?>;">
					<tr>
						<th>클라이언트 Key</th>
						<td><input type="password" name="pg[toss][key]" value="<?php echo $nf_payment->pg_config['toss']['key'];?>"></td>
					</tr>
	
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

