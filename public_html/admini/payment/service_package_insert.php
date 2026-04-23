<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '500302';
if($_GET['code']=='resume') $top_menu_code = '500304';
if(!$_GET['code']) $_GET['code'] = 'employ';
include '../include/header.php';

$package_row = $db->query_fetch("select * from nf_service_package where `no`=".intval($_GET['no']));
if($package_row) $_GET['code'] = $package_row['wr_type'];
$service_package = $nf_util->get_unse($package_row['wr_service']);
if(!$_GET['code']) $_GET['code'] = $package_row['code'];
?>

<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section>
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			

			<form name="fwrite" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
			<input type="hidden" name="mode" value="service_packagae_write" />
			<input type="hidden" name="code" value="<?php echo $nf_util->get_html($_GET['code']);?>" />
			<input type="hidden" name="no" value="<?php echo intval($_GET['no']);?>" />
			<h6><?php echo $nf_job->kind_of[$_GET['code']];?>정보 패키지 등록</h6>
			<table>
				<colgroup>
					<col width="10%">
				</colgroup>
				<tr>
					<th>패키지 사용여부</th>
					<td>
						<label><input type="radio" name="use" value="1" checked>사용</label>
						<label><input type="radio" name="use" value="0" <?php echo !$package_row['wr_use'] ? 'checked' : '';?>>미사용</label>
					</td>
				</tr>
				<tr>
					<th>패키지 제목</th>
					<td><input type="text" name="subject" value="<?php echo $nf_util->get_html($package_row['wr_subject']);?>"></td>
				</tr>
				<tr>
					<th>패키지 설명</th>
					<td><textarea type="editor" name="content" style="height:200px;"><?php echo stripslashes($package_row['wr_content']);?></textarea></td>
				</tr>
				<tr>
					<th>패키지 금액</th>
					<td><input type="text" name="price" value="<?php echo number_format(intval($package_row['wr_price']));?>" onkeyup="this.value=this.value.number_format()" class="input10"> 원</td>
				</tr>
				<tr>
					<th>패키지 내용</th>
					<td>
						<table class="table3" style="width:auto;">
							<tr class="tac">
								<th class="tac">서비스 명</th>
								<th class="tac">기간</th>
							</tr>
							<?php
							if(array_key_exists($_GET['code'], $nf_job->kind_of) && is_array($nf_job->service_name_k[$_GET['code']])) { foreach($nf_job->service_name_k[$_GET['code']] as $k=>$v) {
								$service_arr = $nf_job->service_name[$_GET['code']][$v];
								if(is_array($service_arr)) { foreach($service_arr as $k2=>$v2) {
									$service_k = $k.'_'.$k2;
									$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_GET['code'], $service_k));
									if($service_k!='1_list' && !$service_row['use']) continue; // : 사용여부
									if($service_k=='1_list' && !$service_row['is_pay']) continue; // : 일반리스트 무료면 안나옴
							?>
							<tr>
								<td><label><input type="checkbox" name="package[<?php echo $service_k;?>][use]" value="1" <?php echo $service_package[$service_k]['use'] ? 'checked' : '';?>><?php echo $nf_job->service_name_k_txt[$v].' '.$v2;?> </label></td>	
								<td>
									<input type="text" name="package[<?php echo $service_k;?>][date][]" value="<?php echo intval($service_package[$service_k]['date'][0]);?>" class="input5">
									<select name="package[<?php echo $service_k;?>][date][]">
										<?php
										if(is_array($nf_util->date_arr)) { foreach($nf_util->date_arr as $k3=>$v3) {
											$selected = $service_package[$service_k]['date'][1]==$k3 ? 'selected' : '';
										?>
										<option value="<?php echo $k3;?>" <?php echo $selected;?>><?php echo $v3;?></option>
										<?php
										} }
										?>
									</select>
								</td>	
							</tr>
							<?php
								} }

								if(is_array($nf_job->service_etc)) { foreach($nf_job->service_etc as $k2=>$v2) {
									$service_k = $k.'_'.$k2;
									$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_GET['code'], $service_k));
									if(!$service_row['use']) continue;
							?>
							<tr>
								<td><label><input type="checkbox" name="package[<?php echo $service_k;?>][use]" value="1" <?php echo $service_package[$service_k]['use'] ? 'checked' : '';?>><?php echo $nf_job->service_name_k_txt[$v].' '.$v2;?> </label></td>	
								<td>
									<input type="text" name="package[<?php echo $service_k;?>][date][]" value="<?php echo intval($service_package[$service_k]['date'][0]);?>" class="input5">
									<select name="package[<?php echo $service_k;?>][date][]">
										<?php
										if(is_array($nf_util->date_arr)) { foreach($nf_util->date_arr as $k3=>$v3) {
											$selected = $service_package[$service_k]['date'][1]==$k3 ? 'selected' : '';
										?>
										<option value="<?php echo $k3;?>" <?php echo $selected;?>><?php echo $v3;?></option>
										<?php
										} }
										?>
									</select>
								</td>	
							</tr>
							<?php
								} }
							} }

							// : 열람권은 반대 [ 기업은 인재정보 봐야하고 개인은 채용정보 봐야하니 기업은 resume, 개인은 employ로 처리 ]
							$etc_service = $nf_job->etc_service;
							$etc_service['read'] = $nf_job->kind_of[$nf_job->kind_of_flip[$_GET['code']]].'정보 열람권';

							if(is_array($etc_service)) { foreach($etc_service as $k=>$v) {
								$service_k = $k;
								$service_row = $db->query_fetch("select * from nf_service where `code`=? and `type`=?", array($_GET['code'], $service_k));
								if(!$service_row['use']) continue;
							?>
							<tr>
								<td><label><input type="checkbox" name="package[<?php echo $service_k;?>][use]" value="1" <?php echo $service_package[$service_k]['use'] ? 'checked' : '';?>><?php echo $v;?> </label></td>	
								<td>
									<input type="text" name="package[<?php echo $service_k;?>][date][]" value="<?php echo intval($service_package[$service_k]['date'][0]);?>" class="input5">
									<select name="package[<?php echo $service_k;?>][date][]">
										<?php
										$date_array = $nf_util->date_arr;
										if(in_array($service_k, array('jump'))) $date_array = array('count'=>'건');
										if(in_array($service_k, array('read'))) $date_array['count'] = '건';
										if(is_array($date_array)) { foreach($date_array as $k3=>$v3) {
											$selected = $service_package[$service_k]['date'][1]==$k3 ? 'selected' : '';
										?>
										<option value="<?php echo $k3;?>" <?php echo $selected;?>><?php echo $v3;?></option>
										<?php
										} }
										?>
									</select>
								</td>
							</tr>
							<?php
							} }
							?>
						</table>
					</td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
				<button type="button" class="cancel_btn">취소하기</button>
			</div>
			</form>
		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->