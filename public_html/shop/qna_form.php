<?php
$_SERVER['__USE_API__'] = array("editor");
$_site_title_ = 'QnA';
include_once "../engine/_core.php";
if(!$_GET['no']) {
	if($_GET['qno']) $nf_member->check_login('company');
} else {
	$qna_row = $db->query_fetch("select * from nf_qna as nq where nq.`no`=".intval($_GET['no']));
	$allow = false;
	if($member['no'] && $member['no']==$qna_row['mno']) $allow = true;
	if($_COOKIE['shop_qna_'.$qna_row['no']]) $allow = true;
	if(!$allow) $nf_member->check_login();
}

if(!$env['use_shop_qna']) {
	die($nf_util->move_url($nf_util->page_back(), "Q&A 등록이 불가능합니다."));
}

include '../include/header_meta.php';
include '../include/header.php';

$no = $_GET['no'];
if($_GET['qno']) $no = $_GET['qno'];

$pno = $_GET['pno'];

$_where = "";
if($_GET['no']) $_where .= " and (`mno`=".intval($member['no'])." or `pmno`=".intval($member['no']).")";
if($_GET['qno']) $_where .= " and (`pmno`=".intval($member['no']).")";

$qna_row = $db->query_fetch("select * from nf_qna where `no`=".intval($no).$_where); // : 답변쓸경우에 원글정보 가져오기
if(!$member['no']) $qna_row = $db->query_fetch("select * from nf_qna as nq where nq.`no`=".intval($_GET['no']));
if($qna_row) $pno = $qna_row['pno'];

