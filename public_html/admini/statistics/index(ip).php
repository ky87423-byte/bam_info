<?php
$top_menu_code = '700101';
include '../include/header.php';

if($_GET['date1']) $_date[] = "`visit_date`>='".addslashes($_GET['date1'])."'";
if($_GET['date2']) $_date[] = "`visit_date`<='".addslashes($_GET['date2'])."'";
if($_date[0]) $_date_where = " and (".implode(" and ", $_date).")";

$ip_q = "`nf_visit` where 1 ".$_date_where;

$total = $db->query_fetch("select count(*) as c from ".$ip_q);

$_arr['total'] = $total['c'];
$_paging_ = $nf_util->_paging_($_arr);

$order = " order by `no` desc";
$q = "select * from ".$ip_q." ".$order." ".$_paging_['limit'];
$query = $db->_query($q);
?>

<!-- 접속ip 통계 -->
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
			
			<h6>접속 IP</h6>
			<table class="table4">
				<colgroup>
					<col width="15%">
					<col width="25%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>접속 IP</th>
						<th>접속 경로</th>
						<th>브라우저</th>
						<th>OS</th>
						<th>접속일</th>
					</tr>	
				</thead>
				<tbody>
					<?php
					while($row=$db->afetch($query)) {
						$browser = $nf_util->get_brow($row['visit_agent']);
						$os = $nf_util->get_os($row['visit_agent']);
					?>
					<tr class="tac">
						<td><?php echo $row['visit_ip']?></td>
						<td><a href="<?php echo stripslashes($row['visit_referer']);?>" class="blue"><?php echo stripslashes($row['visit_referer']);?></a></td>
						<td><?php echo $browser;?></td>
						<td><?php echo $os;?></td>
						<td><?php echo $row['visit_date']." ".$row['visit_time'];?></td>
					</tr>
					<?php
					}?>
				</tbody>
			</table>

			
		</div>
		<!--//conbox-->

		<div ><?php echo $_paging_['paging'];?></div>

	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> 