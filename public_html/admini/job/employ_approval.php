<?php
$top_menu_code = $_GET['top_menu_code'];
if(!$top_menu_code) $top_menu_code = '100101';
$_SERVER['__USE_API__'] = array('jqueryui');
include '../include/header.php';


switch($_GET['mode']) {
	case "sel_service":
		$length = count($_GET['chk']);
		if($length<=0) {
			$arr['msg'] = "채용정보를 하나이상 선택해주시기 바랍니다.";
			$arr['move'] = $nf_util->page_back();
			die($nf_util->move_url($arr['move'], $arr['msg']));
		}
	break;

	default:
		$no = $_GET['no'] ? $_GET['no'] : $_GET['nos'];
		if(!$no) {
			$arr['msg'] = "채용정보를 선택하시고 이용해주셔야합니다.";
			$arr['move'] = $nf_util->page_back();
			die($nf_util->move_url($arr['move'], $arr['msg']));
		}

		$em_row = $db->query_fetch("select * from nf_employ where `no`=".intval($_GET['no']));
		$employ_info = $nf_job->employ_info($em_row);
	break;
}
?>


<!--채용정보 서비스 승인-->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->
	<section class="employ_modify">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->

		<form name="fwrite" action="../regist.php" method="post">
		<input type="hidden" name="mode" value="service_update" />
		<input type="hidden" name="kind" value="employ" />
		<?php
		if($_GET['mode']=='sel_service') {
		?>
		<input type="hidden" name="code" value="sel_service" />
		<?php
			if(is_array($_GET['chk'])) { foreach($_GET['chk'] as $k=>$v) {
		?>
			<input type="hidden" name="chk[]" value="<?php echo intval($v);?>" />
		<?php
			} }
		} else {?>
			<input type="hidden" name="no" value="<?php echo $no;?>" />
		<?php
		}?>
		<input type="hidden" name="prev_page" value="<?php echo urlencode($nf_util->page_back());?>" />
		<div class="conbox">
			<h6>메인페이지</h6>
			<table>
				<colgroup>
					<col width="10%">
					<col width="6%">
				</colgroup>
				<tbody>
					<?php
					if(is_array($nf_job->service_name['employ']['main'])) { foreach($nf_job->service_name['employ']['main'] as $k=>$v) {
						$date_val = $em_row['wr_service0_'.$k]>'1970-01-01' ? $em_row['wr_service0_'.$k] : "";
					?>
					<tr>
						<th><?php echo $v;?></th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[0_<?php echo $k;?>]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
					<?php
					} }

					$date_val = $em_row['wr_service0_border']>'1970-01-01' ? $em_row['wr_service0_border'] : "";
					?>
					<tr>
						<th class="bl">테두리강조</th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[0_border]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
				</tbody>
			</table>

			<h6>채용정보메인</h6>
			<table>
				<colgroup>
					<col width="10%">
					<col width="6%">
				</colgroup>
				<tbody>
					<?php
					unset($nf_job->service_name['employ']['sub']['list']);
					if(is_array($nf_job->service_name['employ']['sub'])) { foreach($nf_job->service_name['employ']['sub'] as $k=>$v) {
						$date_val = $em_row['wr_service1_'.$k]>'1970-01-01' ? $em_row['wr_service1_'.$k] : "";
					?>
					<tr>
						<th><?php echo $v;?></th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[1_<?php echo $k;?>]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
					<?php
					} }

					$date_val = $em_row['wr_service1_border']>'1970-01-01' ? $em_row['wr_service1_border'] : "";
					?>
					<tr>
						<th class="bl">테두리강조</th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[1_border]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
				</tbody>
			</table>
			<!--채용정보 일반리스트는 필수값-->
			<table class="MAT5 bt">
				<colgroup>
					<col width="10%">
					<col width="6%">
				</colgroup>
				<?php
				$date_val = $em_row['wr_service1_list']>'1970-01-01' ? $em_row['wr_service1_list'] : "";
				?>
				<tbody>
					<tr>
						<th>채용정보 일반리스트</th>
						<th class="bl">기간</th>
						<td><input type="text" name="service[1_list]" class="input20 datepicker_inp_enddate" value="<?php echo $date_val;?>"></td>
					</tr>
				</tbody>
			</table>

			<h6>강조옵션 상품</h6>
			<table>
				<colgroup>
					<col width="10%">
					<col width="6%">
					<col width="15%">
				</colgroup>
				<tbody>
					<?php
					if(is_array($nf_job->etc_service)) { foreach($nf_job->etc_service as $k=>$v) {
						if(in_array($k, array('jump'))) continue;
						$date_val = $em_row['wr_service_'.$k]>'1970-01-01' ? $em_row['wr_service_'.$k] : "";
					?>
					<tr>
						<th><?php echo $v;?></th>
						<th class="bl">기간</th>
						<td colspan="<?php echo in_array($k, array('busy', 'bold', 'blink')) ? '2' : '1';?>">
							<input type="text" name="service[_<?php echo $k;?>]" value="<?php echo $date_val;?>" class="input20 datepicker_inp_enddate">
						</td>
						<?php
						switch($k) {
							case "icon":
								$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array('employ', 'icon'));
								$option_arr = explode("/", $service_row['option']);
						?>
						<td>
							<?php
							if(is_array($option_arr)) { foreach($option_arr as $k=>$v) {
								$checked = $v==$em_row['wr_service_icon_value'] ? 'checked' : '';
							?>
							<label><input type="radio" name="service_icon_value" value="<?php echo $v;?>" <?php echo $checked;?>><img src="<?php echo NFE_URL;?>/data/service_option/<?php echo $v;?>" alt=""></label>
							<?php
							} }?>
						</td>
						<?php
							break;


							case "neon":
								$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array('employ', 'neon'));
								$option_arr = explode("/", $service_row['option']);
						?>
						<td>
							<?php
							$length = count($option_arr);
							for($i=0; $i<$length; $i++) {
								$checked = $option_arr[$i]==$em_row['wr_service_neon_value'] ? 'checked' : '';
							?>
							<label><input type="radio" name="service_neon_value" value="<?php echo $option_arr[$i];?>" <?php echo $checked;?>><p class="title bgcol1" style="background-color:<?php echo $option_arr[$i];?>">형광펜강조</p></label>
							<?php
							}?>
						</td>
						<?php
							break;


							case "color":
								$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array('employ', 'color'));
								$option_arr = explode("/", $service_row['option']);
						?>
						<td>
							<?php
							$length = count($option_arr);
							for($i=0; $i<$length; $i++) {
								$checked = $option_arr[$i]==$em_row['wr_service_color_value'] ? 'checked' : '';
							?>
							<label><input type="radio" name="service_color_value" value="<?php echo $option_arr[$i];?>" <?php echo $checked;?>><p class="title" style="color:<?php echo $option_arr[$i];?>">글자색강조</p></label>
							<?php
							}?>
						</td>
						<?php
							break;
						}
						?>
					</tr>
					<?php
					} }?>
				</tbody>
			</table>



			<div class="flex_btn">
				<button type="submit" class="save_btn">서비스승인</button>
				<a href="<?php echo $nf_util->page_back();?>"><button type="button" class="cancel_btn">돌아가기</button></a>
			</div>
		</div>
		<!--//conbox-->
		</form>

	</section>

</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->