<?php
$top_menu_code = "200111";
include '../include/header.php';
?>
<style type="text/css">
.upload_ing { position:absolute; background:#000;color:#fff;padding:30px; display:none; }
.upload_ing.on { display:block; }
</style>
<script type="text/javascript">
var submit_func = function(el) {
	$(".upload_ing").addClass("on");
	nf_util.ajax_submit(el);
	return false;
}
</script>
<!-- 지도설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<div class="upload_ing">디비를 업로드중입니다.</div>
	
	<section class="map_setting">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li style="margin-bottom:3px;">- 주소를 다운받으실려면 <a href="https://www.epost.go.kr/search/zipcode/areacdAddressDown.jsp" target="_blank" style="font-weight:bold; text-decoration:underline; background:linear-gradient(#fff,#eee); border:1px solid #ccc; padding:1px 3px 3px 3px; border-radius:2px;">우편번호 내려받기</a> 에서 <span class="red">'범위주소 DB'</span> 를 다운로드하셔서 압축파일을 푸신후 <span class="red">'지번범위'</span> 파일을 업로드하시면 됩니다.</li>
					<li>- 시간이 몇분이 소요될것이므로 완료될때까지 기다려주시기 바랍니다.</li>
					<li>- 시간이 몇분 소요됨으로써 사이트가 정상적으로 열리지 않을 수 있으니 <b class="red" style="text-decoration:underline;">방문자가 없는 시간대에 진행해주시기 바랍니다. </b></li>
					<li>- 더 궁금하신 사항은 넷퓨(1544-9638)로 연락해주시면 친절하게 상담해드리겠습니다.</li>
				</ul>
			</div>

			<h6>지번범위 파일 업로드</h6>
			<table class="table4">
				<tr>
					<th>파일넣기</th>
					<td>
						<form name="fwrite" action="../regist.php" method="post" enctype="multipart/form-data" method="post" onSubmit="return submit_func(this)">
						<input type="hidden" name="mode" value="post_area_write" />
						<input type="file" name="post_file" hname="우체국 지역파일" needed / style="width:50%">
						<button type="submit" class="black common basebtn " style="color:#fff;">밀어넣기</button>
						</form>
					</td>
				</tr>
			</table>

		</div>
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->