$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`=".intval($pno).$nf_shop->shop_where);

if(!$shop_row) {
	die($nf_util->move_url($nf_util->page_back(), "업체정보가 없습니다."));
}
$shop_info = $nf_shop->shop_info($shop_row);


$get_name = $qna_row['no'] ? $qna_row['name'] : $member['mb_name'];
$phone_arr = $qna_row['no'] ? explode("-", $qna_row['phone']) : explode("-", $member['mb_hphone']);
$email_arr = $qna_row['no'] ? explode("@", $qna_row['email']) : explode("@", $member['mb_email']);
?>
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_member.class.js"></script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>Q & A
<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="review_form  common_wrap register ">
	<div class="wrap1400 sub">
		<form name="fwrite" action="../include/regist.php" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="qna_write" />
		<?php if($_GET['no'] && $shop_row) {?>
		<input type="hidden" name="no" value="<?=$qna_row['no'];?>" />
		<?php } else {?>
		<input type="hidden" name="pno" value="<?=$shop_row['no'];?>" />
		<input type="hidden" name="qno" value="<?=$qna_row['no'];?>" />
		<input type="hidden" name="code" value="<?=$_GET['code'];?>" />
		<?php }?>
		<?php
		if(!$member['no']) include NFE_PATH.'/include/etc/google_recaptcha3.inc.php';
		?>
		<p class="s_title">Q&A</p>
		<div class="box_wrap">
			<ul class="help_box">
				<li>· 사이트 운영 정책에 위배되는 게시물을 등록하는 경우 사전 동의 없이 삭제됩니다.</li>
				<li>· 특정인 또는 특정 업체 비방성 게시물을 등록하는 경우 게시글 삭제 후 이용제한 처리됩니다.</li>
				<li>· 성인광고, 사이트 홍보글, 지적 재산권 침해 게시물을 등록하는 경우 게시글 삭제 후 이용제한 처리됩니다.</li>
			</ul>
			<?php if(!$member['no']) {?>
			<div class="terms MBT30">
				<h2 class="MAT0">개인정보수집이용안내</h2>
				<div class="terms_box">
					<p><?php echo stripslashes($env['content_privacy_info']);?></p>
				</div>
				<p>
					<label for="indiagree" class="checkstyle1"><input type="checkbox" name="indiagree" message="개인정보수집 및 이용안내에 동의해주시기 바랍니다." needed id="indiagree">개인정보 수집 및 이용안내에 동의합니다.</label>
				</p>
			</div>
			<?php }?>
			<p class="shop_name"><span>[<?php echo $shop_info['area_end'];?>]</span><?php echo $nf_util->get_text($shop_row['wr_company']);?></p>
			<?php
			if($qna_row && !$_GET['no']) {
			?>
			<div class="user_r"><!--사용자일때 노출-->
				<p class="review_title"><?php echo $nf_util->get_text($qna_row['subject']);?></p>
				<dl>
					<dt><?php echo $nf_util->get_text($qna_row['name']);?></dt>
					<dd><?php echo $qna_row['rdate'];?></dd>
				</dl>
				<div><?php echo stripslashes($qna_row['content']);?></div>
			</div>
			<?php }?>
			<?php
			if(!$_GET['qno']) {
			?>
			<div class="table_wrap"><!--사용자일때 노출-->
				<table>
					<?php
					if(!$member['no']) {
					?>
					<tr>
						<th><i class="axi axi-ion-android-checkmark"></i>이름</th>
						<td><input type="text" name="wr_name" hname="작성자" needed value="<?php echo $nf_util->get_html($get_name);?>"></td>
					</tr>
					<tr>
						<th><i class="axi axi-ion-android-checkmark"></i>비밀번호</th>
						<td><input type="password" name="wr_password" hname="비밀번호" needed></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td class="size1">
							<input type="text" name="wr_phone[]" hname="연락처"  value="<?php echo $nf_util->get_html($phone_arr[0]);?>"> -
							<input type="text" name="wr_phone[]" hname="연락처"  value="<?php echo $nf_util->get_html($phone_arr[1]);?>"> -
							<input type="text" name="wr_phone[]" hname="연락처" value="<?php echo $nf_util->get_html($phone_arr[2]);?>">
						</td>
					</tr>
					<tr>
						<th>이메일</th>
						<td class="email">
							<input type="text" name="wr_email[]" value="<?php echo $email_arr[0];?>" hname="이메일" > @
							<input type="text" name="wr_email[]" value="<?php echo $email_arr[1];?>" hname="이메일"  id="wr_email_" >
							<select onChange="nf_util.ch_value(this, '#wr_email_')">
								<option value="">직접입력</option>
								<?php
								if(is_array($cate_p_array['email'][0])) { foreach($cate_p_array['email'][0] as $k=>$v) {
								?>								
								<option value="<?php echo $nf_util->get_html($v['wr_name']);?>"><?php echo $v['wr_name'];?></option>
								<?php
								} }
								?>
							</select>
						</td>
					</tr>
					<?php
					}?>
					<tr>
						<th><i class="axi axi-ion-android-checkmark"></i>제목</th>
						<td class="secret"><input type="text" class="long100" name="subject" value="<?php echo $nf_util->get_html($qna_row['subject']);?>" hname="제목" needed><em><input type="checkbox" name="wr_secret" value="1" <?php echo $qna_row['secret'] ? 'checked' : '';?>>비밀글</em></td>
					</tr>
				</table>
			</div>
			<?php }?>
			<div class="ed">
				<?php
				$_content = $qna_row['content'];
				if($_GET['qno']) $_content = $qna_row['a_content'];
				?>
				<textarea type="editor" name="content" hname="내용" needed style="width:100%;height:300px;"><?php echo stripslashes($_content);?></textarea>
			</div>
			<table class="style1 MAT20">
						<?php
							include NFE_PATH.'/include/kcaptcha.php';
						?>
			</table>
			<div class="next_btn">
				<?php if($_GET['qno']) {?>
				<button type="submit" class="base"><?php echo $qna_row['answer'] ? '답변수정하기' : '답변하기';?></button>
				<?php } else {?>
				<button type="submit" class="base"><?php echo $_GET['no'] ? '수정하기' : '등록하기';?></button>
				<?php }?>
				<button type="button" onClick="history.back()" class="base darkbluebtn">취소</button>
			</div>
		</div>
		</form>
	</div>
</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>