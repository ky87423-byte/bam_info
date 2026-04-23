<?php
include_once "../engine/_core.php";

$notice_row = $db->query_fetch("select * from nf_notice where `no`=".intval($_GET['no']));
if(!$notice_row) {
	die($nf_util->move_url($nf_util->sess_page("notice_list"), "삭제된 공지사항 정보입니다."));
}
$update = $db->_query("update nf_notice set `wr_hit`=`wr_hit`+1 where `no`=?", array($notice_row['no']));

$_site_title_ = '공지사항 상세';
include '../include/header_meta.php';
include '../include/header.php';

$m_title = '공지사항';
include NFE_PATH.'/include/m_title.inc.php';
?>

	<section class="my_sub wrap1400">
		<!--커뮤 왼쪽 메뉴-->
		<?php include '../include/board_leftmenu.php'; ?>
		<div class="my_con">
			<section class="commu view">
				 <div class="view_wrap">
					<h2><?php echo $nf_util->get_text($notice_row['wr_subject']);?></h2>
					<div class="cmt_view_hd">
						<ul class="cmt_view_info">
							<li class="id"><?php echo $nf_util->get_text($notice_row['wr_name']);?></li>
							<li class="date"><?php echo date("Y.m.d", strtotime($notice_row['wr_date']));?></li>
						</ul>
						<ul class="cmt_view_fnc">
							<li>조회 : <em><?php echo number_format(intval($notice_row['wr_hit']));?></em></li>
						</ul>
					</div>
				</div>
				<?php
				$wr_file_name = $nf_util->get_unse($notice_row['wr_file_name']);
				$wr_file = $nf_util->get_unse($notice_row['wr_file']);
				if(is_array($wr_file_name)) {
				?>
				<ul class="down_list">
					<?php
					foreach($wr_file_name as $k=>$f_name) {
						if(!is_file(NFE_PATH.'/data/notice/'.$wr_file[$k])) continue;
					?>
					<li style="cursor:pointer;"><a href="<?php echo NFE_URL;?>/include/regist.php?mode=download_notice&no=<?php echo $notice_row['no'];?>&k=<?php echo $k;?>"><img src="../images/ic/file.gif" alt="다운로드" style=" vertical-align:middle;"><span class="blue" style="margin:0 5px;"><?php echo $wr_file_name[$k];?></span></a></li>
					<?php
					}?>
				</ul>
				<?php
				}?>
				<div class="cmt_view_con">
					<p><?php echo stripslashes($notice_row['wr_content']);?></p>
				</div>
				<div class="cmt_view_bottom">
					<ul>
						<li><a href="<?php echo $nf_util->sess_page("notice_list");?>">목록보기</a></li>
					</ul>
				</div>	

				<?php
				$q = "nf_notice as nn where 1 ".$$q_where_var;
				$order = " order by nn.`no` desc";
				$total = $db->query_fetch("select count(*) as c from ".$q);

				$_arr = array();
				$_arr['num'] = 10;
				$_arr['tema'] = 'B';
				$_arr['total'] = $total['c'];
				$paging = $nf_util->_paging_($_arr);

				$notice_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);
				?>
				<?php
				include NFE_PATH.'/include/etc/notice_list.inc.php';
				?>
				<div><?php echo $paging['paging'];?></div>
			</section>
		</div>
	</section>

<!--푸터영역-->
<?php include '../include/footer.php'; ?>
