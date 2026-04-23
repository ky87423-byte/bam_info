<?php
$member_code = '';
if(strpos($_SERVER['PHP_SELF'], '/bad_list.php')!==false) $member_code = 'bad';
if(strpos($_SERVER['PHP_SELF'], '/left_list.php')!==false) $member_code = 'left';
?>
<form name="fsearch" action="" method="get">
	<input type="hidden" name="page_row" value="<?php echo $nf_util->get_html($_GET['page_row']);?>" />
	<input type="hidden" name="left" value="<?php echo $nf_util->get_html($_GET['left']);?>" />
	<input type="hidden" name="sort" value="<?php echo $nf_util->get_html($_GET['sort']);?>" />
	<input type="hidden" name="sort_lo" value="<?php echo $nf_util->get_html($_GET['sort_lo']);?>" />
	<div class="search">
		 <table>
			<colgroup>
				<col width="5%">
				<col width="45%">
				<col width="5%">
				<col width="45%">
			</colgroup>
			<tbody>
				<tr>
					<th>
						<select name="rdate">
							<option value="wdate" <?php echo $_GET['rdate']=='wdate' ? 'selected' : '';?>>가입일</option>
							<option value="udate" <?php echo $_GET['rdate']=='udate' ? 'selected' : '';?>>수정일</option>
						</select>
					</th>
					<td>
						<?php
						$date_tag = $nf_tag->date_search();
						echo $date_tag['tag'];
						?>
					</td>
					<th>방문수</th>
					<td><label><input type="checkbox" name="login_count_all" value="1" <?php echo $_GET['login_count_all'] ? 'checked' : '';?>>전체</label> <input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][0]);?>" class="input10"> ~ <input type="text" name="login_count[]" value="<?php echo $nf_util->get_html($_GET['login_count'][1]);?>" class="input10"></td>
				</tr>
				<tr>
					<?php
					if($member_code!='bad') {
					?>
					<th>불량회원구분</th>
					<td colspan="<?php echo $member_code=='left' ? 3 : 1;?>"><label><input type="checkbox" name="badness" value="1" <?php echo $_GET['badness'] ? 'checked' : '';?>>불량회원</label></td>
					<?php
					}?>
					<?php if($member_code!='left') {?>
					<th>탈퇴구분</th>
					<td colspan="<?php echo $member_code=='bad' ? 3 : 1;?>">
						<label><input type="checkbox" name="left_chk" onClick="nf_util.one_check(this)" value="request" <?php echo $_GET['left_chk']=='request' ? 'checked' : '';?>>탈퇴요청</label>
						<label><input type="checkbox" name="left_chk" onClick="nf_util.one_check(this)" value="left" <?php echo $_GET['left_chk']=='left' ? 'checked' : '';?>>탈퇴완료</label>
					</td>
					<?php
					}?>
				<tr>
					<th>휴면회원</th>
					<td colspan="3">
						<label><input type="radio" name="left_chk" checked>휴면회원</label>
						<label><input type="radio" name="left_chk" onClick="nf_util.one_check(this)" value="left" <?php echo $_GET['left_chk']=='left' ? 'checked' : '';?>>휴면예정회원</label>
					</td>
				</tr>
			</tbody>
		 </table>
		<div class="bg_w">
			<select name="search_field">
				<option value="">통합검색</option>
				<option value="id" <?php echo $_GET['search_field']=='id' ? 'selected' : '';?>>아이디</option>
				<option value="name" <?php echo $_GET['search_field']=='name' ? 'selected' : '';?>>이름</option>
				<option value="email" <?php echo $_GET['search_field']=='email' ? 'selected' : '';?>>이메일</option>
				<option value="nick" <?php echo $_GET['search_field']=='nick' ? 'selected' : '';?>>닉네임</option>
				<option value="hphone" <?php echo $_GET['search_field']=='hphone' ? 'selected' : '';?>>휴대폰</option>
			</select>
			<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
			<input type="submit" class="blue" value="검색"></input>
			<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
		</div>
	</div>
</form>