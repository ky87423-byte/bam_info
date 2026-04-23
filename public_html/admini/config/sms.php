<?php
$top_menu_code = "200109";
include '../include/header.php';
?>
<script type="text/javascript">
var charge_frames = function(){
	var wr_api_id = $('#wr_api_id').val();
	var wr_api_key = $('#wr_api_key').val();
	if(!wr_api_id || wr_api_id==''){
		alert("[SMS ID] 를 입력해 주세요.");
		$('#wr_api_id').focus();
		return false;
	}
	if(!wr_api_key || wr_api_key==''){
		alert("[SMS KEY] 를 입력해 주세요.");
		$('#wr_api_key').focus();
		return false;
	}
	$('#smsUserPayFrame').attr('src',"https://netfu.co.kr/sms/smsUserPay_new.html?uid="+wr_api_id+"&passwd="+wr_api_key);
	$('#smsUserPay').toggle();
}
</script>
<!-- SMS환경설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="sms">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- SMS 문자 발송은 넷퓨의 SMS 모듈이 연동이 되어 있습니다.</li>
					<li>- SMS ID, SMS KEY 값은 <a href="https://netfu.co.kr/sms/" class="blue" target="_blank">넷퓨홈페이지 마이페이지 SMS관리</a>에서 확인 가능합니다.</li>
					<li>- SMS 콜수는 해당 화면 <span class="blue">잔여건수 옆에 SMS충전하기 버튼</span>을 통해서 또는 <a href="https://netfu.co.kr/service/sms.php" class="blue" target="_blank">[넷퓨 홈페이지] - 부가서비스 - SMS서비스신청</a> 메뉴를 통하여 충전 가능합니다.</li>
					<li>- 한번에 발송할 수 있는 문자 수는 1000건으로 제한됩니다.</li>
					<li>- 문자 사용시 하단에 SMS메세지 설정에서 설정된 내용들이 자동으로 상황에 맞추어 발송이 됩니다.</li>
					<li>- 문자 내용은 메세지 출력안내에 나온 내용을 참고하여 치환문자를 사용해서 문자 내용을 구성하실수 있습니다. </li>
					<li>- <b>SMS</b> : 최대 90Bytes 이내 (한글기준) 단문 메시지</li>
					<li>- <b>LMS</b> : 90Bytes 초과, 최대 2,000Bytes 이내 (한글기준) 장문 메시지</li>
				</ul>
			</div>

			<!-- SMS 설정 -->
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="sms_config_write" />
			<h6>SMS 설정</h6>
			<table>
				<colgroup>
					<col width="13%">
				</colgroup>
				<tbody>
					<tr>
						<th>SMS 사용유무</th>
						<td>
							<label><input type="radio" name="wr_use" value="1" checked>사용</label>
							<label><input type="radio" name="wr_use" value="0" <?php echo !$nf_sms->config['wr_use'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>LMS 사용유무</th>
						<td>
							<label><input type="radio" name="wr_lms_use" value="1" checked>사용</label>
							<label><input type="radio" name="wr_lms_use" value="0" <?php echo !$nf_sms->config['wr_lms_use'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>잔여건수</th>
						<td><?php echo $sms_count;?> 건 남음 <a onclick="charge_frames()"><button type="button"><strong>SMS</strong>충전하기</button></a></td>
					</tr>
					<tr>
						<th>SMS ID</th>
						<td>
							<input type="text" class="input20" name="wr_api_id" id="wr_api_id" hname="SMS 아이디" needed value="<?php echo $nf_util->get_html($nf_sms->config['wr_api_id']);?>">
							<span>넷퓨 홈페이지 회원 아이디를 입력해주십시오  </span>
						</td>
					</tr>
					<tr>
						<th>SMS KEY</th>
						<td>
							<input type="password" class="input20" name="wr_api_key" id="wr_api_key" hname="SMS KEY" needed value="<?php echo $nf_util->get_html($nf_sms->config['wr_api_key']);?>">
							<span>넷퓨 SMS 서비스키를 입력해주십시오  </span>
						</td>
					</tr>
					<tr id="smsUserPay" style="display:none;">
						<td class="pdlnb2" colspan="3">
							<iframe name="smsUserPay" id="smsUserPayFrame" style="width:100%; height:1000px;border:0px;text-align:center;" scrolling='no'></iframe>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="flex_btn">
				<button class="save_btn">저장하기</button>
			</div>
			</form>

			<!--메세지 출력 안내-->
			<h6>메세지 출력 안내</h6>
			<table class="table2">
				<colgroup>
					<col width="10%">
				</colgroup>
				<tbody>
					<tr>
						<th colspan="4">
							<ul>
								<li>※ SMS 최대 글자수는 90Byte이며, LMS 최대 글자수는 2,000Byte 입니다.</li>
								<li>※ <strong>LMS 사용시</strong> : 문자내용이 90byte를 초과하면 <span>LMS(장문)</span>로 발송 됩니다.</li>
								<li>※ <strong>LMS 미사용시</strong> : 문자내용이 90byte를 초과하면 <span>SMS(단문)로 90byte 까지</span>만 잘려서 발송 됩니다.</li>
								<li style="color:#ff8040; font-weight:bold;">※ 미리보기시 90Byte 이하여도 아래의 변수들이 대입되는 경우 90Byte 를 초과할수 있습니다</li>
								<li style="color:#ff8040; font-weight:bold;">90Byte가 초과되는 경우, LMS 사용시 LMS(장문) 로 발송되며, 미사용시 SMS(단문) 로 발송됩니다.</li>
							</ul>
						</th>	
					</tr>
					<tr>
						<th class="gray">{도메인}</th>
						<td>사이트의 도메인명이 출력됩니다<br>예)netfu.co.kr</td>
						<th class="gray">{사이트명}</th>
						<td>사이트명이 출력됩니다<br>예) 실시간예약 - 넷퓨</td>
					</tr>
					<tr>
						<th class="gray">{아이디}</th>
						<td>고객님의 아이디가 출력됩니다<br>예) netfu</td>
						<th class="gray">{닉네임}</th>
						<td>회원 닉네임이 출력됩니다.</td>
					</tr>
					<tr>
						<th class="gray">{날짜}</th>
						<td>오늘 날짜가 출력됩니다<br>예)1월 1일</td>
						<th class="gray">{이름}</th>
						<td>고객님의 이름이 출력됩니다<br>예)김넷퓨</td>
					</tr>
					<tr>
						<th class="gray">{계좌번호}</th>
						<td>온라인 입금시 고객님이 선택한 계좌번호가 출력됩니다<br>예) 123456-78-90123</td>
						<th class="gray">{은행명}</th>
						<td>온라인 입금시 고객님이 선택한 은행명이 출력됩니다<br>예) 국민은행, 우리은행, 농협 등</td>
					</tr>
					<tr>
						<th class="gray">{예금주}</th>
						<td>온라인 입금시 고객님이 선택한 예금주명이 출력됩니다<br>예) 홍길동</td>
						<th class="gray">{업체명}</th>
						<td>업체회원 업체명이 출력됩니다<br>예) 넷퓨</td>
					</tr>

				</tbody>
			</table>

			<!--SMS메세지 설정-->
			<form name="fwrite2" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="sms_msg_write" />
			<h6>SMS메세지 설정<span>SMS 발송기능 사용할 항목만 체크</span></h6>
			<div class="message_con">
				<?php
				if(is_array($nf_sms->sms_msg_array)) { foreach($nf_sms->sms_msg_array as $k=>$v) {
					$use_checked = ($v['wr_use']) ? 'checked' : '';
					$user_checked = ($v['wr_is_user']) ? 'checked' : '';
					$admin_checked = ($v['wr_is_admin']) ? 'checked' : '';
				?>
				<div class="message">
					<h1><label><input type="checkbox" name="sms_msg[<?php echo $v['no'];?>][msg_use]" value="1" <?php echo $use_checked;?>><?php echo $v['wr_title'];?></label></h1>
					<div>
						<div>
							<h2>회원SMS</h2>
							<p>
								<textarea id="sms_msg_<?php echo $v['no'];?>_msg_content" style="text-align:left; " align="left"  name="sms_msg[<?php echo $v['no'];?>][msg_content]" onkeyup="nf_util.length_counts(this, 2000)" onfocus="nf_util.length_counts(this, 2000)" hname="<?php echo $v['wr_title'];?> 회원SMS" needed ><?php echo $v['wr_content'];?></textarea>
							</p>
							<span><strong class="span_bytes">0</strong> Byte</span>
						</div>
						<p><label><input type="checkbox" name="sms_msg[<?php echo $v['no'];?>][msg_is_user]" value="1" <?php echo $user_checked;?>>회원에게 자동발송</label></p>
					</div>
					<div>
						<div>
							<h2>관리자SMS</h2>
							<p>
								<textarea id="sms_msg_<?php echo $v['no'];?>_wr_admin_content" name="sms_msg[<?php echo $v['no'];?>][wr_admin_content]" onkeyup="nf_util.length_counts(this, 2000)" onfocus="nf_util.length_counts(this, 2000)" hname="<?php echo $v['wr_title'];?> 관리자SMS" needed><?php echo $v['wr_admin_content'];?></textarea>
							</p>
							<span><strong class="span_bytes">0</strong> Byte</span>
						</div>
						<p><label><input type="checkbox" name="sms_msg[<?php echo $v['no'];?>][wr_is_admin]" value="1" <?php echo $admin_checked;?>>관리자에게 자동발송</label></p>
					</div>
					<script type="text/javascript">
						$("#sms_msg_<?php echo $v['no'];?>_msg_content").closest("div").find("strong").html(nf_util.length_count($("#sms_msg_<?php echo $v['no'];?>_msg_content")[0]));
						$("#sms_msg_<?php echo $v['no'];?>_wr_admin_content").closest("div").find("strong").html(nf_util.length_count($("#sms_msg_<?php echo $v['no'];?>_wr_admin_content")[0]));
					</script>
				</div>
				<?php
				} }
				?>
			</div>
			<!--message_con-->
			<div class="flex_btn">
				<button class="save_btn">저장하기</button>
			</div>
			</form>

		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->