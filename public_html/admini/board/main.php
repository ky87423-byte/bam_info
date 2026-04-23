<?php
include "../../engine/_core.php";
$top_menu_code = "600102";
if($_GET['code']=='site_main') $top_menu_code = "600106";
include '../include/header.php';

$first_cate = $_GET['cate'];
if(!$first_cate) $first_cate = $nf_board->first_cate;
if(is_array($nf_board->board_main_rank[$first_cate])) {
	asort($nf_board->board_main_rank[$first_cate]);
	$board_length = count($nf_board->board_main_rank[$first_cate]);
}

if($board_length<=0) {
	die($nf_util->move_url(NFE_URL."/admini/board/index.php", "게시판을 추가해주시기 바랍니다."));
}
?>
<script type="text/javascript">
var move_cate = function(el) {
	location.href = "/admini/board/main.php?code=<?php echo $_GET['code'];?>&cate="+el.value;
}
</script>
<!-- 게시판메인설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="board_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
		<input type="hidden" name="mode" value="board_main_setting" />
		<input type="hidden" name="code" value="<?php echo $_GET['code'];?>" />
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<?php
					if(!$_GET['code']) {
					?>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide6-2','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<?php
					} else {?>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button type="button" class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide6-3','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
					<?php
					}?>
				</ul>
			</div>

			<?php
			if(!$_GET['code']) {
			?>
			<h6>커뮤니티설정/출력수</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>메인페이지 유무</th>
					<td>
						<label><input type="radio" name="use_main" value="1" checked>사용</label>
						<label><input type="radio" name="use_main" value="0" <?php echo (!$nf_board->main_row['use_main'])?'checked':'';?>>미사용</label>
					</td>
				</tr>
				<tr>
					<th>BEST OF BEST 출력설정</th>
					<td>
						<label><input type="radio" name="use_best" value="1" checked>사용</label>
						<label><input type="radio" name="use_best" value="0" <?php echo (!$nf_board->main_row['use_best'])?'checked':'';?>>미사용</label>
						<span> ( 금주의 베스트 출력갯수 <input type="text" name="use_best_count" value="<?php echo $nf_board->main_row['use_best_count'];?>" class="input5"> 개 )</span>
					</td>
				</tr>
				<tr>
					<th>최근게시물 출력형태</th>
					<td>
						<label><input type="radio" name="use_print" value="1" checked>1줄에 1개의 게시판 출력</label>
						<label><input type="radio" name="use_print" value="2" <?php echo ($nf_board->main_row['use_print']==='2')?'checked':'';?>>1줄에 2개의 게시판 출력</label>
					</td>
				</tr>
			</table>

			<h6>커뮤니티 메뉴 선택</h6>
			<table class="table4">
				<tr>
					<td>
			<?php
			}
			
			if($_GET['code']!='site_main') {
			?>
			<div class="category_wrap">
				<?php
				foreach($nf_board->board_menu[0] as $no=>$row) {
					$checked = $first_cate==$no ? 'checked' : '';
				?>
				<label> <input type="radio" name="cate" onClick="move_cate(this)" value="<?php echo $no;?>" <?php echo $checked;?> /> <?php echo $row['wr_name'];?></label>&nbsp;
				<?php
				}?>
			</div>
			<?php
			}?>			</td>
					</tr>
			</table>
			
			<h6>게시판메인설정</h6>
			<table class="table4">
				<colgroup>
					<col width="5%">
					<col width="">
					<col width="8%">
					<col width="8%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th>출력순서</th>
						<th>게시판명</th>
						<th>출력유무</th>
						<th>출력갯수</th>
						<!--<th>이미지사이즈</th>-->
						<th><a href="" onclick="window.open('../pop/board_skin_guide.html','window','width=450, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">출력형태<img src="../../images/ic/q.gif" alt="" class="MAL5" style="vertical-align:baseline;"></a></th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($board_length<=0) {
						case true:
					?>
					<tr><td colspan="7" class="no_list"></td></tr>
					<?php
						break;

						default:
							$print_k = $_GET['code']=='site_main' ? 'print_main' : 'print_board';
							$rank_k = $_GET['code']=='site_main' ? 'm_rank' : 'b_rank';

							$bo_arrays = $nf_board->board_brank_arr[$first_cate];
							if($_GET['code']=='site_main') $bo_arrays = $nf_board->board_mrank_arr;
							asort($bo_arrays);

							if(is_array($bo_arrays)) { foreach($bo_arrays as $bo_table=>$rank) {
								$row = $nf_board->board_table_arr[$bo_table];
								if($_GET['code']!='site_main' && $first_cate!=$row['pcode']) continue;
								$bo_print = $nf_board->main_row[$print_k.'_un'][$bo_table];
					?>
					<tr class="tac">
						<td><input type="text" name="bo_rank[<?php echo $bo_table;?>]" value="<?php echo $rank;?>"></td>
						<td class="tal"><?php echo $row['bo_subject'];?> (<?php echo $bo_table;?>)</td>
						<td><input type="checkbox" name="board[<?php echo $bo_table;?>][view]" value="1" <?php echo ($bo_print['view'])?'checked':'';?>></td>
						<td><input type="text" name="board[<?php echo $bo_table;?>][print_cnt]" value="<?php echo intval($bo_print['print_cnt']);?>"></td>
						<!--
						<td>
							<input type="text" class="input5" name="board[<?php echo $bo_table;?>][img_width]" value="<?php echo intval($bo_print['img_width']);?>"> X
							<input type="text" class="input5" name="board[<?php echo $bo_table;?>][img_height]" value="<?php echo intval($bo_print['img_height']);?>">
						</td>
						-->
						<td>
							<select name="board[<?php echo $bo_table;?>][print_type]">
								<?php
								foreach($nf_board->bo_type as $k=>$v) {
									if($k=='talk') continue;
									$selected = $bo_print['print_type']==$k ? 'selected' : '';
								?>
								<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
								<?php
								}?>
							</select>
						</td>
					</tr>
					<?php
							} }
						break;
					}
					?>
				</tbody>
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