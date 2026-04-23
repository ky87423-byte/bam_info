<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = "400302";
include '../include/header.php';

if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
	$nf_banner->banner_lists['main_category']['main_category_'.$k] = array('name' => $v['wr_name'], 'width' => '1400', 'height' => '&' );
	$nf_banner->banner_lists['sub_category']['sub_category_'.$k] = array('name' => $v['wr_name'], 'width' => '1400', 'height' => '&' );
} }
?>
<script type="text/javascript">
var save_rank = function() {
	var form = document.forms['flist'];
	var para = $(form).serialize();
	$.post("../regist.php", para+"&ajax=1&mode=rank_save_banner", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var save_rank_group = function() {
	var form = document.forms['flist'];
	var para = $(form).serialize();
	$.post("../regist.php", para+"&ajax=1&mode=rank_group_save_banner", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var save_etc = function(el, no) {
	var g_rank = $(el).closest("tr").find("[name='g_rank[]']").val();
	var roll_type = $(el).closest("tr").find("[name='roll_type[]']").val();
	var roll_direction = $(el).closest("tr").find("[name='roll_direction[]']").val();
	var roll_time = $(el).closest("tr").find("[name='roll_time[]']").val();
	var roll_turn = $(el).closest("tr").find("[name='roll_turn[]']").val();

	var para = "g_rank="+g_rank+"&roll_type="+roll_type+"&roll_direction="+roll_direction+"&roll_time="+roll_time+"&roll_turn="+roll_turn;

	$.post("../regist.php", para+"&mode=save_etc_banner&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var save_use = function(el, no) {
	var val = el.checked ? 1 : 0;
	$.post("../regist.php", "mode=save_use_banner&val="+val+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var save_target = function(el, no) {
	var val = el.checked ? '_blank' : '';
	$.post("../regist.php", "mode=save_target_banner&val="+val+"&no="+no, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
	});
}

var open_write = function(el, no) {
	var form = document.forms['fwrite'];
	var offset = $(el).offset();
	$.post("../regist.php", "mode=modify_banner_open&no="+no, function(data){
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
					<li>- 배너의 위치는 <b class="blue">위치안내</b> 버튼을 클릭해 참고하세요.</li>
					<li>- 배너 등록 방법은 오른쪽 메뉴얼을 참고하세요.<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide4-5','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>

			<div class="ass_list">
				<table>
					<colgroup>
						<col width="12%">
					</colgroup>
					<?php
					$count = 0;
					$title_cnt = 0;
					if(is_array($nf_banner->banner_title)) { foreach($nf_banner->banner_title as $k=>$v) {
					?>
					<tr>
						<th>
							<button class="blue" onclick="window.open('./banner_navi.php#banner<?=$title_cnt+1;?>','window','width=990, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">위치안내</button><?php echo $v;?>
						</th>
						<td>
							<ul>
								<?php
								if(is_array($nf_banner->banner_lists[$k])) { foreach($nf_banner->banner_lists[$k] as $k2=>$v2) {
									if($count===0 && !$_GET['position']) $_GET['position'] = $k2;
									$on = $_GET['position']==$k2 ? 'on' : '';
								?>
								<li class="<?php echo $on;?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?position=<?php echo $k2;?>"><?php echo $v2['name'];?></a></li>
								<?php
									$count++;
								} }?>
							</ul>	
						</td>
					</tr>
					<?php
						$title_cnt++;
					} }
					?>
				</table>
			</div>

			<?php
			$query = $db->_query("select * from nf_banner where `wr_position`=? order by `wr_g_rank` asc, `wr_rank` asc", array($_GET['position']));
			$length = $db->num_rows($query);
			?>
			
			<form name="flist" method="post">
			<h6>메인 > A영역 배너관리<span>총 <b><?php echo number_format(intval($length));?></b>개의 배너가 등록되었습니다.</span>
				<!--<p class="h6_right">
					<button class="s_basebtn3 green" onclick="void(window.open('../../admini/pop/guide_banner.html','','width=989,height=900,resizable=no,scrollbars=yes'))">배너등록가이드</button>
				</p>-->
			</h6>
			<div class="table_top_btn">
				<button type="button" class="gray" onClick="nf_util.all_check('#check_all', '.chk_')"><strong>A</strong> 전체선택</button>
				<button type="button" class="gray" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_banner" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
				<?php
				/*
				<button type="button" class="gray"><strong class="blue">O</strong> 사용</button>
				<button type="button" class="gray"><strong>X</strong> 미사용</button>
				*/?>
				<button type="button" class="gray" onClick="save_rank()"><strong>R</strong> 순서저장</button>
				<button type="button" class="gray" onClick="save_rank_group()"><strong>R</strong> 그룹순서저장</button>
				<button type="button" onClick="open_write(this)" class="blue"><strong>+</strong> 배너등록</button>
			</div>

			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="4%">
					<col width="8%">
					<col width="4%">
					<col>
					<col width="8%">
					<col width="7%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" id="check_all" onClick="nf_util.all_check(this, '.chk_')"></th>
						<th>배너설정</th>
						<th>그룹명</th>
						<th>순서</th>
						<th>배너정보</th>
						<th>등록일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
				<?php
				switch($length<=0) {
					case true:
				?>
					<td colspan="6" class="no_list"></td>
				<?php
					break;


					default:
						$group_name = "";
						$group_count = 0;
						$same_group = false;
						while($row=$db->afetch($query)) {
							if($group_name!=$row['wr_g_name']) {
								$tr_class = 'group_'.$count;
								$group_count++;
								$same_group = false;
							} else {
								$same_group = true;
							}
							$rowspan = $nf_banner->get_group['length'][$_GET['position']][$row['wr_g_name']];
							$group_name = $row['wr_g_name'];

							$get_banner = $nf_banner->get_banner($row);
				?>
					<tr class="tac <?php echo $tr_class;?>">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $row['no'];?>" class="check_all"><input type="hidden" name="hidd[]" value="<?php echo $row['no'];?>" /></td>
						<?php if(!$same_group) {?>
						<td rowspan="<?php echo $rowspan;?>">
							그룹순서<br/>
							<input type="text" name="g_rank[]" value="<?php echo $row['wr_g_rank'];?>" style="width:30px;" />
							<input type="hidden" name="g_hidd[]" value="<?php echo addslashes($row['wr_position'].'@/@'.$row['wr_g_name']);?>" />
							<?php if($rowspan>1) {?>
							<div style="margin-top:20px;">
								<select name="roll_type[]">
									<?php
									if(is_array($nf_banner->roll_info['roll_type'])) { foreach($nf_banner->roll_info['roll_type'] as $k=>$v) {
										$selected = $row['wr_roll_type']==$k ? 'selected' : '';
									?>
									<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
									<?php
									} }?>
								</select>
							</div>
							<div>
								<select name="roll_direction[]">
									<?php
									if(is_array($nf_banner->roll_info['roll_direction'])) { foreach($nf_banner->roll_info['roll_direction'] as $k=>$v) {
										$selected = $row['wr_roll_direction']==$k ? 'selected' : '';
									?>
									<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
									<?php
									} }?>
								</select>
							</div>
							<div>
								<input type="text" name="roll_time[]" value="<?php echo intval($row['wr_roll_time']>0 ? $row['wr_roll_time'] : 3);?>" style="width:30px">초
							</div>
							<div>
								<select name="roll_turn[]">
									<?php
									if(is_array($nf_banner->roll_info['roll_turn'])) { foreach($nf_banner->roll_info['roll_turn'] as $k=>$v) {
										$selected = $row['wr_roll_turn']==$k ? 'selected' : '';
									?>
									<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
									<?php
									} }?>
								</select>
							</div>
							<div class="blue" style="font-size:11px; line-height:12px">
								고정배너는<br>새로고침시<br>변경 됩니다.
							</div>
							<div>
								<button type="button" class="blue" onClick="save_etc(this, '<?php echo $row['no'];?>')">저장</button>
							</div>
							<?php }?>
						</td>
						<td rowspan="<?php echo $rowspan;?>">
							<?php echo $nf_util->get_text($row['wr_g_name']);?>
						</td>
						<?php }?>

						<td>
							<input type="text" name="rank[]" value="<?php echo $row['wr_rank'];?>" style="width:30px;" />
						</td>
						<td class="tal" valign="top" style="vertical-align:top;">
							<div style="margin-bottom:10px;">
								<label><input type="checkbox" onClick="save_use(this, '<?php echo $row['no'];?>')" <?php echo $row['wr_view'] ? 'checked' : '';?> />사용</label>
								<label><input type="checkbox" onClick="save_target(this, '<?php echo $row['no'];?>')" <?php echo $row['wr_target']=='_blank' ? 'checked' : '';?> />새창</label>
							</div>
							<div class="table_inbox">
								<p>
									<span>등록사이즈 : <?php echo number_format(intval($row['wr_width']));?>px x <?php echo number_format(intval($row['wr_height']));?>px</span>
									<span>URL : <?php echo $nf_util->get_http($row['wr_url']);?> </span>
								</p>
							</div>
							<?php
							echo $get_banner['content'];
							?>
						</td>
						<td><?php echo substr($row['wr_wdate'], 0, 10);?></td>
						<td class="tac">
							<button type="button" class="gray common" onclick="open_write(this, <?php echo intval($row['no']);?>)"><i class="axi axi-plus2"></i> 수정하기</button>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $row['no'];?>" mode="delete_banner" url="../regist.php"><i class="axi axi-minus2"></i> 삭제하기</button>
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
				<button type="button" class="gray" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_banner" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
				<?php
				/*
				<button type="button" class="gray"><strong class="blue">O</strong> 사용</button>
				<button type="button" class="gray"><strong>X</strong> 미사용</button>
				<button type="button" class="gray"><img src="../../images/ic/pop.gif" alt="새창"> 새창</button>
				<button type="button" class="gray"><img src="../../images/ic/pop.gif" alt="본창"> 본창</button>
				*/
				?>
				<button type="button" class="gray" onClick="save_rank()"><strong>R</strong> 순서저장</button>
				<button type="button" class="gray" onClick="save_rank_group()"><strong>R</strong> 그룹순서저장</button>
				<button type="button" onClick="open_write(this)" class="blue"><strong>+</strong> 배너등록</button>
			</div>
			</form>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php
// : 배너등록폼
include NFE_PATH.'/admini/include/banner.inc.php';
?>

<script>
$( "#draggable" ).draggable();
</script>

<?php include '../include/footer.php'; ?> <!--관리자 footer-->