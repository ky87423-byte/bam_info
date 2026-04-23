<?php
$top_menu_code = '700101';
include '../include/header.php';

if($_GET['date1']) $_date[] = "`visit_date`>='".addslashes($_GET['date1'])."'";
if($_GET['date2']) $_date[] = "`visit_date`<='".addslashes($_GET['date2'])."'";
if($_date[0]) $_date_where = " and (".implode(" and ", $_date).")";

$time_query = $db->_query("select count(*) as c, date_format(visit_time, '%H') as hour, visit_ip from nf_visit where 1 ".$_date_where." group by hour, visit_ip");
$time_group_length = $db->num_rows($time_query);
$total_num = 0;
$dupl_ip_arr = array();
$dupl_hour_arr = array();
while($time_row=$db->afetch($time_query)) {
	if(!is_array($dupl_ip_arr[$time_row['hour']])) $dupl_ip_arr[$time_row['hour']] = array();
	$dupl_ip_arr[$time_row['hour']][$time_row['visit_ip']] = $time_row['visit_ip'];
	$dupl_hour_arr[$time_row['hour']] += $time_row['c'];
	$total_num += $time_row['c'];
}
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
			
			<h6>시간별 통계</h6>
			<table class="table4">
				<colgroup>
					<col width="6%">	
					<col width="6%">	
					<col width="6%">	
					<col width="6%">	
					<col width="%">	
				</colgroup>
				<thead>
					<tr>
						<th>시간</th>
						<th>아이피수</th>
						<th>방문자수</th>
						<th>비율(%)</th>
						<th>그래프</th>
					</tr>	
				</thead>
				<tbody>
					<?php
					for($i=0; $i<24; $i++) {
						$_int = sprintf("%02d", $i);
						$_ip_int = is_array($dupl_ip_arr[$_int]) ? count($dupl_ip_arr[$_int]) : 0;
						$_time_int = $dupl_hour_arr[$_int];

						$_ip_total = $_ip_int>0 ? $_ip_int : 1;
						$_time_total = $_time_int>0 ? $_time_int : 1;

						$graph_width1 = round($_ip_int/$time_group_length*100).'%';
						$graph_width2 = round($_time_int/$total_num*100).'%';


						$time_total += $_time_int;
						$ip_total += $_ip_int;
					?>
					<tr class="tac">
						<td><?php echo $_int;?></td>
						<td class="red"><b><?php echo number_format(intval($_ip_int));?></b></td>
						<td><b><?php echo number_format(intval($_time_int));?></b></td>
						<td><?php echo $graph_width1;?><br><?php echo $graph_width2;?></td>
						<td>
							<div style="background-color:red;width:<?php echo $graph_width1;?>;color:#ffffff;text-align:center;margin-bottom:5px;"><?php echo $graph_width1;?></div>
							<div style="background-color:#627ab8;width:<?php echo $graph_width2;?>;color:#ffffff;text-align:center;"><?php echo $graph_width2;?></div>
						</td>
					</tr>
					<?php
					}?>
				</tbody>
			</table>


		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> 