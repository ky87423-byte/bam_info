<?php
$top_menu_code = "200110";
include '../include/header.php';


$db->_query("alter table nf_config add map_engine varchar(20) comment '지도엔진'");
$db->_query("alter table nf_config add map_daum_key varchar(255) comment '카카오 지도 키값'");
$db->_query("alter table nf_config add map_google_key varchar(255) comment '구글 지도 키값'");
?>
<style type="text/css">
.layer_pop.conbox { display:none; }
</style>
<script type="text/javascript">
var close_ = function(el) {
	$(el).closest(".layer_pop.conbox").css({"display":"none"});
}
$(function(){
	$(".map-help").click(function(){
		var engine = $(this).attr("engine");
		$(".layer_pop.conbox").css({"display":"none"});
		$(".layer_pop.conbox."+engine+"_box-").css({"display":"block"});
	});
});
</script>
<!-- 지도설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="map_setting">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 사이트 메뉴에 지도검색 부분 및 업체 상세페이지에 업체 위치 노출되는 지도에 사용됩니다.</li>
					<li>- 사용하고자 하시는 지도 엔진을 선택하시고 도움말을 통해 개발자도구에 등록후 키값을 입력하세요.</li>
					<li>- 지도 엔진은 2가지중 1가지만 선택하여 사용 가능합니다.</li>
				</ul>
			</div>
			
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="map_config" />
			<h6>지도 엔진 설정</h6>
			<table>
				<colgroup>
				</colgroup>
				<tbody>
					<tr>
						<th>지도 엔진 선택</th>
						<td>
							<label><input type="radio" name="map_engine" value="daum" <?php echo $env['map_engine']=='daum' ? 'checked' : '';?>>카카오 지도맵 Ver.3</label>
							<label><input type="radio" name="map_engine" value="google" <?php echo $env['map_engine']=='google' ? 'checked' : '';?>>구글 지도맵 V3</label>
						</td>
					</tr>
					<tr>
						<th>카카오 API키 <button type="button" class="s_basebtn red MAL5 map-help" onclick="void(window.open('../pop/kakao_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))" engine="daum">?도움말</button></th>
						<td><input type="text" class="input20" name="map_daum_key" style="width:350px;" value="<?php echo $nf_util->get_html($env['map_daum_key']);?>"><a href="https://developers.kakao.com/console/app" target="_blank"><button type="button" class="s_basebtn blue MAL5">카카오 지도형 API 신청</button></a><span>페이지에서 지도형 API 를 신규등록 하세요.</span></td>
					</tr>
					<tr>
						<th>구글 지도맵 지도키 <button type="button" class="s_basebtn red MAL5 map-help" onclick="void(window.open('../pop/goggle_map_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"  engine="google">?도움말</button></th>
						<td><input type="text" class="input20" name="map_google_key" style="width:350px;" value="<?php echo $nf_util->get_html($env['map_google_key']);?>"><a href="https://developers.google.com/maps" target="_blank"><button type="button" class="s_basebtn blue MAL5">구글 지도 API키 발급</button></a><span>페이지에서 지도키를 발급 받으세요. 자세한 설명은 <span class="blue">API 키 얻기 를 참조하시면 됩니다.</span></td>
					</tr>
				</tbody>
			</table>

			<div class="flex_btn">
				<button class="save_btn">저장하기</button>
			</div>
			</form>

			<!--카카오지도맵--><!--
			<div class="layer_pop conbox daum_box-">
				<div class="h6wrap">
					<h6>카카오 지도맵</h6>
					<button class="close" type="button" onClick="close_(this)">X 창닫기</button>
				</div>
				<div class="map_boxwrap">
					<div class="map_imgbox">
						
					</div>
					<div class="map_infobox">
						지도API는 웹사이트에 지도를 표시할 수 있도록 <b style="text-decoration:underline;">카카오에서 제공하는 무료서비스</b>입니다. <br>	
						채용정보 상에 회사위치(기업회원 정보에서 설정한)가 표시될 수 있도록 프로그램 되어 있으며, 이를 활성화하기 위해서는 카카오에서 지도키값을 발급받아 솔루션에 등록해 주셔야 합니다.<br>	
						카카오의 안내를 참고하시면서 설정하시기 바랍니다.<br>	<br>	

						<b>안내1)</b> + 실제서비스 페이지와 안내사항이 다를 수도 있습니다.<br>	
						1. 아래 '카카오API키값 발급받기' 버튼 클릭하여 열리는 사이트에서 <span class="red">지도키발급</span> 탭을 클릭하여 지도키발급 페이지로 전환합니다.<br>	
						2. 발급받은 키값을 복사하여 지도키를 추가합니다.<br>	<br>

						<b>안내2)</b><br>
						카카오에서 지도API의 서비스환경을 변경할 경우 이용에 불편이 생길 수 있습니다.<br>
						카카오지도 이용에 문제가 발생했을 경우 넷퓨 고객센터에 문의해주세요.
					</div>
				</div>
			</div>-->

			<!--구글 지도맵--><!--
			<div class="layer_pop conbox google_box-">
				<div class="h6wrap">
					<h6>구글 지도맵</h6>
					<button class="close" type="button" onClick="close_(this)">X 창닫기</button>
				</div>
				<div class="map_boxwrap">
					<div class="map_imgbox">
						
					</div>
					<div class="map_infobox">
						지도API는 웹사이트에 지도를 표시할 수 있도록 <b style="text-decoration:underline;">구글에서 제공하는 무료서비스</b>입니다.<br>
						채용정보 상에 회사위치(기업회원 정보에서 설정한)가 표시될 수 있도록 프로그램 되어 있으며, 이를 활성화하기 위해서는 구글에서 지도키값을 발급받아 솔루션에 등록해 주셔야 합니다.<br>
						카카오의 안내를 참고하시면서 설정하시기 바랍니다.<br><br>

						<b>안내1)</b> + 실제서비스 페이지와 안내사항이 다를 수도 있습니다.<br>
						1. 아래 '구글API키값 발급받기' 버튼 클릭하여 열리는 사이트에서 지도키발급 탭을 클릭하여  <span class="red">지도키발급</span> 페이지로 전환합니다.<br>
						2. 발급받은 키값을 복사하여 지도키를 추가합니다.<br><br>

						<b>안내2)</b>
						구글에서 지도API의 서비스환경을 변경할 경우 이용에 불편이 생길 수 있습니다.<br>
						구글지도 이용에 문제가 발생했을 경우 넷퓨 고객센터에 문의해주세요.
					</div>
				</div>
			</div>-->
			

		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->