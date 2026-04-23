<script type="text/javascript">
var sms_send = function() {
	var form = document.forms['fsms'];

	if(!form.send_msg.value) {
		alert("문자메시지를 입력해주시기 바랍니다.");
		form.send_msg.focus();
		return;
	}

	if(!form.no_list.value) {
		alert("전송할 연락처가 없습니다.");
		return;
	}

	if(confirm("전송하시겠습니까?")) {
		nf_util.ajax_submit(form);
	}
}

var sms_cancel = function() {
	open_sms('none');
}

var click_part_sms = function(k) {
	var form = document.forms['fsms'];
	form.code.value = k;
	var obj = $(form).find("[name='send_msg']");
	$.post(root+"/admini/regist.php", "mode=get_sms_msg&ajax=1&k="+k, function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data,msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var form_search = function(el) {
	var form = document.forms['fsearch'];
	var para = $(form).serialize();
	$.post("../regist.php", para+"&mode=member_ajax_search", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}

var no_list_array = new Array();
var phone_list_array = new Array();
var add_member = function() {
	var form = document.forms['flist'];
	var fsms = document.forms['fsms'];
	var obj = $(form).find("[name='chk[]']:checked");

	var para = $(form).serialize();

	$.post("../regist.php", para+"&mode=add_sms_member", function(data){
		data = $.parseJSON(data);
		if(data.msg) alert(data.msg);
		if(data.move) location.href = data.move;
		if(data.js) eval(data.js);
	});
}
</script>
<div class="layer_sns_pop">
	<div class="sms_sed1">
		<form name="fsms" action="../regist.php" method="post" onSubmit="return nf_util.ajax_submit(this)">
		<input type="hidden" name="mode" value="sms_send" />
		<input type="hidden" name="code" value="" />
		<table class="sms2">
			<tr><td><img src="../../images/admini/sms_01.gif"></td></tr>
			<tr>
				<td class="contxt" style="padding-bottom:25px;">
					<textarea name="send_msg" style="height:200px;"></textarea>
					<dt><span id="span_bytes" class="b lsm num11 dho">0</span> Byte</dt>
				</td>
			</tr>
			<tr>
				<td class="connum">
					<strong>받는사람</strong>
					<textarea name="rphone_list" style="height:100px;"></textarea>
					<textarea name="no_list" style="display:none;"></textarea>
					<dt class="mt2">여러명 발송시 엔터로 구분</dt>
				</td>
			</tr>
			<tr><td><img src="../../images/admini/sms_05.gif" usemap="#Send"></td></tr>
			<map name="Send">
				<area shape="rect" coords="21,4,95,32" href="javascript:sms_send();" style="cusor:pointer;" alt="전송하기">
				<area shape="rect" coords="96,4,169,32" href="javascript:sms_cancel();" alt="취소하기">
			</map>
		</table>
		</form>
	</div>
	<div class="sms_sed2">
		<h6>메세지 출력 안내</h6>
		<table class="table2">
			<colgroup>
				<col width="13%">
				<col width="">
				<col width="13%">
				<col width="">
			</colgroup>
			<tbody>
				<tr>
					<th colspan="4">
						<ul>
							<li>※ SMS 최대 글자수는 90Byte이며, LMS 최대 글자수는 2,000Byte 입니다.</li>
							<li>※ <strong>LMS 사용시</strong> : 문자내용이 90byte를 초과하면 <span>LMS(장문)</span>로 발송 됩니다.</li>
							<li>※ <strong>LMS 미사용시</strong> : 문자내용이 90byte를 초과하면 <span>SMS(단문)로 90byte 까지</span>만 잘려서 발송 됩니다.</li>
							<li style="color:#ff8040; font-weight:bold;">※ 미리보기시 90Byte 이하여도 아래의 변수들이 대입되는 경우 90Byte 를 초과할수 있습니다</li>
							<li style="color:#ff8040; font-weight:bold;">90Byte가 초과되는 경우, LMS 사용시 LMS(장문) 로 발송되며, 미사용시 SMS(단문) 로 발송됩니다.</li>
						</ul>
					</th>	
				</tr>
				<tr>
					<th class="gray">{도메인}</th>
					<td>사이트의 도메인명이 출력됩니다<br>예) netfu.co.kr</td>
					<th class="gray">{아이디}</th>
					<td>고객님의 아이디가 출력됩니다<br>예) netfu</td>
				</tr>
				<tr>
					<th class="gray">{날짜}</th>
					<td>오늘 날짜가 출력됩니다<br>예) 1월 1일</td>
					<th class="gray">{이름}</th>
					<td>고객님의 이름이 출력됩니다<br>예) 넷퓨</td>
				</tr>
				<tr>
					<th class="gray">{예약일자}</th>
					<td>온라인 입금시 고객님이 선택한 계좌번호가 출력됩니다</td>
					<th class="gray">{은행}</th>
					<td>온라인 입금시 고객님이 선택한 은행명이 출력됩니다</td>
				</tr>
				<tr>
					<th class="gray">{예금주}</th>
					<td>온라인 입금시 고객님이 선택한 예금주명이 출력됩니다</td>
					<th class="gray">{회사}</th>
					<td>기업회원 회사명이 출력됩니다</td>
				</tr>
				<tr>
					<th class="gray">{사이트명}</th>
					<td>사이트명이 출력됩니다<br>예) 넷퓨</td>
					<th class="gray">{닉네임}</th>
					<td>회원 닉네임이 출력됩니다</td>
				</tr>
			</tbody>
		</table>

		<h6 style="overflow:hidden;">문자메세지예제<span>메세지를 클릭하면 메세지창에 바로 입력이 됩니다</span> <a href="/admini/config/sms.php" target="_blank"><button type="button" class="gray s_btn" style="float:right; padding:5px;">예문보기</button></a></h6>
		<table class="table3">
			<colgroup>
				<col>
			</colgroup>
			<tbody class="tac">
				<?php
				$count = 0;
				if(is_array($nf_sms->sms_msg_array)) { foreach($nf_sms->sms_msg_array as $k=>$v) {
					$use_checked = ($v['wr_use']) ? 'checked' : '';
					$user_checked = ($v['wr_is_user']) ? 'checked' : '';
					$admin_checked = ($v['wr_is_admin']) ? 'checked' : '';

					echo $count%4==0 ? '<tr class="tac">' : '';
				?>
					<td onClick="click_part_sms('<?php echo $k;?>')"><a href="#none"><?php echo $v['wr_title'];?></a></td>
				<?php
					$count++;
				} }
				?>
			</tbody>
		</table>


		<?php
		if(strpos($_SERVER['PHP_SELF'], 'admini/member/sms.php')!==false) {
			$where_arr = $nf_search->member();
			$_where = $where_arr['where'];

			$q = "nf_member as nm where `mb_sms`=1 and mb_left=0 and mb_left_request=0 and `is_delete`=0 and mb_badness=0 ".$_where;
			$order = " order by `no` desc";
			if($_GET['sort']) $order = " order by `".addslashes($_GET['sort'])."` ".$_GET['sort_lo'];
			$total = $db->query_fetch("select count(*) as c from ".$q);

			$_arr = array();
			$_arr['num'] = 15;
			if($_GET['page_row']>0) $_arr['num'] = intval($_GET['page_row']);
			$_arr['total'] = $total['c'];
			$paging = $nf_util->_paging_($_arr);

			$mem_query = $db->_query("select * from ".$q.$order." limit ".$paging['start'].", ".$_arr['num']);

			if($_GET['mode']==='search') {
			?>
			<script type="text/javascript">location.href="#search_sms-";</script>
			<?php
			}?>
		<form name="fsearch" method="get" onSubmit="return form_search(this)">
			<input type="hidden" name="mode" value="member_ajax_search" />
			<div id="search_sms-" class="search" style="margin-top:50px;">
				<div class="bg_w">
					<select name="mb_type" id="" class="select10">
						<option value="">회원종류선택</option>
						<?php
						if(is_Array($nf_member->mb_type)) { foreach($nf_member->mb_type as $k=>$v) {
							$selected = $k===$_GET['mb_type'] ? 'selected' : '';
						?>
						<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
						<?php } }?>
					</select>
					<select name="search_field" class="select10">
						<option value="">선택</option>
						<option value="name" <?php echo $_GET['search_field']=='name' ? 'selected' : '';?>>이름</option>
						<option value="nick" <?php echo $_GET['search_field']=='nick' ? 'selected' : '';?>>닉네임</option>
						<option value="id" <?php echo $_GET['search_field']=='id' ? 'selected' : '';?>>아이디</option>
						<option value="phone" <?php echo $_GET['search_field']=='phone' ? 'selected' : '';?>>휴대폰</option>
					</select>
					<select name="mb_sms_receive" class="select10">
						<option value="">SMS허용여부</option>
						<option value="1" <?php echo $_GET['mb_sms_receive']==='1' ? 'selected' : '';?>>수신허용</option>
						<option value="0" <?php echo $_GET['mb_sms_receive']==='0' ? 'selected' : '';?>>수신거부</option>
					</select>
					<input type="text" name="search_keyword" value="<?php echo $nf_util->get_html($_GET['search_keyword']);?>">
					<input type="submit" class="blue" value="검색" onClick="form_search();return false;"></input>
					<button type="button" class="black" onClick="document.forms['fsearch'].reset()">초기화</button>
				</div>
			</div>
		</form>
		
		<form name="flist">
		<div class="table_top_btn">
			<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
			<button type="button" class="blue" onClick="add_member()"><strong>+</strong> 발송회원추가</button></a>
		</div>
		<table class="table4">
			<colgroup>
				<col width="3%">
				<col width="">
				<col width="">
				<col width="">
				<col width="">
				<col width="8%">
			</colgroup>
			<thead>
				<tr>
					<th><input type="checkbox" id="check_all" onclick="nf_util.all_check(this, '.chk_')"></th>
					<th>회원구분</th>
					<th>이름</th>
					<th>아이디</th>
					<th>휴대폰</th>
					<th>수신</th>
				</tr>
			</thead>
			<tbody id="member_tr-">
				
			</tbody>
		</table>
		<div id="member_paging-"></div>
		<div class="table_top_btn bbn">
			<button type="button" onclick="nf_util.all_check('#check_all', '.chk_')" class="gray"><strong>A</strong> 전체선택</button>
			<button type="button" class="blue" onClick="add_member()"><strong>+</strong> 발송회원추가</button></a>
		</div>
		</form>
		<?php
		}
		?>
	</div>
</div>
<script type="text/javascript">
form_search();
</script>