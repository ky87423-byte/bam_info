<?php
$top_menu_code = '700101';
include '../include/header.php';

if($_GET['date1']) $_date[] = "`visit_date`>='".addslashes($_GET['date1'])."'";
if($_GET['date2']) $_date[] = "`visit_date`<='".addslashes($_GET['date2'])."'";
if($_date[0]) $_date_where = " and (".implode(" and ", $_date).")";

$query = $db->_query(" select * from `nf_visit` where 1 ".$_date_where);
$date_group_length = $db->num_rows($query);
$max = 0;
$sum_count = 0;

$domain_arr = array();
while($row=$db->afetch($query)) {
	preg_match("/^http[s]*:\/\/([\.\-\_0-9a-zA-Z]*)\//", $row['visit_referer'], $match);
	$s = $match[1];
	$s = preg_replace("/^(www\.|search\.|dirsearch\.|dir\.search\.|dir\.|kr\.search\.|myhome\.)(.*)/", "\\2", $s);
	$domain_arr[$s]++;
	if ($domain_arr[$s] > $max) $max = $domain_arr[$s];
	$sum_count++;
}
asort($domain_arr);
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
			
			<h6>접속전 도메인</h6>
			<table class="table4">
				<colgroup>
					<col width="33%">
					<col width="33%">
					<col width="33%">
				</colgroup>
				<thead>
					<tr>
						<th>접속전 도메인</th>
						<th>접속자수</th>
						<th>비율</th>
					</tr>	
				</thead>
				<tbody>
					<?php
					if(is_Array($domain_arr)) { foreach($domain_arr as $k=>$v) {
						$rate = ($v / $sum_count * 100);
						$s_rate = number_format(intval($rate), 1);
					?>
					<tr class="tac">
						<td><a href="" class="blue"><?php echo $k;?></a></td>
						<td><?php echo $v;?></td>
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

<?php include '../include/footer.php'; ?> 