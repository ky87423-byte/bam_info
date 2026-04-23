<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = "400203";
include '../include/header.php';

$icon_query = $db->_query("select * from nf_icon where `wr_code`=? order by `wr_rank` asc", array('main_icon'));
$icon_num = $db->num_rows($icon_query);
?>
<script type="text/javascript">
var save_rank = function() {
	var form = document.forms['flist'];
	form.mode.value = "icon_rank_change";
	nf_util.ajax_submit(form);
	return false;
}

var click_use = function(el, no) {
	var form = document.forms['flist'];
	var val = el.checked ? 1 : 0;
	$.post("../regist.php", "mode=icon_use&no="+no+"&val="+val, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<!-- 배너관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="banner_manage">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>					
					<li>- 중앙 아이콘은 최대 8개까지 선택이 가능합니다. .<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide4-4','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="main_icon_type_save" />
			<div class="ass_list">
				<table>
					<colgroup>
						<col width="10%">
					</colgroup>
					<!-- 미사용시 메인 div class="main_quick" 영역 전체삭제-->
					<tr>
						<th>아이콘 사용유무</th>
						<td>
							<label><input type="radio" name="main_icon_type" value="1" checked>사용</label>
							<label><input type="radio" name="main_icon_type" value="0" <?php echo empty($env['main_icon_type']) ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
				</table>
			</div>
			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
			</form>
			
			<form name="flist" action="../regist.php" method="post">
			<input type="hidden" name="mode" value="" />
			<h6>메인 중앙 아이콘 관리<span>총 <b><?php echo number_format(intval($length));?></b>개의 아이콘이 등록되었습니다.</span>
	
			</h6>
			<div class="table_top_btn">
				<button type="button" class="gray" onClick="nf_util.all_check('#check_all', '.chk_')"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_icon" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
	
				<button type="button" class="gray" onClick="save_rank()"><strong>R</strong> 순서저장</button>
				<button type="button" onClick="open_icon(this)" class="blue"><strong>+</strong> 아이콘등록</button>
			</div>

			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="6%">
					<col width="4%">
					<col>
					<col>
					<col>
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" id="check_all" onClick="nf_util.all_check(this, '.chk_')"></th>
						<th>출력순서</th>
						<th>사용</th>
						<th>아이콘 이름</th>
						<th>아이콘 이미지</th>
						<th>링크 연결주소</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($icon_num<=0) {
						case true:
					?>
					<tr><td colspan="7" class="no_list"></td></tr>
					<?php
						break;

						default:
							while($icon_row=$db->afetch($icon_query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $icon_row['wr_id'];?>" class="check_all"><input type="hidden" name="hidd[]" value="<?php echo intval($icon_row['wr_id']);?>" /></td>
						<td><input type="text" name="rank[]" value="<?php echo $icon_row['wr_rank'];?>" style="width:30px;" /></td>
						<td valign="top"><input type="checkbox" onClick="click_use(this, <?php echo intval($icon_row['wr_id']);?>)" <?php echo $icon_row['wr_use'] ? 'checked' : '';?>></td>
						<td valign="top" class="name--"><?php echo $nf_util->get_text($icon_row['wr_name']);?></td>
						<td valign="top"><img src="<?php echo NFE_URL.$nf_shop->attach_dir['icon'].$icon_row['wr_image'];?>" width="90" height="90" /></td>
						<td class="link--"><?php echo $icon_row['wr_link'];?></td>
						<td class="tac">
							<button type="button" class="gray common" onclick="open_icon(this, <?php echo intval($icon_row['wr_id']);?>)"><i class="axi axi-plus2"></i> 수정하기</button>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $icon_row['wr_id'];?>" mode="delete_icon" url="../regist.php"><i class="axi axi-minus2"></i> 삭제하기</button>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
			</table>

			<div class="table_top_btn bbn">
				<button type="button" class="gray" onClick="nf_util.all_check('#check_all', '.chk_')"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_icon" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>

				<button type="button" class="gray" onClick="save_rank()"><strong>R</strong> 순서저장</button>
				<button type="button" onClick="open_icon(this)" class="blue"><strong>+</strong> 아이콘등록</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->
<?php
		include NFE_PATH.'/admini/include/main_icon.inc.php'; // : 메인 중앙 아이콘 등록 레이아웃
		?>

<?php include '../include/footer.php'; ?> <!--관리자 footer-->