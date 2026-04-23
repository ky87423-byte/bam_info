<?php
$top_menu_code = '700101';
include '../include/header.php';

if($_GET['date1']) $_date[] = "`visit_date`>='".addslashes($_GET['date1'])."'";
if($_GET['date2']) $_date[] = "`visit_date`<='".addslashes($_GET['date2'])."'";
if($_date[0]) $_date_where = " and (".implode(" and ", $_date).")";

$query = $db->_query(" select * from `nf_visit` where 1 ".$_date_where);

$max = 0;
$sum_count = 0;
$brower_arr = array();
while($row=$db->afetch($query)) {
	$browser = $nf_util->get_brow($row['visit_agent']);
	$brower_arr[$browser]++;
	if ($brower_arr[$browser] > $max) $max = $brower_arr[$browser];
	$sum_count++;
}
asort($brower_arr);
?>

<!-- 시간별 통계 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 시간 흐름(시간/일간/월간)에 따른 방문자/회원가입/등록현황 등을 한눈에 볼 수 있습니다.</li>
					<li>- 시간 흐름에 따른 중요 데이터를 쉽게 분석할 수 있습니다.</li>
					<li>- 하루 하루 나타나는 데이터를 출력하여 모아 놓으면, 아주 소중한 사이트 운영가이드책이 될 수 있습니다.</li>
				</ul>
			</div>

			<?php
			include NFE_PATH.'/admini/include/statistics_menu.php';
			?>

			<h6>접속 브라우저</h6>
			<table class="table4">
				<colgroup>
					<col width="33%">
					<col width="33%">
					<col width="33%">
				</colgroup>
				<thead>
					<tr>
						<th>브라우저</th>
						<th>방문자수</th>
						<th>비율</th>
					</tr>	
				</thead>
				<tbody>
					<?php
					if(is_Array($brower_arr)) { foreach($brower_arr as $k=>$v) {
						$rate = ($v / $sum_count * 100);
						$s_rate = number_format(intval($rate), 1);
					?>
					<tr class="tac">
						<td><?php echo $k;?></td>
						<td><?php echo number_format(intval($v));?></td>
						<td><?php echo $s_rate;?>%</td>
					</tr>
					<?php
					} }?>
					<tr class="tac blue">
						<td><b>합계</b></td>
						<td><b><?php echo $sum_count;?></b></td>
						<td><b>100 %</b></td>
					</tr>
				</tbody>
			</table>

			
		</div>
		<!--//conbox-->

	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->