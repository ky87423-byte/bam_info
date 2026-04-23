<?php
$_SERVER['__USE_API__'] = array("editor");
$_site_title_ = '이용후기';
include_once "../engine/_core.php";
if(!$_GET['no']) {
	if($_GET['qno']) $nf_member->check_login('company');
	else $nf_member->check_login('', '로그인 후 이용 후기를 작성하실 수 있습니다.');
} else {
	$nf_member->check_login('', '로그인 후 이용 후기를 보실 수 있습니다.');
}

if(!$env['use_shop_guide']) {
	die($nf_util->move_url($nf_util->page_back(), "이용후기 등록이 불가능합니다."));
}

include '../include/header_meta.php';
include '../include/header.php';

$no = $_GET['no'];
if($_GET['qno']) $no = $_GET['qno'];

$pno = $_GET['pno'];

$_where = "";
if($_GET['no']) $_where .= " and (`mno`=".intval($member['no'])." or `pmno`=".intval($member['no']).")";
if($_GET['qno']) $_where .= " and (`pmno`=".intval($member['no']).")";

$guide_row = $db->query_fetch("select * from nf_guide where `no`=".intval($no).$_where); // : 답변쓸경우에 원글정보 가져오기
if($guide_row) $pno = $guide_row['pno'];

$shop_row = $db->query_fetch("select * from nf_shop as ns where ns.`no`=".intval($pno).$nf_shop->shop_where);

if(!$shop_row) {
	die($nf_util->move_url($nf_util->page_back(), "업체정보가 없습니다."));
}
$shop_info = $nf_shop->shop_info($shop_row);
?>
<script type="text/javascript" src="<?php echo NFE_URL;?>/_helpers/_js/nf_member.class.js"></script>
<div class="m_title">
	<button class="back" onclick="history.back()"><i class="axi axi-keyboard-arrow-left"></i></button>이용후기<button class="forward" onclick="history.forward()"><i class="axi axi-keyboard-arrow-right"></i></button>
</div>
<section class="review_form  common_wrap">
	<div class="wrap1400 sub">
		<form name="fwrite" action="../include/regist.php" method="post" onSubmit="return validate(this)">
		<input type="hidden" name="mode" value="guide_write" />
		<?php if($_GET['no'] && $shop_row) {?>
		<input type="hidden" name="no" value="<?=$guide_row['no'];?>" />
		<?php } else {?>
		<input type="hidden" name="pno" value="<?=$shop_row['no'];?>" />
		<input type="hidden" name="qno" value="<?=$guide_row['no'];?>" />
		<input type="hidden" name="code" value="<?=$_GET['code'];?>" />
		<?php }?>
		<p class="s_title">이용후기</p>
		<div class="box_wrap">
			<ul class="help_box">
				<li>· 사이트 운영 정책에 위배되는 게시물을 등록하는 경우 사전 동의 없이 삭제됩니다.</li>
				<li>· 특정인 또는 특정 업체 비방성 게시물을 등록하는 경우 게시글 삭제 후 이용제한 처리됩니다.</li>
				<li>· 성인광고, 사이트 홍보글, 지적 재산권 침해 게시물을 등록하는 경우 게시글 삭제 후 이용제한 처리됩니다.</li>
			</ul>
			<p class="shop_name"><span>[<?php echo $shop_info['area_end'];?>]</span><?php echo $nf_util->get_text($shop_row['wr_company']);?></p>
			<?php
			if($guide_row && !$_GET['no']) {
			?>
			<div class="user_r"><!--사용자일때 노출-->
				<p class="review_title"><?php echo $nf_util->get_text($guide_row['subject']);?></p>
				<dl>
					<dt><?php echo $nf_util->get_text(/*$guide_row['name']*/ "익명");?></dt>
					<dd><?php echo $guide_row['rdate'];?></dd>
				</dl>
				<div><?php echo stripslashes($guide_row['content']);?></div>
			</div>
			<?php }?>
			<?php
			if(!$_GET['qno']) {
			?>
			<div class="table_wrap"><!--사용자일때 노출-->
				<table>
					<?php if($env['use_shop_point']) {?>
					<tr>
						<th><i class="axi axi-ion-android-checkmark"></i>평점</th>
						<td>
							<select name="point">
								<?php
								for($i=5; $i>=1; $i--) {
									$selected = $guide_row['point']==$i ? 'selected' : '';
								?>
								<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i;?>점</option>
								<?php
								}?>
							</select>
						</td>
					</tr>
					<?php
					}?>
					<tr>
						<th><i class="axi axi-ion-android-checkmark"></i>제목</th>
						<td>
							<input type="text" name="subject" value="<?php echo $nf_util->get_html($guide_row['subject']);?>" hname="제목" needed>
						</td>
					</tr>
				</table>
			</div>
			<?php }?>
			<div class="ed">
				<?php
				$_content = $guide_row['content'];
				if($_GET['qno']) $_content = $guide_row['a_content'];
				?>
				<textarea type="editor" name="content" hname="내용" needed style="width:100%;height:300px;"><?php echo stripslashes($_content);?></textarea>
			</div>
			<div class="next_btn">
				<?php if($_GET['qno']) {?>
				<button type="submit" class="base"><?php echo $guide_row['answer'] ? '답변수정하기' : '답변하기';?></button>
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