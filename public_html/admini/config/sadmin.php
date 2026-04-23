<?php
$top_menu_code = "200302";
include '../include/header.php'; // : 관리자 탑메뉴

$_where = "";
if($_GET['search_keyword']) {
	$_search['wr_name'] = "`wr_name` like '%".addslashes($_GET['search_keyword'])."%'";
	$_search['wr_id'] = "`wr_id` like '%".addslashes($_GET['search_keyword'])."%'";

	if($_GET['search_field']) $_where .= " and ".$_search[$_GET['search_field']];
	else $_where .= " and (".@implode(" or ", $_search).")";
}

$admin_query = $db->_query("select * from nf_admin where `wr_level`<10 ".$_where." order by `no` desc");
$admin_length = $db->num_rows($admin_query);
?>
<!-- 부관리자관리 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 관리자페이지를 접근할수 있는 부관리자를 설정하는 페이지 입니다.</li>
					<li>- 부관리자 추가 버튼을 클릭하면 새로운 부관리자를 등록할 수 있습니다.</li>
					<li>: 관리자 사이트에 로그인 할 수 있는 부관리자의 ID, 비밀번호 등을 입력합니다.</li>
					<li>: 권한 설정 : 부관리자에게 부여할 권한을 선택하여 체크한후 하단의 저장하기 버튼을 클릭해주세요.</li>
				</ul>
			</div>
			<form name="fsearch" action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
				<div class="search">
					<div class="bg_w">
						<select name="search_field" id="">
							<option value="">통합검색</option>
							<option value="wr_name" <?php echo $_GET['search_field']=='wr_name' ? 'selected' : '';?>>이름</option>
							<option value="wr_id" <?php echo $_GET['search_field']=='wr_id' ? 'selected' : '';?>>아이디</option>
						</select>
						<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
						<input type="submit" class="blue" value="검색"></input>
						<button class="black">초기화</button>
					</div>
				</div>
			</form>
			
			<h6>부관리자관리<span>총 <b><?php echo number_format(intval($admin_length));?></b>명의 부관리자가 등록되어있습니다.</span></h6>
			<div class="table_top_btn">
				<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_sadmin" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/config/sadmin_modify.php"><button type="button" class="blue"><strong>+</strong> 부관리자추가</button></a>
			</div>
			<form name="flist" method="post">
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="8%">
					<col width="8%">
					<col width="8%">
					<col width="7%">
					<col width="10%">
					<col width="">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" id="check_all" onClick="nf_util.all_check(this, '.chk_')"></th>
						<th>부관리자ID</th>
						<th>부관리자명</th>
						<th>부관리자닉네임</th>
						<th>로그인 횟수</th>
						<th>로그인시각</th>
						<th>권한메뉴</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
				<?php
				switch($admin_length<=0) {
					case true:
					?>
					<tr>
						<td colspan="8" class="no_list"></td>
					</tr>
					<?php
					break;

					default:
						while($admin_row=$db->afetch($admin_query)) {
							$get_sadmin = $nf_admin->get_sadmin($admin_row['wr_id']);
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $admin_row['no'];?>"></td>
						<td><?php echo $admin_row['wr_id'];?></td>
						<td><?php echo $admin_row['wr_name'];?></td>
						<td><?php echo $admin_row['wr_nick'];?></td>
						<td><?php echo number_format(intval($admin_row['wr_login']));?></td>
						<td><?php echo $nf_util->get_date($admin_row['wr_last_login']);?></td>
						<td><?php echo @implode(", ", $get_sadmin['txt']);?></td>
						<td style="text-align:center">
							<a href="./sadmin_modify.php?no=<?php echo $admin_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<button type="button" class="gray common" onclick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $admin_row['no'];?>" mode="delete_sadmin" url="../regist.php"><i class="axi axi-minus2"></i> 삭제하기</button>
						</td>
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
				<button type="button" onClick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_sadmin" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<a href="<?php echo NFE_URL;?>/admini/config/sadmin_modify.php"><button type="button" class="blue"><strong>+</strong> 부관리자추가</button></a>
			</div>
		</div>
		<!--//payconfig conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->