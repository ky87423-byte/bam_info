<?php
$shop_int = $db->query_fetch("select count(*) as c from nf_shop where `is_delete`=0 and `mno`=".intval($mem_row['no']));
?>
<table width="730" class="bg_col tf">
<colgroup>
<col width="100"><col><col width="100"><col>
</colgroup>
<tr>
	<th>아이디</td>
	<td><?php echo $mem_row['mb_id'];?></td>
	<th>이메일</td>
	<td><a href="#none" onClick="member_mno_click(this)" mno="<?php echo $mem_row['no'];?>" code="email-"><?php echo $mem_row['mb_email'];?></a></td>
</tr>
<tr>
	<th>이름</td>
	<td><?php echo $mem_row['mb_name'];?></td>
	<th>닉네임</td>
	<td><?php echo $mem_row['mb_nick'];?></td>
</tr>
<tr>
	<th>레벨</td>
	<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
	<th>연락처</td>
	<td><?php echo $mem_row['mb_hphone'];?></td>
</tr>
<tr>
	<th>업체명</td>
	<td><?php echo $mem_row['mb_company_name'];?></td>
	<th>상품등록수</td>
	<td><a href="<?php echo NFE_URL;?>/admini/introduce/index.php?mno=<?php echo $mem_row['no'];?>" target="_blank"><?php echo number_format(intval($shop_int['c']));?> 건</a></td>
</tr>
<tr>
	<th>가입일</td>
	<td><?php echo $mem_row['mb_wdate'];?></td>
	<th>최종로그인</td>
	<td><?php echo $mem_row['mb_last_login'];?></td>
</tr>
<tr>
	<th>포인트</td>
	<td><?php echo number_format(intval($mem_row['mb_point']));?></td>
	<th>방문수</td>
	<td><?php echo number_format(intval($mem_row['mb_login_count']));?></td>
</tr>
<tr>
	<th>수신여부</th>
	<td colspan="3" style="padding:0">
		<table class="">
			<tr>
				<th class="bg_w_blue bl fwb bn">메일</th>
				<td class="bn"><?php echo $mem_row['mb_email_view'] ? '수신' : '미수신';?></td>
				<th class="bg_w_blue bl fwb bn">문자</th>
				<td class="bn"><?php echo $mem_row['mb_sms'] ? '수신' : '미수신';?></td>
				<th class="bg_w_blue bl fwb bn">쪽지</th>
				<td class="bn"><?php echo $mem_row['mb_message_view'] ? '수신' : '미수신';?></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td colspan="4" class="lnb wbg" height="5"></td></tr>
<tr>
	<th>관리자메모</td>
	<td colspan="3"><?php echo stripslashes(nl2br($mem_row['mb_memo']));?></td>
</tr>
</table>