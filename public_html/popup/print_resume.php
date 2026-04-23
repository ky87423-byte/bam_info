<?php
$add_cate_arr = array('subway', 'job_date', 'job_week', 'job_time', 'job_pay', 'job_pay_support', 'job_type', 'job_welfare', 'job_grade', 'job_position', 'job_age', 'job_target', 'job_document', 'job_tema', 'job_conditions', 'job_resume_report_reason', 'job_language_exam', 'job_computer', 'job_veteran', 'job_pay_employ', 'job_obstacle');
include_once "../engine/_core.php";
$re_row = $db->query_fetch("select * from nf_member as nm right join nf_resume as nr on nm.`no`=nr.`mno` where nr.`no`=".intval($_GET['no']));
$resume_info = $nf_job->resume_info($re_row);
$resume_individual = $nf_job->resume_individual($re_row['mno']);
$get_member = $db->query_fetch("select * from nf_member where `no`=".intval($re_row['mno']));

$m_title = $nf_util->get_html($re_row['wr_subject']);
?>
<!doctype html>
<html lang="ko">
 <head>
  <title><?php echo $m_title;?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <link href="../css/default.css" rel="stylesheet" type="text/css">
  <link href="../css/style.css" rel="stylesheet" type="text/css">
 </head>
<body>
<style type="text/css">
@media print {
	.readBtn.clearfix { display:none; }
}
</style>
<script type="text/javascript">
window.print();
</script>

<div class="readBtn clearfix" >
	<ul style="float:right; overflow:hidden; padding:0; margin:10px 20px;">
		<li style="float:left;list-style:none; "><a href="#none" onClick="window.print()" style="display:block; padding:3px 5px; font-size:13px; text-decoration:none; border:1px solid #ddd;">인쇄</a></li>
	</ul>
</div>


