<?php
$top_menu_code = '700101';
include '../include/header.php';

$get_visits = $nf_statistics->get_visits();
?>

<!-- 접속통계 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	
	<section class="statistics_index">
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
			
			<h6>사이트 접속현황</h6>
			<table class="table4">
				<colgroup>
					<col width="25%">	
					<col width="25%">	
					<col width="25%">	
					<col width="25%">	
				</colgroup>
				<tbody>
					<tr>
						<th>총 접속자 수</th>
						<th>오늘 접속자 수</th>
						<th>가장 많이 접속한 날짜</th>
						<th>가장 많이 접속한 요일</th>
					</tr>
					<tr class="tac">
						<td><b class="blue"><?php echo number_format(intval($get_visits['total']));?></b></td>
						<td><b class="blue"><?php echo number_format(intval($get_visits['today']));?></b></td>
						<td><b class="blue"><?php echo $get_visits['max_date'];?></b></td>
						<td><b class="blue"><?php echo $get_visits['max_week'];?></b></td>
					</tr>
				</tbody>
			</table>

			<h6>접속전 도메인 TOP 10</h6>
			<table class="table4">
				<colgroup>
					<col width="25%">
					<col width="25%">
					<col width="25%">
					<col width="25%">
				</colgroup>
				<tbody>
					<tr>
						<th>오늘 접속전 도메인 TOP 10</th>
						<th>어제 접속전 도메인 TOP 10</th>
						<th>이번주 접속전 도메인 TOP 10</th>
						<th>이번달 접속전 도메인 TOP 10</th>
					</tr>
					<tr>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_domain_int();
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_referer'] ? '<a href="'.$row['visit_referer'].'" target="_blank" class="blue">'.$row['visit_referer'].'</a>' : '직접입력 또는 즐겨찾기';?></dt>
										<dd><?php echo number_format(intval($row['visit_referer_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_domain_int("-1 day");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_referer'] ? '<a href="'.$row['visit_referer'].'" target="_blank" class="blue">'.$row['visit_referer'].'</a>' : '직접입력 또는 즐겨찾기';?></dt>
										<dd><?php echo number_format(intval($row['visit_referer_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_domain_int("this_week");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_referer'] ? '<a href="'.$row['visit_referer'].'" target="_blank" class="blue">'.$row['visit_referer'].'</a>' : '직접입력 또는 즐겨찾기';?></dt>
										<dd><?php echo number_format(intval($row['visit_referer_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_domain_int("this_month");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_referer'] ? '<a href="'.$row['visit_referer'].'" target="_blank" class="blue">'.$row['visit_referer'].'</a>' : '직접입력 또는 즐겨찾기';?></dt>
										<dd><?php echo number_format(intval($row['visit_referer_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
					</tr>
				</tbody>
			</table>

			<h6>접속 IP TOP 10</h6>
			<table class="table4">
				<colgroup>
					<col width="25%">	
					<col width="25%">	
					<col width="25%">	
					<col width="25%">	
				</colgroup>
				<tbody>
					<tr>
						<th>오늘 접속 IP TOP 10</th>
						<th>어제 접속 IP TOP 10</th>
						<th>이번주 접속 IP TOP 10</th>
						<th>이번달 접속 IP TOP 10</th>
					</tr>
					<tr>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_ip_int();
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_ip'];?></dt>
										<dd><?php echo number_format(intval($row['visit_ip_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_ip_int("-1 day");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_ip'];?></dt>
										<dd><?php echo number_format(intval($row['visit_ip_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_ip_int("this_week");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_ip'];?></dt>
										<dd><?php echo number_format(intval($row['visit_ip_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
						<td>
							<ol>
								<?php
								$get_data = $nf_statistics->get_ip_int("this_month");
								while($row=$db->afetch($get_data)) {
								?>
								<li>
									<dl>
										<dt><?php echo $row['visit_ip'];?></dt>
										<dd><?php echo number_format(intval($row['visit_ip_cnt']));?>명</dd>
									</dl>
								</li>
								<?php
								}?>
							</ol>
						</td>
					</tr>
				</tbody>
			</table>



		</div>
		<!--//conbox-->
	</section>


</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->