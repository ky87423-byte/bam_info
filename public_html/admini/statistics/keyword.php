<?php
$top_menu_code = '700201';
include '../include/header.php';

$limit = 50;
$to_search = $db->_query("select *, sum(`wr_hit`) as sum_hit from nf_search where `wr_wdate`>='".today." 00:00:00' group by `wr_content` order by sum_hit desc limit ".$limit);
$yes_search = $db->_query("select *, sum(`wr_hit`) as sum_hit from nf_search where `wr_wdate` between '".date("Y-m-d", strtotime("-1 day"))." 00:00:00' and '".date("Y-m-d", strtotime("-1 day"))." 23:59:59' group by `wr_content` order by sum_hit desc limit ".$limit);
$mon_search = $db->_query("select *, sum(`wr_hit`) as sum_hit from nf_search where `wr_wdate`>='".date("Y-m-01")." 00:00:00' group by `wr_content` order by sum_hit desc limit ".$limit);
?>

<!-- 검색어통계 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="statistics_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>- 오늘, 어제, 이달로 해서 해당 사이트에서 검색된 키워드 상위 50개를 노출합니다.</li>
				</ul>
			</div>

			
			<h6>검색어 통계</h6>
			<table class="table4">
				<colgroup>
					<col width="33%">
					<col width="33%">
					<col width="33%">
				</colgroup>
				<tr>
					<th>오늘 검색한 검색어 TOP50</th>
					<th>어제 검색한 검색어 TOP50</th>
					<th>이달에 검색한 검색어 TOP50</th>
				</tr>	
				<tr>
					<td>
						<ol>
							<?php
							while($row=$db->afetch($to_search)) {
							?>
							<li>
								<dl>
									<dt><?php echo $row['wr_content'];?></dt>
									<dd><?php echo number_format(intval($row['sum_hit']));?></dd>
								</dl>
							</li>
							<?php
							}
							?>
						</ol>
					</td>
					<td>
						<ol>
							<?php
							while($row=$db->afetch($yes_search)) {
							?>
							<li>
								<dl>
									<dt><?php echo $row['wr_content'];?></dt>
									<dd><?php echo number_format(intval($row['sum_hit']));?></dd>
								</dl>
							</li>
							<?php
							}
							?>
						</ol>
					</td>
					<td>
						<ol>
							<?php
							while($row=$db->afetch($mon_search)) {
							?>
							<li>
								<dl>
									<dt><?php echo $row['wr_content'];?></dt>
									<dd><?php echo number_format(intval($row['sum_hit']));?></dd>
								</dl>
							</li>
							<?php
							}
							?>
						</ol>
					</td>
				</tr>
				
			</table>

	

			
		</div>
		<!--//conbox-->


	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->