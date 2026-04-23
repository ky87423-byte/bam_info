<?php
$top_menu_code = '500301';
if($_GET['code']=='resume') $top_menu_code = '500303';
include '../include/header.php';

$query = $db->_query("select * from nf_service_package where `wr_type`=? order by `wr_rank` asc", array($_GET['code']));
$length = $db->num_rows($query);
?>

<!-- 채용공고패키지설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide5-4','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
			
			<form name="flist">
			<h6><?php echo $nf_job->kind_of[$_GET['code']];?>정보 패키지설정</h6>
			<div class="table_top_btn">
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_service_package" tag="chk[]" check_code="checkbox"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" hname="순서" mode="rank_update_service_package" url="../regist.php" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
				<a href="<?php echo NFE_URL;?>/admini/payment/service_package_insert.php?code=<?php echo $_GET['code'];?>"><button type="button" class="blue"><strong>+</strong> 패키지 등록</button></a>
			</div>
			<table class="table4">
				<colgroup>
					<col width="3%">
					<col width="5%">
					<col width="4%">
					<col width="18%">
					<col width="8%">
					<col width="%">
					<col width="7%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th><input type="checkbox" name="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
						<th>사용</th>
						<th>순서</th>
						<th>패키지제목</th>
						<th>결제금액</th>
						<th>패키지내용</th>
						<th>등록일</th>
						<th>편집</th>
					</tr>
				</thead>
				<tbody>
					<?php
					switch($length<=0) {
						case true:
					?>
					<tr><td colspan="8" align="center">등록된 패키지가 없습니다.</td></tr>
					<?php
						break;


						default:
							while($package_row=$db->afetch($query)) {
								$package_arr = $nf_payment->get_package('job', $package_row);
					?>
					<tr class="tac">
						<td><input type="checkbox" name="chk[]" value="<?php echo $package_row['no'];?>" class="chk_"><input type="hidden" name="hidd[]" value="<?php echo $package_row['no'];?>" /></td>
						<td><input type="checkbox" name="use[]" value="1" <?php echo $package_row['wr_use'] ? 'checked' : '';?>></td>
						<td><input type="text" name="rank[]" value="<?php echo intval($package_row['wr_rank']);?>"></td>
						<td><?php echo $nf_util->get_text($package_row['wr_subject']);?></td>
						<td><?php echo number_format(intval($package_row['wr_price']));?> 원</td>
						<td>
							<ul>
								<?php if(is_Array($package_arr['service_txt_arr'])) { foreach($package_arr['service_txt_arr'] as $k=>$v) {?>
								<li><?php echo $v;?></li>
								<?php } }?>
							</ul>
						</td>
						<td><?php echo substr($package_row['wr_wdate'],0,10);?></td>
						<td>
							<a href="./service_package_insert.php?no=<?php echo $package_row['no'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정하기</button></a>
							<button type="button" class="gray common" onClick="nf_util.ajax_post(this, '삭제하시겠습니까?')" no="<?php echo $package_row['no'];?>" mode="delete_service_package" url="../regist.php"><i class="axi axi-minus2"></i> 삭제하기</button>
						</td>
					</tr>
					<?php
							}
						break;
					}
					?>
				</tbody>
			</table>
			<div class="table_top_btn bbn">
				<button type="button" onclick="nf_util.ajax_select_confirm(this, 'flist', '삭제하시겠습니까?')" url="../regist.php" mode="delete_select_service_package" tag="chk[]" check_code="checkbox" class="gray"><strong>-</strong> 선택삭제</button>
				<button type="button" class="gray" onclick="nf_util.ajax_select_confirm(this, 'flist', '순서값을 저장하시겠습니까?')" hname="순서" mode="rank_update_service_package" url="../regist.php" check_code="input" tag="rank[]"><strong>-</strong> 순서저장</button>
				<a href="<?php echo NFE_URL;?>/admini/payment/service_package_insert.php?code=<?php echo $_GET['code'];?>"><button type="button" url="" mode="" tag="" check_code="" class="blue"><strong>+</strong> 패키지 등록</button></a>
			</div>
			</form>
		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->