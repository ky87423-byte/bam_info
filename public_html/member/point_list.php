<?php
include_once "../engine/_core.php";
$nf_member->check_login();

$_site_title_ = '포인트 내역';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '포인트 내역';
include NFE_PATH.'/include/m_title.inc.php';

$_where = "";

$q = "nf_point as np where `mno`=".intval($member['no'])." ".$_where;
$order = " order by np.`no` desc";
$total = $db->query_fetch("select count(*) as c from ".$q);

$_arr = array();
$_arr['num'] = 15;
$_arr['tema'] = 'B';
if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
$_arr['total'] = $total['c'];
$paging = $nf_util->_paging_($_arr);

$point_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
?>
<section class="my_sub wrap1400">
	<?php
		$left_on['point'] = 'on';
		include '../include/my_leftmenu.php';
		?>
	<div class="my_con">
		<section class="point_list">
			<p class="s_title">포인트 내역</p>
			<ul class="help_text">
				<li>회원님의 포인트 내역을 확인할 수 있습니다.</li>
			</ul>
			<ul class="fr">
				<li>나의 포인트 : <span class="orange"><?php echo number_format(intval($member['mb_point']));?> <em>P</em></span></li>
			</ul>
			<table class="style3">
				<tr>
					<th>일시</th>
					<th>적립/사용내역</th>
					<th>포인트내역</th>
				</tr>
				<?php
				switch($_arr['total']<=0) {
					case true:
				?>
				<tr>
					<td colspan="3">포인트 내역이 없습니다.</td>
				</tr>
				<?php
					break;


					default:
						while($row=$db->afetch($point_query)) {
				?>
				<tr>
					<td><?php echo date("Y.m.d", strtotime($row['point_datetime']));?></td>
					<td><?php echo substr($row['point_content'], 11);?></td>
					<td><span class="<?php echo $row['point_point']>=0 ? 'blue' : 'red';?>"><?php echo $row['point_point']>=0 ? '+' : '-';?> <?php echo number_format(abs(intval($row['point_point'])));?></span></td>
				</tr>
				<?php
						}
					break;
				}
				?>
			</table>	
		</section>
		<!--페이징-->
		<div><?php echo $paging['paging'];?></div>
	</div>
</section>


<!--푸터영역-->
<?php include '../include/footer.php'; ?>