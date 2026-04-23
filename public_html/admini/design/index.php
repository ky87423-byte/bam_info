<?php
$_SERVER['__USE_API__'] = array('editor');
$top_menu_code = '400101';
include '../include/header.php';
?>
<!-- 사이트디자인설정 -->
<div class="wrap">
	<?php include '../include/left_menu.php'; ?> <!--관리자 공통 좌측메뉴-->

	<section class="design_index">
		<?php include '../include/title.php'; ?> <!--관리자 타이틀영역-->
		<div class="consadmin conbox">
			<div class="guide">
				<p><img src="../../images/ic/guide.gif" alt="가이드"></p>
				<ul>
					<li>해당 페이지의 안내는 메뉴얼을 참조하세요<button class="s_basebtn4 gray2"  onclick="window.open('../pop/guide.php#guide4-1','window','width=1400, height=800,left=0, top=0, scrollbard=1, scrollbars=yes, resizable=yes')">메뉴얼</button></li>
				</ul>
			</div>
			
			<h6>사이트기본설정</h6>
			<form name="fwrite" action="../regist.php" method="post" onSubmit="return validate(this)">
			<input type="hidden" name="mode" value="design_config" />
			<table>
				<colgroup>
					<col width="12%">
					<col width="10%">
				</colgroup>
				<tbody>
					<tr>
						<th>사이트기본색</th>
						<td colspan="7" class="color_choice">
							<?php
							if(is_array($nf_shop->site_color_arr)) { foreach($nf_shop->site_color_arr as $k=>$v) {
							?>
							<label><input type="radio" name="site_color" value="<?php echo $k;?>" id="site_color_<?php echo $k;?>" <?php echo $env['site_color']==$k ? 'checked' : '';?>><button type="button" onClick="$('#site_color_<?php echo $k;?>').click();" style="background:<?php echo $v['background-color'];?>;color:<?php echo $v['color'];?>;border:1px solid <?php echo $v['border-color'];?>;width:50px;">&nbsp;</button></label> <!-- black.css -->
							<?php
							} }
							?>
						</td>
					</tr>
					<tr>
						<th>업종별 카테고리</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_industry" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_industry" value="0" <?php echo !$env['use_shop_industry'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>테마별 카테고리</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_tema" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_tema" value="0" <?php echo !$env['use_shop_tema'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>대표요금</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_price" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_price" value="0" <?php echo !$env['use_shop_price'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>Q&A(상품문의)</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_qna" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_qna" value="0" <?php echo !$env['use_shop_qna'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>별점</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_point" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_point" value="0" <?php echo !$env['use_shop_point'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>지도검색 메뉴</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_menu_map" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_menu_map" value="0" <?php echo !$env['use_shop_menu_map'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>제휴업체 리스트</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_list" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_list" value="0" <?php echo !$env['use_shop_list'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>입점업체 등록페이지</th>
						<td colspan="7">
							<label><input type="radio" name="use_shop_write" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_write" value="0" <?php echo !$env['use_shop_write'] ? 'checked' : '';?>>미사용</label>
						</td>
					</tr>
					<tr>
						<th>이용후기 설정</th>
						<td>
							<label><input type="radio" name="use_shop_guide" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_guide" value="0" <?php echo !$env['use_shop_guide'] ? 'checked' : '';?>>미사용</label>
						</td>
						<th class="bg_w_blue bl fwb">BEST후기 기준</th>
						<td>
							<label><input type="radio" name="use_shop_guide_sort" value="visit" checked>조회순</label>
							<label><input type="radio" name="use_shop_guide_sort" value="good" <?php echo $env['use_shop_guide_sort']=='good' ? 'checked' : '';?>>공감순</label>
						</td>
						<td class="bg_w_blue bl fwb">출력여부</td>
						<td>
							<label><input type="radio" name="use_shop_guide_open" value="1" checked>바로출력</label>
							<label><input type="radio" name="use_shop_guide_open" value="0" <?php echo !$env['use_shop_guide_open'] ? 'checked' : '';?>>승인후출력</label>
						</td>
						<td class="bg_w_blue bl fwb">보기권한</td>
						<td>
							<label><input type="radio" name="use_shop_guide_view_auth" value="1" checked>회원가능</label>
							<label><input type="radio" name="use_shop_guide_view_auth" value="0" <?php echo !$env['use_shop_guide_view_auth'] ? 'checked' : '';?>>비회원가능</label>
						</td>
					</tr>
					<tr>
						<th rowspan="3">메인 커뮤니티 설정</th>
						<th class="bl">BEST후기</th>
						<td class="bg_w_blue bl fwb">사용여부</td>
						<td>
							<label><input type="radio" name="use_shop_guide_best" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_guide_best" value="0" <?php echo !$env['use_shop_guide_best'] ? 'checked' : '';?>>미사용</label>
						</td>
						<td class="bg_w_blue bl fwb">출력갯수</td>
						<td><input type="text" name="use_shop_guide_best_int" value="<?php echo $env['use_shop_guide_best_int'];?>" class="input10" placeholder="숫자입력"> 개 출력</td>
						<td class="bg_w_blue bl fwb">출력 기준일</td>
						<td><input type="text" name="use_shop_guide_date" value="<?php echo $env['use_shop_guide_date'];?>" class="input10" placeholder="숫자입력"> 일 이내</td>
					</tr>
					<tr>
						<th class="bl">TOP조회</th>
						<td class="bg_w_blue bl fwb">사용여부</td>
						<td>
							<label><input type="radio" name="use_shop_top" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_top" value="0" <?php echo !$env['use_shop_top'] ? 'checked' : '';?>>미사용</label>
						</td>
						<td class="bg_w_blue bl fwb">출력갯수</td>
						<td><input type="text" name="use_shop_top_int" value="<?php echo $env['use_shop_top_int'];?>" class="input10" placeholder="숫자입력"> 개 출력</td>
						<td class="bg_w_blue bl fwb">출력 기준일</td>
						<td><input type="text" name="use_shop_top_date" value="<?php echo $env['use_shop_top_date'];?>" class="input10" placeholder="숫자입력"> 일 이내</td>
					</tr>
					<tr>
						<th class="bl">추천게시물</th>
						<td class="bg_w_blue bl fwb">사용여부</td>
						<td>
							<label><input type="radio" name="use_shop_chu" value="1" checked>사용</label>
							<label><input type="radio" name="use_shop_chu" value="0" <?php echo !$env['use_shop_chu'] ? 'checked' : '';?>>미사용</label>
						</td>
						<td class="bg_w_blue bl fwb">출력갯수</td>
						<td><input type="text" name="use_shop_chu_int" value="<?php echo $env['use_shop_chu_int'];?>" class="input10" placeholder="숫자입력"> 개 출력</td>
						<td class="bg_w_blue bl fwb">출력 기준일</td>
						<td><input type="text" name="use_shop_chu_date" value="<?php echo $env['use_shop_chu_date'];?>" class="input10" placeholder="숫자입력"> 일 이내</td>
					</tr>
					<tr>
						<th>new 아이콘 노출 시간</th>
						<td colspan="7"><input type="text" name="use_shop_new_time" value="<?php echo $env['use_shop_new_time'];?>" class="input10" placeholder="숫자입력"> 시간 이내</td>
					</tr>
				</tbody>
			 </table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>


			<h6>업체정보 서비스 출력설정</h6>
			<?php
			$count = 0;
			if(is_array($nf_shop->service_name['shop']['main'])) { foreach($nf_shop->service_name['shop']['main'] as $k=>$v) {
				$table_c = $count===0 ? '' : 'MAT10 bt';
				$service_k = '0_'.$k;
			?>
			<table class="<?php echo $table_c;?>">
				<colgroup>
					<col width="12%">
					<col width="8%">
				</colgroup>
				<tr>
					<th rowspan="3"><?php echo $v;?> 업체</th>
					<td colspan="2">
						<label><input type="checkbox" name="service_config[shop][<?php echo $service_k;?>][use]" value="1" <?php echo $env['service_config_arr']['shop'][$service_k]['use'] ? 'checked' : '';?>>출력 </label>
					</td>
				</tr>
				<tr>
					<th class="bl">메인 페이지</th>
					<td><b>가로한줄</b>에
						<?php
						$start_i = 3;
						$service_width = 6;
						if(in_array($service_k, array('0_0'))) { $start_i = 3; $service_width = 6; }
						if(in_array($service_k, array('0_1'))) { $start_i = 3; $service_width = 6; }
						if(in_array($service_k, array('0_2'))) { $start_i = 3; $service_width = 3; }
						if(in_array($service_k, array('0_3'))) { $start_i = 2; $service_width = 2; }

						$start_i2 = 3;
						$service_width2 = 6;
						if(in_array($service_k, array('0_0'))) { $start_i2 = 3; $service_width2 = 4; }
						if(in_array($service_k, array('0_1'))) { $start_i2 = 3; $service_width2 = 4; }
						if(in_array($service_k, array('0_2'))) { $start_i2 = 2; $service_width2 = 2; }
						if(in_array($service_k, array('0_3'))) { $start_i2 = 1; $service_width2 = 1; }
						?>
						<select name="service_config[shop][<?php echo $service_k;?>][width]" >
							<?php
							for($i=$start_i; $i<=$service_width; $i++) {
							?>
							<option value="<?php echo $i;?>" <?php echo $env['service_config_arr']['shop'][$service_k]['width']==$i ? 'selected' : '';?>><?php echo $i;?></option>
							<?php
							}?>
						</select> 개씩 출력
						/ <b>페이지당</b>
						 <input type="text" name="service_config[shop][<?php echo $service_k;?>][height]" value="<?php echo $env['service_config_arr']['shop'][$service_k]['height'];?>" class="input10" placeholder="숫자입력"> 개 출력
					</td>
				</tr>
				<tr>
					<th class="bl">서브 페이지</th>
					<td> <b>가로한줄</b>에 
						<select name="service_config[shop][<?php echo $service_k;?>][sub_width]" >
							<?php
							for($i=$start_i2; $i<=$service_width2; $i++) {
							?>
							<option value="<?php echo $i;?>" <?php echo $env['service_config_arr']['shop'][$service_k]['sub_width']==$i ? 'selected' : '';?>><?php echo $i;?></option>
							<?php
							}?>
						</select> 개씩 출력
					 / <b>페이지당</b>
						<input type="text" name="service_config[shop][<?php echo $service_k;?>][sub_height]" value="<?php echo $env['service_config_arr']['shop'][$service_k]['sub_height'];?>" class="input10" placeholder="숫자입력"> 개 출력
					</td>
				</tr>
				<tr>
					<th><?php echo $v;?> 서비스안내</th>
					<td colspan="2"><textarea type="editor" name="service_intro[shop][<?php echo $service_k;?>]" cols="30" rows="10"><?php echo stripslashes($env['service_intro_arr']['shop'][$service_k]);?></textarea></td>
				</tr>
			</table>
			<?php
				$count++;
			} }
			?>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>

			<h6>업종별 카테고리 메인(서브)페이지 상품 출력 설정</h6>

			<table class="">
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th rowspan="3">업종별 상품 출력 설정</th>
					<td>
						<label><input type="checkbox" name="service_config[shop][job_part][use]" value="1" <?php echo $env['service_config_arr']['shop']['job_part']['use'] ? 'checked' : '';?>>출력 </label><span class="">* 예시 : 가로 출력건수 3 / 세로 출력건수 5 입력시, 15건이 출력됨(3 X 5).</span>
					</td>
				</tr>
				<tr>
					<td>
						<?php
						if(is_array($cate_p_array['job_part'][0])) { foreach($cate_p_array['job_part'][0] as $k=>$v) {
							$checked = is_array($env['service_config_arr']['shop']['job_part']['part']) && in_array($k, $env['service_config_arr']['shop']['job_part']['part']) ? 'checked' : '';
						?>
						<label><input type="checkbox" name="service_config[shop][job_part][part][]" value="<?php echo $k;?>" <?php echo $checked;?> > <?php echo $v['wr_name'];?></label>
						<?php
						} }?>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table3" style="width:auto;">
							<tr>
								<th class="tac">메인 가로 출력건수 X 세로 출력건수</th>
							</tr>
							<tr>
								<td>
									가로 
									<?php
									$service_width = 6;
									?>
									<select name="service_config[shop][job_part][width]" >
										<?php
										for($i=3; $i<=$service_width; $i++) {
										?>
										<option value="<?php echo $i;?>" <?php echo $env['service_config_arr']['shop']['job_part']['width']==$i ? 'selected' : '';?>><?php echo $i;?></option>
										<?php
										}?>
									</select>
									<b>X</b> 세로 
									<input type="text" name="service_config[shop][job_part][height]" value="<?php echo $env['service_config_arr']['shop']['job_part']['height'];?>" class="input10" placeholder="숫자입력">
								</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>

			<h6>기타 서비스 안내 설정</h6>
			<table>
				<colgroup>
					<col width="12%">
				</colgroup>
				<tr>
					<th>업체정보 점프 서비스안내</th>
					<td><textarea type="editor" name="service_intro[shop][jump]" cols="30" rows="10"><?php echo stripslashes($env['service_intro_arr']['shop']['jump']);?></textarea></td>
				</tr>
			</table>

			<div class="flex_btn">
				<button type="submit" class="save_btn">저장하기</button>
			</div>
			</form>

		</div>
		<!--//conbox-->


		
	</section>
</div>
<!--//wrap-->

<?php include '../include/footer.php'; ?> <!--관리자 footer-->