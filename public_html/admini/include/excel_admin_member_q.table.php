<table class="table4" id="excel_table_">
<colgroup>
	<col width="3%">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="">
	<col width="8%">
</colgroup>
<?php
$mb_type_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_type' ? 'desc' : 'asc';
$mb_id_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_id' ? 'desc' : 'asc';
$mb_name_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_name' ? 'desc' : 'asc';
$mb_level_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_level' ? 'desc' : 'asc';
$mb_point_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_point' ? 'desc' : 'asc';
$mb_wdate_order = strpos($_GET['sort_lo'], 'asc')!==false && $_GET['sort']=='mb_wdate' ? 'desc' : 'asc';
?>
<thead>
	<tr>
		<th><input type="checkbox" name="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
		<th><a href="#none" onClick="click_sort('mb_type', '<?php echo $mb_type_order;?>')">회원구분<?php echo $mb_type_order=='desc' ? '▲' : '▼';?></a></th>
		<th><a href="#none" onClick="click_sort('mb_id', '<?php echo $mb_id_order;?>')">회원ID<?php echo $mb_id_order=='desc' ? '▲' : '▼';?></a></th>
		<th><a href="#none" onClick="click_sort('mb_name', '<?php echo $mb_name_order;?>')">이름/대표자 (성별/나이)<?php echo $mb_name_order=='desc' ? '▲' : '▼';?></a></th>
		<th><a href="#none" onClick="click_sort('mb_level', '<?php echo $mb_level_order;?>')">회원등급<?php echo $mb_level_order=='desc' ? '▲' : '▼';?></a></th>
		<th><a href="#none" onClick="click_sort('mb_point', '<?php echo $mb_point_order;?>')">포인트<?php echo $mb_point_order=='desc' ? '▲' : '▼';?></a></th>
		<th><a href="#none">회사명</a></th>
		<th><a href="#none">채용공고</a></th>
		<th><a href="#none">이력서</a></th>
		<th><a href="#none">입사지원</a></th>
		<th><a href="#none">열람서비스</a></th>
		<th><a href="#none" onClick="click_sort('mb_wdate', '<?php echo $mb_wdate_order;?>')">가입일<?php echo $mb_wdate_order=='desc' ? '▲' : '▼';?></a></th>
		<th>불량</th>
		<th>로그인</th>
		<th>편집</th>
	</tr>
</thead>
<tbody>
	<?php
	switch($_arr['total']<=0) {
		case true:
	?>
	<tr><td colspan="15" class="no_list"></td></tr>
	<?php
		break;


		default:
			while($mem_row=$db->afetch($mem_query)) {
				$update_url = $mem_row['mb_type']=='company' ? './company_insert.php' : './individual_insert.php';
	?>
	<tr class="tac" mb_id="<?php echo $mem_row['mb_id'];?>">
		<td><input type="checkbox" name="chk[]" class="chk_" value="<?php echo $mem_row['no'];?>"></td>
		<td ><?php echo $nf_member->mb_type[$mem_row['mb_type']];?>회원</td>
		<td><a href="#none" onClick="member_mno_click(this)" class="blue fwb" mno="<?php echo $mem_row['no'];?>"><?php echo $nf_util->get_text($mem_row['mb_id']);?></a></td>
		<td>
			<?php
			$get_name = $mem_row['mb_name'];
			echo $nf_util->get_text($get_name);
			if($mem_row['mb_type']=='individual') {
			?>
			(<?php echo $nf_util->gender_arr[$mem_row['mb_gender']];?>/<?php echo $nf_util->get_age($mem_row['mb_birth']);?>세)
			<?php
			}?>
		</td>
		<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
		<td><?php echo intval($mem_row['mb_point']);?></td>
		<td><?php if($mem_row['mb_type']=='company') { echo $mem_row['mb_company_name']; } else {?><span class="gray">개인</span><?php }?></td>
		<td>
			<?php if($mem_row['mb_type']=='company') {?>
			<a href="" class="blue fwb">1</a>
			<?php } else {?><span class="gray">개인</span><?php }?>
		</td>
		<td>
			<?php if($mem_row['mb_type']=='individual') {?>
			12
			<?php } else {?><span class="gray">기업</span><?php }?>
		</td>
		<td>
			<?php if($mem_row['mb_type']=='individual') {?>
			12
			<?php } else {?><span class="gray">기업</span><?php }?>
		</td>
		<td>
			<?php if($mem_row['mb_type']=='company') {?>
			2015-05-55
			<?php } else {?><span class="gray">개인</span><?php }?>
		</td>
		<td><?php echo substr($mem_row['mb_wdate'],0,10);?></td>
		<td><a href="#none" onClick="open_box(this, 'badness-')" class="<?php echo $mem_row['mb_badness'] ? 'red' : 'blue';?> fwb"><?php echo $mem_row['mb_badness'] ? '불량' : '정상';?></a></td>
		<td><a href="" class="blue fwb">로그인</a></td>
		<td>
			<a href="<?php echo $update_url;?>?mb_id=<?php echo $mem_row['mb_id'];?>"><button type="button" class="gray common"><i class="axi axi-plus2"></i> 수정</button></a>
			<button type="button" class="gray common"><i class="axi axi-minus2"></i> 삭제</button>
			<button type="button" onClick="open_box(this, 'sms-')" class="gray common">문자</button>
			<button type="button" onClick="member_mno_click(this)" mno="<?php echo $mem_row['no'];?>" code="email-" class="gray common">메일</button>
			<button type="button" onClick="open_box(this, 'memo-')" class="gray common">메모</button>
			<button type="button" class="blue common">맞춤정보설정열람</button>
		</td>
	</tr>
	<?php
			}
		break;
	}
	?>
</tbody>
</table>