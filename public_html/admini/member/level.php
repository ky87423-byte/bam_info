<?php
$top_menu_code = '300201';
include '../include/header.php';
?>
<script type="text/javascript">
var click_view = function(el) {
	var txt = el.checked ? 1 : "";
	$(el).closest("tr").find("[name='view[]']").val(txt);
};

var delete_level = function(el, sun) {
	if(confirm("삭제하시겠습니까?")) {
		$.post("../regist.php", "mode=delete_member_level&sun="+sun, function(data){
			data = $.parseJSON(data);
			if(data.msg) alert(data.msg);
			if(data.move) location.href = data.move;
			if(data.js) eval(data.js);
		});
	}
}
</script>
<!-- 회원등급/포인트설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="../regist.php" method="post" method="post" enctype="multipart/form-data" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="member_level_update" />
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide3-3','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
			
			<h6>회원등급/포인트설정</h6>
			<div class="table_top_btn">
				<button type="button" class="gray"><strong>O</strong> 일괄수정</button>
				<button type="button" class="gray"><strong class="blue">O</strong> 등급추가</button>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="3%">
					<col width="10%">
					<col width="3%">
					<col width="">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th>출력</th>
						<th>레벨</th>
						<th>회원등급명</th>
						<th colspan="2">등급설정</th>
						<th>삭제</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="tac"><input type="checkbox" disabled value=""><input type="hidden" name="view[]" value="1" /></td>
						<td class="tac">1<input type="hidden" name="rank[]" value="first" /></td>
						<td><input type="text" name="name[]" value="<?php echo $nf_util->get_html($env['member_level_arr'][0]['name']);?>"></td>
						<td><img src="<?php echo NFE_URL.$nf_member->attach['member_level'].$env['member_level_arr'][0]['icon'];?>" /></td>
						<td>
							활동포인트 0 <input type="hidden" name="point[]" value="0"> <img src="../../images/admini/point.gif" alt=""> 이상일때
							<input type="file" name="img[]" class="input20">
						</td>
						<td class="tac"></td>
					</tr>
				</tbody>
				<tbody class="paste-body-">
					<?php
					$length = count($env['member_level_arr']);
					if($length<=0) $length = 2;
					for($i=1; $i<$length; $i++) {
						$member_level_arr = $env['member_level_arr'][$i];
					?>
					<tr>
						<td class="tac"><input type="checkbox" onClick="click_view(this)" <?php echo $member_level_arr['view'] ? 'checked' : '';?>><input type="hidden" name="view[]" value="<?php echo $member_level_arr['view'] ? 1 : '';?>" /></td>
						<td class="tac"><input type="text" name="rank[]" value="<?php echo $i+1;?>"></td>
						<td><input type="text" name="name[]" value="<?php echo $nf_util->get_html($member_level_arr['name']);?>"></td>
						<td><img src="<?php echo NFE_URL.$nf_member->attach['member_level'].$member_level_arr['icon'];?>" /></td>
						<td>
							활동포인트 <input type="text" name="point[]" value="<?php echo intval($member_level_arr['point']);?>" class="input10">
							<img src="../../images/admini/point.gif" alt=""> 이상일때
							<span><input type="file" name="img[]" class="input20"></span>
						</td>
						<td class="tac"><button type="button" class="gray common" onClick="delete_level(this, '<?php echo $i;?>')"><strong>-</strong> 삭제</button></td>
					</tr>
					<?php
					}?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="submit" class="gray"><strong>O</strong> 일괄수정</button>
				<button type="button" class="gray" onClick="nf_util.clone_paste2('.paste-body-', 'tr')"><strong class="blue">O</strong> 등급추가</button>
			</div>
			</form>

			<form name="fwrite" action="../regist.php" method="post" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="member_point_config_update" />
			<h6>기본포인트설정</h6>
			<table>	
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>회원가입시 등급</th>
					<td>
						<select name="register_level">
							<?php
							if(is_array($env['member_level_arr'])) { foreach($env['member_level_arr'] as $k=>$v) {
								if($k<=0) continue;
								$selected = $env['member_point_arr']['register_level']==$k ? 'selected' : '';
							?>
							<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v['name'];?> (<?php echo $k+1;?>레벨)</option>
							<?php
							} }
							?>
						</select>
						<span>* 회원가입시 자동으로 부여될 회원등급을 설정합니다.</span>
					</td>
				</tr>
				<tr>
					<th>포인트 사용유무</th>
					<td>
						<label><input type="radio" name="use_point" value="1" checked>사용</label>
						<label><input type="radio" name="use_point" value="0" <?php echo !$env['member_point_arr']['use_point'] ? 'checked' : '';?>>미사용</label>
						<span>* 전체 포인트 기능을 사용할지 설정합니다.</span>
					</td>
				</tr>
				<tr>
					<th>회원등급자동설정</th>
					<td>
						<label><input type="radio" name="auto_level" value="1" checked>사용</label>
						<label><input type="radio" name="auto_level" value="0" <?php echo !$env['member_point_arr']['auto_level'] ? 'checked' : '';?>>미사용</label>
						<span>* 포인트 지급시 설정된 포인트에 따라 자동으로 회원등급이 변경 됩니다.</span>
					</td>
				</tr>
				<tr>
					<th>회원 가입시 지급 포인트</th>
					<td><input type="text" name="register_point" value="<?php echo intval($env['member_point_arr']['register_point']);?>" class="input10">포인트 <span>* 회원 가입 시 한번만 부여, 마이너스(-) 도 가능합니다.</span></td>
				</tr>
				<tr>
					<th>로그인시 지급 포인트</th>
					<td><input type="text" name="login_point" value="<?php echo intval($env['member_point_arr']['login_point']);?>" class="input10">포인트 <span>* 회원 로그인 시 하루에 한번만 부여, 마이너스(-) 도 가능합니다.</span></td>
				</tr>
				<tr>
					<th>출석체크 지급 포인트</th>
					<td><input type="text" name="chulsuk_point" value="<?php echo intval($env['member_point_arr']['chulsuk_point']);?>" class="input10">포인트 <span>* 회원 출석 시 하루에 한번만 부여, 마이너스(-) 도 가능합니다.</span></td>
				</tr>
				<tr>
					<th>업체 유료결제 포인트</th>
					<td><input type="text" name="company_point_percent" value="<?php echo intval($env['member_point_arr']['company_point_percent']);?>" class="input10">% 포인트 <span>* 상품 유료 결제등록 금액 대비 %로 포인트를 지급합니다.</span></td>
				</tr>
				<!-- <tr>
					<th>개인 유료결제 포인트</th>
					<td><input type="text" name="individual_point_percent" value="<?php //echo intval($env['member_point_arr']['individual_point_percent']);?>" class="input10">% 포인트 <span>* 이력서 유료 결제등록 금액 대비 %로 포인트를 지급합니다.</span></td>
				</tr> -->
			</table>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
		</div>
		</form>
		
		
		<!--//payconfig conbox-->

		

		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->