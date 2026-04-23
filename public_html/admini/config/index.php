<?php
$top_menu_code = '200101';
include '../include/header.php';
?>

<script type="text/javascript">
$(function(){
	var form = document.forms['fwrite'];
	$(form).find("[name='login_return']").click(function(){
		$(form).find("[name='login_return_page']").css({"display":"none"});
		if($(this).val()==='2') $(form).find("[name='login_return_page']").css({"display":"inline"});
	});
});
</script>
<!--기본정보설정-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="config_index">
		<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="config_update" />
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide2-1','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
		</div>
		<div class="conbox">
			<h6>사이트기본설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tbody>
					<tr>
						<th>메일,SMS 발송시 사이트명</th>
						<td><input type="text" name="site_name" value="<?php echo $nf_util->get_html($env['site_name']);?>" needed hname="문자 발송 사이트명"><span>메일,SMS 발송시 발송자의 이름으로 출력</span></td>
					</tr>
					<!-- <tr>
						<th>사이트 영문명</th>
						<td>
							<input type="text" name="site_english" value="<?php echo $nf_util->get_html($env['site_english']);?>" needed hname="사이트 영문명">
							<span>사이트 하단 등 영문이 필요한 경우 사용됩니다.</span>
						</td>
					</tr> -->
					<tr>
						<th>파비콘 아이콘설정</th>
						<td>
							<input type="file" name="favicon">
							<span>파일규격 : 16 x 16 또는 32 x 32 픽셀, 16/256/트루컬러의 *.ico 등록가능</span>
							<?php
							if(is_file(NFE_PATH.'/data/favicon/'.$env['favicon'])) {
							?>
							<img src="/data/favicon/<?php echo $env['favicon'];?>" style="width:32px;" />
							<?php
							}?>
						</td>
					</tr>
				</tbody>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>관리자이메일</th>
					<td><input type="text" name="email" needed hname="관리자이메일" value="<?php echo $nf_util->get_html($env['email']);?>"><span>수신/발신용 기본 메일 주소</span></td>
				</tr>
				<tr>
					<th>고객센터 번호</th>
					<td><input type="text" name="call_center" needed hname="고객센터 번호" value="<?php echo $nf_util->get_html($env['call_center']);?>"><span>사이트 발신용 번호</span></td>
				</tr>
				<tr class="c_textarea">
					<th>고객센터 안내문구</th>
					<td>
						<textarea name="call_time" needed hname="고객센터 안내문구"><?php echo stripslashes($env['call_time']);?></textarea>
						<span>입력예)<br>평일 Am 09:00 ~ Pm 06:00 <br>점심 Pm 12:00 ~ Pm 01:00 <br>주말/공휴일은 휴무입니다</span>
					</td>
				</tr>

			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>로그인후 리턴페이지</th>
					<td>
						<label><input type="radio" name="login_return" value="0" checked>로그인전 페이지</label>
						<label><input type="radio" name="login_return" value="1" <?php echo $env['login_return']==='1' ? 'checked' : '';?>>메인페이지</label>
						<label><input type="radio" name="login_return" value="2" <?php echo $env['login_return']==='2' ? 'checked' : '';?>>지정페이지</label>
						<input type="text" name="login_return_page" style="display:<?php echo $env['login_return']==='2' ? 'inline' : 'none';?>;" value="<?php echo $nf_util->get_html($env['login_return_page']);?>"><!--지정페이지선택시 input노출--></td>
				</tr>
				<tr>
					<th>로그인 유지시간</th>
					<td><input type="text" name="session_time" hname="로그인 유지시간" needed class="input10" value="<?php echo $nf_util->get_html($env['session_time']);?>">분 <span>로그인 유지시간은 로그인후 페이지가 로딩된 후 아무 행동도 하지 않고 유지되는 시간</span></td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>이미지 업로드 최대 크기</th>
					<td>
						<input type="text" name="editor_max_size" value="<?php echo intval($env['editor_max_size']);?>" style="width:50px;" />M 까지 업로드 가능
					</td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>

				<tr>
					<th>아이핀 사용여부</th>
					<td>
						<label><input type="radio" name="use_ipin" value="1" checked>사용함</label>
						<label><input type="radio" name="use_ipin" value="0" <?php echo !$env['use_ipin'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
				<tr>
					<th>아이핀 설정</th>
					<td>아이핀 코드 <input type="text" class="input10" name="ipin_id" value="<?php echo $nf_util->get_html($env['ipin_id']);?>" hname="아이핀 코드"></td>
				</tr>
				<tr>
					<th>휴대폰 인증 사용여부</th>
					<td>
						<label><input type="radio" name="use_hphone" value="1" checked>사용함</label>
						<label><input type="radio" name="use_hphone" value="0" <?php echo !$env['use_hphone'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
				<tr>
					<th>휴대폰 인증 설정</th>
					<td>인증 코드 <input type="text" name="hphone_id" class="input10" value="<?php echo $nf_util->get_html($env['hphone_id']);?>" hname="휴대폰 인증 설정"></td>
				</tr>
				<tr>
					<th>비바톤 인증 사용여부</th>
					<td>
						<label><input type="radio" name="use_bbaton" value="1" checked>사용함</label>
						<label><input type="radio" name="use_bbaton" value="0" <?php echo !$env['use_bbaton'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
				<tr>
					<th>비바톤 인증 설정<br><a href="#none"  onclick="void(window.open('../pop/bbaton_guide.html','','width=845,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/bbaton.png" alt="비바톤 가입 방법"></a></th>
					<td>
						<ul class="MAB10">
							<li>서버아이피 : <?php echo $_SERVER['SERVER_ADDR'];?></li>
							<li>redirect uri : <?php echo $env['bbaton_redirect_uri'];?></li>
						</ul>
						<div>인증 코드 <input type="text" name="bbaton_id" class="input20" hname="비바톤 인증코드" value="<?php echo $nf_util->get_html($env['bbaton_id']);?>">&nbsp;&nbsp;&nbsp;SecretKey <input type="text" name="bbaton_key" class="input20" hname="비바톤 인증키" value="<?php echo $nf_util->get_html($env['bbaton_key']);?>"></div>
					</td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>회원가입 본인인증 사용여부</th>
					<td>
						<label><input type="radio" name="use_auth_member" value="1" checked>사용함</label>
						<label><input type="radio" name="use_auth_member" value="0" <?php echo !$env['use_auth_member'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
				<tr>
					<th>성인 인증 사용여부</th>
					<td>
						<label><input type="radio" name="use_adult" value="1" checked>사용함</label>
						<label><input type="radio" name="use_adult" value="0" <?php echo !$env['use_adult'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<!-- <tr>
					<th>게시판 조회,추천 기간설정</th>
					<td>
						<input type="text" name="board_hit_date" value="<?php //echo $nf_util->get_html($env['board_hit_date']);?>" style="width:50px;" />일이내
						<span>게시물중에 입력기간 안에 조회수나 추천수가 높은순으로 노출하기 위해</span>
					</td>
				</tr> -->
				<tr>
					<th>게시물 자동등록방지 설정</th>
					<td>
						<label><input type="radio" name="article_denied" value="0" checked>사용안함</label>
						<label><input type="radio" name="article_denied" value="1" <?php echo $env['article_denied']==='1' ? 'checked' : '';?>>KCAPTCHA 이미지</label><span>게시물이 자동등록되지 않도록 설정하여 광고글이 도배되는걸 방지</span>
					</td>
				</tr>
				<tr>
					<th rowspan=2>구글 리캡챠 V3 설정<br><a href="#none"  onclick="void(window.open('../pop/auto_guide.html','','width=845,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/bbaton.png" alt="구글 리캡챠 V3 설정 가입 방법"></a></th>
					<td>
						<label><input type="radio" name="google_recaptcha" value="0" checked>사용안함</label>
						<label><input type="radio" name="google_recaptcha" value="1" <?php echo $env['google_recaptcha'] ? 'checked' : '';?>>reCAPTCHA V3</label><span>게시물이 자동등록되지 않도록 설정하여 광고글이 도배되는걸 방지</span>
					</td>
				</tr>
				<tr>
					<td>
						<label>사이트키 코드 <input type="text" class="input20" name="google_recaptcha_site" value="<?php echo $nf_util->get_html($env['google_recaptcha_site']);?>" hname="사이트키"></label>
						<label>비밀키 코드 <input type="text" class="input20" name="google_recaptcha_secret" value="<?php echo $nf_util->get_html($env['google_recaptcha_secret']);?>" hname="비밀키"></label>
					</td>
				</tr>
				<tr>
					<th>사이트 공사중페이지 설정</th>
					<td>
						<label><input type="radio" name="under_construction" value="1" checked>사용함</label>
						<label><input type="radio" name="under_construction" value="0" <?php echo !$env['under_construction'] ? 'checked' : '';?>>사용안함</label><span>사이트 처음 접속시에 공사중 페이지 노출</span>
						<span></span>
					</td>
				</tr>
				<!-- <tr>
					<th>RSS Feed 설정</th>
					<td>
						<label><input type="radio" name="rss_feed" value="1" checked>사용함</label>
						<label><input type="radio" name="rss_feed" value="0" <?php echo !$env['rss_feed'] ? 'checked' : '';?>>사용안함</label>
					</td>
				</tr> -->
				<tr>
					<th>상세페이지 SNS 설정</th>
					<td class="chk-body-">
						<label><input type="checkbox" onClick="nf_util.child_allcheck(this)">ALL</label>
						<?php
						$count = 0;
						if(is_array($nf_util->sns_arr)) { foreach($nf_util->sns_arr as $k=>$v) {
							$checked = in_array($k, $env['sns_feed_arr']) ? 'checked' : '';
						?>
						<label><input type="checkbox" name="sns_feed[]" value="<?php echo $k;?>" <?php echo $checked;?>><img src="../../images/main_sns_<?php echo $k;?>.png" alt="<?php echo $v;?>공유"><?php echo $v;?></label>
						<?php
						} }
						?>
					</td>
				</tr>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>SNS 로그인 설정</th>
					<td class="chk-body-">
						<label><input type="checkbox" onClick="nf_util.child_allcheck(this)">ALL</label>
						<?php
						if(is_array($nf_util->sns_login_arr)) { foreach($nf_util->sns_login_arr as $k=>$v) {
							$checked = in_array($k, $env['sns_login_feed_arr']) ? 'checked' : '';
						?>
						<label><input type="checkbox" name="sns_login_feed[]" value="<?php echo $k;?>" <?php echo $checked;?>><img src="../../images/main_sns_<?php echo $k;?>.png" alt="<?php echo $v;?>로그인"><?php echo $v;?></label>
						<?php
						} }?>
					</td>
				</tr>
				<?php if(array_key_exists('facebook', $nf_util->sns_login_arr)) {?>
				<tr>
					<th>페이스북 앱 ID</th>
					<td><input type="text" class="input20" name="facebook_appid" value="<?php echo $nf_util->get_html($env['facebook_appid']);?>"><span><a href="https://developers.facebook.com/apps" class="blue" target="_blank" >https://developers.facebook.com/apps</a> 에서 발급 받으신 App Id 를 입력해 주세요. <a href="#none" onclick="void(window.open('../pop/facebook_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="페이스북앱 가이드"></a></span></td>
				</tr>
				<tr>
					<th>페이스북 앱 Secret</th>
					<td><input type="text" class="input20" name="facebook_secret" value="<?php echo $nf_util->get_html($env['facebook_secret']);?>"><span><a href="https://developers.facebook.com/apps" class="blue" target="_blank" >https://developers.facebook.com/apps</a> 에서 발급 받으신 Consumer key 를 입력해 주세요. <a href="#none"  onclick="void(window.open('../pop/facebook_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="페이스북앱 가이드"></a></span></td>
				</tr>
				<?php }?>
				<?php if(array_key_exists('twitter', $nf_util->sns_login_arr)) {?>
				<tr>
					<th>트위터 API Key</th>
					<td><input type="text" class="input20" name="twitter_key" value="<?php echo $nf_util->get_html($env['twitter_key']);?>"><span><a href="https://developer.twitter.com/apps" class="blue" target="_blank" >https://developer.twitter.com/apps</a> 에서 발급 받으신  API key 를 입력해 주세요. <a href="#none"  onclick="void(window.open('../pop/twitter_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="트위터 api 가이드"></a></span></td>
				</tr>
				<tr>
					<th>트위터 API Key Secret</th>
					<td><input type="text" class="input20" name="twitter_secret" value="<?php echo $nf_util->get_html($env['twitter_secret']);?>"><span><a href="https://developer.twitter.com/apps" class="blue" target="_blank" >https://developer.twitter.com/apps</a> 에서 발급 받으신 API key Secret 를 입력해 주세요. <a href="#none"  onclick="void(window.open('../pop/twitter_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="트위터 api 가이드"></a></span></td>
				</tr>
				<?php }?>
				<?php if(array_key_exists('kakao_talk', $nf_util->sns_login_arr)) {?>
				<tr>
					<th>카카오 API Key</th>
					<td><input type="text" class="input20" name="kakao" value="<?php echo $nf_util->get_html($env['kakao']);?>"><span><a href="https://developers.kakao.com/login" class="blue" target="_blank" >https://developers.kakao.com/login</a> 에서 키를 발급 받습니다.</span> <a href="#none"  onclick="void(window.open('../pop/kakao_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="카카오 api 가이드"></a></td>
				</tr>
				<?php }?>
				<?php if(array_key_exists('naver', $nf_util->sns_login_arr)) {?>
				<tr>
					<th>네이버 Client ID</th>
					<td>
						<ul class="MAB10">
							<li>redirect uri : <?php echo $env['naver_redirect_uri'];?></li>
						</ul>
						<input type="text" class="input20" name="naver_id" value="<?php echo $nf_util->get_html($env['naver_id']);?>"><span><a href="https://developers.naver.com/main/" class="blue" target="_blank" >https://developers.naver.com/main/</a> 에서 키를 발급 받습니다. <a href="#none"  onclick="void(window.open('../pop/naver_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="네이버 api 가이드"></a></span>
					</td>
				</tr>
				<tr>
					<th>네이버 Client Secret</th>
					<td><input type="text" class="input20" name="naver_secret" value="<?php echo $nf_util->get_html($env['naver_secret']);?>"><span><a href="https://developers.naver.com/main/" class="blue" target="_blank" >https://developers.naver.com/main/</a> 에서 키를 발급 받습니다. <a href="#none"  onclick="void(window.open('../pop/naver_guide.html','','width=815,height=900,resizable=no,scrollbars=yes'))"><img src="../../images/admini/guide_btn.png" alt="네이버 api 가이드"></a></span></td>
				</tr>
				<?php }?>
			</table>

			<table class="MAT10 bt">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>META 타이틀</th>
					<td>
						<input type="text" name="site_title" value="<?php echo $nf_util->get_html($env['site_title']);?>" needed hname="META 타이틀">
						<span>검색엔진에 노출될 사이트명(제호)</span>
					</td>
				</tr>
				<tr>
					<th>META 간단한 설명글</th>
					<td><textarea name="meta_description" cols="30" rows="5"><?php echo stripslashes($env['meta_description']);?></textarea> <span>검색엔진에 노출된 사이트설명글 (해당 사이트는 어떤 사이트라는것을 알려주는 설명글 80자이내로 작성)</span></td>
				</tr>
				<tr>
					<th>META 검색 키워드</th>
					<td><textarea name="meta_keywords" cols="30" rows="5"><?php echo stripslashes($env['meta_keywords']);?></textarea></td>
				</tr>
				<!-- <tr>
					<th>META 사이트 제작자</th>
					<td><input type="text" name="meta_author" value="<?php echo $nf_util->get_html($env['meta_author']);?>" > <span>HTML 상단 소스 <meta name="Author" content=""> 부분 (사이트 제작자를 나타냅니다.)</span></td>
				</tr> 
				<tr>
					<th>META 저작권 정보</th>
					<td><input type="text" name="meta_copyright" value="<?php echo $nf_util->get_html($env['meta_copyright']);?>"> <span>HTML 상단 소스 <meta name="Copyright" content=""> 부분 (사이트 저작권 정보를 나타냅니다.)</span></td>
				</tr>
				<tr>
					<th>META 사이트 분류</th>
					<td><input type="text" name="meta_classifiction" value="<?php echo $nf_util->get_html($env['meta_classifiction']);?>"> <span>HTML 상단 소스 <meta name="Classification" content=""> 부분 (검색엔진에서 사이트의 분류를 지정/파악 합니다.)</span></td>
				</tr>
				<tr>
					<th>META 퍼블리셔명</th>
					<td><input type="text" name="meta_publisher" value="<?php echo $nf_util->get_html($env['meta_publisher']);?>"> <span>HTML 상단 소스 <meta name="Publisher" content=""> 부분 (사이트 퍼블리셔명을 지정합니다.)</span></td>
				</tr> -->
				<tr>
					<th>Head 스크립트</th>
					<td>
						<textarea name="head_scripts" cols="30" rows="10"><?php echo stripslashes($env['head_scripts']);?></textarea>
						 <span>head 안에 들어갈 스크립트 넣으시면 됩니다. </span>
					</td>
				</tr>
				<tr>
					<th>아이피 차단 설정<span style="font-size:12px; color:#808080; margin-top:8px; display:block; font-size:normal;">입력시 쉼표(,)로 구분</span></th>
					<td><textarea name="intercept_ip" cols="30" rows="5"><?php echo stripslashes($env['intercept_ip']);?></textarea> <span>입력된 IP의 컴퓨터는 접근할 수 없음. 123.123.123.* 도 입력 가능.</span></td>
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