<section class="print_pop">
	<div class="top">
		<ul class="date">
			<li>등록일 : <?php echo substr($re_row['wr_wdate'],0, 10);?></li>
		</ul>
		<p class="logo"><img src="<?php echo NFE_URL.'/data/logo/'.$env['logo_top'];?>" alt="<?php echo $nf_util->get_text($env['site_title']);?>"></p>
	</div>
	<div class="title">
		이력서제목표기
	</div>
	<div class="table_wrap">
		<table class="style2">
			<colgroup>
				<col style="width:25%">
				<col style="width:20%">
				<col style="width:55%">
			</colgroup>
			<tbody>
				<tr>
					<td rowspan="5" class="logo"><img src="<?php echo NFE_URL;?>/data/resume/<?php echo $get_member['mb_photo'];?>" alt="인물사진"></td>
					<th>이름</th>
					<td><?php echo $resume_info['name_txt'];?> <span><?php echo $resume_info['gender_age_txt'];?></span></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><?php echo $get_member['mb_phone'];?></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><?php echo $get_member['mb_email'];?></td>
				</tr>
				<tr>
					<th>주소</th>
					<td>[<?php echo $get_member['mb_zipcode'];?>] <?php echo $get_member['mb_address1'].' '.$get_member['mb_address1'];?></td>
				</tr>
				<tr>
					<th>홈페이지</th>
					<td><?php echo $get_member['mb_homepage'];?></td>
				</tr>
				<tr>
					<th>학력</th>
					<td colspan="2">
						<?php
						if(is_array($resume_individual['wr_school_info'])) { foreach($resume_individual['wr_school_info'] as $k=>$v) {
							if(is_array($v['name'])) { foreach($v['name'] as $k2=>$v2) {
								$school_name = $v2;
								$school_syear = $v['syear'][$k2];
								$school_eyear = $v['eyear'][$k2];
								$school_graduation = $v['graduation'][$k2];
								$school_specialize = $v['specialize'][$k2];
								$school_grade = $v['grade'][$k2];
						?>
						<?php
							echo $school_name.$nf_job->school_part[$k];
							if($school_specialize) echo ' ('.$school_specialize.') ';
							echo $nf_job->school_graduation[$school_graduation];
						?><br>
						<?php
							} }
						} }
						?>
					</td>
				</tr>
				<tr>
					<th>경력</th>
					<td colspan="2">
						<?php
						if(is_array($resume_individual['career_info']['name'])) { foreach($resume_individual['career_info']['name'] as $k=>$v) {
							$career_name = $v;
							$career_job_type = $resume_individual['career_info']['job_type'][$k];
							$career_syear = $resume_individual['career_info']['syear'][$k];
							$career_smonth = sprintf("%02d", $resume_individual['career_info']['smonth'][$k]);
							$career_eyear = $resume_individual['career_info']['eyear'][$k];
							$career_emonth = sprintf("%02d", $resume_individual['career_info']['emonth'][$k]);
							$career_industry = $resume_individual['career_info']['industry'][$k];
							$career_content = $resume_individual['career_info']['content'][$k];

							$month_int = $nf_util->get_month_calc($career_syear.' '.$career_smonth, $career_eyear.' '.$career_emonth);
						?>
						<?php echo $career_name.' '.$nf_util->get_month_year($month_int);?><br>
						<?php
						} }?>
					</td>
				</tr>
				<tr>
					<th>연락가능시간</th>
					<td colspan="2">
						<?php echo $resume_info['calltime_arr'][0].':00 ~ '.$resume_info['calltime_arr'][1].':00';?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<h2>희망 근무조건</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:80%">
		</colgroup>
		<tr>
			<th>근무지</th>
			<td>
				<?php
					$area_text_arr = array();
					if(is_array($resume_info['area_text_arr2_txt'])) { foreach($resume_info['area_text_arr2_txt'] as $k=>$v) {
						$area_text_arr[$k] = $v;
						if($resume_info['area_text_arr2_txt2'][$k]) $area_text_arr[$k] .= ' (재택근무가능)';
					} }
					echo implode("<br/>", $area_text_arr);
				?>
			</td>
		</tr>
		<tr>
			<th>업·직종</th>
			<td><?php echo implode("<br/>", $resume_info['job_type_text_arr2_txt']);?></td>
		</tr>
		<tr>
			<th>근무일시</th>
			<td><?php echo $cate_p_array['job_date'][0][$re_row['wr_date']]['wr_name'];?></td>
		</tr>
		<tr>
			<th>근무요일</th>
			<td><?php echo $cate_p_array['job_week'][0][$re_row['wr_week']]['wr_name'];?></td>
		</tr>
		<tr>
			<th>근무시간</th>
			<td><?php echo $cate_p_array['job_time'][0][$re_row['wr_time']]['wr_name'];?></td>
		</tr>
		<tr>
			<th>급여</th>
			<td><?php echo $resume_info['pay_type'];?> <?php echo number_format(intval($re_row['wr_pay']));?></td>
		</tr>
		<tr>
			<th>근무형태</th>
			<td><?php echo strtr($re_row['wr_work_type'], $cate_array['job_type']);?></td>
		</tr>
	</table>

	<h2>학력사항</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>재학기간</th>
			<th>학교명</th>
			<th>상태</th>
			<th>학과</th>
			<th>학위</th>
		</tr>
		<?php
		if(is_array($resume_individual['wr_school_info'])) { foreach($resume_individual['wr_school_info'] as $k=>$v) {
			if(is_array($v['name'])) { foreach($v['name'] as $k2=>$v2) {
				$school_name = $v2;
				$school_syear = $v['syear'][$k2];
				$school_eyear = $v['eyear'][$k2];
				$school_graduation = $v['graduation'][$k2];
				$school_specialize = $v['specialize'][$k2];
				$school_grade = $v['grade'][$k2];
		?>
		<tr>
			<td><?php echo $school_syear;?>년 ~ <?php echo $school_eyear;?>년</td>
			<td><?php echo $school_name.$nf_job->school_part[$k];?></td>
			<td><?php echo $nf_job->school_graduation[$school_graduation];?></td>
			<td><?php echo $school_specialize;?></td>
			<td><?php echo $school_grade ? $nf_job->school_grade[$school_grade] : '-';?></td>
		</tr>
		<?php
			} }
		} }?>
	</table>

	<h2>경력사항</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>근무기간</th>
			<th>회사명</th>
			<th>근무직종</th>
			<th>담당업무</th>
			<th>상세업무</th>
		</tr>
		<?php
		if(is_array($resume_individual['career_info']['name'])) { foreach($resume_individual['career_info']['name'] as $k=>$v) {
			$career_name = $v;
			$career_job_type = $resume_individual['career_info']['job_type'][$k];
			$career_syear = $resume_individual['career_info']['syear'][$k];
			$career_smonth = sprintf("%02d", $resume_individual['career_info']['smonth'][$k]);
			$career_eyear = $resume_individual['career_info']['eyear'][$k];
			$career_emonth = sprintf("%02d", $resume_individual['career_info']['emonth'][$k]);
			$career_industry = $resume_individual['career_info']['industry'][$k];
			$career_content = $resume_individual['career_info']['content'][$k];

			$month_int = $nf_util->get_month_calc($career_syear.' '.$career_smonth, $career_eyear.' '.$career_emonth);
		?>
		<tr>
			<td><?php echo $career_syear;?>년 <?php echo $career_smonth;?>월 ~ <?php echo $career_eyear;?>년 <?php echo $career_emonth;?>월<br><span class="blue">(<?php echo $nf_util->get_month_year($month_int);?>)</span></td>
			<td><?php echo $career_name;?></td>
			<td><?php echo strtr(implode(">", $career_job_type), $cate_array['job_part']);?></td>
			<td><?php echo $career_industry;?></td>
			<td><?php echo $career_content ? $career_content : '-';?></td>
		</tr>
		<?php
		} }?>
	</table>

	<h2>자기소개서</h2>
	<table class="style2">
		<tr>
			<td><?php echo stripslashes($re_row['wr_introduce']);?></td>
		</tr>
	</table>

	<h2>자격증</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:60%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>취득년도</th>
			<th>자격증명</th>
			<th>발행처</th>
		</tr>
		<?php
		if(is_array($resume_individual['license_arr']['name'])) { foreach($resume_individual['license_arr']['name'] as $k=>$v) {
			$license_name = $resume_individual['license_arr']['name'][$k];
			$license_public = $resume_individual['license_arr']['public'][$k];
			$license_date = $resume_individual['license_arr']['date'][$k];
		?>
		<tr>
			<td><?php echo $license_date;?>년</td>
			<td><?php echo $license_name;?></td>
			<td><?php echo $license_public;?></td>
		</tr>
		<?php
		} }?>
	</table>

	<h2>외국어능력</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>외국어</th>
			<th>공인시험</th>
			<th>어학연수</th>
			<th>점수/등급/취득년도</th>
		</tr>
		<?php
		if(is_array($resume_individual['language_arr']['name'])) { foreach($resume_individual['language_arr']['name'] as $k=>$v) {
			$language_name = $v;
			$language_level = $resume_individual['language_arr']['level'][$k];
			$language_study = $resume_individual['language_arr']['study'][$k];
			$language_study_date = $resume_individual['language_arr']['study_date'][$k];
			$language_license = $resume_individual['language_arr']['license'][$k];
			$language_license_level = $resume_individual['language_arr']['license_level'][$k];
			$language_license_date = $resume_individual['language_arr']['license_date'][$k];
		?>
		<tr>
			<td><span class="class class<?php echo $language_level+1;?>"><?php echo $nf_util->level_arr[$language_level];?></span> <?php echo $language_name;?></td>
			<td>
				<?php
				if(is_Array($language_license)) { foreach($language_license as $k2=>$v2) {
					echo $cate_p_array['job_language_exam'][0][$v2]['wr_name'].'<br/>';
				} }
				?>
			</td>
			<td><?php if($language_study) echo strtr(strtr($language_study_date, array(' '=>'')), $nf_util->date_arr);?></td>
			<td>
				<?php
				if(is_Array($language_license_level)) { foreach($language_license_level as $k2=>$v2) {
					echo $v2.'/'.$language_license_date[$k2].'<br/>';
				} }
				?>
			</td>
		</tr>
		
		<?php
		} }?>
	</table>

	<h2>보유기술 및 능력</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>OA능력</th>
			<?php
			if(is_array($nf_job->oa_arr)) { foreach($nf_job->oa_arr as $k=>$v) {
			?>
			<td class="tac">
				<p><span>P</span><?php echo $v;?></p>
				<span class="<?php if($resume_individual['oa_arr'][$k]<=2) {?>class class<?php echo $resume_individual['oa_arr'][$k]+1; }?>"><?php echo $resume_individual['oa_arr'][$k]>3 ? '해당없음' : $nf_util->level_arr[$resume_individual['oa_arr'][$k]];?></span>
			</td>
			<?php
			} }?>
		</tr>
		<tr>
			<th>컴퓨터능력</th>
			<td colspan="4"><?php echo strtr(strtr($resume_individual['wr_computer'], array(","=>", ")), $cate_array['job_computer']);?></td>
		</tr>
	</table>

	<h2>수상·수료활동</h2>
	<table class="style2">
		<tr>
			<td><?php echo stripslashes($resume_individual['wr_prime']);?></td>
		</tr>
	</table>

	<h2>부가정보</h2>
	<table class="style2">
		<colgroup>
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
			<col style="width:20%">
		</colgroup>
		<tr>
			<th>국가보훈</th>
			<th>고용지원금</th>
			<th>병역여부</th>
			<th>장애여부</th>
		</tr>
		<tr>
			<td>
				<?php
				echo $nf_job->target_arr[$resume_individual['wr_veteran_use']];
				if($resume_individual['wr_veteran_use'] && $resume_individual['wr_veteran']) {
				?>
				(<?php echo $cate_array['job_veteran'][$resume_individual['wr_veteran']];?>)
				<?php
				}?>
			</td>
			<td>
				<?php
				echo $nf_job->target_arr[$resume_individual['wr_treatment_use']];
				if($resume_individual['wr_treatment_use'] && $resume_individual['wr_treatment_service']) {
				?>
				(<?php echo strtr($resume_individual['wr_treatment_service'], $cate_array['job_pay_employ']);?>)
				<?php
				}?>
			</td>
			<td>
				<?php
				echo $nf_job->army_arr[$resume_individual['wr_military']];
				if($resume_individual['wr_military']==='1' && $resume_individual['military_sdate_arr'][0]) {
				?>
					(<?php echo $resume_individual['military_sdate_arr'][0].'년 '.$resume_individual['military_sdate_arr'][1].'월 ~ '.$resume_individual['military_edate_arr'][0].'년 '.$resume_individual['military_edate_arr'][1].'월';?>)
				<?php
				}?>

				<?php
				if($resume_individual['wr_military']==='2' && $resume_individual['wr_military_content']) {
				?>
				(<?php echo $nf_util->get_text($resume_individual['wr_military_content']);?>)
				<?php
				}
				?>
				</td>
			<td>
				<?php
				echo $nf_job->target_arr[$resume_individual['wr_impediment_use']];
				if($resume_individual['wr_impediment_use'] && $resume_individual['wr_impediment_level']) {
				?>
				(<?php echo $cate_array['job_obstacle'][$resume_individual['wr_impediment_level']].' '.$resume_individual['wr_impediment_name'];?>)
				<?php
				}?></td>
		</tr>
	</table>
</section>


</body>
</html>