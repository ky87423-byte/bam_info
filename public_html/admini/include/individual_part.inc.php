<table class="style1">
	<colgroup>
		<col width="10%">
	</colgroup>
	<tbody>
		<tr>
			<th>아이디</th>
			<td><?php echo $mem_row['mb_id'];?></td>
			<th>이메일</th>
			<td><a href="#none" onClick="member_mno_click(this)" mno="<?php echo $mem_row['no'];?>" code="email-"><?php echo $mem_row['mb_email'];?></a></td>
		</tr>
		<tr>
			<th>이름</th>
			<td><?php echo $mem_row['mb_name'];?></td>
			<th>닉네임</th>
			<td><?php echo $mem_row['mb_nick'];?></td>
		</tr>
		<tr>
			<th>레벨</th>
			<td><?php echo $env['member_level_arr'][$mem_row['mb_level']]['name'];?></td>
			<th>연락처</th>
			<td><?php echo $mem_row['mb_hphone'];?></td>
		</tr>
		<tr>
			<th>가입일</th>
			<td><?php echo $mem_row['mb_wdate'];?></td>
			<th>최종로그인</th>
			<td><?php echo $mem_row['mb_last_login'];?></td>
		</tr>
		<tr>
			<th>포인트</th>
			<td><?php echo $mem_row['mb_point'];?></td>
			<th>방문수</th>
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
			<th>관리자메모</th>
			<td colspan="3"><?php echo stripslashes(nl2br($mem_row['mb_memo']));?></td>
		</tr>
	</tbody>
</table>