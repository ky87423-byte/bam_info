<?php
$top_menu_code = "400303";
include '../include/header.php';

$query = $db->_query("select * from nf_popup order by `rank` asc");
$length = $db->num_rows($query);
?>
<!-- 팝업관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 사이트접속시 열리는 첫화면 즉, 메인페이지에서 설정하신 팝업이 뜨게 됩니다.</li>
					<li>- 팝업기능을 사용하지 않으려면 전체선택후 '미출력'버튼을 클릭하시면 됩니다.</li>
					<li>- 출력할 팝업이 여러개라면 팝업순서에 따라 팝업의 출력 순서가 바뀝니다.</li>
				</ul>
			</div>

			
			<h6>팝업관리<span>총 <b><?php echo number_format(intval($length));?></b>개의 팝업이 등록되었습니다.</span>
				<p class="h6_right">
				</p>
			</h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_popup" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" hname="순서" mode="rank_update_popup" url="../regist.php" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
				<a href="<?php echo NFE_URL;?>/admini/design/popup_insert.php"><button type="button" class="blue"><strong>+</strong> 팝업등록</button></a>
			</div>
			<form name="flist" method="post">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="4%">
					<col width="6%">
					<col width="6%">
					<col width="4%">
					<col width="%">
					<col width="16%">
					<col width="6%">
					<col width="4%">
					<col width="4%">
					<col width="6%">
					<col width="6%">
					<col width="6%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onClick="nf_util.all_check(this, '.chk_')"></th>
						<th>출력</th>
						<th>서브출력</th>
						<th>제목출력</th>
						<th>순서</th>
						<th>팝업제목</th>
						<th>출력기간</th>
						<th>팝업크기</th>
						<th>Top</th>
						<th>Left</th>
						<th>등록일</th>
						<th>수정</th>
						<th>삭제</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($length<=0) {
						case true:
					?>
					<tr>
						<td class="no_list not_list-" colspan="14"></td>
					</tr>
					<?php
						break;


						default:
							while($popup_row=$db->afetch($query)) {
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $popup_row['no'];?>"></td>
						<td><input type="checkbox" value="<?php echo $popup_row['no'];?>" <?php echo $popup_row['popup_view'] ? 'checked' : '';?> onclick="nf_util.ajax_post(this)" no="<?php echo $popup_row['no'];?>" mode="popup_view" url="../regist.php"></td>
						<td><input type="checkbox" value="<?php echo $popup_row['no'];?>" <?php echo $popup_row['popup_sub_view'] ? 'checked' : '';?> onclick="nf_util.ajax_post(this)" no="<?php echo $popup_row['no'];?>" mode="popup_sub_view" url="../regist.php"></td>
						<td><input type="checkbox" value="<?php echo $popup_row['no'];?>" <?php echo $popup_row['popup_title_view'] ? 'checked' : '';?> onclick="nf_util.ajax_post(this)" no="<?php echo $popup_row['no'];?>" mode="popup_title_view" url="../regist.php"></td>
						<td class="sequence">
							<input type="text" name="rank[]" value="<?php echo $popup_row['rank'];?>" style="width:30px;" /><input type="hidden" name="hidd[]" value="<?php echo $popup_row['no'];?>" style="width:30px;" />
						</td>
						<td align="left"><?php echo $popup_row['popup_title'];?></td>
						<td>
							<?php
							$popup_date = array();
							if($popup_row['popup_begin']>=today) $popup_date[] = date("Y-m-d H시", strtotime($popup_row['popup_begin'])).'부터';
							if($popup_row['popup_end']>=today) $popup_date[] = date("Y-m-d H시", strtotime($popup_row['popup_end'])).'까지';
							if($popup_date['popup_unlimit']) echo '기간제한없음';
							else echo implode(" ~ ", $popup_date);
							?>
						</td>
						<td><?php echo intval($popup_row['popup_width']);?> x <?php echo intval($popup_row['popup_height']);?></td>
						<td><?php echo intval($popup_row['popup_top']);?></td>
						<td><?php echo intval($popup_row['popup_left']);?></td>
						<td><?php echo substr($popup_row['wdate'],0,10);?></td>
						<td><a href="<?php echo NFE_URL;?>/admini/design/popup_insert.php?no=<?php echo $popup_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a></td>
						<td><button type="button" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $popup_row['no'];?>" mode="delete_popup" url="../regist.php" class="gray common"><i class="axi axi-minus2"></i> 삭제</button></td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			</form>

			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_popup" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button> 
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" hname="순서" mode="rank_update_popup" url="../regist.php" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
				<a href="<?php echo NFE_URL;?>/admini/design/popup_insert.php"><button type="button" class="blue"><strong>+</strong> 팝업등록</button></a>
			</div>
		</div>
		<!--//conbox-->

		
		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->